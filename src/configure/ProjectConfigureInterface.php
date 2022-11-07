<?php


namespace innln\unifiedapi\configure;

use innln\unifiedapi\exception\ApiClassObjectNotFoundException;

/**
 * Interface ProjectConfigureInterface
 * @package innln\unifiedapi\configure
 */
interface ProjectConfigureInterface
{
    /**
     * 验证状态码是否是符合规则匹配的状态码
     * @param int $httpStatusCode http状态码
     * @param string $projectHttpStatus  项目上配置的成功，或失败的状态码匹配规则数据
     * @param string $apiHttpStatus api接口上的状态码配置规则
     * @return bool
     */
    public function validateHttpStatusCode(int $httpStatusCode, string $projectHttpStatus, string $apiHttpStatus = ''):bool;

    /**
     * 验证状态码是否是失败状态码
     *
     * @param int $httpStatusCode 结果状态码
     * @param ApiConfigure $apiConfigure  接口配置信息类
     * @return bool
     */
    public function validateFailureHttpStatusCode(int $httpStatusCode, ApiConfigure $apiConfigure):bool;


    /**
     * 验证状态码是否是成功状态码
     * @param int $httpStatusCode 结果状态码
     * @param ApiConfigure $apiConfigure  接口配置信息类
     * @return bool
     */
    public function validateSuccessHttpStatusCode(int $httpStatusCode, ApiConfigure $apiConfigure):bool;

    /**
     * @param string $apiKey
     * @return bool
     */
    public function hasApi(string $apiKey):bool;

    /**
     * @param string $apiKey
     * @return ApiConfigure
     * @throws ApiClassObjectNotFoundException
     */
    public function getApi(string $apiKey):ApiConfigure;
}