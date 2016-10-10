<?php

namespace spec\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UriSpec extends ObjectBehavior {

    protected $sample = 'http://username:password@hostname:9090/path?arg=value#anchor';

    protected $malformed = '/gaming/19-most-mindblowing-video-game-plot-twists-since-2000/page:12';

	public function it_is_initializable() {
		$this->shouldHaveType('Http\Uri');
	}

	public function it_should_parse_given_uri() {
        $this->parse($this->sample);
        $this->__toString()->shouldEqual($this->sample);
	}

    public function it_should_return_parsed_scheme() {
        $this->parse($this->sample);
        $this->getScheme()->shouldEqual('http');
	}

    public function it_should_return_parsed_user_and_host() {
        $this->parse($this->sample);
        $this->getAuthority()->shouldEqual('username:password@hostname:9090');
	}

    public function it_should_return_parsed_user() {
        $this->parse($this->sample);
        $this->getUserInfo()->shouldEqual('username:password');
	}

    public function it_should_return_parsed_host() {
        $this->parse($this->sample);
        $this->getHost()->shouldEqual('hostname');
	}

    public function it_should_return_parsed_port() {
        $this->parse('http://localhost:9090/');

        $this->getPort()->shouldEqual(9090);

        $this->parse('http://localhost/');

        $this->getPort()->shouldEqual('');

        $this->parse('https://localhost/');

        $this->getPort()->shouldEqual('');
	}

    public function it_should_return_parsed_pathname() {
        $this->parse($this->sample);
        $this->getPath()->shouldEqual('/path');
	}

    public function it_should_return_parsed_query() {
        $this->parse($this->sample);
        $this->getQuery()->shouldEqual('arg=value');
	}

    public function it_should_return_parsed_fragment() {
        $this->parse($this->sample);
        $this->getFragment()->shouldEqual('anchor');
	}

    public function it_should_throw_malformed_exception() {
        $this->shouldThrow('Http\UriMalformedException')->during('parse', [$this->malformed]);
	}

}
