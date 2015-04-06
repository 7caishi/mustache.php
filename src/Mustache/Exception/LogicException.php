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
 * Logic exception.
 */
namespace Mustache\Exception;
use Mustache\MustacheException;
class LogicException extends \LogicException implements MustacheException
{
    // This space intentionally left blank.
}
