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

namespace fkooman\OAuth\Client\Tests;

use fkooman\OAuth\Client\AccessToken;
use fkooman\OAuth\Client\TokenStorageInterface;

class TestTokenStorage implements TokenStorageInterface
{
    /** @var array */
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    /**
     * @param string $userId
     *
     * @return array
     */
    public function getAccessTokenList($userId)
    {
        if (!array_key_exists(sprintf('_oauth2_token_%s', $userId), $this->data)) {
            return [];
        }

        return $this->data[sprintf('_oauth2_token_%s', $userId)];
    }

    /**
     * @param string      $userId
     * @param AccessToken $accessToken
     */
    public function storeAccessToken($userId, AccessToken $accessToken)
    {
        $this->data[sprintf('_oauth2_token_%s', $userId)][] = $accessToken;
    }

    /**
     * @param string      $userId
     * @param AccessToken $accessToken
     */
    public function deleteAccessToken($userId, AccessToken $accessToken)
    {
        foreach ($this->getAccessTokenList($userId) as $k => $v) {
            if ($accessToken->getProviderId() === $v->getProviderId()) {
                if ($accessToken->getToken() === $v->getToken()) {
                    unset($this->data[sprintf('_oauth2_token_%s', $userId)][$k]);
                }
            }
        }
    }
}
