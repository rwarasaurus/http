<?php

namespace Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface {

	protected $scheme;

	protected $user;

	protected $pass;

	protected $host;

	protected $port;

	protected $path;

	protected $query;

	protected $fragment;

	public function fromServerParams(array $params) {
		// parse uri
		if(array_key_exists('REQUEST_URI', $params)) {
			$this->parse($params['REQUEST_URI']);
		}

		// set host
		if(array_key_exists('HTTP_HOST', $params)) {
			$this->host = explode(':', $params['HTTP_HOST'])[0];
		}

		// set port
		if(array_key_exists('SERVER_PORT', $params)) {
			$this->port = $params['SERVER_PORT'];
		}

		// Set to a non-empty value if the script was queried through the HTTPS protocol.
		if( ! empty($params['HTTPS'])) {
			$this->scheme = 'https';
		}

		return $this;
	}

	public function parse(string $url) {
		$components = parse_url($url);

		if(false === $components) {
			throw new \InvalidArgumentException('failed to parse malformed uri');
		}

		$defaults = [
			'scheme' => 'http',
			'user' => '',
			'pass' => '',
			'host' => 'localhost',
			'port' => 80,
			'path' => '/',
			'query' => '',
			'fragment' => '',
		];

		foreach(array_merge($defaults, $components) as $key => $value) {
			$this->$key = $value;
		}

		// default to port 443 for https
		if($this->scheme == 'https' && $this->port == 80) {
			$this->port = 443;
		}

		return $this;
	}

	public function getScheme() {
		return $this->scheme;
	}

	public function withScheme($scheme) {
		$this->scheme = strtolower($scheme);

		return $this;
	}

	public function getAuthority() {
		$authority = '';

		if($info = $this->getUserInfo()) {
			$authority .= $info . '@';
		}

		$authority .= $this->host;

		if($this->port) {
			$authority .= ':' . $this->port;
		}

		return $authority;
	}

	public function getUserInfo() {
		$userInfo = '';

		if($this->user) {
			$userInfo .= $this->user;

			if($this->pass) {
				$userInfo .= ':' . $this->pass;
			}
		}

		return $userInfo;
	}

	public function withUserInfo($user, $password = null) {
		$this->user = $user;
		$this->pass = $password;

		return $this;
	}

	public function getHost() {
		return $this->host;
	}

	public function withHost($host) {
		if($host != '' && strpos(':', $host) !== false) {
			list($host, $port) = explode(':', $host);

			return $this->withPort($port)->withHost($host);
		}

		$this->host = $host;

		return $this;
	}

	public function getPort() {
		return $this->port;
	}

	public function withPort($port) {
		$this->port = $port;

		return $this;
	}

	public function getPath() {
		return $this->path;
	}

	public function withPath($path) {
		$this->path = '/' . ltrim($path, '/');

		return $this;
	}

	public function getQuery() {
		return $this->query;
	}

	public function withQuery($query) {
		$this->query = $query;

		return $this;
	}

	public function getFragment() {
		return $this->fragment;
	}

	public function withFragment($fragment) {
		$this->fragment = $fragment;

		return $this;
	}

	public function __toString() {
		$str = $this->scheme . '://';

		if($info = $this->getUserInfo()) {
			$str .= $info . '@';
		}

		$str .= $this->host;

		if(
			! ($this->scheme == 'http' && $this->port == 80) &&
			! ($this->scheme == 'https' && $this->port == 443)
		) {
			$str .= ':' . $this->port;
		}

		if($this->path) {
			$str .= $this->path;
		}

		if($this->query) {
			$str .= '?' . $this->query;
		}

		if($this->fragment) {
			$str .= '#' . $this->fragment;
		}

		return $str;
	}

}
