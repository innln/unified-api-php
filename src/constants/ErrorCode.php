<?php declare(strict_types=1);
namespace innln\unifiedapi\constants;


/**
 * 错误码常量
 * @package innln\unifiedapi\constants
 */
class ErrorCode
{
    const ERROR_HTTP_STATUS_RULE_CONFIGURE_IS_NOT_CONFIG = 1000000;
    const ERROR_CLASS_OBJECT_IS_NOT_EXIST = 1000001;
    const ERROR_CLASS_PROPERTY_IS_NOT_EXIST = 1000002;

    const MESSAGES = [
        self::ERROR_HTTP_STATUS_RULE_CONFIGURE_IS_NOT_CONFIG => "状态码规则未配置",
        self::ERROR_CLASS_OBJECT_IS_NOT_EXIST => "%s对象类不存在",
        self::ERROR_CLASS_PROPERTY_IS_NOT_EXIST => "%s类%s属性不存在"
    ];

    /**
     * 获得错误码对应消息
     * @param $code 错误码
     * @param array $params 消息参数替换
     * @return string 消息字符串
     */
    public static function getMessage($code, $params = []){
        $key = isset(self::$messages[$code]) ? self::$messages[$code]:self::$messages[self::ERROR_API_CALL];
        $params = is_array($params) ? $params : [$params];
        return $params ? vsprintf($key, $params) : $key;
    }
}