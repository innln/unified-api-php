<?php

use innln\unifiedapi\configure\ProjectConfigure;

class FirstCest
{
    /**
     * @var ProjectConfigure
     */
    private $projectConfigure;

    public function _before(UnitTester $I)
    {

        $oContainer = new \innln\unifiedapi\Container();
        $channel = "debug";
        $logger = new \Monolog\Logger("debug");
        //$logger->pushHandler();
        $file = ROOT_PATH. DIRECTORY_SEPARATOR. "var" .DIRECTORY_SEPARATOR. "log" .DIRECTORY_SEPARATOR."debug.log";
        // 日志类加入到容器里面
        $oContainer->addShared("logger", Logger::class)->addArgument($channel)
            ->addMethodCall("pushHandler", [new \Monolog\Handler\StreamHandler($file, \Monolog\Logger::DEBUG)]);
        // 从容器内获得日志类，调用日志类函数记录日志
        $oContainer->get("logger")->warning("get logger from container ", ['params']);
        $this->projectConfigure = new ProjectConfigure();
    }

    // tests
    public function tryToTest(UnitTester $I)
    {
    }
}
