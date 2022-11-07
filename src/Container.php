<?php declare(strict_types=1);

namespace innln\unifiedapi;
use League\Container\Definition\DefinitionAggregateInterface;
use League\Container\ServiceProvider\ServiceProviderAggregateInterface;
use League\Container\Inflector\InflectorAggregateInterface;
/**
 * 统一接口调用库容器
 *
 * @package innln\unifiedapi
 */
class Container extends \League\Container\Container
{
    public function __construct(DefinitionAggregateInterface $definitions = null, ServiceProviderAggregateInterface $providers = null,
                                InflectorAggregateInterface $inflectors = null)
    {
        parent::__construct($definitions, $providers, $inflectors);
    }
}