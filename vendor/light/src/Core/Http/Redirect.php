<?php

namespace Core\Http;

class Redirect extends HTTP
{
    /**
     * Send redirect.
     *
     * @param (string) $url
     *                      (int) $code
     *                      (mixed) $version
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function __construct($url, $code = '302', $version = '1.1')
    {
        if (headers_sent()) {
            throw new \Exception('The headers have already been sent.');
        }

        if (!array_key_exists($code, self::$responseCodes)) {
            throw new \Exception('The header code '.$code.' is not allowed.');
        }

        header("HTTP/{$version} {$code} ".self::$responseCodes[$code]);
        header("Location: {$url}");
    }
}
