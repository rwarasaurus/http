<?php

namespace Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Kernel implements KernelInterface {

	protected $request;

	public function __construct(ServerRequestInterface $request) {
		$this->request = $request;
	}

	public function redirectTrailingSlash() {
		$path = $this->request->getUri()->getPath();

		if($path != '/' && substr($path, -1) == '/') {
			header('Location: ' . $this->request->getUri()->withPath(rtrim($path, '/')), true, 301);
			exit;
		}
	}

	public function handle(callable $callback) {
		return $callback($this->request);
	}

	public function output(ResponseInterface $response) {
		if(true === headers_sent()) {
			throw new \ErrorException('headers already sent.');
		}

		header(sprintf('%s %s %s',
			$response->getProtocolVersion(),
			$response->getStatusCode(),
			$response->getReasonPhrase()
		));

		foreach($response->getHeaders() as $name => $values) {
			foreach($values as $value) {
				header(sprintf('%s: %s', $name, $value));
			}
		}

		echo $response->getBody();
	}

}
