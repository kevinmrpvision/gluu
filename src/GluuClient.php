<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mrpvision\Gluu;
use Mrpvision\Gluu\Http\Request;
use Mrpvision\Gluu\SessionTokenStorage;
use Mrpvision\Gluu\AccessToken;
/**
 * Description of GluuClient
 *
 * @author kevpat
 */
class GluuClient {

    /**
     * @var string arbitrary id value
     */
    private $clientID;

    /*
     * @var string arbitrary name value
     */
    private $clientName = 'sso_api_admin';

    /**
     * @var string arbitrary secret value
     */
    private $clientSecret;

    /**
     * @var array holds the provider configuration
     */
    private $providerConfig = array();

    /**
     * @var string http proxy if necessary
     */
    private $httpProxy;

    /**
     * @var string full system path to the SSL certificate
     */
    private $certPath;

    /**
     * @var bool Verify SSL peer on transactions
     */
    private $verifyPeer = true;

    /**
     * @var bool Verify peer hostname on transactions
     */
    private $verifyHost = true;

    /**
     * @var string if we aquire an access token it will be stored here
     */
    private $accessToken;

    /**
     * @var string if we aquire a refresh token it will be stored here
     */
    private $refreshToken;

    /**
     * @var string if we acquire an id token it will be stored here
     */
    private $idToken;

    /**
     * @var string stores the token response
     */
    private $tokenResponse;

    /*
     * @var array holds the scopes
     */
    private $scopes = array();
    
    /**
     * @var mixed holds well-known openid server properties
     */
    private $wellKnown = false;
    
    private $tokenStorage;

    private $httpClient;
    public function __construct($provider_url = null, $client_id = null, $client_secret = null) {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!class_exists('\phpseclib\Crypt\RSA') && !class_exists('Crypt_RSA')) {
            user_error('Unable to find phpseclib Crypt/RSA.php.  Ensure phpseclib is installed and in include_path before you include this file');
        }
        $this->setProviderURL($provider_url);
        $this->clientID = $client_id;
        $this->clientSecret = $client_secret;
        $this->httpClient = new Request($provider_url);
        $this->tokenStorage = new SessionTokenStorage();
    }

    public function providerConfigParam($array) {
        $this->providerConfig = array_merge($this->providerConfig, $array);
    }

    public function getHttpProxy() {
        return $this->httpProxy;
    }

    public function getCertPath() {
        return $this->certPath;
    }

    public function getVerifyPeer() {
        return $this->verifyPeer;
    }

    public function getVerifyHost() {
        return $this->verifyHost;
    }

    public function getAccessToken() {
        return $this->accessToken;
    }

    public function getRefreshToken() {
        return $this->refreshToken;
    }

    public function getIdToken() {
        return $this->idToken;
    }

    public function getTokenResponse() {
        return $this->tokenResponse;
    }

    public function getScopes() {
        return $this->scopes;
    }

    public function setHttpProxy($httpProxy) {
        $this->httpProxy = $httpProxy;
    }

    public function setCertPath($certPath) {
        $this->certPath = $certPath;
    }

    public function setVerifyPeer($verifyPeer) {
        $this->verifyPeer = $verifyPeer;
    }

    public function setVerifyHost($verifyHost) {
        $this->verifyHost = $verifyHost;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
    }

    public function setIdToken($idToken) {
        $this->idToken = $idToken;
    }

    public function setTokenResponse($tokenResponse) {
        $this->tokenResponse = $tokenResponse;
    }

    public function setScopes($scopes) {
        $this->scopes = $scopes;
    }

    public function getClientName() {
        return $this->clientName;
    }

    public function setClientName($clientName) {
        $this->clientName = $clientName;
    }

     /**
     * @param $provider_url
     */
    public function setProviderURL($provider_url) {
        $this->providerConfig['issuer'] = $provider_url;
    }
    
    public function getUser() {

//        if(func_num_args() > 2){
//            throw new \InvalidArgumentException('Two argument are available userid or options array.');
//        }
//        $arg_list = func_get_args();
//        $user_id = null;
//        $query = [];
//        foreach($arg_list as $arg){
//            if(is_string($arg)){
//                $user_id = $arg;
//            }
//            if(is_array($arg)){
//                $query = $arg;
//            }
//        }
//        $accessToken = $this->requestTokens();
//        $user_endpoint = $this->getProviderConfigValue("user_endpoint");
//        if($user_id){
//            $user_endpoint.='/'.$user_id;
//        }
//        $options = [
//            "headers" => [
//                "Authorization" => sprintf('Bearer %s',$accessToken->getToken()),
//            ],
//            'query'=>$query
//        ];
//        $this->getOptions($options);
//        $response = $this->httpClient->get(
//                $user_endpoint, $options
//        );
//         
//        $collection = Models\User\Collection::fromJson((string)$response->getBody());

        $collection = Models\Collection::fromJson(file_get_contents('D:\wamp64\www\demo\scim\vendor\mrpvision\gluu\tests\users.json'));
        dd($collection->resources[1]->json());
    }
    
    
     /**
     * Requests ID and Access tokens
     *
     * @param $code
     * @return mixed
     */
     public function requestTokens() {
        $accessTokenList = $this->tokenStorage->getAccessTokenList($this->clientName);
        foreach ($accessTokenList as $accessToken) {
            if ($accessToken->isExpired()) {
                $this->tokenStorage->deleteAccessToken($this->clientName, $accessToken);
                break;
            }
            return $accessToken;
        }
        $token_endpoint = $this->getProviderConfigValue("token_endpoint");
        $grant_type = "client_credentials";
        $options = [
            "form_params" => [
                "grant_type" => $grant_type,
            ],
            "auth" => [
                $this->clientID,
                $this->clientSecret
            ]
        ];

        $this->getOptions($options);
        $response = $this->httpClient->post(
                $token_endpoint, $options
        );
        if ($response->getStatusCode() == 200) {
            $accessToken = AccessToken::fromJson((string) $response->getBody());
            $this->tokenStorage->storeAccessToken($this->clientName, $accessToken);
            return $accessToken;
        }
        throw new Exception\AccessTokenException("Getting code {$response->getStatusCode()} from SSO server.");
    }

    private function getOptions(array &$options=[]){
        $options['verify'] = $this->verifyPeer;
        $options['proxy'] = $this->httpProxy;
    }
    
    private function getProviderConfigValue($param, $default = null) {
        if (isset($this->providerConfig[$param])) {
            return $this->providerConfig[$param];
        }
        throw new Exception\OpenIDConnectClientException("The provider {$param} has not been set. Make sure your provider has a well known configuration available.");
    }

    /**
     * @return string
     * @throws Exception\OpenIDConnectClientException
     */
    public function getProviderURL() {

        if (!isset($this->providerConfig['issuer'])) {
            throw new Exception\OpenIDConnectClientException("The provider URL has not been set");
        } else {
            return $this->providerConfig['issuer'];
        }
    }
    /**
     * A wrapper around base64_decode which decodes Base64URL-encoded data,
     * which is not the same alphabet as base64.
     */
    private function base64url_decode($base64url) {
        return base64_decode(b64url2b64($base64url));
    }

    /**
     * Per RFC4648, "base64 encoding with URL-safe and filename-safe
     * alphabet".  This just replaces characters 62 and 63.  None of the
     * reference implementations seem to restore the padding if necessary,
     * but we'll do it anyway.
     *
     */
    private function b64url2b64($base64url) {
        // "Shouldn't" be necessary, but why not
        $padding = strlen($base64url) % 4;
        if ($padding > 0) {
            $base64url .= str_repeat("=", 4 - $padding);
        }
        return strtr($base64url, '-_', '+/');
    }

}
