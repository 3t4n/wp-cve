<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation;

use WPPayVendor\Symfony\Contracts\Translation\TranslatableInterface;
use WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Nate Wiebe <nate@northern.co>
 */
class TranslatableMessage implements \WPPayVendor\Symfony\Contracts\Translation\TranslatableInterface
{
    private $message;
    private $parameters;
    private $domain;
    public function __construct(string $message, array $parameters = [], ?string $domain = null)
    {
        $this->message = $message;
        $this->parameters = $parameters;
        $this->domain = $domain;
    }
    public function __toString() : string
    {
        return $this->getMessage();
    }
    public function getMessage() : string
    {
        return $this->message;
    }
    public function getParameters() : array
    {
        return $this->parameters;
    }
    public function getDomain() : ?string
    {
        return $this->domain;
    }
    public function trans(\WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface $translator, ?string $locale = null) : string
    {
        return $translator->trans($this->getMessage(), \array_map(static function ($parameter) use($translator, $locale) {
            return $parameter instanceof \WPPayVendor\Symfony\Contracts\Translation\TranslatableInterface ? $parameter->trans($translator, $locale) : $parameter;
        }, $this->getParameters()), $this->getDomain(), $locale);
    }
}
