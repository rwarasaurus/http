<?php

namespace spec\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServerRequestSpec extends ObjectBehavior {

    public function it_is_initializable() {
        $this->shouldHaveType('Http\ServerRequest');
    }

    public function it_should_parse_requested_uri() {
        $server = [
            'REQUEST_URI' => '/foo?bar=baz#qux',
            'REQUEST_METHOD' => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'HTTP_HOST' => 'loclahost.localdomain',
            'HTTPS' => '1',
        ];
        $this->beConstructedWith([], [], $server, [], [], []);
        $this->getUri()->getHost()->shouldEqual('loclahost.localdomain');
        $this->getUri()->getPath()->shouldEqual('/foo');
        $this->getUri()->getScheme()->shouldEqual('https');
    }

}
