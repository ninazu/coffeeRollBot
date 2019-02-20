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
						//$response =
						$action = "action" . ucfirst($matches[1]);
						$this->$action($message);
					}
				}

				break;
		}

		$this->bot->response->sendMessage($message->chat->id, $this->bot->request->getRawData());
	}

	private static function getTemp($chatId, $ext) {
		return __DIR__ . "/../../tmp/{$chatId}.{$ext}";
	}

	private function actionRoll(Message $message) {
		$users = $users = self::getStats($message);
		$exclude = '@user1';
		$choseOne = '@user2';
		$this->bot->response->sendMessage($message->chat->id, "Час кави! {$choseOne} ти обраний.\n{$exclude} готовував минулого разу, і виключається із черги");
		file_put_contents(self::getTemp($message->chat->id, "last"), $choseOne);
	}

	private function actionInclude(Message $message) {
		$message->reply->from->id;
		$this->bot->response->sendMessage($message->chat->id, "Доданий до списку {$message->reply->from->firstName}");
		$users = self::getStats($message);
	}

	private function actionExclude(Message $message) {
		$users = self::getStats($message);
	}

	private function actionDelete(Message $message) {
		$users = self::getStats($message);
	}

	private static function getStats(Message $message) {
		$fileName = self::getTemp($message->chat->id, "stats");
		$data = null;

		if (file_exists($fileName)) {
			$data = file_get_contents($fileName);
		}

		if (!$data) {
			return [];
		}

		return json_decode($data, true);
	}

	private function actionNews(Message $message) {

	}

	private function actionStats(Message $message) {

	}

	private function actionOk(Message $message) {
	}
}