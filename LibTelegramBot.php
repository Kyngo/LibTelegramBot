<?php

/**
 * LibTelegramBot, por Kyngo
 * ----------------------
 * Pequeña librería en PHP para interactuar con la API de Telegram.
 */

class TelegramBot {
	// variables del token y la url base
	private $token;
	private $base_url = 'https://api.telegram.org/bot';

	public function __construct($token) {
		$this->token = $token;
		$this->base_url .= $token . '/';
	}

	private function showError($msg) {
		die(json_encode(["error" => $msg]));
	}

	private function sendRequest($function, $params) {
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $this->base_url . $function,
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_RETURNTRANSFER => 1
		]);
		// ejecuta la conexion
		$res = curl_exec($ch);
		// cierra la conexion
		curl_close($ch);
		return json_decode($res);
	}

	public function parseUpdate($update) {
		// ips de los servers de telegram
		$telegram_ip_lower = '149.154.167.197';
		$telegram_ip_upper = '149.154.167.233';

		// comprobacion de la ip de origen
		$ip = $_SERVER['REMOTE_ADDR'];
		foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR'] as $key) {
		    $addr = @$_SERVER[$key];
		    if (filter_var($addr, FILTER_VALIDATE_IP)) {
		        $ip = $addr;
		    }
		}

		// cotejemos la ip obtenida con las direcciones de confianza
		$lower_dec = (float) sprintf("%u", ip2long($telegram_ip_lower));
		$upper_dec = (float) sprintf("%u", ip2long($telegram_ip_upper));
		$ip_dec    = (float) sprintf("%u", ip2long($ip));
		// si no coincide, lo matamos
		if ($ip_dec < $lower_dec || $ip_dec > $upper_dec) {
			header("HTTP/1.1 403 Forbidden");
			header("Content-Type: text/html");
		    echo "<h1>403 Forbidden</h1>";
		}
		// si coincide, parseamos la update
		if ($update != "") {
			$data;
			try {
				$data = json_decode($update, true);
			} catch (Exception $e) {
				showError("libtelegrambot had an exception: " . $e);
			}
			header("HTTP/1.1 200 OK");
			return $data['message'];	
		} else {
			$this->showError("meh");
		}
		
	}

	// GetMe
	public function getMe() {
		return $this->sendRequest("getMe", []);
	}

	// Update
	public function getUpdates($params) {
		return $this->sendRequest("getUpdates", $params);
	}

	// Webhook
	public function setWebhook($params) {
		if (empty($params['url'])) {
			$this->showError("setWebhook requires a valid url");
		}
		return $this->sendRequest("setWebhook", $params);
	}

	public function getWebhookInfo() {
		return $this->sendRequest("getWebhookInfo", []);
	}

	public function deleteWebhook() {
		return $this->sendRequest("deleteWebhook", []);	
	}

	// Enviar mensajes
	public function sendMessage($params) {
		return $this->sendRequest("sendMessage", $params);
	}

	public function forwardMessage($params) {
		return $this->sendRequest("forwardMessage", $params);
	}

	// Multimedia
	public function sendPhoto($params) {
		return $this->sendRequest("sendPhoto", $params);
	}

	public function sendAudio($params) {
		return $this->sendRequest("sendAudio", $params);
	}

	public function sendDocument($params) {
		return $this->sendRequest("sendDocument", $params);
	}

	public function sendVideo($params) {
		return $this->sendRequest("sendVideo", $params);
	}

	public function sendVoice($params) {
		return $this->sendRequest("sendVoice", $params);
	}

	public function sendVideoNote($params) {
		return $this->sendRequest("sendVideoNote", $params);
	}

	public function sendMediaGroup($params) {
		return $this->sendRequest("sendMediaGroup", $params);
	}

	// Ubicación
	public function sendLocation($params) {
		return $this->sendRequest("sendLocation", $params);
	}

	public function editMessageLiveLocation($params) {
		return $this->sendRequest("editMessageLiveLocation", $params);
	}

	public function stopMessageLiveLocation($params) {
		return $this->sendRequest("stopMessageLiveLocation", $params);
	}

	// Pagos
	public function sendVenue($params) {
		return $this->sendRequest("sendVenue", $params);
	}

	// Contactos
	public function sendContact($params) {
		return $this->sendRequest("sendContact", $params);
	}
}

?>