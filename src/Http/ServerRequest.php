<?php

namespace Http;

use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends Request implements ServerRequestInterface {

	protected $query;

	protected $data;

	protected $server;

	protected $cookies;

	protected $files;

	protected $params;

	public function __construct(array $query = null, array $data = null, array $server = null, array $cookies = null, array $files = [], array $params = []) {
		$this->query = $query;
		$this->data = $data;
		$this->server = $server;
		$this->cookies = $cookies;
		$this->params = $params;

		foreach($this->server as $name => $value) {
			if(strpos($name, 'HTTP_') === 0) {
				$this->withAddedHeader(substr($name, 5), $value);
			}
		}

		$this->withRequestTarget($this->server['REQUEST_URI']);
		$this->withMethod($this->server['REQUEST_METHOD']);
		$this->withProtocolVersion($this->server['SERVER_PROTOCOL']);

		$uri = new Uri();
		$uri->parse($this->getHeaderLine('Host').$this->server['REQUEST_URI']);
		$this->withUri($uri);

		$this->files = [];

		foreach($files as $file) {
			if(is_array($file['name'])) {
				for($index = 0; $index < count($file['name']); $index++) {
					$this->files[] = new UploadedFile($file['name'][$index], $file['tmp_name'][$index], $file['size'][$index], $file['type'][$index], $file['error'][$index]);
				}
			}
			else {
				$this->files[] = new UploadedFile($file['name'], $file['tmp_name'], $file['size'], $file['type'], $file['error']);
			}
		}
	}

	public function getServerParams() {
		return $this->server;
	}

	public function getCookieParams() {
		return $this->cookies;
	}

	public function withCookieParams(array $cookies) {
		$this->cookies = $cookies;

		return $this;
	}

	public function getQueryParams() {
		return $this->query;
	}

	public function withQueryParams(array $query) {
		$this->query = $query;

		return $this;
	}

	public function getUploadedFiles() {
		return $this->files;
	}

	public function withUploadedFiles(array $uploadedFiles) {
		$this->files = $uploadedFile;

		return $this;
	}

	public function getParsedBody() {
		return $this->data;
	}

	public function withParsedBody($data) {
		$this->data = $data;

		return $this;
	}

	public function getAttributes() {
		return $this->params;
	}

	public function withAttributes(array $params) {
		$this->params = $params;

		return $this;
	}

	public function getAttribute($name, $default = null) {
		return array_key_exists($name, $this->params) ? $this->params[$name] : $default;
	}

	public function withAttribute($name, $value) {
		$this->params[$name] = $value;

		return $this;
	}

	public function withoutAttribute($name) {
		unset($this->params[$name]);

		return $this;
	}

}
