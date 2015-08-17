<?php

namespace News\Config;

/**
 * There is an initilizer that checks if class implements this interface.
 * If yes, it injects module config to it
 *
 * Interface ConfigAwareInterface
 * @package News\Config
 */
interface ConfigAwareInterface
{
    /**
     * Set config
     *
     * @param array $config
     */
    public function setConfig(array $config);
}
