<?php

namespace Http;

use Psr\Http\Message\UriInterface;
use Psr\Http\Message\RequestInterface;

class Uri implements UriInterface {

	protected $scheme = 'http';

	protected $user = '';

	protected $pass = '';

	protected $host = 'localhost';

	protected $port = '';

	protected $path = '/';

	protected $query = '';

	protected $fragment = '';

	public static function fromServerParams(array $params) {
		$uri = new Uri;

		// Set to a non-empty value if the script was queried through the HTTPS protocol.
		$uri->withScheme( ! empty($params['HTTPS']) ? 'https' : 'http');

		if(array_key_exists('HTTP_HOST', $params)) {
			$uri->withHost($params['HTTP_HOST']);
		}

		if(array_key_exists('SERVER_PORT', $params)) {
			$uri->withPort($params['SERVER_PORT']);
		}

		if(array_key_exists('REQUEST_URI', $params)) {
			$uri->withPath(explode('?', $params['REQUEST_URI'])[0]);
		}

		if(array_key_exists('QUERY_STRING', $params)) {
			$uri->withQuery($params['QUERY_STRING']);
		}

		return $uri;
	}

	public function parse(string $uri) {
		$parts = parse_url($uri);

		if($parts === false) {
			throw new UriMalformedException(sprintf('failed to parse malformed uri: %s', $uri));
		}

		$defaults = [
			'scheme' => 'http',
			'host' => '',
			'port' => '',
			'user' => '',
			'pass' => '',
			'path' => '',
			'query' => '',
			'fragment' => '',
		];

		foreach(array_merge($defaults, $parts) as $part => $value) {
			$this->{$part} = $value;
		}
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

		if($this->port) {
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
