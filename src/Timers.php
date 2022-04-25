<?php

namespace Cumulati\Monolog;

trait Timers
{
	/**
	 * The timers
	 * @var array
	 */
	private $timers = [];

	/**
	 * The key to use for timers
	 *
	 * @var string
	 */
	private $keyTimer;

	/**
	 * The default timer key
	 *
	 * @var string
	 */
	private static $defaultKeyTimer = '_timer';

	/**
	 * Timers to attach to next log message
	 *
	 * @var array
	 */
	private $withTimers = [];

	/**
	 * The decimal precision with which to format the timers
	 * @var integer
	 */
	private $timerPrecision = 3;

	/**
	 * Add timers
	 *
	 * @param mixed $timers the timers to add
	 */
	public function addTimer(...$timers): self
	{
		$time = microtime(true);

		foreach ($timers as $c) {
			if (is_array($c)) {
				return $this->addTimer(...$c);
			}

			$this->timers[$c] = $time;
		}

		return $this;
	}

	/**
	 * Get timers
	 *
	 * @return array
	 */
	public function getTimers(): array
	{
		return $this->timers;
	}

	/**
	 * Get timerPrecision
	 *
	 * @return int
	 */
	public function getTimerPrevision(): int
	{
		return $this->timerPrevision;
	}

	/**
	 * Set timerPrecision
	 *
	 * @param int $precision the precision
	 */
	public function setTimerPrevision(int $precision)
	{
		$this->timerPrevision = $precision;
	}

	/**
	 * Set the key to use for Timers
	 *
	 * @param string $key the key
	 */
	public function setTimerKey(string $key)
	{
		$this->keyTimer = $key;
	}

	/**
	 * Get the timer key
	 *
	 * @return string
	 */
	public function getTimerKey(): string
	{
		return $this->keyTimer ?? static::getDefaultKeyTimer();
	}

	/**
	 * Set the default key timer
	 *
	 * @param string $key the default timer key
	 */
	public static function setDefaultKeyTimer(string $key)
	{
		static::$defaultKeyTimer = $key;
	}

	/**
	 * Get the default key timer
	 *
	 * @return string
	 */
	public static function getDefaultKeyTimer(): string
	{
		return static::$defaultKeyTimer;
	}

	/**
	 * Attach a timer to the next log message
	 *
	 * @param string ...$timer the timer(s)
	 */
	public function withTimer(string ...$timer): self
	{
		if (!is_array($timer)) {
			$timer = [$timer];
		}

		$this->withTimers = $timer;
		return $this;
	}

	/**
	 * Get the timers
	 */
	private function applyTimers(array $context): ?array
	{
		$timers = [];
		$key = $this->getTimerKey();

		// pull timers from context
		if (array_key_exists($key, $context)) {
			$timers = $context[$key];
			if (!is_array($timers)) {
				$timers = [$timers];
			}
		}

		// add inline timers
		if (!empty($this->withTimers)) {
			$timers = array_replace($timers, $this->withTimers);
			$this->withTimers = [];
		}

		if (empty($timers)) {
			// no timers to add
			return null;
		}

		$data = [];
		$time = microtime(true);
		foreach ($timers as $timer) {
			if (!array_key_exists($timer, $this->timers)) {
				continue;
			}

			$data[$timer] = round(
				$time - $this->timers[$timer],
				$this->timerPrecision
			);
		}

		return empty($data) ? null : $data;
	}
}
