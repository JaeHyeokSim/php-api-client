<?php

class HttpClient {

	private $baseUrl;
	private $defaultParams = [];
	private $timeout = 5;
	private $retry = 2;
	private $cache;

	public function __construct($baseUrl, $options = []) {

		$this->baseUrl = rtrim($baseUrl, "/");

		if (isset($options['params'])) {
			$this->defaultParams = $options['params'];
		}

		if (isset($options['timeout'])) {
			$this->timeout = $options['timeout'];
		}

		if (isset($options['retry'])) {
			$this->retry = $options['retry'];
		}

		if (isset($options['cache'])) {
			$this->cache = $options['cache'];
		}
	}

	public function get($endpoint, $params = []) {

		return $this->request("GET", $endpoint, $params);
	}

	public function post($endpoint, $params = []) {

		return $this->request("POST", $endpoint, $params);
	}

	private function request($method, $endpoint, $params) {

		$params = array_merge($this->defaultParams, $params);

		$url = $this->baseUrl . "/" . ltrim($endpoint, "/");

		if ($method === "GET") {
			$url .= "?" . http_build_query($params);
		}

		$cacheKey = md5($method . $url . json_encode($params));

		if ($this->cache) {
			$cached = $this->cache->get($cacheKey);
			if ($cached !== null) {
				return $cached;
			}
		}

		$attempt = 0;

		while ($attempt <= $this->retry) {

			$ch = curl_init();

			curl_setopt_array($ch, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => $this->timeout,
				CURLOPT_CUSTOMREQUEST => $method
			]);

			if ($method === "POST") {
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			}

			$response = curl_exec($ch);
			$error = curl_error($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			if (!$error && $status < 500) {

				$data = json_decode($response, true);

				if ($this->cache) {
					$this->cache->set($cacheKey, $data);
				}

				return $data;
			}

			$attempt++;
		}

		throw new ApiException("API request failed");
	}

}