<?php
namespace SeriaCAPTCHA;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SeriaCAPTCHA
{
    protected $endpoint;
    protected $timeout;
    protected $client;
    protected $valid = true;

    /**
     * @param string $endpoint Only root domains ending with .seriacaptcha.com are allowed
     * @param float $timeout Timeout in seconds
     * @throws \InvalidArgumentException
     */
    public function __construct($endpoint = 'https://hk.seriacaptcha.com', $timeout = 3.0)
    {
        // Validate endpoint must be https://xxx.seriacaptcha.com or http://xxx.seriacaptcha.com
        $parsed = parse_url($endpoint);

        if (!isset($parsed['host'])) {
            $this->valid = false;
            throw new \InvalidArgumentException('Invalid endpoint format');
        }

        $host = $parsed['host'];

        if (!preg_match('/\\.seriacaptcha\\.com$/', $host)) {
            $this->valid = false;
            throw new \InvalidArgumentException('Only .seriacaptcha.com root domains are allowed as endpoint');
        }

        // Automatically append /verify.php
        $this->endpoint = rtrim($endpoint, "/ ") . "/verify.php";
        $this->timeout = $timeout;
        $this->client = new Client([
            'timeout' => $this->timeout,
            'verify' => false // Disable SSL verification if needed
        ]);
    }

    /**
     * Verify captcha
     * @param string $appid
     * @param string $token
     * @param string $secret
     * @param string|null $ip Optional, client IP
     * @param string|null $userAgent Optional, client User-Agent
     * @return array|false
     * @throws \Exception
     */
    public function verify($appid, $token, $secret, $ip = null, $userAgent = null)
    {
        if (!$this->valid) {
            return false;
        }

        $params = [
            'key' => $appid,
            'token' => $token,
            'secret' => $secret
        ];

        if ($ip !== null) {
            $params['ip'] = $ip;
        }

        if ($userAgent !== null) {
            $params['user_agent'] = $userAgent;
        }

        try {
            $response = $this->client->get($this->endpoint, [
                'headers' => [
                    'User-Agent' => 'SeriaCAPTCHA PHP SDK/1.0'
                ],
                'query' => $params
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Response is not valid JSON: ' . $body);
            }

            return $data;
        } catch (RequestException $e) {
            throw new \Exception('Request failed: ' . $e->getMessage());
        }
    }
} 