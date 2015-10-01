#how to use?
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
