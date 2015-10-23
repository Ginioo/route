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
```php
use \Sandbox\Application as TestApplication;

$app = new TestApplication();
$eventName = $app->getRequestEvent();
$input = $app->getInputData();

// route
$app->post('path/sandBox.php', 'TestController', 'test');
$app->get('path/sandBox.php', 'TestController', 'test2');
$app->put('path/sandBox.php', 'TestController', 'test');
$app->delete('path/sandBox.php', 'TestController', 'test');

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
