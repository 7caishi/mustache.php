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
 * Unknown template exception.
 */
namespace Mustache\Exception;
use Mustache\MustacheException;
class UnknownTemplateException extends \InvalidArgumentException implements MustacheException
{
    protected $templateName;

    /**
     * @param string $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = $templateName;
        parent::__construct(sprintf('Unknown template: %s', $templateName));
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }
}
