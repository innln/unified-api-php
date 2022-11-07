<?php


namespace innln\unifiedapi\configure;

/**
 * Class AuthenticationConfigure 认证配置类
 * @package innln\unifiedapi\configure
 */
class AuthenticationConfigure
{
    /**
     * @var string 认证类型
     * 可选类型：
     * HTTP_BASIC_AUTH,DIGEST,
     * HTTP_SSL_CLIENT,
     * OAUTH2，
     * COOKIE_SESSION_AUTH(用户密码认证)，
     * TOKEN_AUTH,
     * JSON_WEB_TOEKN
     *
     */
    public $type;
    /**
     * @var string 授权类型
     * 当type=OAUTH2时，需设置此参数，可选值：
     * AUTHENTICATION_CODE
     * IMPLICIT
     * RESOURCE_OWNER_PASSWORD_CREDENTIALS
     * CLIENT_CREDENTIALS
     */
    public $grantType;
    /**
     * @var int 有效时长
     * @example 7200
     */
    public $expires;


}