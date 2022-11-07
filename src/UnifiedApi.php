<?php declare(strict_types=1);
namespace innln\unifiedapi;
use GuzzleHttp\Client;
use innln\unifiedapi\configure\BaseObject;
use innln\unifiedapi\configure\ProjectConfigure;
use innln\unifiedapi\constants\HttpConstant;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

/**
 * 统一api基础类
 *
 * Class UnifiedApi
 *
 * @property-read Container $container 容器
 * @property-read ProjectConfigure $projectConfigure 项目配置
 * @package innln\unifiedapi
 */
class UnifiedApi extends  BaseObject implements UnifiedApiInterface
{
    /**
     * @var null|Container 容器
     */
    protected $container = null;
    /**
     * 项目配置
     * @var ProjectConfigure
     */
    protected $projectConfigure;
    public function __construct(Container $container, ProjectConfigure $projectConfigure, array $config = [])
    {
        parent::__construct($config);
        $this->container = $container;
        $this->container->get(Logger::class)->debug("实例化统一接口类", []);
        if(!$this->container->has(Client::class)){
           // 公共Client
           $this->container->addShared(Client::class);
        }
    }

    /**
     * 过滤请求数据
     * @param array $requestParameters
     * @return array
     */
    public function filterRequestParameters(array $requestParameters): array
    {
        // TODO: Implement filterRequestParameters() method.
    }

    /**
     * 验证数据
     * @param array $requestParameters
     * @return bool
     */
    public function validate(array $requestParameters): bool
    {
        // TODO: Implement validate() method.
    }

    /**
     * 请求
     * @param string $apiKey 接口key在项目中具有唯一值
     * @param array $parameters 请求参数
     * @param string $requestMethod 请求方式
     * @param string $requestType 请求类型
     * @param array $headers 请求头
     * @return ResponseInterface
     */
    public function send(string $apiKey,
                         array $parameters = [],
                         string $requestMethod = HttpConstant::REQUEST_METHOD_GET,
                         string $requestType = HttpConstant::REQUEST_TYPE_QUERY,
                         array $headers = []): ResponseInterface
    {
        // TODO: Implement send() method.
        try{
            // 接口配置数据
            $oApiConfigure = $this->projectConfigure->getApi($apiKey);
            $this->filterRequestParameters($parameters);
        } catch(\Exception $e){
            throw $e;
        }
    }
}