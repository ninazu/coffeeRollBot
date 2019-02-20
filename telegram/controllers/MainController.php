<?php

namespace telegram\controllers;

use Telegram;
use vendor\ninazu\framework\Component\BaseController;
use vendor\ninazu\framework\Component\Telegram\v2\Message\Message;

class MainController extends BaseController {

	private $bot;

	public function actionMain() {
		$this->bot = Telegram::$app->bot;
		$message = $this->bot->request->getMessage();
		$response = null;

		switch (true) {
			case $message instanceof Message:
				if ($entities = $message->getEntities()) {
					if (preg_match("/\/(\w+)@{$this->bot->getBotName()}/", $entities[0], $matches)) {
						$response = $this->bot->response->sendMessage($message->chat->id, print_r($matches, true));
					}
				}

				break;
		}

		return $response;
	}

	private function getCommand() {

	}
}