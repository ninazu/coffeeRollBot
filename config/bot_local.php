<?php

use vendor\ninazu\framework\Component\Telegram\v2\Bot;

return [
	'adminEmail' => 'sayu.urs@gmail.com',
	'components' => [
		'bot' => [
			'class' => Bot::class,
			'config' => [
				'debug' => true,
				'secureParam' => 'c9f0fa9e9609a659b385e06f33003a79',
				'key' => '741472895:AAGmimIZQNEOLrUlfXleRwlanug_ycWM61g',
				'botName' => 'coffeeRollBot',
				'webHookUrl' => 'https://profile.pay.call4me.info/telegram/c9f0fa9e9609a659b385e06f33003a79',
			],
		],
		'mail' => [
			'config' => [
				'transport' => [
					'host' => 'mail.ukraine.com.ua',
					'username' => "profilepay@call4me.info",
					'password' => "hR3CM9M5uzb1",
				],
			],
		],
	],
];