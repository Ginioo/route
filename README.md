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

$route = new \Ginioo\Sandbox\Route();
// use this only under develop environment
$route->debug(function ($input) {
    // 設定錯誤訊息層級
    error_reporting(E_ALL);
    // 設定是否顯示錯誤訊息
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
    ini_set("html_errors", 1);
    $uniqId = uniqid('', true);

    echo "<hr/>";
    echo "<strong>{$_SERVER['HTTP_HOST']}:</strong>Hello {$uniqId}";
    echo "<br>debug start<br>";
    var_dump($input);
    echo '<br>debug end<br>';
});
// route settings
$route->group('endpoint', function() use ($route) {
    $route->group('v1', function() use ($route) {
        //route: /endpoint/v1/test/
        $route->get("test/:id?/:id2?/", "\Ginioo\Sandbox\TestController", "test");
    });
});


$inputData = $route->getInputData();
$requestRoute = $route->getRequestRoute();

try {
    // use this only under develop environment
    if (isset($inputData['debug']) && $app->hasRoute('debug')) {
        $app->emit('debug', $inputData);
    }

    // 觸發事件
    if ($app->hasRoute($requestRoute)) {
        $app->emit($requestRoute, $inputData);
    }
} catch (Exception $e) {
    echo $e->getMessage();
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
