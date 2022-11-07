<?php


namespace innln\unifiedapi\configure;

use innln\unifiedapi\BaseObject;
use innln\unifiedapi\Container;

/**
 * 接口配置类
 * @package innln\unifiedapi\configure
 */
class ApiConfigure extends BaseObject implements ApiConfigureInterface
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var string 接口名称-中文名
     */
    private $name;
    /**
     * @var string 接口地址，完整地址：baseUri+/+version+/+uri
     */
    private $uri;
    /**
     * @var string 请求方式，可选值：GET|POST|PUT|DELETE|PATCH
     */
    private $method;
    /**
     * @var string 请求类型，form_params，multipart，json
     */
    private $requestType;
    /**
     * @var string 返回数据类型 ，url, stream, xml，text，json
     */
    private $responseType;
    /**
     * @var int 接口失败最大重试次数
     */
    private $maxRetryCount;
    /**
     * @var int 重试间隔时长，单位：毫秒
     */
    private $retryInterval;
    /**
     * @var array 请求参数格式配置
     */
    private $request = [];
    /**
     * @var string 成功返回的状态，多个以“|”,如：200|201|202，范围以“-”，如：200-299
     */
    private $httpStatusCode;
    /**
     * @var array 返回的数据格式配置
     */
    private $response = [];
    /**
     * @var string 失败返回的状态匹配规则，多个以“|”,如：200|201|202，范围以“-”，如：200-299
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
     * @var array 返回失败的数据格式
     */
    private $failureResponse;

    /**
     * ApiConfigure constructor.
     * @param Container $container
     * @param array $config
     */
    public function __construct(Container $container, array $config = [])
    {
        parent::__construct($config);
        $this->container = $container;
    }

    public function convertRequest(array $request = []): array
    {
        // TODO: Implement convertRequest() method.
        return [];
    }

    public function convertResponse(array $response = []): array
    {
        // TODO: Implement convertResponse() method.
        return [];
    }

    public function hasFailureHttpStatusCode(): bool
    {
        // TODO: Implement hasFailureHttpStatusCode() method.
        return !empty($this->failureHttpStatusCode);
    }

    /**
     * 返回失败状态码匹配规则属性
     * @return string
     */
    public function getFailureHttpStatusCode():string{
        return $this->failureHttpStatusCode;
    }


    public function hasFailureResponse(): bool
    {
        // TODO: Implement hasFailureResponse() method.
        return !empty($this->failureResponse);
    }

}