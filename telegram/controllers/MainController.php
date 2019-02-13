<?php

namespace telegram;

use Telegram;
use vendor\ninazu\framework\Component\BaseController;

class MainController extends BaseController {

	public function actionMain() {
		return [];

		return Telegram::$app->bot->response->sendMessage(212856439, 'Hello');
	}
}