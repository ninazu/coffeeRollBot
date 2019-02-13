<?php

use vendor\ninazu\framework\Core\BaseApplication;

require_once __DIR__ . '/../vendor/ninazu/framework/Core/BaseApplication.php';

/**
 * @inheritdoc
 *
 * @property \vendor\ninazu\framework\Component\Telegram\v2\Bot $bot
 */
class Telegram extends BaseApplication {

	/**
	 * @var Telegram
	 */
	public static $app;
}