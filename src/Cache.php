<?php

class Cache {

	private $data = [];
	private $ttl = [];

	public function set($key, $value, $expire = 60) {

		$this->data[$key] = $value;
		$this->ttl[$key] = time() + $expire;
	}

	public function get($key) {

		if (!isset($this->data[$key])) {
			return null;
		}

		if ($this->ttl[$key] < time()) {
			unset($this->data[$key]);
			unset($this->ttl[$key]);
			return null;
		}

		return $this->data[$key];
	}

}