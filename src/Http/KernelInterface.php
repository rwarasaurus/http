<?php

namespace Http;

use Psr\Http\Message\ResponseInterface;

interface KernelInterface {

	public function handle(callable $callback);

	public function output(ResponseInterface $response);

}
