<?php

namespace Core\Http;

use Core\Http\Clients\Client;

abstract class Headers extends Client
{
    /**
     * Get the headers.
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) == 'HTTP_') {
                    $key = ucfirst(strtolower(str_replace('HTTP_', '', $key)));
                    if (strpos($key, '_') !== false) {
                        $ary = explode('_', $key);
                        foreach ($ary as $k => $v) {
                            $ary[$k] = ucfirst(strtolower($v));
                        }
                        $key = implode('-', $ary);
                    }
                    $headers[$key] = $value;
                }
            }
        }
        $headers = array_change_key_case($headers, CASE_LOWER);

        $this->headers = $headers;
    }

    /**
     * append new header.
     *
     * @param (string) $key The header key
     *                      (string) $value The header value
     *
     * @return void
     * @since 3.0.0
     *
     */
    public function setHeader($key, $value)
    {
        $this->headers[$this->normalizeKey($key)] = $value;
    }

    /**
     * Set response headers.
     *
     * @param (array) $headers
     *
     * @return object
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }

        return $this;
    }

    /**
     * Update existing header.
     *
     * @param (string) $key The header key
     *                      (string) $value The new header value
     *
     * @return void
     * @since 3.0.0
     *
     */
    public function update($key, $value)
    {
        if (!empty($this->get($this->normalizeKey($key)))) {
            $this->headers[$this->normalizeKey($key)] = $value;
        }
    }

    /**
     * Get all headers.
     *
     * @return bool|string
     * @since 3.0.0
     *
     */
    public function gets()
    {
        return $this->headers;
    }

    /**
     * Determine if header exists.
     *
     * @param (string) $key The header key
     *
     * @return bool|string
     * @since 3.0.0
     *
     */
    public function get($key)
    {
        return $this->headers[$this->normalizeKey($key)];
    }

    /**
     * Get the header by key.
     *
     * @param (string) $key The header key
     *
     * @return bool
     * @since 3.0.0
     *
     */
    public function has($key)
    {
        return (isset($this->headers[$this->normalizeKey($key)])) ? true : false;
    }

    /**
     * Remove the header by key.
     *
     * @param (string) $key The header key
     *
     * @return void
     * @since 3.0.0
     *
     */
    public function remove($key)
    {
        unset($this->headers[$key]);
    }

    /**
     * Normalize header name.
     *
     * @param (string) $key The case-insensitive header name
     *
     * @return string
     */
    public function normalizeKey($key)
    {
        $key = strtr(strtolower($key), '_', '-');

        return $key;
    }

    /**
     * Send response.
     *
     * @param (int) $code
     *                    (array) $headers
     *
     * @return void
     * @since 3.0.0
     *
     */
    public function send($code = null, array $headers = null)
    {
        if ($code !== null) {
            $this->withStatus($code);
        }
        if ($headers !== null) {
            $this->setHeaders($headers);
        }

        $body = $this->body;

        if (array_key_exists('Content-Encoding', $this->headers)) {
            $body = self::encodeBody($body, $this->headers['Content-Encoding']);
            $this->headers['Content-Length'] = strlen($body);
        }

        $this->sendHeaders();
        echo $body;
    }

    /**
     * Send headers.
     *
     * @return void
     * @since 3.0.0
     *
     */
    public function sendHeaders()
    {
        if (headers_sent()) {
            throw new \Exception('The headers have already been sent.');
        }

        header("HTTP/{$this->version} {$this->code} {$this->reasonPhrase}");
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
    }
}
