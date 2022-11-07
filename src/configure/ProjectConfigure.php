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
    private $container;
    /**
     * @var string 接口域名， 带scheme
     * @example https://api.innln.com
     * 接口的前缀为：$this->baseUri . $this->version
     */
    private $baseUri;
    /**
     * @var string 版本
     * @example v2
     */
    private $version;
    /**
     * @var int 查询失败默认最大重试次数
     */
    private $defaultMaxRetryCount;

    /**
     * @var int 重试间隔时间，单位：毫秒
     */
    private $defaultRetryInterval;

    /**
     * @var AuthenticationConfigure
     */
    private $authentication;
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
    private $failureHttpStatusCode;

    /**
     * 接口配置数据
     * key为接口唯一名，以便获取指定接口数据
     * @var array
     */
    private $apis = [];

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
     * @param string $apiHttpStatus api接口上的状态码配置规则
     * @param string $projectHttpStatus  项目上配置的成功，或失败的状态码匹配规则数据
     * @return bool
     */
    public function validateHttpStatusCode(int $httpStatusCode, string $apiHttpStatus = '', string $projectHttpStatus = ''):bool{
        // 接口上有规则时，优先接口规则，否则读取项目中的状态码规则
        $sPregStatusCodes  = !empty($apiHttpStatus) ? $apiHttpStatus : $projectHttpStatus;
        if(empty($sPregStatusCodes)){
            throw new ApiHttpException(ErrorCode::ERROR_HTTP_STATUS_RULE_CONFIGURE_IS_NOT_CONFIG);
        }
        $result = false;
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
                // 是否匹配范围格式数据
                if(0 === stripos('!', $statusCodeConfig)){
                    $statusCodeConfigRemoveNon = substr($statusCodeConfig, 1);
                    if(is_numeric($statusCodeConfigRemoveNon)){
                        $statusCodes['notIn'][] = $statusCodeConfigRemoveNon == $httpStatusCode;
                    } elseif (preg_match($pregScope, $statusCodeConfig, $matches)) {
                        $statusCodes['notInScope'] = $matches[1] <= $httpStatusCode && $httpStatusCode <= $matches[2];
                    }
                } else {
                    // 包含，匹配上了，直接返回结果
                    if(is_numeric($statusCodeConfig)){
                        if($statusCodeConfig == $httpStatusCode){
                            $result = true;
                            break;
                        }
                    } elseif(preg_match($pregScope, $statusCodeConfig, $matches)) {
                        if($matches[1] <= $httpStatusCode && $httpStatusCode <= $matches[2]){
                            $result = true;
                            break;
                        }
                    }
                }
            }
            // 是否在不允许范围内，只要有一个成立，则条件就成立
            $notIn = !empty($statusCodes['notIn'] && array_sum($statusCodes['notIn']));
            $notInScope = !empty($statusCodes['notInScope'] && array_sum($statusCodes['notInScope']));
            return $result || (!$result && !($notIn || $notInScope));
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
     * @return ApiConfigure
     * @throws ApiClassObjectNotFoundException
     */
    public function getApi(string $apiKey): ApiConfigure
    {
        // TODO: Implement getApi() method.
        if(!$this->hasApi($apiKey)){
            throw new ApiClassObjectNotFoundException(ErrorCode::ERROR_CLASS_OBJECT_IS_NOT_EXIST, [ApiConfigure::class]);
        }
        return ($this->apis[$apiKey] instanceof ApiConfigure) ?
            new ApiConfigure($this->container, $this->apis[$apiKey]) : $this->apis[$apiKey];
    }
}