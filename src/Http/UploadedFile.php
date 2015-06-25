<?php

namespace Http;

use InvalidArgumentException;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface {

	protected $name;

	protected $tmp_name;

	protected $size;

	protected $type;

	protected $error;

	public function __construct($name, $tmp_name, $size, $type, $error) {
		$this->name = $name;
		$this->tmp_name = $tmp_name;
		$this->size = $size;
		$this->type = $type;
		$this->error = $error;
	}

	public function getStream() {
		if(false === is_file($this->tmp_name)) {
			throw new InvalidArgumentException('temp file has been removed.');
		}

		return new Stream($this->tmp_name);
	}

	public function moveTo($targetPath) {
		if(false === is_uploaded_file($this->tmp_name)) {
			throw new InvalidArgumentException(sprintf('Invalid temporary file "%s".', $this->tmp_name));
		}

		return move_uploaded_file($this->tmp_name, $targetPath);
	}

	public function getSize() {
		return $this->size;
	}

	public function getError() {
		return $this->error;
	}

	public function getClientFilename() {
		return $this->name;
	}

	public function getClientMediaType() {
		return $this->type;
	}

}
