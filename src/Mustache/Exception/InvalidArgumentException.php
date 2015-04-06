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
 * Invalid argument exception.
 */
namespace Mustache\Exception;
use Mustache\MustacheException;
class InvalidArgumentException extends \InvalidArgumentException implements MustacheException
{
    // This space intentionally left blank.
}
