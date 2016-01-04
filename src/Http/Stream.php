<?php

namespace Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface {

	protected $stream;

	protected $readable;

	protected $writable;

	protected $seekable;

	public function __construct($filepath = null) {
		$this->stream = fopen(null === $filepath ? 'php://temp' : $filepath, 'r+');
		$this->readable = $this->writable = $this->seekable = true;
	}

	public function __destruct() {
		$this->close();
	}

	public function __toString() {
		return stream_get_contents($this->stream, -1, 0);
	}

	public function close() {
		fclose($this->stream);
	}

	public function detach() {
		$stream = $this->stream;

		$this->stream = null;

		$this->readable = $this->writable = $this->seekable = false;

		return $stream;
	}

	public function getSize() {
		if($stat = fstat($this->stream)) {
			return $stat['size'];
		}
	}

	public function tell() {
		return ftell($this->stream);
	}

	public function eof() {
		return feof($this->stream);
	}

	public function isSeekable() {
		return $this->seekable;
	}

	public function seek($offset, $whence = SEEK_SET) {
		return fseek($this->stream, $offset, $whence);
	}

	public function rewind() {
		rewind($this->stream);
	}

	public function isWritable() {
		return $this->writable;
	}

	public function write($string) {
		return fwrite($this->stream, $string);
	}

	public function isReadable() {
		return $this->readable;
	}

	public function read($length) {
		return fread($this->stream, $length);
	}

	public function getContents() {
		return stream_get_contents($this->stream);
	}

	public function getMetadata($key = null) {
		$metadata = stream_get_meta_data($this->stream);

		if(null === $key) return $metadata;

		return array_key_exists($key, $metadata) ? $metadata[$key] : null;
	}

}
