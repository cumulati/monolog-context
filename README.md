# monolog-context
A utility to create monolog context, timers and counters.

## Create a logger
```php
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Cumulati\Monolog\LogContext;
use Monolog\Formatter\LineFormatter;

// create a simple monolog logger
$lineFormat = '%level_name% > %message% %context% %extra%' . PHP_EOL;
$formatter = new LineFormatter($lineFormat);
$logger = new Logger('context');
$handler = new StreamHandler('php://stdout');
$handler->setFormatter($formatter);
$logger->pushHandler($handler);
$logger->info('Generic Logger');
```
```
INFO > Generic Logger [] []
```

## Create a LogContext
```php
$cx = new LogContext();
$cx->setLogger($logger);
$cx->info('Using manually set logger');
# Using manually set logger [] []

// Logging with the default logger
$cx = new LogContext();

// clear the default logger
LogContext::setDefaultLogger();
$cx->info('This will not log');

// set the default logger
LogContext::setDefaultLogger($logger);
$cx->info('Using the default logger');
```
```
INFO > Using manually set logger [] []
INFO > Using the default logger [] []
```

## Add Context
```php
$cx = new LogContext(['Tyrion' => 'good']);

// log with context
$cx->info('This will log with context');

// update context
$cx->addContext(['Tyrion' => 'Lannister']);
$cx->info('This will replace context');

// override context
$cx->info('This will override context', ['Tyrion' => 'bad']);

// replace all context
$cx->setContext(['Dolores' => 'Evan Rachel Wood']);
$cx->info('This will log with replaced context');

// remove all context
$cx->setContext();
$cx->info('No context added');
```
```
INFO > This will log with context {"Tyrion":"good"} []
INFO > This will replace context {"Tyrion":"Lannister"} []
INFO > This will override context {"Tyrion":"bad"} []
INFO > This will log with replaced context {"Dolores":"Evan Rachel Wood"} []
INFO > No context added [] []
```

## Counters
```php
$cx = new LogContext();

$cx->addCounter('apples');
$cx->info('Log with a counter', ['_counter' => 'apples']);
$cx->info('Log with a counter', ['_counter' => 'apples']);
$cx->info('Not incrementing a counter');
// log with multiple counters, creating one on the fly
$cx->info('Log with a counter', ['_counter' => ['apples', 'bananas']]);

// get the counters
print_r($cx->getCounters());

// reset a counter
$cx->addCounter('apples');
$cx->info('Counter is reset', ['_counter' => 'apples']);

// add multiple counters
$cx->addCounter(['apples', 'bananas']);
$cx->addCounter('pears', 'grapes');

// inline counters
$cx->withCounter('universe')
	->info('With a counter');
$cx->withCounter('hello', 'world')
	->info('With multiple counters');

// set the counter key
$cx->setCounterKey('_c');
$cx->info('Using a different counter key', ['_c' => 'fruits']);

// Set the default counter key
LogContext::setDefaultKeyCounter('C');
$cx = new LogContext();
$cx->addCounter('t');
$cx->info('This has default counter key', ['C' => 't']);
```
```
INFO > Log with a counter {"_counter":{"apples":1}} []
INFO > Log with a counter {"_counter":{"apples":2}} []
INFO > Not incrementing a counter [] []
INFO > Log with a counter {"_counter":{"apples":3,"bananas":1}} []
Array
(
    [apples] => 3
    [bananas] => 1
)
INFO > Counter is reset {"_counter":{"apples":1}} []
INFO > With a counter {"_counter":{"universe":1}} []
INFO > With multiple counters {"_counter":{"hello":1,"world":1}} []
INFO > Using a different counter key {"_c":{"fruits":1}} []
INFO > This has default counter key {"C":{"t":1}} []
```

## Timers
```php
$cx = new LogContext();

// create a timer
$cx->addTimer('start');

usleep(20000);
// log with the timer
$cx->info('Log with a timer', ['_timer' => 'start']);

// add multiple timers
$cx->addTimer(['go0', 'go1']);
$cx->addTimer('go2', 'go3');

usleep(10000);
// with an inline timer
$cx->withTimer('start')
->info('Log with a timer inline');

// get the counters
print_r($cx->getCounters());

// set the counter key
$cx->setTimerKey('_t');
$cx->info('Using a different timer key', ['_t' => 'start']);

// Set the default timer key
LogContext::setDefaultKeyTimer('T');
$cx = new LogContext();
$cx->addTimer('x');
usleep(10000);
$cx->info('This has default timer key', ['T' => 'x']);
```
```
INFO > Log with a timer {"_timer":{"start":0.022}} []
INFO > Log with a timer inline {"_timer":{"start":0.034}} []
INFO > Using a different timer key {"_t":{"start":0.034}} []
INFO > This has default timer key {"T":{"x":0.012}} []
```

# Context Ids
```php
$cx = new LogContext();
$cx->setAppendCtxId(true);
$cx->info('Add context id');
$cx->info('Which is shared with all messages through this context');

// set the ctxId key
$cx->setCtxIdKey('_');
$cx->info('Different ctx key');

// remove custom ctxIdKey
$cx->setCtxIdKey(null);

// Set default ctxId key
LogContext::setDefaultKeyCtxId('___');
$cx->info('Apple');
```
```
INFO > Add context id {"_ctx":"a554920f5edb20d1"} []
INFO > Which is shared with all messages through this context {"_ctx":"a554920f5edb20d1"} []
INFO > Different ctx key {"_":"a554920f5edb20d1"} []
INFO > Apple {"___":"73ae2580f5b59a9e"} []
```

# Levels
All levels defined in [RFC 5424](http://tools.ietf.org/html/rfc5424) are supported.
```php
$cx = new LogContext();
$cx->debug('This is a debug message');
$cx->info('This is a info message');
$cx->notice('This is a notice message');
$cx->warning('This is a warning message');
$cx->error('This is a error message');
$cx->critical('This is a critical message');
$cx->alert('This is a alert message');
$cx->emergency('This is fine.');
```
```
DEBUG > This is a debug message [] []
INFO > This is a info message [] []
NOTICE > This is a notice message [] []
WARNING > This is a warning message [] []
ERROR > This is a error message [] []
CRITICAL > This is a critical message [] []
ALERT > This is a alert message [] []
EMERGENCY > This is fine. [] []
```
