<?php

namespace Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface {

	protected $requestTarget;

	protected $method;

	protected $uri;

	public function getRequestTarget() {
		return $this->requestTarget;
	}

	public function withRequestTarget($requestTarget) {
		$this->requestTarget = $requestTarget;

		return $this;
	}

	public function getMethod() {
		return $this->method;
	}

	public function withMethod($method) {
		$this->method = $method;

		return $this;
	}

	public function getUri() {
		return $this->uri;
	}

	public function withUri(UriInterface $uri, $preserveHost = false) {
		$this->uri = $uri;

		return $this;
	}

}
