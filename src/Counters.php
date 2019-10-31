<?php

namespace Cumulati\Monolog;

trait Counters {
	/**
	 * The counters
	 * @var array
	 */
	private $counters = [];

	/**
	 * The key to use for counters
	 *
	 * @var string
	 */
	private $keyCounter;

	/**
	 * The default counter key
	 *
	 * @var string
	 */
	private static $defaultKeyCounter = '_counter';

	/**
	 * Counters to attach to next log message
	 *
	 * @var array
	 */
	private $withCounters = [];

	/**
	 * Add counters
	 *
	 * @param mixed $counters the counters to add
	 */
	public function addCounter(...$counters)
	{
		foreach ($counters as $c)
		{
			if (is_array($c)) {
				return $this->addCounter(...$c);
			}

			$this->counters[$c] = 0;
		}
	}

	/**
	 * Get counters
	 *
	 * @return array
	 */
	public function getCounters(): array
	{
		return $this->counters;
	}

	/**
	 * Set the key to use for Counters
	 *
	 * @param string $key the key
	 */
	public function setCounterKey(string $key)
	{
		$this->keyCounter = $key;
	}

	/**
	 * Get the counter key
	 *
	 * @return string
	 */
	public function getCounterKey(): string
	{
		return $this->keyCounter ?? static::getDefaultKeyCounter();
	}

	/**
	 * Set the default key counter
	 *
	 * @param string $key the default counter key
	 */
	public static function setDefaultKeyCounter(string $key)
	{
		static::$defaultKeyCounter = $key;
	}

	/**
	 * Get the default key counter
	 *
	 * @return string
	 */
	public static function getDefaultKeyCounter(): string
	{
		return static::$defaultKeyCounter;
	}

	/**
	 * Attach a counter to the next log message
	 *
	 * @param string ...$counter the counter(s)
	 */
	public function withCounter(string ...$counter): self
	{
		if (! is_array($counter)) {
			$counter = [$counter];
		}

		$this->withCounters = $counter;
		return $this;
	}

	/**
	 * Get the counters
	 */
	private function applyCounters(array $context):? array
	{
		$counters = [];
		$key = $this->getCounterKey();

		// pull counters from context
		if (array_key_exists($key, $context)) {
			$counters = $context[$key];
			if (! is_array($counters)) {
				$counters = [$counters];
			}
		}

		// add inline counters
		if (! empty($this->withCounters)) {
			$counters = array_replace($counters, $this->withCounters);
			$this->withCounters = [];
		}

		if (empty($counters)) {
			// no counters to add
			return null;
		}

		$counts = [];
		foreach ($counters as $counter) {
			if (! array_key_exists($counter, $this->counters)) {
				$this->counters[$counter] = 0;
			}

			$counts[$counter] = ++$this->counters[$counter];
		}

		return empty($counts) ? null : $counts;
	}
}
