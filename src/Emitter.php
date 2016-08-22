<?php

namespace Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Emitter {

	public function emit(ResponseInterface $response) {
		if(true === headers_sent()) {
			throw new \RuntimeException('headers already sent.');
		}

		header(sprintf('%s %s %s',
			$response->getProtocolVersion(),
			$response->getStatusCode(),
			$response->getReasonPhrase()
		));

		foreach($response->getHeaders() as $name => $values) {
			foreach($values as $value) {
				header(sprintf('%s: %s', $name, $value), false);
			}
		}

		echo $response->getBody();
	}

}
