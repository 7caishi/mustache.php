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
 * Mustache syntax exception.
 */
namespace Mustache\Exception;
use Mustache\MustacheException;
class SyntaxException extends \LogicException implements MustacheException
{
    protected $token;

    /**
     * @param string $msg
     * @param array  $token
     */
    public function __construct($msg, array $token)
    {
        $this->token = $token;
        parent::__construct($msg);
    }

    /**
     * @return array
     */
    public function getToken()
    {
        return $this->token;
    }
}
