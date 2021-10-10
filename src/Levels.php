<?php

namespace Cumulati\Monolog;

trait Levels
{
	/**
	 * Log an emergency message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function emergency($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}

	/**
	 * Log an alert message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function alert($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}

	/**
	 * Log a critical message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function critical($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}

	/**
	 * Log an error message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function error($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}

	/**
	 * Log a warning message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function warning($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}

	/**
	 * Log a notice to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function notice($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}

	/**
	 * Log an informational message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function info($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}

	/**
	 * Log a debug message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return self
	 */
	public function debug($message, array $context = []): self
	{
		$this->writeLog(__FUNCTION__, $message, $context);

		return $this;
	}
}
