#how to use Event?
```php
// varialbe outside of the closure bellow
$message = 'test Message';

// initialize event object;
$oEvent = new \Sandbox\Event();

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

#how to use Application?
add index.php with sample code as following, move index.php into {web root}/vendor/ginioo/sample/ 
```php
//using composer's autoload
require(dirname(dirname(__DIR__)) . '/autoload.php');
$loader = new \Composer\Autoload\ClassLoader();
// register classes with namespaces
$loader->addPsr4('Ginioo\Sandbox\\', dirname(dirname(__DIR__)) . '/Ginioo/Sandbox');
// activate the autoloader
$loader->register();
// $loader->setUseIncludePath(true);

use \Ginioo\Sandbox\Application as SampleApplication;
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

.htaccess sample
```sh
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule "^api(.*)" "EstBadminton/index.php" [NC,L]
```
