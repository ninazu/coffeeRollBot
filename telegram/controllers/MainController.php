<?php

namespace telegram\controllers;

use Telegram;
use vendor\ninazu\framework\Component\BaseController;
use vendor\ninazu\framework\Component\Telegram\v2\Message\CallbackQuery;
use vendor\ninazu\framework\Component\Telegram\v2\Message\DummyMessage;
use vendor\ninazu\framework\Component\Telegram\v2\Message\Message;

class MainController extends BaseController {

	private $bot;

	public function actionMain() {
		$this->bot = Telegram::$app->bot;

		$message = $this->bot->request->getMessage();
		$response = null;

		switch (true) {
			case $message instanceof Message:
				$response = $this->bot->response->sendMessage($message->user->id, $this->bot->request->getRawData());

				break;
		}

		return $response;
	}
}