<?php

namespace telegram\controllers;

use Telegram;
use vendor\ninazu\framework\Component\BaseController;

class MainController extends BaseController {

	public function actionMain() {
		//return [];
		//$response = Telegram::$app->bot->response->install();
		$message = Telegram::$app->bot->request->getMessage();

		return $message;
	}
}