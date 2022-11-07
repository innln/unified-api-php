<?php


namespace innln\unifiedapi\configure;


interface ApiConfigureInterface
{
    /**
     * 转换请求数据，符合请求
     * @param array $request
     * @return array
     */
    public function convertRequest(array $request = []):array;

    /**
     * 转换结果数据，符合本地数据字段结构
     * @param array $response
     * @return array
     */
    public function convertResponse(array $response = []):array;

    /**
     * 判断是否有值
     *
     * 当没有时，则取项目配置中对应数据
     *
     * @return bool
     */
    public function hasFailureHttpStatusCode():bool;

    public function hasFailureResponse():bool;
}