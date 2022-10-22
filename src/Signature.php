<?php declare(strict_types=1);
namespace innln\unifiedapi;

use Monolog\Logger;

/**
 * 签名基础类
 * @package innln\unifiedapi
 */
class Signature implements SignatureInterface
{
    /**
     * @var Container 容器
     */
    protected $container = null;
    /**
     * @var string 数据签名的名称
     */
    protected $signatureColumnName = 'Signature';

    /**
     * Signature constructor.
     * @param Container $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        // 日志
        $this->container->get(Logger::class)->debug('实例化签名类', ['class' => self::class]);
    }

    /**
     * 排序
     * @param array $parameters
     * @param int $sortingType
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function sort(array $parameters, int $sortingType):array {
        $this->container->get(Logger::class)->debug('开始进行排序,参数', [
            'parameters' => $parameters, 'sortingType' => $sortingType]);

        // 结果
        $result = [];
        $this->container->get(Logger::class)->debug('排序结果', ['result' => $result]);
        return $result;
    }

    /**
     * 签名
     * @param array $parameters
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function sign(array $parameters):string {
        $signature = null;
        $this->container->get(Logger::class)->debug('签名前，参数', ['parameters' => $parameters]);

        $this->container->get(Logger::class)->debug('签名结果', ['signature' => $signature]);
        return $signature;
    }

    /**
     * 获得签名的key
     * @return string
     */
    public function getSignatureColumnName(){
        return $this->signatureColumnName;
    }
}