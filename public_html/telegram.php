<?php

use vendor\ninazu\framework\Core\Environment;

require_once 'Telegram.php';
require_once __DIR__ . '/../vendor/autoload.php';

(new Telegram([
	'vendor' => [__DIR__ . '/../vendor'],
	'telegram' => [__DIR__ . '/../telegram'],
]))
	->setup(function () {
		Environment::setDevelopmentIP([
			'127.0.0.1',
			'127.0.1.1',
		]);

		if (Environment::getLastPath(__DIR__ . '/../') === 'coffee.loc') {
			Environment::setEnvironment(Environment::ENVIRONMENT_LOCAL);
		} else {
			Environment::setEnvironment(Environment::ENVIRONMENT_DEVELOPMENT);
		}

		return array_replace_recursive(require __DIR__ . '/../config/bot.php', require __DIR__ . "/../config/bot_local.php" . '');
	})
	->execute();

