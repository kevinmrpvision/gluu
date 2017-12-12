<?php

/**
 * Copyright (c) 2016, 2017 François Kooman <Mrpvision\Gluu@tuxed.net>.
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

namespace Mrpvision\Gluu\Http;
use GuzzleHttp\Psr7\Request as HttpRequest;
use GuzzleHttp\Client;
class Request
{
    /** @var string */
    private $requestMethod;

    /** @var string */
    private $requestUri;

    /** @var string|null */
    private $requestBody;

    /** @var array */
    private $requestHeaders;

    /**
     * @param string $requestMethod
     * @param string $requestUri
     * @param array  $requestHeaders
     * @param string $requestBody
     */
    public function __construct($base_uri)
    {
        $this->client = new Client([
             'base_uri' => $base_uri,
        ]);
        
    }

    /**
     * @param string $requestUri
     * @param array  $requestHeaders
     *
     * @return Request
     */
    public function get($requestUri, array $options = [])
    {
        return $res = $this->client->request('GET', $requestUri,$options);
    }

    /**
     * @param string $requestUri
     * @param array  $postData
     * @param array  $requestHeaders
     *
     * @return Request
     */
    public function post($requestUri, array $options = [])
    {
        return $res = $this->client->request('POST', $requestUri,$options);
    }
    /**
     * @param string $requestUri
     * @param array  $postData
     * @param array  $requestHeaders
     *
     * @return Request
     */
    public function put($requestUri, array $options = [])
    {
        return $res = $this->client->request('PUT', $requestUri,$options);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setHeader($key, $value)
    {
        $this->requestHeaders[$key] = $value;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->requestUri;
    }

    /**
     * @return string|null
     */
    public function getBody()
    {
        return $this->requestBody;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->requestHeaders;
    }
}
