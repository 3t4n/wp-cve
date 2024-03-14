<?php

namespace WunderAuto\Types\Parameters;

use WunderAuto\Types\Internal\FieldDescriptor;

/**
 * Class ConfirmationLink
 */
class ConfirmationLink extends BaseParameter
{
    /**
     * @var bool
     */
    public $usesConfirmationLinkFields = true;

    /**
     * ConfirmationLink
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'general';
        $this->title       = 'confirmationlink';
        $this->description = __(
            'Use this parameter to create a link that, when clicked by an end user, will trigger another workflow. ' .
            'The target workflow needs to use one of the Confirmation Link< trigger types.',
            'wunderauto'
        );
        $this->objects     = '*';

        $this->usesDefault = false;

        add_filter('wunderauto/parameters/editorfields', [$this, 'editorFields'], 10, 1);
    }

    /**
     * @param object    $object
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($object, $modifiers)
    {
        $wunderAuto = wa_wa();
        $wpdb       = wa_get_wpdb();

        $resolver = $wunderAuto->getCurrentResolver();
        $code     = md5(SECURE_AUTH_SALT . uniqid());

        $name       = isset($modifiers->name) ? $modifiers->name : 'link-' . rand(1000, 9999);
        $created    = date('Y-m-d H:i:s');
        $expires    = $this->getExpires($modifiers);
        $clicked    = 0;
        $clickLimit = isset($modifiers->limit) ? $modifiers->limit : 1;
        $args       = $resolver->getObjectIdArray();
        $onSuccess  = isset($modifiers->onsuccess) ? $modifiers->onsuccess : null;
        $onExpired  = isset($modifiers->onexpired) ? $modifiers->onexpired : null;

        $sql = "INSERT INTO  
            {$wpdb->prefix}wa_confirmationlinks 
            (name, code, created, expires, clicked, click_limit, args, on_success, on_expired) 
            VALUES(%s,%s,%s,%s,%d,%d,%s,%s,%s)";

        /** @var string $preparedSql */
        $preparedSql = $wpdb->prepare(
            $sql,
            $name,
            $code,
            $created,
            $expires,
            $clicked,
            $clickLimit,
            json_encode($args),
            $onSuccess,
            $onExpired
        );

        $wpdb->query($preparedSql);
        return $this->getLink($code);
    }

    /**
     * @param \stdClass $modifiers
     *
     * @return string|null
     */
    private function getExpires($modifiers)
    {
        $expires     = isset($modifiers->expires) ? $modifiers->expires : 0;
        $expiresUnit = isset($modifiers->expiresUnit) ? $modifiers->expiresUnit : 'days';

        if ((int)$expires === 0) {
            return null;
        }

        $time = time();
        switch ($expiresUnit) {
            case 'minutes':
                $time += $expires * MINUTE_IN_SECONDS;
                break;
            case 'hours':
                $time += $expires * HOUR_IN_SECONDS;
                break;
            case 'days':
                $time += $expires * DAY_IN_SECONDS;
                break;
            case 'weeks':
                $time += $expires * WEEK_IN_SECONDS;
                break;
        }

        return date('Y-m-d H:i:s', $time);
    }

    /**
     * @param string $code
     *
     * @return string
     */
    private function getLink($code)
    {
        $options = get_option('wunderauto-general');
        $slug    = isset($options['confirmationslug']) ?
            $options['confirmationslug'] :
            'wa-confirm';

        return site_url() . '/' . $slug . '/' . $code;
    }

    /**
     * @param array<int, FieldDescriptor> $editorFields
     *
     * @return array<int, FieldDescriptor>
     */
    public function editorFields($editorFields)
    {
        $newFields = [
            new FieldDescriptor(
                [
                    'label'       => __('Name', 'wunderauto'),
                    'description' => __(
                        'A unique name for this link. I.e. "confirm_phone" or "confirm_email"',
                        'wunderauto'
                    ),
                    'type'        => 'text',
                    'model'       => 'linkName',
                    'variable'    => 'name',
                    'condition'   => "parameters[editor.phpClass].usesConfirmationLinkFields",
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Expires after', 'wunderauto'),
                    'description' => '',
                    'type'        => 'number',
                    'min'         => 0,
                    'model'       => 'linkExpires',
                    'variable'    => 'expires',
                    'condition'   => "parameters[editor.phpClass].usesConfirmationLinkFields",
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __(' ', 'wunderauto'),
                    'description' => __(
                        'Time until link expires. Leave blank if the link should never expire',
                        'wunderauto'
                    ),
                    'type'        => 'select',
                    'options'     => [
                        (object)['value' => '', 'label' => __('', 'wunderauto')],
                        (object)['value' => 'minutes', 'label' => __('Minutes', 'wunderauto')],
                        (object)['value' => 'hours', 'label' => __('Hours', 'wunderauto')],
                        (object)['value' => 'days', 'label' => __('Days', 'wunderauto')],
                        (object)['value' => 'weeks', 'label' => __('Weeks', 'wunderauto')],
                    ],
                    'model'       => 'exiresUnit',
                    'variable'    => 'unit',
                    'condition'   => "parameters[editor.phpClass].usesConfirmationLinkFields",
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Nr of clicks', 'wunderauto'),
                    'description' => __(
                        'Max clicks before the link expires, defaults to  1. Set to -1 for unlimited clicks',
                        'wunderauto'
                    ),
                    'type'        => 'number',
                    'min'         => -1,
                    'model'       => 'linkClickLimit',
                    'variable'    => 'limit',
                    'condition'   => "parameters[editor.phpClass].usesConfirmationLinkFields",
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Redirect (success)', 'wunderauto'),
                    'description' => __(
                        'URL to redirect the user to after successful confirmation. Leave blank to default to ' .
                        'redirect to the target object (post, page, user etc).',
                        'wunderauto'
                    ),
                    'type'        => 'text',
                    'model'       => 'linkOnSuccess',
                    'variable'    => 'onsuccess',
                    'condition'   => "parameters[editor.phpClass].usesConfirmationLinkFields",
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Redirect (expired)', 'wunderauto'),
                    'description' => __(
                        'URL to redirect when the link is expired. Leave blank to default to ' .
                        'redirect to the target object (post, page, user etc).',
                        'wunderauto'
                    ),
                    'type'        => 'text',
                    'model'       => 'linkOnExpired',
                    'variable'    => 'onexpired',
                    'condition'   => "parameters[editor.phpClass].usesConfirmationLinkFields",
                ]
            )
        ];

        return array_merge($editorFields, $newFields);
    }
}
