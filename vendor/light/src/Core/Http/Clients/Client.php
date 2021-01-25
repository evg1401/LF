<?php

namespace Core\Http\Clients;

class Client
{
    /**
     * Access to curl clients.
     *
     * @param (string) $url
     *                      (string) $method
     *                      (array) $options
     *
     * @since 3.0.0
     */
    public function curl($url, $method = 'GET', array $options = null)
    {
        return new CURL($url, $method, $options);
    }
}
