<?php

namespace FluentSupport\App\Services;

use FluentSupport\App\App;
use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Meta;
use FluentSupport\App\Models\Person;
use FluentSupport\App\Services\EmailNotification\Settings;
use FluentSupport\Framework\Support\Arr;

/**
 *  Helper - REST API Helper Class
 *
 *  App helper for REST API
 *
 * @package FluentSupport\App\Services
 *
 * @version 1.0.0
 */
class Helper
{
    public static function FluentSupport($module = null)
    {
        return App::getInstance($module);
    }

    /**
     * Get agent information by user id
     * The function will get user id as parameter or get id from session and return agent information
     * @param null $userId
     * @return false | Agent
     */
    public static function getAgentByUserId($userId = null)
    {
        if ($userId === null) {
            $userId = get_current_user_id();
        }
        if (!$userId) {
            return false;
        }

        return Agent::where('user_id', $userId)->first();
    }

    /**
     * This function will return the list of ticket priorities list for customer
     *
     * @return mixed
     */
    public static function customerTicketPriorities()
    {
        return apply_filters('fluent_support/customer_ticket_priorities', [
            'normal'   => __('Normal', 'fluent-support'),
            'medium'   => __('Medium', 'fluent-support'),
            'critical' => __('Critical', 'fluent-support')
        ]);
    }

    /**
     * This function will return the list of ticket priorities list for Admin
     *
     * @return mixed
     */
    public static function adminTicketPriorities()
    {
        return apply_filters('fluent_support/admin_ticket_priorities', [
            'normal'   => __('Normal', 'fluent-support'),
            'medium'   => __('Medium', 'fluent-support'),
            'critical' => __('Critical', 'fluent-support')
        ]);
    }


    /**
     * This function will return ticket status group
     *
     * @return mixed
     */
    public static function ticketStatusGroups()
    {
        return apply_filters('fluent_support/ticket_status_groups', [
            'open'   => ['new', 'active'],
            'active' => ['active'],
            'closed' => ['closed'],
            'new'    => ['new'],
            'all'    => []
        ]);
    }

    /**
     * This function will return custom ticket status group
     *
     * @return mixed
     */
    public static function changeableTicketStatuses()
    {
        $ticketStatus = static::ticketStatusGroups();

        unset($ticketStatus['all']);
        unset($ticketStatus['open']);

        return apply_filters('fluent_support/changeable_ticket_statuses', $ticketStatus);
    }

    /**
     * This function will return ticket status list
     *
     * @return mixed
     */
    public static function ticketStatuses()
    {
        return apply_filters('fluent_support/ticket_statuses', [
            'new'    => __('New', 'fluent-support'),
            'active' => __('Active', 'fluent-support'),
            'closed' => __('Closed', 'fluent-support'),
        ]);
    }

    public static function getTkStatusesByGroupName($groupName)
    {
        $groups = self::ticketStatusGroups();
        return Arr::get($groups, $groupName, []);
    }

    public static function ticketAcceptedFileMiles()
    {
        $groups = self::getMimeGroups();
        $globalSettings = (new Settings())->globalBusinessSettings();

        if (empty($globalSettings['accepted_file_types'])) {
            return apply_filters('fluent_support/accepted_ticket_mimes', []);
        }

        $mimes = [];
        $typesGroups = Arr::only($groups, $globalSettings['accepted_file_types']);
        foreach ($typesGroups as $mimesGroup) {
            $mimes = array_merge($mimes, $mimesGroup['mimes']);
        }

        return apply_filters('fluent_support/accepted_ticket_mimes', $mimes);
    }

    public static function getAcceptedMimeHeadings()
    {
        $groups = self::getMimeGroups();
        $globalSettings = (new Settings())->globalBusinessSettings();

        if (empty($globalSettings['accepted_file_types'])) {
            return [];
        }

        $mimeNames = [];
        $typesGroups = Arr::only($groups, $globalSettings['accepted_file_types']);
        foreach ($typesGroups as $mimesGroup) {
            $mimeNames[] = $mimesGroup['title'];
        }

        return $mimeNames;
    }

    public static function getFileUploadMessage()
    {
        $mimeHeadings = self::getAcceptedMimeHeadings();
        $settings = (new Settings())->globalBusinessSettings();
        $maxFileSize = floatval($settings['max_file_size']);

        return sprintf(__('Supported Types: %s and max file size: %.01fMB', 'fluent-support'), implode(', ', $mimeHeadings), $maxFileSize);
    }

    public static function getMimeGroups()
    {
        return apply_filters('fluent_support/mime_groups', [
            'images'    => [
                'title' => __('Photos', 'fluent-support'),
                'mimes' => [
                    'image/gif',
                    'image/ief',
                    'image/jpeg',
                    'image/webp',
                    'image/pjpeg',
                    'image/ktx',
                    'image/png'
                ]
            ],
            'csv'       => [
                'title' => __('CSV', 'fluent-support'),
                'mimes' => [
                    'application/csv',
                    'application/txt',
                    'text/csv',
                    'text/plain',
                    'text/comma-separated-values',
                    'text/anytext',
                ]
            ],
            'documents' => [
                'title' => __('PDF/Docs', 'fluent-support'),
                'mimes' => [
                    'application/excel',
                    'application/vnd.ms-excel',
                    'application/vnd.msexcel',
                    'application/octet-stream',
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ]
            ],
            'zip'       => [
                'title' => __('Zip', 'fluent-support'),
                'mimes' => [
                    'application/zip'
                ]
            ],
            'json'      => [
                'title' => __('JSON', 'fluent-support'),
                'mimes' => [
                    'application/json',
                    'application/jsonml+json'
                ]
            ]
        ]);
    }

    /**
     * getOption method will return settings using key
     * This method will get key as parameter, fetch data from database, beautify the data and return
     * @param $key
     * @param string $default
     * @return mixed|string
     */
    public static function getOption($key, $default = '')
    {
        //Get settings from meta table using the key
        $data = Meta::where('object_type', 'option')
            ->where('key', $key)
            ->first();

        if ($data) {
            $value = maybe_unserialize($data->value);
            if ($value) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * updateOption method will update or insert settings
     * This method will get key and value as parameter, check exists or not. If exist update value by key, else insert value for the key
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function updateOption($key, $value)
    {
        //Get settings from meta table using the key
        $data = Meta::where('object_type', 'option')
            ->where('key', $key)
            ->first();

        //If data is available, update existing data and return
        if ($data) {
            return Meta::where('id', $data->id)
                ->update([
                    'value' => maybe_serialize($value)
                ]);
        }

        //If newly submit, create new record and return
        return Meta::insert([
            'object_type' => 'option',
            'key'         => $key,
            'value'       => maybe_serialize($value)
        ]);
    }

    public static function deleteOption($key)
    {
        return Meta::where('object_type', 'option')
            ->where('key', $key)
            ->delete();
    }

    /**
     * getIntegrationOption method will return the integration settings by integration key
     * @param $key
     * @param string $default
     * @return mixed|string
     */
    public static function getIntegrationOption($key, $default = '')
    {
        $data = Meta::where('object_type', 'integration_settings')
            ->where('key', $key)
            ->first();

        if ($data) {
            $value = maybe_unserialize($data->value);
            if ($value) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * updateIntegrationOption method will update existing settings or create new settings by integration key
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function updateIntegrationOption($key, $value)
    {
        $data = Meta::where('object_type', 'integration_settings')
            ->where('key', $key)
            ->first();

        if ($data) {
            return Meta::where('id', $data->id)
                ->update([
                    'value' => maybe_serialize($value)
                ]);
        }

        return Meta::insert([
            'object_type' => 'integration_settings',
            'key'         => $key,
            'value'       => maybe_serialize($value)
        ]);
    }

    public static function getTicketViewUrl($ticket)
    {
        $baseUrl = self::getPortalBaseUrl();

        return $baseUrl . '/#/ticket/' . $ticket->id . '/view';
    }

    public static function getTicketViewSignedUrl($ticket)
    {
        if (!self::isPublicSignedTicketEnabled()) {
            return self::getTicketViewUrl($ticket);
        }

        $baseUrl = self::getPortalBaseUrl();

        $baseUrl = add_query_arg([
            'fs_view'      => 'ticket',
            'support_hash' => $ticket->hash,
            'ticket_id'    => $ticket->id
        ], $baseUrl);

        return $baseUrl . '#/ticket/' . $ticket->id . '/view';
    }

    public static function isPublicSignedTicketEnabled()
    {
        $businessSettings = self::getBusinessSettings();

        return (Arr::get($businessSettings, 'disable_public_ticket') != 'yes');
    }

    public static function getTicketAdminUrl($ticket)
    {
        $baseUrl = self::getPortalAdminBaseUrl();
        return $baseUrl . 'tickets/' . $ticket->id . '/view';
    }

    /**
     * getPortalBaseUrl will get the portal page id and return link of the page
     * @return mixed
     */
    public static function getPortalBaseUrl()
    {
        $businessSettings = self::getBusinessSettings();
        $baseUrl = get_permalink($businessSettings['portal_page_id']);
        $baseUrl = rtrim($baseUrl, '/\\');
        return apply_filters('fluent_support/portal_base_url', $baseUrl);
    }

    public static function getPortalAdminBaseUrl()
    {
        return apply_filters('fluent_support/portal_admin_base_url', admin_url('admin.php?page=fluent-support/#/'));
    }

    public static function getBusinessSettings($key = null)
    {
        static $settings;

        if ($settings && $key) {
            return Arr::get($settings, $key);
        }

        if ($settings) {
            return $settings;
        }

        $settings = (new Settings())->globalBusinessSettings();

        if ($key) {
            return Arr::get($settings, $key);
        }
        return $settings;
    }

    public static function isAgentFeedbackEnabled()
    {
        return self::getBusinessSettings('agent_feedback_rating', 'no') == 'yes';
    }

    public static function getTicketMeta($ticketId, $key, $default = '')
    {
        $data = Meta::where('object_type', 'ticket_meta')
            ->where('key', $key)
            ->where('object_id', $ticketId)
            ->first();

        if ($data) {
            $value = maybe_unserialize($data->value);
            if ($value) {
                return $value;
            }
        }

        return $default;
    }

    public static function updateTicketMeta($ticketId, $key, $value)
    {
        $data = Meta::where('object_type', 'ticket_meta')
            ->where('key', $key)
            ->where('object_id', $ticketId)
            ->first();

        if ($data) {
            return Meta::where('id', $data->id)
                ->update([
                    'value' => maybe_serialize($value)
                ]);
        }

        return Meta::insert([
            'object_type' => 'ticket_meta',
            'object_id'   => $ticketId,
            'key'         => $key,
            'value'       => maybe_serialize($value)
        ]);
    }

    public static function getWPPages()
    {
        $pages = (self::FluentSupport())->app->db
            ->table('posts')
            ->select(['ID', 'post_title'])
            ->where('post_type', 'page')
            ->where('post_status', 'publish')
            ->latest('ID')
            ->get();
        $formattedPages = [];
        foreach ($pages as $page) {
            $formattedPages[] = [
                'id'    => strval($page->ID),
                'title' => $page->post_title
            ];
        }
        return $formattedPages;
    }

    public static function getDefaultMailBox()
    {
        $mailbox = MailBox::where('is_default', 'yes')->first();

        if ($mailbox) {
            return $mailbox;
        }

        return MailBox::oldest('id')->first();
    }

    public static function getCurrentAgent()
    {
        // If user is logged in then return the agent by user id.
        // This `get_current_user_id` function is WP function and
        // it returns user id if user is logged in.
        if (get_current_user_id()) {
            return Agent::where('user_id', get_current_user_id())->first();
        }
    }

    public static function getCurrentCustomer()
    {
        // If user is logged in then return the customer by user id.
        // This `get_current_user_id` function is WP function and
        // it returns user id if user is logged in.
        if (get_current_user_id()) { //if user is logged in
            return Customer::where('user_id', get_current_user_id())->first();
        }
    }

    public static function getCurrentPerson()
    {
        // If user is logged in then return the person(agent/customer) by user id.
        // This `get_current_user_id` function is WP function and
        // it returns user id if user is logged in.
        if (get_current_user_id()) {
            return Person::where('user_id', get_current_user_id())
                ->orderBy('id', 'ASC')
                ->first();
        }
        return null;
    }

    public static function getCustomerByID($customerid)
    {
        return Customer::where('id', $customerid)->first();
    }

    public static function sanitizeOrderValue($orderType = '')
    {
        $orderBys = ['ASC', 'DESC'];

        $orderType = trim(strtoupper($orderType));

        return in_array($orderType, $orderBys) ? $orderType : 'DESC';
    }

    public static function getFluentCRMTagConfig()
    {
        if (!defined('FLUENTCRM')) {
            return [
                'can_add_tags' => false,
                'tags'         => [],
                'lists'        => [],
                'logo'         => FLUENT_SUPPORT_PLUGIN_URL . 'assets/images/fluentcrm-logo.svg',
                'icon'         => FLUENT_SUPPORT_PLUGIN_URL . 'assets/images/fluent-crm-icon.png',
            ];
        }

        $canAddTags = \FluentCrm\App\Services\PermissionManager::currentUserCan('fcrm_manage_contacts');

        $canAddTags = apply_filters('fluent_support/can_user_add_tags_to_customer', $canAddTags);
        $crmTags = [];
        $crmLists = [];
        if ($canAddTags) {
            $crmTags = \FluentCrm\App\Models\Tag::select(['id', 'title'])->oldest('title')->get();
            $crmLists = \FluentCrm\App\Models\Lists::select(['id', 'title'])->oldest('title')->get();
        }

        $crmConfigs = [
            'can_add_tags' => $canAddTags,
            'tags'         => $crmTags,
            'lists'        => $crmLists,
            'logo'         => FLUENT_SUPPORT_PLUGIN_URL . 'assets/images/fluentcrm-logo.svg',
            'icon'         => FLUENT_SUPPORT_PLUGIN_URL . 'assets/images/fluent-crm-icon.png',
        ];

        if (defined('FLUENTCRM')) {
            $crmConfigs['contacts'] = []; //(new \FluentCrm\App\Models\Subscriber)->get();
        }

        return $crmConfigs;
    }

    /**
     * getFluentCrmContactData method will get information from fluent crm using user email
     * @param $customer
     * @return array|false
     */
    public static function getFluentCrmContactData($customer)
    {
        if (!defined('FLUENTCRM')) {
            return false;
        }
        //Get contact info from FluentCRM using customer email
        $contact = \FluentCrmApi('contacts')->getContactByUserRef($customer->email);
        if ($contact) {
            $tags = $contact->tags;
            $lists = $contact->lists;
            $urlBase = apply_filters('fluentcrm_menu_url_base', admin_url('admin.php?page=fluentcrm-admin#/'));
            $crmProfileUrl = $urlBase . 'subscribers/' . $contact->id;

            //Return contact data
            return [
                'id'            => $contact->id,
                'first_name'    => $contact->first_name,
                'last_name'     => $contact->last_name,
                'full_name'     => $contact->full_name,
                'name_mismatch' => $contact->full_name != $customer->full_name,
                'tags'          => $tags,
                'lists'         => $lists,
                'status'        => $contact->status,
                'stats'         => $contact->stats(),
                'view_url'      => $crmProfileUrl
            ];
        }

        return false;
    }

    public static function showTicketSummaryAdminBar()
    {
        $data = self::getOption('global_business_settings');

        if ($data && isset($data["enable_admin_bar_summary"]) && $data["enable_admin_bar_summary"] == 'yes') {
            return true;
        }

        return false;
    }

    public static function generateMessageID($email)
    {
        $emailParts = explode('@', $email);
        if (count($emailParts) != 2) {
            return false;
        }

        $emailDomain = $emailParts[1];
        try {
            return sprintf(
                "<%s.%s@%s>",
                base_convert((int)microtime(true), 10, 36),
                base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
                $emailDomain
            );
        } catch (\Exception $exception) {
            return false;
        }
    }

    public static function getExportOptions()
    {
        $data = [
            'Agent First Name' => __('Agent First Name', 'fluent-support'),
            'Agent Last Name'  => __('Agent Last Name', 'fluent-support'),
            'Agent Full Name'  => __('Agent Full Name', 'fluent-support'),
            'Responses'        => __('Responses', 'fluent-support'),
            'Interactions'     => __('Interactions', 'fluent-support'),
            'Open Tickets'     => __('Open Tickets', 'fluent-support'),
            'Closed'           => __('Closed', 'fluent-support'),
            'Waiting Tickets'  => __('Waiting Tickets', 'fluent-support'),
            'Average Waiting'  => __('Average Waiting', 'fluent-support'),
            'Max Waiting'      => __('Max Waiting', 'fluent-support'),
        ];

        if (Helper::isAgentFeedbackEnabled()) {
            $data['Likes'] = __('Likes', 'fluent-support');
            $data['Dislikes'] = __('Dislikes', 'fluent-support');
        }

        return $data;
    }

    public static function getAuthProvider()
    {
        if (defined('FLUENT_AUTH_PLUGIN_PATH')) {
            $settings = \FluentAuth\App\Helpers\Helper::getAuthFormsSettings();
            if ($settings['enabled'] == 'yes') {
                return 'fluent_auth';
            }
        }

        return 'fluent_support';
    }

    public static function getDriversKey(){
        return [
            'dropbox_settings',
            'google_drive_settings',
            'local'
        ];
    }


    public static function getUploadDriverKey()
    {
        if (!defined('FLUENTSUPPORTPRO')) {
            return 'local';
        }

        $driver = self::getOption('file_upload_driver');

        if ($driver) {
            return $driver;
        }

        // Now guess the driver and save it

        // check if dropbox is enabled
        $dropboxSettings = self::getIntegrationOption('dropbox_settings', null);
        if ($dropboxSettings) {
            $dropBoxEnabled = Meta::where('object_type', 'enabled_upload_drivers')
                ->where('key', 'dropbox_settings')
                ->where('value', 'yes')
                ->first();

            if ($dropBoxEnabled) {
                $driver = 'dropbox';
                self::updateOption('file_upload_driver', $driver);
                return $driver;
            }
        }

        // check if google drive is enabled
        $googleDriveSettings = self::getIntegrationOption('google_drive_settings', null);

        if ($googleDriveSettings) {
            $googleDriveEnabled = Meta::where('object_type', 'enabled_upload_drivers')
                ->where('key', 'google_drive_settings')
                ->where('value', 'yes')
                ->first();

            if ($googleDriveEnabled) {
                $driver = 'google_drive';
                self::updateOption('file_upload_driver', $driver);
                return $driver;
            }
        }

        self::updateOption('file_upload_driver', 'local');
        return 'local';
    }
}
