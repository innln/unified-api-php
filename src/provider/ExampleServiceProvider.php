<?php declare(strict_types=1);
namespace innln\unifiedapi\provider;

use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class ExampleServiceProvider
 * @package innln\unifiedapi\serviceprovider
 */
class ExampleServiceProvider extends AbstractServiceProvider
{
    /**
     *
     * @param string $id
     * @return bool
     */
    public function provides(string $id): bool
    {
        $services = [
        ];

        return in_array($id, $services);
    }

    /**
     *
     */
    public function register(): void
    {
//        $this->getContainer()->add('key', 'value');
//
//        $this->getContainer()
//            ->add(Some\Controller::class)
//            ->addArgument(Some\Request::class)
//            ->addArgument(Some\Model::class)
//        ;
//
//        $this->getContainer()->add(Some\Request::class);
//        $this->getContainer()->add(Some\Model::class);
    }
}