<?php

namespace News\Config;

trait ConfigAwareTrait
{
    /**
     * @var array
     */
    protected $config = null;

    /**
     * Set config
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }
}