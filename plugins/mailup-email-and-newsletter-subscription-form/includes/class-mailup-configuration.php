<?php

declare(strict_types=1);

class Mailup_Platform_Configuration
{
    public const BASE_URL = 'https://services.mailup.com/';

    public const CLIENT_ID = '541dc22b-11eb-4b12-9752-fc6451c2f634';

    public const CLIENT_SECRET = 'f5486be1-6c47-4731-9a0f-0e215556dddd';

    public const URL_LOGON = 'Authorization/OAuth/LogOn';

    public const URL_TOKEN = 'Authorization/OAuth/Token';

    public const URL_LIST = 'API/v1.1/Rest/ConsoleService.svc/Console/List';

    public const URL_TYPE_FIELDS = 'API/v1.1/Rest/ConsoleService.svc/Console/Recipient/DynamicFields';

    /**
     * PUBLIC SERVICE URL.
     */
    public const URL_ADD_RECIPIENT = 'API/v1.1/Rest/ConsoleService.svc/Console/List/%s/Recipient';

    public const URL_ADD_TO_GROUP = 'API/v1.1/Rest/ConsoleService.svc/Console/Group/%s/Subscribe/%s';

    /**
     * COMMON SERVICE URL.
     */
    public const URL_LIST_GROUPS = 'API/v1.1/Rest/ConsoleService.svc/Console/List/%s/Groups';

    public const URL_CREATE_GROUP = 'API/v1.1/Rest/ConsoleService.svc/Console/List/%s/Group';

    public const URL_UPDATE_GROUP = 'API/v1.1/Rest/ConsoleService.svc/Console/List/%s/Group/%s';

    public $services_url;

    public $auth;

    public function __construct()
    {
        $this->services_url = [
            'logon' => $this->build_service_url(self::URL_LOGON),
            'token' => $this->build_service_url(self::URL_TOKEN),
            'lists' => $this->build_service_url(self::URL_LIST),
            'type_fields' => $this->build_service_url(self::URL_TYPE_FIELDS),
            'add_recipient' => $this->build_service_url(self::URL_ADD_RECIPIENT),
            'add_to_group' => $this->build_service_url(self::URL_ADD_TO_GROUP),
            'list_groups' => $this->build_service_url(self::URL_LIST_GROUPS),
            'create_group' => $this->build_service_url(self::URL_CREATE_GROUP),
            'update_group' => $this->build_service_url(self::URL_UPDATE_GROUP),
        ];
        $this->auth = [
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'redirect_uri' => sprintf('%sadmin.php?page=mailup-settings', admin_url()),
        ];
    }

    private function build_service_url($partial_service)
    {
        return sprintf('%s%s', self::BASE_URL, $partial_service);
    }
}
