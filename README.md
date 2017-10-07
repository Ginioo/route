# Component name and description
This's a micro component that makes you construct a tiny sandbox website easily.

# Install instruction
```
$ composer require ginioo/route
```

# Usage instruction
add `route.php` under project root folder with sample code as follows
```php
<?php

$route = new \Ginioo\Route\Route;

// use this only under develop environment
$route->debug(function ($input) {
    error_reporting(E_ALL);       // 設定錯誤訊息層級
    ini_set("display_errors", 1); // 設定是否顯示錯誤訊息
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
    $route->group('v1.0', function() use ($route) {
        // route: /endpoint/v1.0/test/123/
        // ":id"  is a variable
        // "?":   means optional
        $route->get("test/:id?/", "\Ginioo\Route\TestController", "test");
    });
});
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
        // route: /endpoint/v1/test/
        // "?": optional
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
