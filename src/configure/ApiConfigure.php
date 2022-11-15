<?php


namespace innln\unifiedapi\configure;

use GuzzleHttp\Client;
use innln\unifiedapi\BaseObject;
use innln\unifiedapi\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * 接口配置类
 * @package innln\unifiedapi\configure
 */
class ApiConfigure extends BaseObject implements ApiConfigureInterface
{
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var string 接口名称-中文名
     */
    protected $name;
    /**
     * @var string 接口名
     */
    protected $api;
    /**
     * @var string 接口地址，完整地址：baseUri+/+version+/+uri
     */
    protected $uri;
    /**
     * @var string 请求方式，可选值：GET|POST|PUT|DELETE|PATCH
     */
    protected $method;
    /**
     * @var string 请求类型，form_params，multipart，json
     */
    protected $requestType;
    /**
     * @var string 返回数据类型 ，url, stream, xml，text，json
     */
    protected $responseType;
    /**
     * @var int 接口失败最大重试次数
     */
    protected $maxRetryCount;
    /**
     * @var int 重试间隔时长，单位：毫秒
     */
    protected $retryInterval;
    /**
     * @var array 请求参数格式配置
     */
    protected $request = [];
    /**
     * @var string 成功返回的状态，多个以“|”,如：200|201|202，范围以“-”，如：200-299
     */
    protected $httpStatusCode;
    /**
     * @var array 返回的数据格式配置
     */
    protected $response = [];
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
    protected $failureHttpStatusCode;
    /**
     * @var array 返回失败的数据格式
     */
    protected $failureResponse;

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

    /**
     * Send an HTTP request.
     *
     * @param array $options Request options to apply to the given
     *                       request and to the transfer. See \GuzzleHttp\RequestOptions.
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function send(RequestInterface $request, array $options = []){
        return $this->container->get(Client::class)->send($request, $options);
    }

    /**
     * 接口中文名
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * 接口名（唯一）
     * @return mixed
     */
    public function getApi(){
        return $this->api;
    }

    /**
     * @return string
     */
    public function getRequestType(){
        return $this->requestType;
    }

    /**
     * @return string
     */
    public function getResponseType(){
        return $this->responseType;
    }

    /**
     * @return int
     */
    public function getMaxRetryCount(){
        return $this->maxRetryCount;
    }

    /**
     * @return int
     */
    public function getRetryInterval(){
        return $this->retryInterval;
    }

    /**
     * @return array
     */
    public function getRequest(){
        return $this->request;
    }

    /**
     * @return string
     */
    public function getHttpStatusCode(){
        return $this->httpStatusCode;
    }

    /**
     * @return array
     */
    public function getResponse(){
        return $this->response;
    }

}