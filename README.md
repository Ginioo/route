# Component name and description
This's a micro component that makes you construct a tiny sandbox website easily.

# Install instruction
1. create a composer.json in your local project folder
```
{
    "require": {
        "ginioo/sandbox": "dev-master"
    }
}
```
2. then execute
```
$ composer install
```
   or execute this command
```
$ composer require  "ginioo/sandbox:”dev-master"
```

# Usage instruction
##how to use Event?
```php
// varialbe outside of the closure bellow
$message = 'test Message';

// initialize event object;
$oEvent = new Ginioo\Sandbox\Event();

// 註冊事件
$oEvent->on('test', function ($a) use ($message) {
    echo $message;
    var_dump($a);
});

// 確認事件是否存在
if ($oEvent->hasEvent('test')) {
    // 觸發事件
    $oEvent->emit('test', array('1', '2'));
}
```

# Testing instruction
add index.php under project root folder with sample code as follows
```php
//using composer's autoload
require('vendor/autoload.php');

$rootPath = getcwd();
$loader = new \Composer\Autoload\ClassLoader();
// register classes with namespaces
$loader->addPsr4('Controller\\', $rootPath . '/controllers');
// activate the autoloader
$loader->register();
// $loader->setUseIncludePath(true);

use \Ginioo\Sandbox\Route as SampleApplication;
$app = new SampleApplication();

// route
$app->get('auction/', '\Controller\AuctionController', 'index');

if ($app->hasEvent($eventName)) {
    // 觸發事件
    try {
        $app->emit($eventName, $input);
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
}

/*
// use this only under develop environment
$app->debug(function ($input) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
    ini_set("html_errors", 1);

    echo 'debug start<br>';
    var_dump($input);
    echo 'debug end<br>';
});
// */

$input = $app->getInputData();
$eventName = $app->getRequestEvent();

try {
    if ($app->hasEvent($eventName)) {
        // 觸發事件
        $app->emit($eventName, $input);
    } else {
        $resources = $app->getResource();
        $header = $resources[0];
        $subject = "{$rootPath}{$resources[1]}";
        header($header);
        echo file_get_contents($subject);
        exit();
    }
} catch (Exception $e) {
    var_dump($e->getMessage());
}

if (isset($input['debug']) && $app->hasEvent('debug')) {
    $app->emit('debug', $input);
}
```

if you use apache, add .htaccess under project root folder with sample code as follows
```sh
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule "^(.*)" "index.php" [NC,L]
```

# Contributing instruction

# Support resources

# Author credit

# Software license