# unified-api-php
【php版本】统计对接第三方接口项目，支持对接无限个第三方
## 安装
```shell
composer require innln/unified-api-php
```

## 测试命令
### 测试文档-https://codeception.com/docs/UnitTests
```shell
// 生成单元测试类
php vendor/codeception/codeception/codecept generate:test unit 类名
// 运行整个测试
php vendor/codeception/codeception/codecept run
// 运行所有的单元测试
php vendor/codeception/codeception/codecept run unit

//运行某个单元测试
php vendor/codeception/codeception/codecept run unit businessTest.php
//或者
php vendor/codeception/codeception/codecept run tests/unit/businessTest.php

//运行某个单元测试里的某个测试用例
php vendor/codeception/codeception/codecept run tests/unit/businessTest.php:testMe

PHP codecept.phar help # 帮助文档
PHP codecept.phar bootstrap # 创建 test & codeception.yml 到当前文件夹
PHP codecept.phar build # 通过配置生成必要的类，每次修改配置都需要运行次命令
PHP codecept.phar run {suite_name} # 不跟 suite name 即是运行全部
PHP codecept.phar run api ThingTest.PHP
PHP codecept.phar run api ThingTest.PHP:method

```

codeception 提供了两种写测试代码的方式

Cept: 一个独立的测试文件。可以写一套测试流程。
Cest: 一个类文件，多个测试写在一个类中。
PHPUnit: 支持 PHPunit 原生测试框架的格式

