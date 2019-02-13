<?php

use vendor\ninazu\framework\Component\Response\Response;
use vendor\ninazu\framework\Component\Response\Serializer\EmailSerializer;

return [
	'basePath' => __DIR__ . '/../',
	'components' => [
		'bot' => [
			'config' => [
				'debug' => true,
				'secureParam' => null,
			],
		],
		'mail' => [
			'config' => [
				'transport' => [
					'username' => null,
					'password' => null,
					'port' => null,
					'host' => null,
				],
			],
		],
		'router' => [
			'config' => [
				'namespace' => 'telegram\\',
				'rules' => [
					'/' => [
						'(POST|GET)/telegram/[h:key]/' => ['main', 'main'],
					],
				],
			],
		],
		'response' => [
			'config' => [
				'contentType' => Response::CONTENT_JSON,
				'serializers' => [
					Response::CONTENT_JSON => [
						'class' => EmailSerializer::class,
					],
				],
			],
		],
	],
];