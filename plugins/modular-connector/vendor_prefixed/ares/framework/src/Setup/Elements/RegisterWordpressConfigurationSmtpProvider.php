<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements;

use Modular\ConnectorDependencies\Ares\Framework\Setup\AbstractServiceProvider;
/** @internal */
class RegisterWordpressConfigurationSmtpProvider extends AbstractServiceProvider
{
    /**
     * @var string
     */
    protected string $hook = 'phpmailer_init';
    /**
     * Replace SMTP for wp_mail function
     */
    public function load() : void
    {
        $phpmailer = \func_get_arg(0);
        $mailer = \defined('Modular\\ConnectorDependencies\\SMTP_HOST') && \defined('Modular\\ConnectorDependencies\\SMTP_USER') && \defined('Modular\\ConnectorDependencies\\SMTP_PASSWORD') && \defined('Modular\\ConnectorDependencies\\SMTP_FROM') && \defined('Modular\\ConnectorDependencies\\SMTP_NAME');
        if ($mailer) {
            $phpmailer->isSMTP();
            $phpmailer->Host = SMTP_HOST;
            $phpmailer->SMTPAuth = \defined('Modular\\ConnectorDependencies\\SMTP_AUTH') ? SMTP_AUTH : \true;
            $phpmailer->Port = \defined('Modular\\ConnectorDependencies\\SMTP_PORT') ? SMTP_PORT : 587;
            $phpmailer->Username = SMTP_USER;
            $phpmailer->Password = SMTP_PASSWORD;
            $phpmailer->SMTPSecure = \defined('Modular\\ConnectorDependencies\\SMTP_SECURE') ? SMTP_SECURE : 'tls';
            $phpmailer->From = SMTP_FROM;
            $phpmailer->FromName = SMTP_NAME;
        }
    }
}
