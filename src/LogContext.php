<?php

namespace Cumulati\Monolog;

use Monolog\Logger;

class LogContext
{
	use Levels;
	use Timers;
	use Counters;

	/**
	 * The default logger to use
	 * @var Logger
	 */
	private static $defaultLogger = null;

	/**
	 * The logger
	 * @var Logger
	 */
	private $logger;

	/**
	 * The context
	 * @var array
	 */
	private $context = [];


	public function __construct(array $ctx = [], Logger $logger = null)
	{
		$this->addContext($ctx);

		if ($logger !== null) {
			$this->setLogger($logger);
		}
	}

	/**
	 * Set the logger
	 *
	 * @param Logger $logger
	 */
	public function setLogger(Logger $logger = null)
	{
		$this->logger = $logger;
	}

	/**
	 * Set the default logger
	 *
	 * @param Logger $logger
	 */
	public static function setDefaultLogger(Logger $logger = null)
	{
		static::$defaultLogger = $logger;
	}

	/**
	 * Get the logger. Return default logger if none
	 *
	 * @return Logger
	 */
	public function getLogger():? Logger
	{
		if ($this->logger) {
			return $this->logger;
		}

		return static::$defaultLogger;
	}

	/**
	 * Add context
	 *
	 * @param array $context the context to add
	 */
	public function addContext(array $context)
	{
		if (empty($context)) {
			return;
		}

		if (array_key_exists($this->keyTimer, $context)) {
			unset($context[$this->keyTimer]);
		}

		if (array_key_exists($this->keyCounter, $context)) {
			unset($context[$this->keyCounter]);
		}

		$this->context = array_replace_recursive($this->context, $context);
	}

	/**
	 * Set the context, replacing existing context
	 *
	 * @param array $context the context
	 */
	public function setContext(array $context = [])
	{
		$this->context = $context;
	}

	/**
	 * Get the context
	 *
	 * @return array
	 */
	public function getContext(): array
	{
		return $this->context;
	}

	/**
	 * Write a message to the log.
	 *
	 * @param  string  $level
	 * @param  string  $message
	 * @param  array   $context
	 * @return void
	 */
	private function writeLog($level, $message, $context)
	{
		$logger = $this->getLogger();
		if (empty($logger)) {
			return;
		}

		$counts = $this->applyCounters($context);
		$timers = $this->applyTimers($context);
		$context = array_replace_recursive($this->context, $context);

		if (! empty($counts)) {
			$context[$this->keyCounter] = $counts;
		}

		if (! empty($timers)) {
			$context[$this->keyTimer] = $timers;
		}

		$logger->{$level}($message, $context);
	}
}
