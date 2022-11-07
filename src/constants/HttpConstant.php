<?php declare(strict_types=1);
namespace innln\unifiedapi\constants;

/**
 * Class HttpConstant http相关的常量
 * @package innln\unifiedapi\constants
 */
class HttpConstant
{
    /**
     * HTTP 请求方式-获取 请求
     */
    const REQUEST_METHOD_GET = 'GET';
    /**
     * HTTP POST 添加请求
     */
    const REQUEST_METHOD_POST = 'POST';
    /**
     * HTTP PUT 更新全部内容请求
     */
    const REQUEST_METHOD_PUT = 'PUT';
    /**
     * HTTP PATCH 更新部分内容请求
     */
    const REQUEST_METHOD_PATCH = 'PATCH';
    /**
     * HTTP DELETE 删除请求
     */
    const REQUEST_METHOD_DELETE = 'DELETE';

    /**
     * http 请求数据提交类型
     */
    const REQUEST_TYPE_QUERY = "query";
    const REQUEST_TYPE_JSON = "json";
    const REQUEST_TYPE_FORM_PARAMS = "form_params";
    const REQUEST_TYPE_MULTIPART = "multipart";
    /**
     * http请求返回值类型
     */
    const RESPONSE_TYPE_TEXT = 'text';
    const RESPONSE_TYPE_XML = 'xml';
    const RESPONSE_TYPE_JSON = 'json';
    const RESPONSE_TYPE_STREAM = 'stream';


}