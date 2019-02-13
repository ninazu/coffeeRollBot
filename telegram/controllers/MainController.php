<?php

namespace telegram\controllers;

use Telegram;
use vendor\ninazu\framework\Component\BaseController;

class MainController extends BaseController {

	public function actionMain() {
		//return [];
		$response = Telegram::$app->bot->response->install();
		return $response;
	}
}