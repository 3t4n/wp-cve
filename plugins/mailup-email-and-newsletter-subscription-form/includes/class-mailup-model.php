<?php

declare(strict_types=1);

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.2.6
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_Model
{
    public $terms;

    public $settings;

    protected $forms = [];

    protected $messages;

    protected $api;

    protected $options;

    protected $type_fields;

    protected $term_value = 'TC';

    // protected $term_value = 'Terms and Conditions';
    protected $term_note = 'Terms and Conditions';

    private $mailup;

    public function __construct($mailup)
    {
        $this->mailup = $mailup;
        $this->load_dependencies($mailup);
    }

    public function getUrlLogon()
    {
        return Mailup_Requests::getUrlLogon();
    }

    public function setTokensFromCode($code): void
    {
        $this->api = Mailup_Requests::tokenFromCode($code, $this->options);
    }

    public static function get_option()
    {
        $mailup = Mailup::MAILUP_NAME();
        $mup_option = get_option($mailup);

        if (!$mup_option) {
            return null;
        }

        return $mup_option;
    }

    public function has_tokens()
    {
        return isset($this->options['tokens']);
    }

    public function has_form()
    {
        return isset($this->options['forms']) && !empty($this->options['forms']);
    }

    public function removeTokens(): void
    {
        unset($this->options['tokens']);
        update_option($this->mailup, $this->options);
    }

    public function setForms($value): void
    {
        try {
            $form = new Mailup_Form();
            $form->set_form($value);

            if ($form->group) {
                $this->manage_groups($form);
            }

            $this->forms = (array) null;
            $this->forms[] = $form;
            $this->options['forms'] = json_decode(json_encode($this->forms), true);

            update_option($this->mailup, $this->options);

            Mailup_WPML::registerForm($form, $this->getTypeFields());
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function setTerms($value): void
    {
        $this->terms = Mailup_Terms::getTerms($value);
        $this->options['terms'] = json_decode(json_encode($this->terms), true);

        update_option($this->mailup, $this->options);

        Mailup_WPML::registerTerms($this->terms);
    }

    public function setMessages($value): void
    {
        $this->messages = array_map(
            static function ($message) {
                return trim(stripslashes($message));
            },
            array_column($value, 'value', 'name')
        );

        $this->options['messages'] = $this->messages;
        update_option($this->mailup, $this->options);

        Mailup_WPML::registerMessages($this->messages);
    }

    public function setSettings($value): void
    {
        $value['custom_css'] = base64_encode($value['custom_css']);
        $this->settings = (object) $value;
        $this->options['settings'] = $this->settings;
        update_option($this->mailup, $this->options);
    }

    public function fillList()
    {
        $lists = $this->get_lists();
        $list_ids = array_column($lists, 'id');
        $form_sel = null;

        if ($this->forms) {
            $opt_list_ids = array_column($this->forms, 'list_id');
            $form_sel = $this->forms[0];
        }

        if (count($lists) > 0) {
            $list_group = null !== $form_sel ? $form_sel : (object) [
                'list_id' => $lists[0]['id'],
                'group' => __('Wordpress MailUp Plugin', 'mailup'),
            ];
        }

        return [
            'api-lists' => $lists,
            'forms' => $this->forms,
            'type-fields' => $this->getTypeFields($form_sel),
            'messages' => $this->messages,
        ];
    }

    public function getTypeFields($args = null)
    {
        $exist_fields = [];
        $items = $this->cached_type_fields();

        if ($args && isset($args->fields)) {
            $exist_fields = array_column($args->fields, 'id');
        }

        foreach ($exist_fields as $ex_field) {
            $ix_field = array_search($ex_field, array_column($items, 'id'), true);
            array_splice($items, $ix_field, 1);
        }

        if (empty($items)) {
            throw new \Exception('error on Catch List');
        }

        return $items;
    }

    public function add_recipient($parameters): void
    {
        if (!is_array($parameters)) {
            throw new \Exception();
        }

        try {
            $body = array_merge(
                ...array_map(
                    static function ($key, $param) {
                        if ('email' === $key) {
                            return ['Email' => $param];
                        }

                        if ('phone' === $key) {
                            return ['MobileNumber' => $param];
                        }

                        if ('fields' === $key) {
                            return [ucfirst($key) => array_map(
                                static function ($k_field, $field) {
                                    return [
                                        'Id' => $k_field,
                                        'Value' => sanitize_text_field(stripslashes($field)),
                                    ];
                                },
                                array_keys(array_filter($param)),
                                array_filter($param)
                            )];
                        } elseif ('terms' === $key) {
                            return [];
                        } else {
                            return [ucfirst($key) => $param];
                        }
                    },
                    array_keys($parameters),
                    $parameters
                )
            );

            $form = $this->get_fe_form();
            $terms = isset($parameters['terms']) ? array_keys($parameters['terms']) : [];
            $groups = $this->prepare_fe_groups($form, $terms);

            $args = [
                'body' => json_encode($body),
                'confirm' => $form->confirm,
                'list_id' => $form->list_id,
            ];

            $recipient_id = $this->api->addRecipient((object) $args);

            foreach ($groups as $group_id) {
                $args['group_id'] = $group_id;
                $args['recipient_id'] = $recipient_id;
                $this->api->addToGroup((object) $args);
            }

            wp_send_json_success($this->messages['success-message'], 200);
        } catch (\Exception $ex) {
            wp_send_json_error($this->messages['generic-error-message'], $ex->getCode());
        }
    }
    
    public function get_groups($args)
    {
        return $this->api->getGroups($args);
    }

    public function update_group_name(): void
    {
        $mup_form = new Mailup_Form(reset($this->forms));
        $group_name = $mup_form->group;
        $args = (object) [
            'list_id' => $mup_form->list_id,
            'group' => $group_name,
        ];

        $groups = $this->get_groups($args);

        if (is_object($groups) && isset($groups->Items)) {
            $platform_groups = array_column($groups->Items, 'Name', 'idGroup');

            if (in_array($group_name, $platform_groups, true)) {
                if (strlen($group_name) < 45) {
                    $group_to_rename = array_filter(
                        $platform_groups,
                        function ($value) use ($group_name) {
                            $group_term = trim(str_replace($group_name, '', $value));

                            return preg_match(sprintf('/^%s [1-3]/', $this->term_note), $group_term, $out) ? strpos($value, $this->term_note) : null;
                        }
                    );

                    if (!empty($group_to_rename)) {
                        foreach ($group_to_rename as $key => $value) {
                            $new_name_group = str_replace($this->term_note, $this->term_value, $value);
                            $args = (object) [
                                'params' => [
                                    $mup_form->list_id,
                                    $key,
                                ],
                                'body' => json_encode(
                                    [
                                        'Name' => $new_name_group,
                                        'Notes' => trim(str_replace($group_name, '', $value)),
                                    ]
                                ),
                            ];

                            try {
                                $this->api->renameGroup($args);
                            } catch (\Exception $ex) {
                                throw $ex;
                            }
                        }
                    }
                } elseif (strlen($group_name) > 44) {
                    try {
                        $args = (object) [
                            'params' => [
                                $mup_form->list_id,
                                array_search($group_name, $platform_groups, true),
                            ],
                            'body' => json_encode(
                                [
                                    'Name' => substr($group_name, 0, 45),
                                    'Notes' => 'Original Name: '.$group_name,
                                ]
                            ),
                        ];
                        $this->api->renameGroup($args);

                        $mup_form->group = substr($group_name, 0, 45);
                        $this->setForms((array) $mup_form);
                    } catch (\Exception $ex) {
                        throw $ex;
                    }
                }
            }
        }
    }

    public function get_fe_form()
    {
        if (count($this->forms) > 0) {
            $parameters = $this->forms[0];
            $parameters->terms = $this->terms;
            $parameters->confirm = $this->settings->confirm;
            $parameters->placeholder = $this->settings->placeholder;
            $parameters->custom_css = $this->settings->custom_css;
            $form = Mailup_WPML::getTranslationFields($parameters, $this->getTypeFields());

            return new Mailup_FE_Form($form);
        }

        return null;
    }

    public function get_terms($value): void
    {
        $this->terms = Mailup_Terms::getTerms($value);
    }

    protected function cached_type_fields()
    {
        global $wp_object_cache;

        if (!$wp_object_cache->get('mup_type_fields')) {
            $type_fields = $this->prepare_api_request('typeFields');
            $items = array_map(
                static function ($type_field) {
                    return (object) [
                        'id' => strval($type_field->Id),
                        'name' => $type_field->Description,
                    ];
                },
                $type_fields->Items
            );

            $must_fields = [
                (object) [
                    'id' => 'email',
                    'name' => __('email'),
                ],
                (object) [
                    'id' => 'phone',
                    'name' => __('phone'),
                ],
            ];
            $fields = array_merge($must_fields, $items);

            wp_cache_set('mup_type_fields', $fields);

            return $fields;
        }

        return $wp_object_cache->get('mup_type_fields');
    }

    protected function get_lists()
    {
        $res_lists = $this->prepare_api_request('listsUser');

        if (empty($res_lists->Items)) {
            throw new \Exception('error on Catch List');
        }

        return array_map(
            static function ($list) {
                return [
                    'id' => $list->IdList,
                    'name' => $list->Name,
                    'description' => $list->Description,
                ];
            },
            $res_lists->Items
        );
    }

    protected function manage_groups($list_group)
    {
        $groups = $this->get_groups($list_group);
        $groups_obj = [];

        if (is_object($groups) && 4 === $groups->TotalElementsCount) {
            return array_column($groups->Items, 'Name', 'idGroup');
        }
        $groups_obj = array_column($groups->Items, 'Name', 'idGroup') ?? [];
        $prefix_group = $list_group->group;
        $groups_default = $this->get_groups_name($prefix_group);

        foreach ($groups_default as $group) {
            $args = (object) [
                'list_id' => $list_group->list_id,
                'body' => json_encode($group),
            ];

            try {
                $new_group = $this->api->createGroup($args);
                $groups_obj[$new_group->idGroup] = $new_group->Name;
            } catch (\Exception $ex) {
                error_log($ex->getMessage());
            }
        }

        return array_filter($groups_obj);
    }

    protected function get_groups_name($prefix)
    {
        $groups_name = [];

        $groups_name[] =
        [
            'Name' => $prefix,
        ];

        for ($i = 1; $i <= 3; ++$i) {
            $groups_name[] =
            [
                'Name' => sprintf('%s %s %s', $prefix, $this->term_value, $i),
                'Notes' => sprintf('%s %s', $this->term_note, $i),
            ];
        }

        return $groups_name;
    }

    protected function prepare_fe_groups($form, $groups_req = [])
    {
        $groups = $this->manage_groups($form);

        if (empty($groups_req)) {
            $groups_req = [(int) 0];
        } else {
            array_unshift($groups_req, 0);
        }

        $groups_resp = [];

        foreach ($groups_req as $key => $val) {
            $groups_resp[] = array_keys($groups)[$val];
        }

        return $groups_resp;
    }

    protected function get_forms($args): void
    {
        if (is_array($args) && count($args) > 0) {
            $form = new Mailup_Form((object) $args[0]);
            $this->forms[] = $form;
        }
    }

    protected function get_messages(): void
    {
        if (isset($this->options['messages']) && is_array($this->options['messages'])) {
            $this->messages = $this->options['messages'];
        } else {
            $this->messages = [
                'success-message' => __('Successful registration.', 'mailup'),
                'generic-error-message' => __('There was an error. Please try again later.', 'mailup'),
            ];
        }
        $this->messages = Mailup_WPML::getTranslationMessages($this->messages);
    }

    protected function get_settings(): void
    {
        if (isset($this->options['settings']) && is_object($this->options['settings'])) {
            $settings = $this->options['settings'];
            $this->settings = (object) [
                'confirm' => filter_var($settings->confirm, FILTER_VALIDATE_BOOLEAN),
                'placeholder' => filter_var($settings->placeholder, FILTER_VALIDATE_BOOLEAN),
                'custom_css' => base64_decode($settings->custom_css, true),
            ];
        } else {
            $this->settings = (object) [
                'confirm' => true,
                'placeholder' => false,
                'custom_css' => '',
            ];
        }
    }

    protected function prepare_api_request($function, ...$args)
    {
        return call_user_func([$this->api, $function]);
    }

    private function load_dependencies(): void
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once plugin_dir_path(__DIR__).'includes/class-mailup-requests.php';

        include_once plugin_dir_path(__DIR__).'includes/class-mailup-tokens.php';

        include_once plugin_dir_path(__DIR__).'includes/class-mailup-terms.php';

        include_once plugin_dir_path(__DIR__).'includes/class-mailup-form.php';

        include_once plugin_dir_path(__DIR__).'includes/class-mailup-fe-form.php';

        include_once plugin_dir_path(__DIR__).'includes/class-mailup-formfield.php';

        include_once plugin_dir_path(__DIR__).'includes/class-mailup-wpml.php';

        $this->options = self::get_option();

        if ($this->options) {
            if ($this->has_tokens()) {
                $this->api = new Mailup_Requests($this->mailup);
            }

            if (isset($this->options['forms'])) {
                $this->get_forms($this->options['forms']);
            }

            if (isset($this->options['terms'])) {
                $this->get_terms($this->options['terms']);
            }
        }
        // SET DEFAULT OPTIONS
        $this->get_messages();
        $this->get_settings();
    }
}
