<?php

namespace Drupal\jwt_parser\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtParser {

  /**
   * Configuration.
   */
  protected $config;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('jwt_parser.settings');
  }

  /**
   * Decode a JWT.
   */
  public function decode($jwt) {

    $isAuth = $this->jwtAuth($jwt);

    if (!$isAuth) {
      return [
        'status' => 'error',
        'data' => 'authentication failed'
      ];
    }

    $parts = explode('.', $jwt);

    if (count($parts) !== 3) {
      throw new \InvalidArgumentException('Invalid JWT format.');
    }

    list($header, $payload, $signature) = $parts;

    return [
      'status' => 'success',
      'data' => json_decode($this->base64UrlDecode($payload), TRUE)
    ];
  }

  /**
   * Base64 URL decode.
   */
  private function base64UrlDecode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) {
      $data .= str_repeat('=', 4 - $remainder);
    }
    return base64_decode(strtr($data, '-_', '+/'));
  }

  /**
   * Authenticate JWT.
   */
  private function jwtAuth($jwt) {
    try {
      $decoded = JWT::decode(
        $jwt, 
        new Key(
          $this->getSecret(), 
          $this->getAlgorithm()
        )
      );
    } catch (\Exception $e) {
      \Drupal::logger('jwt_parser')->error($e->getMessage());
      return false;
    }
    
    return true;
  }

  /**
   * get the secret key.
   */
  public function getSecret() {
    return $this->config->get('secret');
  }

  /**
   * get the algorithm.
   */
  public function getAlgorithm() {
    return $this->config->get('algorithm');
  }

}


// Sample JWT token
// eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1cmwiOiIvYXBpL3YxL2NvbnRlbnQifQ.Z8EKkrSANt9twVYBAG_cbbdHU7tRGb0rlQrXILrlqeU

// algorithm type
// {
//   "alg": "HS256",
//   "typ": "JWT"
// }

// payload
// {
//   "url": "/api/v1/content"
// }

// Secret
// a10529ad8836a5696281c1a319ed528e9210845bc1a3eb59d840e762e967d7c5

