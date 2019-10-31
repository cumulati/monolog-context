<?php

require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Cumulati\Monolog\LogContext;

$logger = new Logger('context');
$logger->pushHandler(new StreamHandler('php://stdout'));
$logger->info('Generic Logger');

// Set the default logge
LogContext::setDefaultLogger($logger);


/**
 * Define a LogContext
 */
echo '# Loggers' . PHP_EOL;
$cx = new LogContext();
$cx->setLogger($logger);
$cx->info('Using manually set logger');

// Logging with the default logger
$cx = new LogContext();

// clear the default logger
LogContext::setDefaultLogger();
$cx->info('This will not log');

// set the default logger
LogContext::setDefaultLogger($logger);
$cx->info('Using the default logger');


/**
 * Context
 */
echo '# Context' . PHP_EOL;
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

/**
 * Counters
 */
echo '# Counters' . PHP_EOL;
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


/**
 * Timers
 */
echo '# Timers' . PHP_EOL;
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


/**
 * Context Ids
 */
echo '# CtxIds' . PHP_EOL;
$cx = new LogContext();
$cx->setAppendCtxId(true);
$cx->info('Add context id');
$cx->info('Which is shared with all messages through this context');

// set the ctxId key
$cx->setCtxIdKey('_');
$cx->info('Different ctx key');

// remove custom ctxIdKey
$cx->setCtxIdKey(null);

// default to appending ctxId
LogContext::setDefaultAppendCtxId(true);

// Set default ctxId key
LogContext::setDefaultKeyCtxId('___');
$cx = new LogContext();
$cx->info('Apple');
