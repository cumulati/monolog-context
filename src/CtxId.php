<?php

namespace Cumulati\Monolog;

use Closure;

trait CtxId
{
	/**
	 * Include CtxId by default
	 *
	 * @var bool
	 */
	private static $defaultAppendCtxId = false;

	/**
	 * Do we include ids
	 *
	 * @var bool
	 */
	private $appendCtxId = true;

	/**
	 * The ctxId Key
	 *
	 * @var string
	 */
	private $keyCtxId;

	/**
	 * The default ctxId key
	 *
	 * @var string
	 */
	private static $defaultKeyCtxId = '_ctx';

	/**
	 * The ctxId
	 *
	 * @var string
	 */
	private $ctxId = null;

	/**
	 * Set if we should include ids
	 *
	 * @param bool $include should we include ids
	 */
	public function setAppendCtxId(bool $include)
	{
		$this->appendCtxId = $include;
	}

	/**
	 * Set default if we should append a ctx id
	 *
	 * @param bool $include should we include id by default
	 */
	public static function setDefaultAppendCtxId(bool $include)
	{
		static::$defaultAppendCtxId = $include;
	}

	/**
	 * Get default if we should append a ctx id
	 *
	 * @return bool
	 */
	public static function getDefaultAppendCtxId()
	{
		return static::$defaultAppendCtxId;
	}

	/**
	 * Get if we should include ids
	 *
	 * @return bool
	 */
	public function getAppendCtxId(): bool
	{
		return $this->appendCtxId ?? static::getDefaultAppendCtxId();
	}

	/**
	 * Get the ctxId
	 */
	public function getCtxId(): string
	{
		return $this->ctxId;
	}

	/**
	 * Set the ctxId key
	 *
	 * @param string $key the CtxId Key
	 */
	public function setCtxIdKey(string $key = null)
	{
		$this->keyCtxId = $key;
	}

	/**
	 * Get the ctxId key
	 *
	 * @return string
	 */
	public function getCtxIdKey(): string
	{
		return $this->keyCtxId ?? static::getDefaultKeyCtxId();
	}

	/**
	 * Set the default key ctxId
	 *
	 * @param string $key the default ctxId key
	 */
	public static function setDefaultKeyCtxId(string $key)
	{
		static::$defaultKeyCtxId = $key;
	}

	/**
	 * Get the default key ctxId
	 *
	 * @return string
	 */
	public static function getDefaultKeyCtxId(): string
	{
		return static::$defaultKeyCtxId;
	}

	/**
	 * Generate a the ctxId
	 *
	 * @return string
	 */
	public function generateCtxId(): string
	{
		return $this->ctxId = substr(md5(rand() . rand()), 0, 16);
	}
}
