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
require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

use fkooman\OAuth\Client\Http\CurlHttpClient;
use fkooman\OAuth\Client\OAuthClient;
use fkooman\OAuth\Client\Provider;
use fkooman\OAuth\Client\SessionTokenStorage;

$requestScope = 'foo bar';
$resourceUri = 'http://localhost:8080/api.php';

// absolute link to callback.php in this directory
$callbackUri = 'http://localhost:8081/callback.php';

// the user ID to bind to, typically the currently logged in user on the
// _CLIENT_ service...
$userId = 'foo';

try {
    // we assume your application has proper (SECURE!) session handling
    if (PHP_SESSION_ACTIVE !== session_status()) {
        session_start();
    }

    $client = new OAuthClient(
        // for DEMO purposes we store the AccessToken in the user session
        // data...
        new SessionTokenStorage(),
        // for DEMO purposes we also allow connecting to HTTP URLs, do **NOT**
        // do this in production
        new CurlHttpClient(['allowHttp' => true])
    );

    // the OAuth provider configuration
    $client->setProvider(
        new Provider(
            'demo_client',                          // client_id
            'demo_secret',                          // client_secret
            'http://localhost:8080/authorize.php',  // authorization_uri
            'http://localhost:8080/token.php'       // token_uri
        )
    );

    // set the userId to bind the access token to
    $client->setUserId($userId);

    if (false === $response = $client->get($requestScope, $resourceUri)) {
        // "false" is returned for a number of reasons:
        // * no access_token yet for this user ID / scope
        // * access_token expired (and no refresh_token available)
        // * access_token was not accepted (revoked?)
        // * refresh_token was rejected (revoked?)
        //
        // we need to re-request authorization at the OAuth server, redirect
        // the browser to the authorization endpoint (with a 302)
        http_response_code(302);
        header(
            sprintf(
                'Location: %s',
                $client->getAuthorizeUri($requestScope, $callbackUri)
            )
        );
        exit(0);
    }

    // getting the resource succeeded!
    // print the Response object
    echo sprintf('<pre>%s</pre>', var_export($response, true));
} catch (Exception $e) {
    echo sprintf('ERROR: %s', $e->getMessage());
    exit(1);
}
