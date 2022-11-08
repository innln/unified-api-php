<?php

use innln\unifiedapi\configure\ProjectConfigure;
use innln\unifiedapi\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class FirstCest
{
    /**
     * @var ProjectConfigure
     */
    private $projectConfigure;

    public function _before(UnitTester $I)
    {

        $oContainer = new Container();
        $channel = "debug";
//        $logger = new Logger("debug");
        //$logger->pushHandler();
        $currentTestFoldRoot = dirname(__DIR__);
        $unifiedFoldRoot = dirname($currentTestFoldRoot);
        $file = $unifiedFoldRoot. DIRECTORY_SEPARATOR. "logs" .DIRECTORY_SEPARATOR."debug.log";
        // 日志类加入到容器里面
        $oContainer->addShared("logger", Logger::class)->addArgument($channel)
            ->addMethodCall("pushHandler", [new StreamHandler($file, Logger::DEBUG)]);
        // 从容器内获得日志类，调用日志类函数记录日志
//        $oContainer->get("logger")->warning("get logger from container ", ['params']);

        // 读取配置文件
        $configDirectors = [
            $currentTestFoldRoot . DIRECTORY_SEPARATOR . '_data'
        ];
        // 文件定位器
        $oLocator = new \Symfony\Component\Config\FileLocator($configDirectors);
        $jsonDatabaseFile = $oLocator->locate('project-example001.json', null, false);
        $sProjectConfigure = file_get_contents(array_pop($jsonDatabaseFile));
        // json数据数组化
        $aProjectConfigure = json_decode($sProjectConfigure, true);
        $this->projectConfigure = new ProjectConfigure($oContainer, $aProjectConfigure);
    }

    // tests
    public function tryToTest(UnitTester $I)
    {
        $testConfig = [
            ['httpStatusCode' => 200, 'apiHttpStatus' => '![200-299]|[400-499]|300', 'result' => false],
            ['httpStatusCode' => 401, 'apiHttpStatus' => '![200-299]|[400-499]|300', 'result' => true],
            ['httpStatusCode' => 300, 'apiHttpStatus' => '![200-299]|[400-499]|300', 'result' => true],
            ['httpStatusCode' => 500, 'apiHttpStatus' => '![200-299]|[400-499]|300', 'result' => true],
        ];
        foreach($testConfig as $config){
            $I->assertSame($config['result'], $this->projectConfigure->validateHttpStatusCode($config['httpStatusCode'], $config['apiHttpStatus']),
                "{$config['httpStatusCode']}符合{$config['apiHttpStatus']}预期判断{$config['result']}");
        }
    }
}
