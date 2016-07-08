<?php

namespace Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface {

	protected $code = '200';

	protected $reasonPhrase = 'OK';

	public function getStatusCode() {
		return $this->code;
	}

	public function withStatus($code, $reasonPhrase = '') {
		$this->code = $code;
		$this->reasonPhrase = $reasonPhrase;

		return $this;
	}

	public function getReasonPhrase() {
		return $this->reasonPhrase;
	}

}
