<?php

namespace SendWP;

/**
 * An interface for decorating phpMailer.
 */
interface MailerInterface
{
    public function send();
}
