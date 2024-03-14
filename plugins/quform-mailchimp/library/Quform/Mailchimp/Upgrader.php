<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Upgrader
{
    const DB_VERSION = 1;

    /**
     * @var Quform_Mailchimp_Integration_Repository
     */
    protected $repository;

    /**
     * @var Quform_Mailchimp_Permissions
     */
    protected $permissions;

    /**
     * @param  Quform_Mailchimp_Integration_Repository  $repository
     * @param  Quform_Mailchimp_Permissions             $permissions
     */
    public function __construct(Quform_Mailchimp_Integration_Repository $repository, Quform_Mailchimp_Permissions $permissions)
    {
        $this->repository = $repository;
        $this->permissions = $permissions;
    }

    /**
     * Check if any upgrades need to be processed
     */
    public function upgradeCheck()
    {
        if (get_option('quform_mailchimp_activated') === '1' || get_option('quform_mailchimp_db_version') != self::DB_VERSION) {
            // Trigger plugin activation
            $this->activate();

            // Get the version again (as it can change during plugin activation, this will be the previously installed version)
            $version = get_option('quform_mailchimp_db_version');

            update_option('quform_mailchimp_db_version', self::DB_VERSION);
            delete_option('quform_mailchimp_activated');

            // Process any upgrades as required here
        }
    }

    /**
     * Run the plugin activation functions
     */
    public function activate()
    {
        add_option('quform_mailchimp_db_version', self::DB_VERSION);

        $this->repository->activate();
        $this->permissions->activate();
    }

    /**
     * On plugin uninstall remove the plugin version
     */
    public function uninstall()
    {
        delete_option('quform_version');
    }
}
