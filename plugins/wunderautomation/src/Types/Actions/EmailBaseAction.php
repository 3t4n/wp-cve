<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class EmailBaseAction
 */
class EmailBaseAction extends BaseAction
{
    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $cc;

    /**
     * @var string
     */
    protected $bcc;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $heading;

    /**
     * @var string
     */
    protected $replyTo;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var bool
     */
    protected $skipTemplate;

    /**
     * @var string
     */
    protected $style;

    /**
     * Set the email content type (hooked)
     *
     * @return string
     */
    public function mailContentType()
    {
        return 'text/html';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'useToRole', 'key');
        $config->sanitizeObjectProp($config->value, 'useCcRole', 'key');
        $config->sanitizeObjectProp($config->value, 'useBccRole', 'key');
        $config->sanitizeValueArray($config->value, 'toRole', 'key');
        $config->sanitizeValueArray($config->value, 'ccRole', 'key');
        $config->sanitizeValueArray($config->value, 'bccRole', 'key');
        $config->sanitizeObjectProp($config->value, 'to', 'text');
        $config->sanitizeObjectProp($config->value, 'cc', 'text');
        $config->sanitizeObjectProp($config->value, 'bc', 'text');
        $config->sanitizeObjectProp($config->value, 'replyto', 'text');
        $config->sanitizeObjectProp($config->value, 'from', 'text');
        $config->sanitizeObjectProp($config->value, 'subject', 'text');
        $config->sanitizeObjectProp($config->value, 'heading', 'textarea');
        $config->sanitizeObjectProp($config->value, 'content', 'kses_post');
        $config->sanitizeObjectProp($config->value, 'skipTemplate', 'bool');
        $config->sanitizeObjectProp($config->value, 'style', 'kses_post');
    }

    /**
     * Read the action config
     *
     * @return void
     */
    protected function readConfig()
    {
        $useToRole  = $this->getResolved('value.useToRole', false);
        $useCcRole  = $this->getResolved('value.useCcRole', false);
        $useBccRole = $this->getResolved('value.useBccRole', false);

        $this->to  = $useToRole === '1' ?
            $this->resolveRoles($this->get('value.toRole')) :
            $this->getResolved('value.to');
        $this->cc  = $useCcRole === '1' ?
            $this->resolveRoles($this->get('value.ccRole')) :
            $this->getResolved('value.cc');
        $this->bcc = $useBccRole === '1' ?
            $this->resolveRoles($this->get('value.bccRole')) :
            $this->getResolved('value.bcc');

        $this->replyTo = $this->getResolved('value.replyto');
        $this->from    = $this->getResolved('value.from');

        $this->subject      = $this->getResolved('value.subject');
        $this->body         = $this->getResolved('value.content');
        $this->heading      = $this->getResolved('value.heading');
        $this->skipTemplate = (bool)$this->getResolved('value.skipTemplate');
        $this->style        = $this->getResolved('value.style');
    }

    /**
     * @param array<int, string> $to
     *
     * @return string
     */
    private function resolveRoles($to)
    {
        $ret = [];

        foreach ($to as $role) {
            $users = get_users(['role' => $role]);
            foreach ($users as $user) {
                $ret[] = $user->user_email;
            }
        }

        return join(',', array_unique($ret));
    }

    /**
     * Send the email
     *
     * @return void
     */
    protected function sendEmail()
    {
        $headers = $this->createHeaders();
        wp_mail($this->to, $this->subject, $this->body, $headers);
    }

    /**
     * Create headers based on cc, bcc and reply to
     *
     * @return array<int, string>
     */
    protected function createHeaders()
    {
        $headers = [];

        if (strlen(trim($this->from)) > 0) {
            /*if (strpos($this->from, '<') === false) {
                $this->from = "$this->from <$this->from>";
            }*/
            $headers[] = "From: $this->from";
        }
        if (strlen(trim($this->replyTo)) > 0) {
            $headers[] = "Reply-To: $this->replyTo";
        }
        if (strlen(trim((string)$this->cc)) > 0) {
            $parts = explode(',', $this->cc);
            foreach ($parts as $part) {
                $headers[] = "Cc: $part";
            }
        }
        if (strlen(trim((string)$this->bcc)) > 0) {
            $parts = explode(',', $this->bcc);
            foreach ($parts as $part) {
                $headers[] = "Bcc: $part";
            }
        }

        return $headers;
    }
}
