Just another psr7 http library

	$request = new Http\ServerRequest($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES);

	$uri = $request->getUri()->getPath();

	$server = $request->getServerParams();

	$cookies = $request->getCookieParams();

	$get = $request->getQueryParams();

	$files = $request->getUploadedFiles();

	$post = $request->getParsedBody();

	$params = $request->getAttributes();
