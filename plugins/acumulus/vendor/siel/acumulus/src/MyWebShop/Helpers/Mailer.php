<?php

declare(strict_types=1);

namespace Siel\Acumulus\MyWebShop\Helpers;

use Siel\Acumulus\Helpers\Mailer as BaseMailer;

/**
 * Extends the base mailer class to send a mail using the MyWebShop mailer.
 */
class Mailer extends BaseMailer
{
    public function sendMail(string $from, string $fromName, $to, $subject, $bodyText, $bodyHtml)
    {
        // @todo: adapt to MyWebShop's way of creating a mailer, a "mail object", and having the "mail object" sent by the mailer.
        // @todo: if necessary, cast the result to a bool indicating success.
        return Mail::Send($this->translator->getLanguage(), $from, $fromName, $subject, $to, $bodyHtml);
    }

    public function getFrom(): string
    {
        // @todo: adapt to MyWebShop's way of getting the from email address to use.
        return Configuration::get('SHOP_EMAIL');
    }

    public function getFromName(): string
    {
        // @todo: adapt to MyWebShop's way of getting the webshop name.
        return Configuration::get('SHOP_NAME');
    }

    public function getTo(): string
    {
        $return = parent::getTo();
        if (empty($return)) {
            // @todo: does this shop configure an administrator address?
            $return = $this->getFrom();
        }
        return $return;
    }
}
