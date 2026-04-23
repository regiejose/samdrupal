<?php

namespace Drupal\jwt_parser\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Request;

class ExternalApiController {

  public function jwtParser($token) {
    $jwt_data = $this->jwtTokenParser($token);

    $data = [
      'status' => 'success',
      'message' => '',
      'jwt_data' => $jwt_data
    ];

    return new JsonResponse($data);
  }

  /**
   * Parse JWT
   */
  private function jwtTokenParser($jwt) {
    /** @var \Drupal\jwt_parser\Service\JwtParser $parser */
    $parser = \Drupal::service('jwt_parser.service');
    $decoded = [];

    try {
      $decoded = $parser->decode($jwt);

      $header = $decoded['header'];
      $payload = $decoded['payload'];

    }
    catch (\Exception $e) {
      \Drupal::logger('jwt_parser')->error($e->getMessage());
    }

    return $decoded;
  }
}