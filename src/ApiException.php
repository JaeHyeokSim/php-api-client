<?php

class ApiException extends Exception {

	private $httpStatus;
	private $errorCode;
	private $responseBody;
	private $requestUrl;
	private $requestMethod;
	private $requestParams;
	private $retryCount;
	private $timestamp;

	public function __construct(
		$message,
		$httpStatus = null,
		$responseBody = null,
		$requestUrl = null,
		$requestMethod = null,
		$requestParams = [],
		$retryCount = 0,
		$errorCode = null
	) {

		parent::__construct($message);

		$this->httpStatus = $httpStatus;
		$this->responseBody = $responseBody;
		$this->requestUrl = $requestUrl;
		$this->requestMethod = $requestMethod;
		$this->requestParams = $requestParams;
		$this->retryCount = $retryCount;
		$this->errorCode = $errorCode;
		$this->timestamp = time();
	}

	public function getHttpStatus() {
		return $this->httpStatus;
	}

	public function getErrorCode() {
		return $this->errorCode;
	}

	public function getResponseBody() {
		return $this->responseBody;
	}

	public function getRequestUrl() {
		return $this->requestUrl;
	}

	public function getRequestMethod() {
		return $this->requestMethod;
	}

	public function getRequestParams() {
		return $this->requestParams;
	}

	public function getRetryCount() {
		return $this->retryCount;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function isClientError() {
		return $this->httpStatus >= 400 && $this->httpStatus < 500;
	}

	public function isServerError() {
		return $this->httpStatus >= 500;
	}

	public function toArray() {

		return [
			"message" => $this->getMessage(),
			"httpStatus" => $this->httpStatus,
			"errorCode" => $this->errorCode,
			"url" => $this->requestUrl,
			"method" => $this->requestMethod,
			"params" => $this->requestParams,
			"retryCount" => $this->retryCount,
			"responseBody" => $this->responseBody,
			"timestamp" => $this->timestamp
		];
	}

	public function __toString() {

		return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}
}