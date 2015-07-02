<?php

namespace spec\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageSpec extends ObjectBehavior {

	public function it_is_initializable() {
		$this->shouldHaveType('Http\Message');
	}

	public function it_should_normalize_headers_from_uppercase() {
		$this->withHeader('X-FOO-TEST', 'hello')->getHeaders()->shouldHaveKey('X-Foo-Test');
	}

	public function it_should_normalize_headers_from_lowercase() {
		$this->withHeader('x-bar-test', 'hello')->getHeaders()->shouldHaveKey('X-Bar-Test');
	}

	public function it_should_normalize_headers_from_mixedcase() {
		$this->withHeader('X-bAz-TeSt', 'hello')->getHeaders()->shouldHaveKey('X-Baz-Test');
	}

}
