<?php declare(strict_types=1);
namespace innln\unifiedapi\configure;

use innln\unifiedapi\BaseObject;
use innln\unifiedapi\constants\ErrorCode;
use innln\unifiedapi\Container;
use innln\unifiedapi\exception\ApiClassObjectNotFoundException;
use innln\unifiedapi\exception\ApiHttpException;

/**
 * Class ProjectConfigure
 * 第三方项目接口配置类
 * @package innln\unifiedapi\configure
 */
class ProjectConfigure extends BaseObject implements ProjectConfigureInterface
{
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var string 接口域名， 带scheme
     * @example https://api.innln.com
     * 接口的前缀为：$this->baseUri . $this->version
     */
    protected $baseUri;
    /**
     * @var string 版本
     * @example v2
     */
    protected $version;
    /**
     * @var int 查询失败默认最大重试次数
     */
    protected $defaultMaxRetryCount;

    /**
     * @var int 重试间隔时间，单位：毫秒
     */
    protected $defaultRetryInterval;

    /**
     * @var AuthenticationConfigure
     */
    protected $authentication;
    /**
     * @var string 失败返回的状态，多个以“|”,如：200|201|202，范围以“-”，如：200-299
     * @example ![200-299]|300|400
     * 目前只支持2种格式数据解析
     * ![200-299], 非某个数字范围
     * [200-299]，某个数值范围
     * !400, 指定非值
     * 400, 指定值
     *
     */
    protected $failureHttpStatusCode;

    /**
     * 接口配置数据
     * key为接口唯一名，以便获取指定接口数据
     * @var array
     */
    protected $apis = [];

    /**
     * ProjectConfigure constructor.
     * @param Container $container 容器
     * @param array $config 配置信息
     */

    public function __construct(Container $container, array $config = [])
    {
        parent::__construct($config);
        $this->container = $container;
    }

    /**
     * 【失败】验证状态码是否是失败状态码
     * @param int $httpStatusCode 结果状态码
     * @param ApiConfigure $apiConfigure  接口配置信息类
     * @return bool
     */
    public function validateFailureHttpStatusCode(int $httpStatusCode, ApiConfigure $apiConfigure):bool {
        return $this->validateHttpStatusCode($httpStatusCode, $apiConfigure->getFailureHttpStatusCode(), $this->failureHttpStatusCode);
    }


    /**
     * 【成功】验证状态码是否是成功状态码
     * @param int $httpStatusCode 结果状态码
     * @param ApiConfigure $apiConfigure  接口配置信息类*
 * @return bool
     */
    public function validateSuccessHttpStatusCode(int $httpStatusCode, ApiConfigure $apiConfigure):bool
    {
        return $this->validateHttpStatusCode($httpStatusCode, $apiConfigure->getFailureHttpStatusCode());
    }
    /**
     * 判断返回的http状态吗是否是错误码范围
     * @param int $httpStatusCode http状态码
     * @param string $apiHttpStatus api接口上的状态码配置规则，规则填写时，必须每项都要保存互斥， 尽量少用“非”操作
     * @param string $projectHttpStatus  项目上配置的成功，或失败的状态码匹配规则数据
     * @return bool
     */
    public function validateHttpStatusCode(int $httpStatusCode, string $apiHttpStatus = '', string $projectHttpStatus = ''):bool{
        // 接口上有规则时，优先接口规则，否则读取项目中的状态码规则
        $sPregStatusCodes  = !empty($apiHttpStatus) ? $apiHttpStatus : $projectHttpStatus;
        if(empty($sPregStatusCodes)){
            throw new ApiHttpException(ErrorCode::ERROR_HTTP_STATUS_RULE_CONFIGURE_IS_NOT_CONFIG);
        }
        // ![200-299] | [400-420] | 500 | !501
        $pregScope = "/\!?\[(\d*)\-(\d*)\]/"; // 范围匹配， 如：![200-299] 或 [200-299]
        $failureHttpStatusCodes = explode("|",$sPregStatusCodes);
        if(!empty($failureHttpStatusCodes)){
            $statusCodes = [
                'in' => [], // 包含
                'notIn' => [], // 不包含
                'inScope' => [], // 包含范围
                'notInScope' => [] // 不包含范围
            ];
            foreach($failureHttpStatusCodes as $statusCodeConfig){
                $this->container->get("logger")->debug("状态码匹配规则切割成项：{$statusCodeConfig}, 判断匹配 !："  . ('!' === substr($statusCodeConfig, 0,1)?"EXIST":"NOT EXIST"));
                // 是否匹配范围格式数据
                if('!' === substr($statusCodeConfig, 0,1)){
                    $statusCodeConfigRemoveNon = substr($statusCodeConfig, 1);
                    if(is_numeric($statusCodeConfigRemoveNon)){
                        $statusCodes['notIn'][] = ($statusCodeConfigRemoveNon == $httpStatusCode);
                    } elseif (preg_match($pregScope, $statusCodeConfig, $matches)) {
                        $statusCodes['notInScope'][] = $matches[1] <= $httpStatusCode && $httpStatusCode <= $matches[2];
                    }
                } else {
                    // 包含，匹配上了，直接返回结果
                    if(is_numeric($statusCodeConfig)){
                        $statusCodes['in'][] = $statusCodeConfig == $httpStatusCode;
//                        if($statusCodeConfig == $httpStatusCode){
//                            $result = true;
//                            break;
//                        }
                    } elseif(preg_match($pregScope, $statusCodeConfig, $matches)) {
                        $statusCodes['inScope'][] = $matches[1] <= $httpStatusCode && $httpStatusCode <= $matches[2];
//                        if($matches[1] <= $httpStatusCode && $httpStatusCode <= $matches[2]){
//                            $result = true;
//                            break;
//                        }
                    }
                }
            }
            $this->container->get("logger")->debug("状态码匹配结果", $statusCodes);
            // 在是或运算, 只要存在一个，则为真
            $in = !empty($statusCodes['in']) && (array_sum($statusCodes['in']));
            // 只要存在一个，则为真
            $inScope = !empty($statusCodes['inScope']) && (array_sum($statusCodes['inScope']));
            // 判断不应该在这个外围内，但是在的则只要有一个true就是错的
            // 只要存在一个在这个返回内，则为假
            $notIn = !empty($statusCodes['notIn']) && array_sum($statusCodes['notIn']);
            // 只要存在一个在这个返回内，则为假
            $notInScope = !empty($statusCodes['notInScope']) && array_sum($statusCodes['notInScope']);
//            return $result || (!$result && !($notIn && $notInScope));
            $this->container->get("logger")->debug("四种结果集", ['in' =>$in ,'notIn' =>$notIn, 'inScope' => $inScope, 'noInScope' =>$notInScope,'result' => ($in || $inScope) && ($notIn && $notInScope)]);
            return $in || $inScope || !($notIn || $notInScope);
        }
    }

    /**
     * 是否存在接口配置
     * @param string $apiKey
     * @return bool
     */
    public function hasApi(string $apiKey): bool
    {
        // TODO: Implement hasApi() method.
        return isset($this->apis[$apiKey]);
    }

    /**
     * 返回接口配置对象
     * @param string $apiKey
     * @return ApiConfigure|array
     * @throws ApiClassObjectNotFoundException
     */
    public function getApi(string $apiKey): ApiConfigure
    {
        // TODO: Implement getApi() method.
        if(!$this->hasApi($apiKey)){
            throw new ApiClassObjectNotFoundException(ErrorCode::ERROR_CLASS_OBJECT_IS_NOT_EXIST, [ApiConfigure::class]);
        }
        $this->container->get("logger")->debug('接口配置数据', $this->apis);
        return ($this->apis[$apiKey] instanceof ApiConfigure) ?
             $this->apis[$apiKey] : new ApiConfigure($this->container, $this->apis[$apiKey]);
    }
}