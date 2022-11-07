<?php declare(strict_types=1);
namespace innln\unifiedapi;

use innln\unifiedapi\constants\HttpConstant;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface UnifiedApiInterface
 * 统一api接口
 * @package innln\unifiedapi
 */
interface UnifiedApiInterface
{
    /**
     * 验证数据是否有效（符合要求）
     * @param array $requestParameters 请求参数
     * @return bool 验证状态：true 成功，false 失败
     */
    public function validate(array $requestParameters):bool;

    /**
     * 过滤请求数据，获得有效的请求数据
     *
     * 用在验证数据前先进行过滤
     *
     * @param array $requestParameters 请求参数
     * @return array 返回有效请求参数数据
     */
    public function filterRequestParameters(array $requestParameters):array;

    /**
     * 发送请求，返回结果
     * @param string $apiKey 接口名， 对应配置文件中的接口名-唯一性，可以定位接口配置信息
     * @param array $parameters 请求参数
     * @param string $requestMethod 请求方式
     * @param string $requestType 请求类型
     * @param array $headers 请求头
     * @return ResponseInterface
     */
    public function send(string $apiKey, array $parameters = [],
                         string $requestMethod = HttpConstant::REQUEST_METHOD_GET,
                         string $requestType = "query", array $headers = []):ResponseInterface;


}