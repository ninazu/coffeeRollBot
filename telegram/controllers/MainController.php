<?php

namespace telegram\controllers;

use Telegram;
use vendor\ninazu\framework\Component\BaseController;
use vendor\ninazu\framework\Component\Telegram\v2\Message\Message;
use vendor\ninazu\framework\Helper\Formatter;

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
						$action = "action" . ucfirst($matches[1]);
						$this->$action($message);
					}
				}

				break;
		}
	}

	private static function getTemp(int $chatId, string $ext) {
		return __DIR__ . "/../../tmp/{$chatId}.{$ext}";
	}

	private static function saveStats(Message $message, array $users) {
		file_put_contents(self::getTemp($message->chat->id, "stats"), json_encode($users));
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

	private static function getActiveList(array $users) {
		$headers = [
			" Ім'я",
			"Кількість",
			"Статус ",
		];
		$maxLen = [];
		$list = [
			null => $headers,
			0 => ["", "", ""],
		];

		foreach ($headers as $name) {
			$maxLen[] = strlen($name);
		}

		$columnsNames = ['name', 'count', 'status'];
		$columnsNamesCount = count($columnsNames);

		foreach ($users as $userId => $row) {
			if ($row['deleted']) {
				continue;
			}

			$columns = [];

			foreach ($columnsNames as $index => $column) {
				if (is_bool($row[$column])) {
					$row[$column] = $row[$column] ? "On" : "Off";
				}

				$strLen = strlen($row[$column]);

				if (!isset($maxLen[$index]) || $strLen > $maxLen[$index]) {
					$maxLen[$index] = $strLen;
				}

				if ($index === 0) {
					$row[$column] = " {$row[$column]}";
				} elseif ($index === $columnsNamesCount) {
					$row[$column] = "{$row[$column]} ";
				}

				$columns[] = $row[$column];
			}

			$list[$userId] = $columns;
		}

		foreach ($list as $row => $columns) {
			foreach ($columns as $column => $value) {
				$list[$row][$column] = Formatter::strPad($value, $maxLen[$column]);
			}

			$list[$row] = implode(' | ', $list[$row]);
		}

		$list[0] = str_repeat("-", strlen($list[0]));

		return $list;
	}

	protected function actionRoll(Message $message) {
		$users = self::getStats($message);
		$list = self::getActiveList($users);

		$excludeId = file_get_contents(self::getTemp($message->chat->id, "last"));
		unset($list[$excludeId]);

		$choseOneId = array_rand(array_filter(array_keys($list)));
		$choseOne = $users[$choseOneId]['name'];
		$response = "Час кави!\n{$choseOne} ти обраний.";

		if (isset($users[$excludeId])) {
			$response .= "\n{$users[$excludeId]} готовував минулого разу, і виключається із черги";
		}

		$this->bot->response->sendMessage($message->chat->id, $response);
		file_put_contents(self::getTemp($message->chat->id, "last"), $choseOne);
	}

	protected function actionInclude(Message $message) {
		$users = self::getStats($message);
		$users[$message->reply->from->id] = [
			'status' => true,
			'count' => isset($users[$message->reply->from->id]) ? $users[$message->reply->from->id]['count'] : 0,
			'last' => null,
			'name' => $message->reply->from->getSafeName(),
			'deleted' => false,
		];
		self::saveStats($message, $users);
		$this->bot->response->sendMessage($message->chat->id, "<b>{$message->reply->from->getSafeName()}</b> доданий до черги");
		self::actionStats($message);
	}

	protected function actionExclude(Message $message) {
		$users = self::getStats($message);
		$message->reply->from->id;
		$users[$message->reply->from->id]['status'] = false;
		$users[$message->reply->from->id]['name'] = $message->reply->from->getSafeName();
		self::saveStats($message, $users);
		$this->bot->response->sendMessage($message->chat->id, "<b>{$message->reply->from->getSafeName()}</b> виключений із черги");
		self::actionStats($message);
	}

	protected function actionDelete(Message $message) {
		$users = self::getStats($message);
		$message->reply->from->id;
		$users[$message->reply->from->id]['deleted'] = true;
		$users[$message->reply->from->id]['name'] = $message->reply->from->getSafeName();
		self::saveStats($message, $users);
		$this->bot->response->sendMessage($message->chat->id, "<b>{$message->reply->from->getSafeName()}</b> видаленний");
		self::actionStats($message);
	}

	protected function actionStats(Message $message) {
		$rows = self::getActiveList(self::getStats($message));
		$table = "<code>" . implode("\n", $rows) . "</code>";

		$this->bot->response->sendMessage($message->chat->id, $table);
	}

	protected function actionOk(Message $message) {
		$userId = file_get_contents(self::getTemp($message->chat->id, "last"));
		$users = self::getStats($message);
		$users[$userId]['count']++;
		$users[$userId]['last'] = time();
		self::saveStats($message, $users);
		self::actionStats($message);
	}

	protected function actionNews(Message $message) {

	}
}