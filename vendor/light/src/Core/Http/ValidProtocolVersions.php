<?php

namespace Core\Http;

trait ValidProtocolVersions
{
    /**
     * Valid protocol versions.
     *
     * @since 3.0.0
     *
     * @var array
     */
    protected static $validProtocolVersions = [
        '1.0' => true,
        '1.1' => true,
        '2.0' => true,
        '2'   => true,
    ];
}
