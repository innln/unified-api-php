<?php

use innln\unifiedapi\configure\ApiConfigure;
use innln\unifiedapi\configure\ProjectConfigure;
use innln\unifiedapi\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class FirstCest
 *
 */
class FirstCest
{
    private $unifiedApi;
    /**
     * @var ProjectConfigure
     */
    private $projectConfigure;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var array 项目配置文件信息
     */
    private $projectConfig = [];

    public function _before(UnitTester $I)
    {

        $oContainer = new Container();
        $channel = "debug";// 日志记录的文件
//        $logger = new Logger("debug");
        //$logger->pushHandler();
        $currentTestFoldRoot = dirname(__DIR__);
        $unifiedFoldRoot = dirname($currentTestFoldRoot);
        $file = $unifiedFoldRoot. DIRECTORY_SEPARATOR. "logs" .DIRECTORY_SEPARATOR."debug.log";
        // 日志类加入到容器里面
        $oContainer->addShared("logger", Logger::class)->addArgument($channel)
            ->addMethodCall("pushHandler", [new StreamHandler($file, Logger::DEBUG)]);
        // http客户端
        $oContainer->addShared(\GuzzleHttp\Client::class);
        $this->container = $oContainer;
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
        $this->projectConfig = $aProjectConfigure = json_decode($sProjectConfigure, true);
        $oContainer->get("logger")->debug('项目配置数据', $aProjectConfigure);
        $this->projectConfigure = new ProjectConfigure($oContainer, $aProjectConfigure);
        $this->unifiedApi = new \innln\unifiedapi\UnifiedApi($oContainer, $this->projectConfigure);
    }

    // tests
    public function validateHttpStatusCodeTest(UnitTester $I)
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

    /**
     * @param UnitTester $I
     */
    public function hasTest(UnitTester $I){
        $apiKey = 'users';
        $api = $this->projectConfigure->getApi($apiKey);
        $this->container->get("logger")->debug($api);
        $I->assertSame($apiKey, $api->getApi(), "不存在指定{$apiKey}接口");
        $I->assertTrue($this->projectConfigure->hasApi($apiKey), "不存在{$apiKey}接口");
    }
}
