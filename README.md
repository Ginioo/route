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
$app->on('create', function ($a) use ($message) {
    echo $a . ' ' . $message . '<br>';
});

$app->on('retrieve', function ($a) use ($message) {
    echo $a . ' ' . $message . '<br>';
});

$app->on('update', function ($a) use ($message) {
    echo $a . ' ' . $message . '<br>';
});

$app->on('delete', function ($a) use ($message) {
    echo $a . ' ' . $message . '<br>';
});

$app->on('debug', function ($message) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    echo var_export($message, true) . '<br>';
});

$eventName = $app->getRequestEvent();
$input = $app->getInputData();

if (isset($input['debug']) && $app->hasEvent('debug')) {
    $app->emit('debug', $input);
}

if ($app->hasEvent($eventName)) {
    // 觸發事件
    $message = var_export($input, true);
    $app->emit($eventName, $message);
}

```
