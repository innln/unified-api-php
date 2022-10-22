<?php declare(strict_types=1);
namespace innln\unifiedapi;
use Monolog\Logger;

/**
 * 统一api基础类
 *
 * Class UnifiedApi
 *
 * @package innln\unifiedapi
 */
class UnifiedApi implements UnifiedApiInterface
{
    /**
     * @var null|Container 容器
     */
    protected $container = null;
    public function __construct(Container $container)
    {
       $this->container = $container;
       $this->get(Logger::class)->debug("实例化统一接口类", []);
    }
}