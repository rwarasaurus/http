<?php

namespace Http;

use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface {

	protected $protocol = 'HTTP/1.1';

	protected $headers = [];

	protected $body = '';

	protected function normalize($str) {
		if(false === is_string($str)) {
			throw new InvalidArgumentException(sprintf('field name should be a string, %s given', gettype($str)));
		}

		$str = str_replace(['_', '-'], ' ', $str);

		$str = ucwords(strtolower($str));

		return str_replace(' ', '-', $str);
	}

	public function getProtocolVersion() {
		return $this->protocol;
	}

	public function withProtocolVersion($version) {
		$this->protocol = $version;

		return $this;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function hasHeader($name) {
		return array_key_exists($this->normalize($name), $this->headers);
	}

	public function getHeader($name) {
		return $this->headers[$this->normalize($name)];
	}

	public function getHeaderLine($name) {
		return $this->hasHeader($name) ? implode(',', $this->getHeader($name)) : '';
	}

	public function withHeader($name, $value) {
		$this->headers[$this->normalize($name)] = [$value];

		return $this;
	}

	public function withAddedHeader($name, $value) {
		if(false === $this->hasHeader($name)) {
			return $this->withHeader($name, $value);
		}

		$this->headers[$this->normalize($name)][] = $value;

		return $this;
	}

	public function withoutHeader($name) {
		unset($this->headers[$this->normalize($name)]);

		return $this;
	}

	public function getBody() {
		return $this->body;
	}

	public function withBody(StreamInterface $body) {
		$this->body = $body;

		return $this;
	}

}
