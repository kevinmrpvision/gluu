<?php

/**
 * Copyright (c) 2016, 2017 François Kooman <fkooman@tuxed.net>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Mrpvision\Gluu;

use DateTime;
use Mrpvision\Gluu\Exception\AccessTokenException;
use RuntimeException;

class AccessToken
{

    /** @var string */
    private $accessToken;

    /** @var string */
    private $tokenType;

    /** @var int|null */
    private $expiresIn = null;

    /** @var string|null */
    private $buffer = 5;

    /**
     * @param array $tokenData
     */
    public function __construct(array $tokenData)
    {
        $requiredKeys = ['access_token', 'token_type', 'expires_in'];
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $tokenData)) {
                throw new AccessTokenException(sprintf('missing key "%s"', $requiredKey));
            }
        }
        $this->setAccessToken($tokenData['access_token']);
        $this->setTokenType($tokenData['token_type']);

        // set optional keys
        if (array_key_exists('expires_in', $tokenData)) {
            $this->setExpiresIn($tokenData['expires_in']);
        }
    }


    /**
     * @return string
     *
     * @see https://tools.ietf.org/html/rfc6749#section-5.1
     */
    public function getToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     *
     * @see https://tools.ietf.org/html/rfc6749#section-7.1
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @return int|null
     *
     * @see https://tools.ietf.org/html/rfc6749#section-5.1
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }


    /**
     * @param 
     *
     * @return bool
     */
    public function isExpired()
    {
        if (null === $this->getExpiresIn()) {
            // if no expiry was indicated, assume it is valid
            return false;
        }

        return time() + $this->buffer >= $this->getExpiresIn();
    }

    /**
     * @param string $jsonString
     *
     * @return AccessToken
     */
    public static function fromJson($jsonString)
    {
        $tokenData = json_decode($jsonString, true);
        if (null === $tokenData && JSON_ERROR_NONE !== json_last_error()) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();
            throw new AccessTokenException(sprintf('unable to decode JSON from storage: %s', $errorMsg));
        }

        return new self($tokenData);
    }

    /**
     * @return string
     */
    public function toJson()
    {
        $jsonData = [
                'access_token' => $this->getToken(),
                'token_type' => $this->getTokenType(),
                'expires_in' => $this->getExpiresIn(),
        ];

        if (false === $jsonString = json_encode($jsonData)) {
            throw new RuntimeException('unable to encode JSON');
        }

        return $jsonString;
    }

    /**
     * @param string $accessToken
     *
     * @return void
     */
    private function setAccessToken($accessToken)
    {
        self::requireString('access_token', $accessToken);
        // access-token = 1*VSCHAR
        // VSCHAR       = %x20-7E
        if (1 !== preg_match('/^[\x20-\x7E]+$/', $accessToken)) {
            throw new AccessTokenException('invalid "access_token"');
        }
        $this->accessToken = $accessToken;
    }

    /**
     * @param string $tokenType
     *
     * @return void
     */
    private function setTokenType($tokenType)
    {
        self::requireString('token_type', $tokenType);
        if ('bearer' !== $tokenType) {
            throw new AccessTokenException('unsupported "token_type"');
        }
        $this->tokenType = $tokenType;
    }

    /**
     * @param int|null $expiresIn
     *
     * @return void
     */
    private function setExpiresIn($expiresIn)
    {
        if (null !== $expiresIn) {
            self::requireInt('expires_in', $expiresIn);
            if (0 >= $expiresIn) {
                throw new AccessTokenException('invalid "expires_in"');
            }
            $expiresIn = time() + $expiresIn;
        }
        $this->expiresIn = $expiresIn;
    }

    /**
     * @param string $k
     * @param string $v
     *
     * @return void
     */
    private static function requireString($k, $v)
    {
        if (!is_string($v)) {
            throw new AccessTokenException(sprintf('"%s" must be string', $k));
        }
    }

    /**
     * @param string $k
     * @param int    $v
     *
     * @return void
     */
    private static function requireInt($k, $v)
    {
        if (!is_int($v)) {
            throw new AccessTokenException(sprintf('"%s" must be int', $k));
        }
    }
}
