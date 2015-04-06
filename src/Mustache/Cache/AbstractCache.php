<?php

/*
 * This file is part of Mustache.php.
 *
 * (c) 2010-2014 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Abstract Mustache Cache class.
 *
 * Provides logging support to child implementations.
 *
 * @abstract
 */
namespace Mustache\Cache;

use Mustache\Cache;
use Mustache\Logger;
use Mustache\Exception;

abstract class AbstractCache implements Cache
{
    private $logger = null;

    /**
     * Get the current logger instance.
     * @return Mustache\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set a logger instance.
     *
     * @return Mustache\Logger
     */
    public function setLogger($logger = null)
    {
        if ($logger !== null && !($logger instanceof Logger || is_a($logger, 'Psr\\Log\\LoggerInterface'))) {
            throw new Exception\InvalidArgumentException('Expected an instance of Mustache_Logger or Psr\\Log\\LoggerInterface.');
        }
        $this->logger = $logger;
    }

    /**
     * Add a log record if logging is enabled.
     *
     * @param int $level The logging level
     * @param string $message The log message
     * @param array $context The log context
     */
    protected function log($level, $message, array $context = array())
    {
        if (isset($this->logger)) {
            $this->logger->log($level, $message, $context);
        }
    }
}
