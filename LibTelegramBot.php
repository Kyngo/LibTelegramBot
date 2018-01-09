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
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code != 200) { 
			return ["error" => "server returned an error", "response" => $res];
		}
		// cierra la conexion
		curl_close($ch);
		return json_decode($res);
	}

	public function parseUpdate($update) {
		$data;
		try {
			$data = json_decode($update);
		} catch (Exception $e) {
			showError("an exception ocurred: " . $e);
		}
		return $data['message'];
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