<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->post('/doc/base64', function ($request, $response, $args) {
  $this->logger->info("POST doc/base64 ".date("Y-m-d H:i:s"));

  $this->doc->writeHTML($request->getBody());

  return $response->getBody()->write($this->doc->output(uniqid().'.pdf', 'E'));
});

$app->post('/doc/pdf', function ($request, $response, $args) {
  $this->logger->info("POST doc/pdf ".date("Y-m-d H:i:s"));

  $input = $request->getParsedBody();

  $this->doc->writeHTML($input['content']);

  $doc = $this->doc->output($input['name'].'.pdf', 'S');

  $stream = fopen('php://memory','r+');
  fwrite($stream, $doc);
  rewind($stream);

  $stream = new \Slim\Http\Stream($stream);

  return $response->withHeader('Content-Type', 'application/force-download')
  ->withHeader('Content-Type', 'application/octet-stream')
  ->withHeader('Content-Type', 'application/download')
  ->withHeader('Content-Description', 'File Transfer')
  ->withHeader('Content-Transfer-Encoding', 'binary')
  ->withHeader('Content-Disposition', 'attachment; filename="' . basename($input['name']) . '"')
  ->withHeader('Expires', '0')
  ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
  ->withHeader('Pragma', 'public')
  ->withBody($stream);
});
