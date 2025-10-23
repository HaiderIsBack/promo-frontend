<?php

session_start();

class Woo_Fetch {
    private $store;
    private $ck;
    private $cs;

    private $ch;

    function __construct($store_url, $consumer_key, $consumer_secret) {
        $this->store = $store_url;
        $this->ck = $consumer_key;
        $this->cs = $consumer_secret;

        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);
        
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);  // Or false if self-signed
    }

    function request($method, $endpoint, $params = [], $body = null) {
        $params = array_merge($params, [
            'consumer_key'    => $this->ck,
            'consumer_secret' => $this->cs
        ]);

        $url = rtrim($this->store, '/') . '/' . ltrim($endpoint, '/');
        $url .= '?' . http_build_query($params);

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($body) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $response = curl_exec($this->ch);
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if (curl_errno($this->ch)) {
            return ['success' => false, 'message' => curl_error($this->ch)];
        }

        if ($httpCode >= 400) {
            return [
                'success' => false,
                'status' => $httpCode,
                'message' => $response
            ];
        }

        return [
            'success' => true,
            'status' => $httpCode,
            'response' => $response
        ];
    }

    function __destruct() {
        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }
    }
}

// WooCommerce store URL (no trailing slash)
const STORE_URL = "https://practice-php.page.gd";

// REST API credentials
const CONSUMER_KEY = "ck_cdf73c66628cad7706d9cc63c9c1e94321972f42";
const CONSUMER_SECRET = "cs_47352918dcdc3dae091c00fcab0c35bedbb387e2";

const SITE_URL = '/php_lab'; // for Development
// $SITE_URL = ''; // for Live

function verify_user_token() {
    if (empty($_SESSION['token'])) {
        return false;
    }
    
    $token = $_SESSION['token'];
    $url = STORE_URL . '/wp-json/jwt-auth/v1/token/validate';

    $options = array(
        'http' => array(
            'header' => "Content-type: application/json\r\n" .
                    "Authorization: Bearer $token\r\n",
            'method' => 'POST',
            'content' => '{}'
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === false) {
        unset($_SESSION['token']);
        return false;
    }

    $result = json_decode($result, true);
    
    if (isset($result['code']) && $result['code'] === "jwt_auth_valid_token") {
        return true;
    }

    unset($_SESSION['token']);
    return false;
}