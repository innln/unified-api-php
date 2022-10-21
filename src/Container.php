<?php declare(strict_type=1)

namespace innln\unifiedapiphp;

/**
 * 统一接口调用库容器
 * @package innln\unifiedppiphp
 */
class Container extends \League\Container\Container
{
    public function __construct(\League\Container\Definition\DefinitionAggregateInterface $definitions = null, \League\Container\ServiceProvider\ServiceProviderAggregateInterface $providers = null, \League\Container\Inflector\InflectorAggregateInterface $inflectors = null)
    {
        parent::__construct($definitions, $providers, $inflectors);
    }
}