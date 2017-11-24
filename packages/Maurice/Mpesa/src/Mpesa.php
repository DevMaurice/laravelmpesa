<?php

namespace Maurice\Mpesa;

class Mpesa
{
    /**
     * @var ConfigurationStore
     */
    public $config;

    /**
     * Create a new Mpesa instance
     */
    public function __construct($config)
    {
       $this->config = $config;
    }

    /**
     * Friendly welcome
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function echoPhrase($phrase)
    {
        return $phrase;
    }
}
