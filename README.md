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

use \Ginioo\Sandbox\Route as SampleApplication;
$app = new SampleApplication();
$eventName = $app->getRequestEvent();
$input = $app->getInputData();

// route
$app->post('vendor/ginioo/sample/', '\Ginioo\Sandbox\TestController', 'test');
$app->get('vendor/ginioo/sample/', '\Ginioo\Sandbox\TestController', 'test2');
$app->put('vendor/ginioo/sample/', '\Ginioo\Sandbox\TestController', 'test');
$app->delete('vendor/ginioo/sample/', '\Ginioo\Sandbox\TestController', 'test');

$app->debug(function ($input) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
    ini_set("html_errors", 1);

    echo 'debug start<br>';
    var_dump($input);
    echo 'debug end<br>';
});

if (isset($input['debug']) && $app->hasEvent('debug')) {
    $app->emit('debug', $input);
}

if ($app->hasEvent($eventName)) {
    // 觸發事件
    try {
        $app->emit($eventName, $input);
    } catch (Exception $e) {
        var_dump($e->getMessage());
    }
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