<?php

namespace WunderAuto\Types\Actions;

/**
 * Class HTMLEmail
 */
class HTMLEmail extends EmailBaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Send HTML email', 'wunderauto');
        $this->description = __('Send html email', 'wunderauto');
        $this->group       = 'Email';

        $this->docLink = "https://www.wundermatics.com/docs/sending-html-emails/";
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $this->readConfig();

        if (!$this->to && !$this->bcc) {
            return false;
        }

        $css  = !$this->skipTemplate ? $this->getCssFromTemplate() . "\n" : '';
        $css .= $this->style;

        $content  = !$this->skipTemplate ?
            $this->getHeaderFromTemplate(['css' => $css, 'email_heading' => $this->heading]) :
            '';
        $content .= wpautop(wptexturize($this->body));

        $content .= !$this->skipTemplate ?
            $this->getFooterFromTemplate() :
            '';

        if ($this->skipTemplate) {
            $content = str_replace('[WASTYLE]', $css, $content);
        }

        $this->body = $content;
        add_filter('wp_mail_content_type', [$this, 'mailContentType']);

        $this->sendEmail();

        return true;
    }

    /**
     * Return style
     *
     * @return string|false
     */
    private function getCssFromTemplate()
    {
        ob_start();
        wa_get_template('emails/email-styles.php');
        return ob_get_clean();
    }

    /**
     * Return email header
     *
     * @param array<string, string> $args
     *
     * @return string|false
     */
    private function getHeaderFromTemplate($args)
    {
        ob_start();
        wa_get_template('emails/email-header.php', $args);
        return ob_get_clean();
    }

    /**
     * Return footer
     *
     * @return string|false
     */
    private function getFooterFromTemplate()
    {
        ob_start();
        wa_get_template('emails/email-footer.php');
        return ob_get_clean();
    }
}
