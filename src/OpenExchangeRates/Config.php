<?php

namespace OpenExchangeRates;

use Symfony\Component\Yaml\Parser;

class Config
{
    protected $config = array(
        'app_id' => '',
        'use_ssl' => false,
        'cache_dir' => 'cache',
    );

    public function __construct($params)
    {
        $this->load($params);
    }
    
    public function load($config_file) {
        $parser = new Parser();
        $this->config = $parser->parse(file_get_contents($config_file));
    }

    public function __get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            return null;
        }
    }
}
