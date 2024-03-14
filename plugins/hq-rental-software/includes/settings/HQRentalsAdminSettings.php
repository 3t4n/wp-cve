<?php

namespace HQRentalsPlugin\HQRentalsSettings;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDatesHelper;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesBrands;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;

class HQRentalsAdminSettings
{
    protected static $settingsPageTitle = 'HQ Rentals';
    protected static $settingsMenuTitle = 'Settings';
    protected static $settingsSlug = 'hq-wordpress-settings';
    protected static $settingBrandPageTitle = 'Brands';
    protected static $settingBrandPageSlug = 'hq-brands';
    protected static $settingsLocationPageTitle = 'Locations';
    protected static $settingsLocationPageSlug = 'hq-locations';
    protected static $settingsVehicleClassPageTitle = 'Vehicle Classes';
    protected static $settingsVehicleClassPageSlug = 'hq-vehicle-classes';

    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
        $this->dateHelper = new HQRentalsDatesHelper();
        $this->frontHelper = new HQRentalsFrontHelper();
        $this->assets = new HQRentalsAssetsHandler();
        add_action('admin_menu', array($this, 'setAdminMenuOptions'), 10);
    }

    public function setAdminMenuOptions()
    {
        add_menu_page(
            HQRentalsAdminSettings::$settingsMenuTitle,
            HQRentalsAdminSettings::$settingsPageTitle,
            'manage_options',
            HQRentalsAdminSettings::$settingsSlug,
            array($this, 'displaySettingsPage'),
            HQRentalsAssetsHandler::getLogoForAdminMenu(),
            100
        );
        add_submenu_page(
            HQRentalsAdminSettings::$settingsSlug,
            HQRentalsAdminSettings::$settingBrandPageTitle,
            HQRentalsAdminSettings::$settingBrandPageTitle,
            'manage_options',
            HQRentalsAdminSettings::$settingBrandPageSlug,
            array($this, 'displayBrandsPage')
        );
        add_submenu_page(
            HQRentalsAdminSettings::$settingsSlug,
            HQRentalsAdminSettings::$settingsLocationPageTitle,
            HQRentalsAdminSettings::$settingsLocationPageTitle,
            'manage_options',
            HQRentalsAdminSettings::$settingsLocationPageSlug,
            array($this, 'displayLocationsPage')
        );
        add_submenu_page(
            HQRentalsAdminSettings::$settingsSlug,
            HQRentalsAdminSettings::$settingsVehicleClassPageTitle,
            HQRentalsAdminSettings::$settingsVehicleClassPageTitle,
            'manage_options',
            HQRentalsAdminSettings::$settingsVehicleClassPageSlug,
            array($this, 'displayVehicleClassPage')
        );
    }

    public function displaySettingsPage()
    {

        if (!empty($_POST)) {
            $this->settings->updateSettings($_POST);
            ?>
            <?php if (isset($_POST['success']) && $_POST['success'] == 'success') : ?>
                <div class="wrap">
                    <div class="message updated"><p>The settings were saved successfully.</p></div>
                </div>
            <?php elseif (isset($_POST['success']) && $_POST['success'] == 'error') : ?>
                <div class="wrap">
                    <div class="notice notice-error"><p>Error Processing the
                            information: <?php echo $_POST['error_message']; ?></p></div>
                </div>
            <?php else : ?>
                <div class="wrap">
                    <div class="message updated"><p>There was an issue with your request.</p></div>
                </div>
            <?php endif; ?>
            <?php
        } else {
            $this->assets->loadAssetsForAdminSettingPage();
            $okAPI = $this->settings->isApiOkay();
            HQRentalsAssetsHandler::getHQFontAwesome();
            $devMode = isset($_GET['dev']);
            $tenantLink = $this->settings->getTenantLink();
            ?>
            <script>
                var loginActive = <?php echo ($okAPI) ? 'true' : 'false'; ?>;
                var hqWebsiteURL = "<?php echo home_url(); ?>"
            </script>
            <div id="hq-settings-page" class="wrap">
                <div id="wrap">
                    <div class="form-outer-wrapper">
                        <div class="hq-title-wrapper">
                            <img src="<?php echo HQRentalsAssetsHandler::getLogoForAdminArea(); ?>" alt="">
                            <?php if ($okAPI) : ?>
                                <div id="hq-connected-indicator"
                                     style="background-color: #28a745; border: 2px solid #28a745;"
                                     class="hq-connected-sign">
                                    <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                </div>
                            <?php else : ?>
                                <div id="hq-not-connected-indicator"
                                     style="background-color: #dc3545; border: 2px solid #dc3545;"
                                     class="hq-connected-sign">
                                    <h6 class="hq-connected-sign-text">NOT CONNECTED</h6>
                                </div>
                                <div id="hq-connected-indicator"
                                     style="background-color: #28a745; border: 2px solid #28a745;"
                                     class="hq-connected-sign">
                                    <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                </div>
                                <style>
                                    #hq-connected-indicator {
                                        display: none;
                                    }
                                </style>
                            <?php endif; ?>
                        </div>
                        <form action="" method="post" id="hq-admin-form">
                            <div class="hq-title-item">
                                <h1 class="hq-admin-h1">Settings
                                <?php if ($devMode) : ?>
                                    <a class="hq-tenant-link-button" target="_blank" href="<?php echo $tenantLink; ?>"><i class="far fa-building"></i></a>
                                <?php endif; ?></h1>
                                <button
                                        type="submit"
                                        name="save"
                                        class="button button-primary button-large hq-admin-submit-button">Save Changes</button>
                            </div>
                            <div class="hq-general-settings-section-wrapper">
                                <div class="hq-form-component-wrapper">
                                    <div class="hq-tabs-wrapper">
                                        <ul data-tabs-position="vertical left" data-role="tabs">
                                            <?php if ($devMode) : ?>
                                                <li><a href="#dev">Dev Options</a></li>
                                            <?php else : ?>
                                                <li><a href="#auth">Authentication</a></li>
                                            <?php endif; ?>
                                            <li><a href="#iframe">Iframe</a></li>
                                            <li><a href="#custom_fields">Custom Fields</a></li>
                                            <li><a href="#sync">Syncronization</a></li>
                                            <li><a href="#theme">Theme</a></li>
                                            <li><a href="#keys">Keys</a></li>
                                        </ul>
                                    </div>
                                    <div class="hq-tabs-content border bd-default no-border-top p-2">
                                        <?php if ($devMode) : ?>
                                            <!-- Begin Dev Tab -->
                                            <div id="dev">
                                                <div class="hq-text-items-wrappers">
                                                    <div class="hq-general-settings-item">
                                                        <div class="hq-general-label-wrapper-big-fields">
                                                            <h4 class="wp-heading-inline" for="title">Tenant token</h4>
                                                            <span id="hq-tooltip-tenant-token"
                                                                  class="dashicons dashicons-search"
                                                                  data-tippy-content="
                                                                  Log in to your HQ account and navigate to settings
                                                                  > settings > integrations >
                                                                  copy the API token and paste it here."></span>
                                                        </div>
                                                        <div class="hq-general-input-wrapper-big-fields tokens">
                                                            <input class="hq-admin-text-input"
                                                                   type="text"
                                                                   name="<?php echo esc_attr($this->settings->api_tenant_token); ?>"
                                                                   value="<?php echo esc_attr($this->settings->getApiTenantToken()); ?>"
                                                                   id="hq-api-tenant-token"
                                                                   spellcheck="true" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="hq-general-settings-item">
                                                        <div class="hq-general-label-wrapper-big-fields">
                                                            <h4 class="wp-heading-inline" for="title">User token</h4>
                                                            <span id="hq-tooltip-tenant-token"
                                                                  class="dashicons dashicons-search"
                                                                  data-tippy-content="Log in to your HQ account and navigate to settings
                                                                  > user management > users > integrations > select your user profile
                                                                  > generate and copy the API token and paste it here.">
                                                            </span>
                                                        </div>
                                                        <div class="hq-general-input-wrapper-big-fields tokens">
                                                            <input class="hq-admin-text-input"
                                                                   type="text"
                                                                   name="<?php echo esc_attr($this->settings->api_user_token); ?>"
                                                                   value="<?php echo esc_attr($this->settings->getApiUserToken()); ?>"
                                                                   id="hq-api-user-token"
                                                                   spellcheck="true" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="hq-general-settings-item">
                                                        <div class="hq-general-label-wrapper">
                                                            <h4 class="wp-heading-inline" for="title">Select front-end
                                                                date
                                                                format</h4>
                                                            <span id="hq-tooltip-tenant-token"
                                                                  class="dashicons dashicons-search"
                                                                  data-tippy-content=" This is the format of the dates on
                                                                  your website, and this must match the system date format."></span>
                                                        </div>
                                                        <div class="hq-general-input-wrapper">
                                                            <select class="hq-admin-select-input"
                                                                    name="<?php echo esc_attr($this->settings->front_end_datetime_format); ?>">
                                                                <?php echo $this->dateHelper->getHtmlOptionForFrontEndDateSettingOption(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="hq-general-settings-item">
                                                        <div class="hq-general-label-wrapper">
                                                            <h4 class="wp-heading-inline" for="title">Select system date
                                                                format</h4>
                                                            <span id="hq-tooltip-tenant-token"
                                                                  class="dashicons dashicons-search"
                                                                  data-tippy-content="This is the date format set up on your HQ
                                                                  account settings. You can find this under general settings."></span>
                                                        </div>
                                                        <div class="hq-general-input-wrapper">
                                                            <select class="hq-admin-select-input"
                                                                    name="<?php echo $this->settings->hq_datetime_format; ?>">
                                                                <?php echo $this->dateHelper->getHtmlOptionForSystemDateSettingOption(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="hq-general-settings-item">
                                                        <div class="hq-general-label-wrapper">
                                                            <h4 class="wp-heading-inline" for="title">API Tenant
                                                                Region</h4>
                                                            <span id="hq-tooltip-tenant-token"
                                                                  class="dashicons dashicons-search"
                                                                  data-tippy-content="<span>For xxx.caagcrm.com, your region is America</span>
                                                                        <p>For xxx.hqrentals.app, your region is in America 2</p>
                                                                        <p>For xxx.west.hqrentals.app, your region is in America West</p>
                                                                        <p>For xxx.hqrentals.eu, your region is Europe</p>
                                                                        <p>For xxx.hqrentals.asia, your region is Asia</p>"></span>
                                                        </div>
                                                        <div class="hq-general-input-wrapper">
                                                            <select
                                                                    id="hq-api-user-base-url"
                                                                    class="hq-admin-select-input "
                                                                    name="<?php echo esc_attr($this->settings->api_base_url); ?>">
                                                                <option value="https://api.caagcrm.com/api/"
                                                                <?php echo ($this->settings->getApiBaseUrl() == 'https://api.caagcrm.com/api/')
                                                                ? 'selected="selected"' : ''; ?>>
                                                                    America
                                                                </option>
                                                                <option value="https://api-america-2.caagcrm.com/api-america-2/"
                                                                <?php
                                                                echo ($this->settings->getApiBaseUrl() ==
                                                                'https://api-america-2.caagcrm.com/api-america-2/')
                                                                ? 'selected="selected"' : ''; ?>>
                                                                    America 2
                                                                </option>
                                                                <option value="https://api-america-3.caagcrm.com/api-america-3/"
                                                                <?php echo
                                                                ($this->settings->getApiBaseUrl() ==
                                                                'https://api-america-3.caagcrm.com/api-america-3/')
                                                                ? 'selected="selected"' : ''; ?>>
                                                                    America 3
                                                                </option>
                                                                <option value="https://api-america-west.caagcrm.com/api-america-west/"
                                                                <?php echo
                                                                ($this->settings->getApiBaseUrl() ==
                                                                'https://api-america-west.caagcrm.com/api-america-west/')
                                                                ? 'selected="selected"' : ''; ?>>
                                                                    America West
                                                                </option>
                                                                <option value="https://api-america-miami.caagcrm.com/api-america-miami/"
                                                                <?php echo
                                                                    ($this->settings->getApiBaseUrl() ==
                                                                    'https://api-america-miami.caagcrm.com/api-america-miami/')
                                                                ? 'selected="selected"' : ''; ?>>
                                                                    America Miami
                                                                </option>
                                                                <option value="https://api-europe.caagcrm.com/api-europe/"
                                                                <?php
                                                                    echo
                                                                        ($this->settings->getApiBaseUrl() == 'https://api-europe.caagcrm.com/api-europe/')
                                                                        ? 'selected="selected"' : ''; ?>>
                                                                    Europe
                                                                </option>
                                                                <option value="https://api-asia.caagcrm.com/api-asia/"
                                                                <?php echo ($this->settings->getApiBaseUrl() == 'https://api-asia.caagcrm.com/api-asia/')
                                                                ? 'selected="selected"' : ''; ?>>
                                                                    Asia
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Dev Tab -->
                                        <?php else : ?>
                                            <!-- Begin Auth Tab -->
                                            <div id="auth">
                                                <div class="hq-text-items-wrappers">
                                                    <div class="hq-general-settings-item">
                                                        <div class="hq-general-label-wrapper-big-fields">
                                                            <h4 class="wp-heading-inline" for="title">Email</h4>
                                                        </div>
                                                        <div class="hq-general-input-wrapper-big-fields tokens">
                                                            <input class="hq-admin-text-input"
                                                                   type="text"
                                                                   name="hq-email"
                                                                   id="hq-email"
                                                                   spellcheck="true" autocomplete="off"
                                                                   value="<?php echo esc_attr($this->settings->getEmail()); ?>"
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="hq-general-settings-item">
                                                        <div class="hq-general-label-wrapper-big-fields">
                                                            <h4 class="wp-heading-inline" for="title">Password</h4>
                                                        </div>
                                                        <div class="hq-general-input-wrapper-big-fields tokens">
                                                            <input
                                                                    class="hq-admin-text-input"
                                                                    type="password"
                                                                    name="hq-password"
                                                                    id="hq-password"
                                                                    spellcheck="true" autocomplete="off"/>
                                                        </div>
                                                        <input type="hidden"
                                                               name="<?php echo esc_attr($this->settings->api_tenant_token); ?>"
                                                               value="<?php echo esc_attr($this->settings->getApiTenantToken()); ?>"
                                                               id="hq-api-tenant-token">
                                                        <input type="hidden"
                                                               name="<?php echo esc_attr($this->settings->api_user_token); ?>"
                                                               value="<?php echo esc_attr($this->settings->getApiUserToken()); ?>"
                                                               id="hq-api-user-token">

                                                        <input
                                                                type="hidden"
                                                                name="<?php echo esc_attr($this->settings->api_base_url); ?>"
                                                                value="<?php echo esc_attr($this->settings->getApiBaseUrl()); ?>"
                                                                id="hq-api-user-base-url"
                                                                spellcheck="true" autocomplete="off">
                                                    </div>
                                                    <div class="hq-loader">
                                                        <div class="hq-loader-inner-wrapper">
                                                            <img src="<?php echo plugins_url('hq-rental-software/includes/assets/img/spinner.gif'); ?>"
                                                                 alt="">
                                                        </div>
                                                    </div>
                                                    <div class="hq-submit-login-button-wrapper">
                                                        <button id="hq-submit-login-button" type="button" name="save"
                                                                value="Save" class="button button-primary button-large">
                                                            AUTHENTICATE
                                                        </button>
                                                    </div>
                                                    <div class="hq-messages-box-failed hq-box">
                                                        <div class="alert alert-danger" role="alert">
                                                            There was an issue, please review your login information
                                                        </div>
                                                    </div>
                                                    <div class="hq-messages-box-success hq-box">
                                                        <div class="alert alert-success" role="alert">
                                                            The settings were saved successfully.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Auth Tab -->
                                        <?php endif; ?>
                                        <!-- Begin Iframe Tab -->
                                        <div id="iframe">
                                            <div class="hq-text-items-wrappers">
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">URL from public reservation process</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the URL from the reservation snippet/shortcode"></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-medium"
                                                               name="<?php echo $this->settings->hq_public_reservation_workflow_url; ?>"
                                                               value="<?php echo esc_attr($this->settings->getPublicReservationWorkflowURL()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Domain to replace
                                                            in the
                                                            public reservation process</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This domain will be used to replace the system
                                                              url in public reservation processes"></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-medium"
                                                               name="<?php echo $this->settings->hq_url_to_replace_on_brands_option; ?>"
                                                               value="<?php echo esc_attr($this->settings->getBrandURLToReplaceSetting()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Disable safari
                                                            redirect</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This will disable the redirection to the public reservation link of your
                                                              HQ account for users using a Safari browser. You will need to update
                                                              CNAME record for compatibility"></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_disable_safari_functionality; ?>"
                                                               value="true" <?php echo ($this->settings->getDisableSafari() === 'true') ? 'checked' : ''; ?>/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Support for
                                                            reservation iFrame on homepage</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Support for reservations iframe on the home page  - this should be
                                                              applied in case that you are placing the reservation process on the home page."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_integration_on_home; ?>"
                                                               value="true" <?php echo ($this->settings->getSupportForHomeIntegration() === 'true')
                                                                ? 'checked' : ''; ?>/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Enable change of
                                                            branch url</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This option will enable you to change the base url for the
                                                              public links of the reservation process."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_replace_url_on_brand_option; ?>"
                                                               value="true" <?php echo ($this->settings->getReplaceBaseURLOnBrandsSetting() === 'true')
                                                                ? 'checked' : ''; ?>/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Iframe Tab -->
                                        <!-- Begin Custom Fields Tab -->
                                        <div id="custom_fields">
                                            <div class="hq-text-items-wrappers">
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            coordinates
                                                            field id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you added
                                                              and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_coordinate_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getLocationCoordinateField()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            image field
                                                            id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you
                                                              added and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_image_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getLocationImageField()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            description
                                                            field id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you added
                                                               and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_description_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getLocationDescriptionField()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            address field
                                                            id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you
                                                              added and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_address_label_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getAddressLabelField()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            office hours
                                                            field id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you added
                                                              and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_office_hours_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getOfficeHoursSetting()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            address field
                                                            id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you added
                                                              and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_address_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getAddressSetting()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            phone number
                                                            field id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you added
                                                              and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_phone_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getPhoneSetting()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet location
                                                            vehicle brands
                                                            field id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the locations form.
                                                              Please navigate to settings > items > fields > search for the custom field you added
                                                              and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_location_brands_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getBrandsSetting()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Fleet Vehicle Class Banner field id</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This is the id of the custom field added to the vehicle classes form.
                                                              Please navigate to settings > items > fields > search for the custom field you added
                                                              and paste the number under DB column here."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_vehicle_class_banner_image_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getVehicleClassBannerImageField()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Default latitude
                                                            for Map
                                                            Shortcode</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Place here the latitude of the location you would like for
                                                              the map to set focus on in case the user denies location access."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-small-medium"
                                                               name="<?php echo $this->settings->hq_default_latitude_for_map_shortcode; ?>"
                                                               value="<?php echo esc_attr($this->settings->getDefaultLatitudeSetting()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Default longitude
                                                            for Map
                                                            Shortcode</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Place here the longitude of the location you would
                                                              like for the map to set focus on in case the user denies location access."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-small-medium"
                                                               name="<?php echo $this->settings->hq_default_longitude_for_map_shortcode; ?>"
                                                               value="<?php echo esc_attr($this->settings->getDefaultLongitudeSetting()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Vehicle Class Type Field</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Place here vehicle type field to filter vehicles."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-small-medium"
                                                               name="<?php echo $this->settings->hq_vehicle_class_type_field; ?>"
                                                               value="<?php echo esc_attr($this->settings->getVehicleClassTypeField()); ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Custom Fields Tab -->
                                        <!-- Begin Sync Tab -->
                                        <div id="sync">
                                            <div class="hq-text-items-wrappers">
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Disable Data
                                                            Synchronization</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Select this option to stop data sychronization
                                                              between HQ Rental Software and the website."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_disable_cronjob_option; ?>"
                                                               value="true" <?php echo ($this->settings->getDisableCronjobOption() === 'true')
                                                                ? 'checked' : ''; ?>/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Enable Sync Through Webhooks</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Select this option to enable keeping
                                                              the website sync using system webhooks."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_webhook_sync; ?>"
                                                               value="true" <?php echo ($this->settings->getWebhookSyncOption() === 'true')
                                                                ? 'checked' : ''; ?>/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Sync Tab -->
                                        <!-- Begin Theme Tab -->
                                        <div id="theme">
                                            <div class="hq-text-items-wrappers">
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">ORDER VEHICLE
                                                            CLASSES ON
                                                            WIDGET WITH RATES FROM HIGHEST TO LOWEST</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This option will display the vehicles with rates
                                                              in the vehicle widget from highest to lowest instead of the default
                                                              ascending order."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_enable_decreasing_rate_order_on_vehicles_query; ?>"
                                                               value="true" <?php
                                                                                echo ($this->settings->getDecreasingRateOrder() === 'true')
                                                                                ? 'checked' : ''; ?> />
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">ENABLE CUSTOM
                                                            POSTS PAGES</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This option will enable Vehicle Classes single pages on your website."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_enable_custom_post_pages; ?>"
                                                               value="true" <?php
                                                                                echo
                                                                                    ($this->settings->getEnableCustomPostsPages() === 'true')
                                                                                     ? 'checked' : ''; ?> />
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">OVERRIDE DAILY
                                                            RATE WITH PRICE INTERVALS</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This option will force the plugin
                                                              to display price intervals intead of the daily rate."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper hq-general-input-wrapper-checkbox">
                                                        <input type="checkbox"
                                                               name="<?php echo $this->settings->hq_override_daily_rate_with_price_interval; ?>"
                                                               value="true"
                                                               <?php
                                                                   echo
                                                                    ($this->settings->getOverrideDailyRateWithCheapestPriceInterval() === 'true')
                                                                     ? 'checked' : ''; ?> />
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">DEFAULT CURRENCY
                                                            DISPLAY</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This will be the currency the displayed
                                                              by default on the vehicle class grid, and the dedicated
                                                              vehicle class pages."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_currency_symbol; ?>"
                                                               value="<?php echo esc_attr($this->settings->getCurrencyIconOption()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">GOOGLE MAP COUNTRY</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="This will be the country
                                                              for google services(Places API, Google Map API, etc)."></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input"
                                                               name="<?php echo $this->settings->hq_google_country; ?>"
                                                               value="<?php echo esc_attr($this->settings->getGoogleCountry()); ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Theme Tab -->
                                        <!-- Begin Key Tab -->
                                        <div id="keys">
                                            <div class="hq-text-items-wrappers">
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Google API
                                                            Key</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Google Services API Key"></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-medium medium large"
                                                               name="<?php echo $this->settings->hq_google_api_key; ?>"
                                                               value="<?php echo esc_attr($this->settings->getGoogleAPIKey()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Google Captcha Site Key</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Google Captcha Site Key"></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-medium medium large"
                                                               name="<?php echo $this->settings->hq_captcha_key; ?>"
                                                               value="<?php echo esc_attr($this->settings->getCaptchaKey()); ?>"/>
                                                    </div>
                                                </div>
                                                <div class="hq-general-settings-item">
                                                    <div class="hq-general-label-wrapper">
                                                        <h4 class="wp-heading-inline" for="title">Google Captcha Secret Key</h4>
                                                        <span id="hq-tooltip-tenant-token"
                                                              class="dashicons dashicons-search"
                                                              data-tippy-content="Google Captcha Secret Key"></span>
                                                    </div>
                                                    <div class="hq-general-input-wrapper">
                                                        <input type="text"
                                                               class="hq-admin-text-input hq-admin-text-input-medium medium large"
                                                               name="<?php echo $this->settings->hq_captcha_secret; ?>"
                                                               value="<?php echo esc_attr($this->settings->getCaptchaSecret()); ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Key Tab -->
                                    </div>
                                </div>
                                <div class="hq-admin-help-section">
                                    <p>Need help? Please click <strong>
                                        <a target="_blank"
                                            href="https://www.notion.so/hqrs/HQRS-WordPress-Plugin-22982d6ceb6e439aaa0cf4d66a84f6dc">
                                            here
                                        </a></strong>For more information on how to set up the HQ Rentals plugin.</p>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            <?php
        }
    }
    public function displayBrandsPage()
    {
        $this->assets->loadAssetsForAdminSettingPage();
        $okAPI = $this->settings->isApiOkay();
        HQRentalsAssetsHandler::getHQFontAwesome();
        $devMode = isset($_GET['dev']);
        $query = new HQRentalsDBQueriesBrands();
        $brands = $query->allBrands();
        ?>
                <script>
                    var loginActive = <?php echo ($okAPI) ? 'true' : 'false'; ?>;
                    var hqWebsiteURL = "<?php echo home_url(); ?>"
                </script>
                <div id="hq-settings-page" class="wrap">
                    <div id="wrap">
                        <div class="form-outer-wrapper-tables">
                            <div class="hq-title-wrapper">
                                <img src="<?php echo HQRentalsAssetsHandler::getLogoForAdminArea(); ?>" alt="">
                                <?php if ($okAPI) : ?>
                                    <div id="hq-connected-indicator"
                                         style="background-color: #28a745; border: 2px solid #28a745;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                    </div>
                                <?php else : ?>
                                    <div id="hq-not-connected-indicator"
                                         style="background-color: #dc3545; border: 2px solid #dc3545;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">NOT CONNECTED</h6>
                                    </div>
                                    <div id="hq-connected-indicator"
                                         style="background-color: #28a745; border: 2px solid #28a745;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                    </div>
                                    <style>
                                        #hq-connected-indicator {
                                            display: none;
                                        }
                                    </style>
                                <?php endif; ?>
                            </div>
                            <div class="hq-title-item-tables">
                                <h1 class="hq-admin-h1">Brands</h1>
                            </div>
                            <div>
                                <table class="hq-table wp-list-table widefat striped table-view-list posts">
                                    <?php echo $this->renderHeaderAndOrFooter([
                                        'Id',
                                        'Name',
                                        'Reservation Form Snippet',
                                        'Reservation Snippet',
                                        'Vehicle Class Calendar',
                                        'Updated At'
                                    ]); ?>
                                    <tbody>
                                        <?php foreach ($brands as $brand) : ?>
                                            <tr>
                                                <th><?php echo $brand->getId(); ?></th>
                                                <th><?php echo $brand->getName(); ?></th>
                                                <th><code>[hq_rentals_reservation_form_snippet id=<?php echo $brand->getId(); ?>]</code></th>
                                                <th><code>[hq_rentals_reservations_snippet id=<?php echo $brand->getId(); ?>]</code></th>
                                                <th><code>[hq_rentals_vehicle_calendar id=<?php echo $brand->getId(); ?>]</code></th>
                                                <th><?php echo $brand->getUpdatedAt(); ?></th>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <?php echo $this->renderHeaderAndOrFooter([
                                        'Id',
                                        'Name',
                                        'Reservation Form Snippet',
                                        'Reservation Snippet',
                                        'Vehicle Class Calendar',
                                        'Updated At'
                                    ], true); ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
    }
    public function displayLocationsPage()
    {
        $this->assets->loadAssetsForAdminSettingPage();
        $okAPI = $this->settings->isApiOkay();
        HQRentalsAssetsHandler::getHQFontAwesome();
        $devMode = isset($_GET['dev']);
        $query = new HQRentalsDBQueriesLocations();
        $locations = $query->allLocations();
        ?>
                <script>
                    var loginActive = <?php echo ($okAPI) ? 'true' : 'false'; ?>;
                    var hqWebsiteURL = "<?php echo home_url(); ?>"
                </script>
                <div id="hq-settings-page" class="wrap">
                    <div id="wrap">
                        <div class="form-outer-wrapper-tables">
                            <div class="hq-title-wrapper">
                                <img src="<?php echo HQRentalsAssetsHandler::getLogoForAdminArea(); ?>" alt="">
                                <?php if ($okAPI) : ?>
                                    <div id="hq-connected-indicator"
                                         style="background-color: #28a745; border: 2px solid #28a745;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                    </div>
                                <?php else : ?>
                                    <div id="hq-not-connected-indicator"
                                         style="background-color: #dc3545; border: 2px solid #dc3545;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">NOT CONNECTED</h6>
                                    </div>
                                    <div id="hq-connected-indicator"
                                         style="background-color: #28a745; border: 2px solid #28a745;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                    </div>
                                    <style>
                                        #hq-connected-indicator {
                                            display: none;
                                        }
                                    </style>
                                <?php endif; ?>
                            </div>
                            <div class="hq-title-item-tables">
                                <h1 class="hq-admin-h1">Locations</h1>
                            </div>
                            <div>
                                <table class="hq-table wp-list-table widefat fixed striped table-view-list posts">
                                    <?php echo $this->renderHeaderAndOrFooter([
                                        'Id',
                                        'Name',
                                        'Updated At'
                                    ]); ?>
                                    <tbody>
                                        <?php foreach ($locations as $location) : ?>
                                            <tr>
                                                <th><?php echo $location->getId(); ?></th>
                                                <th><?php echo $location->getName(); ?></th>
                                                <th><?php echo $location->getUpdatedAt(); ?></th>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <?php echo $this->renderHeaderAndOrFooter([
                                        'Id',
                                        'Name',
                                        'Updated At'
                                    ], true); ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
    }
    public function displayVehicleClassPage()
    {
        $this->assets->loadAssetsForAdminSettingPage();
        $okAPI = $this->settings->isApiOkay();
        HQRentalsAssetsHandler::getHQFontAwesome();
        $devMode = isset($_GET['dev']);
        $query = new HQRentalsDBQueriesVehicleClasses();
        $vehicles = $query->allVehicleClasses(true);
        ?>
                <script>
                    var loginActive = <?php echo ($okAPI) ? 'true' : 'false'; ?>;
                    var hqWebsiteURL = "<?php echo home_url(); ?>"
                </script>
                <div id="hq-settings-page" class="wrap">
                    <div id="wrap">
                        <div class="form-outer-wrapper-tables">
                            <div class="hq-title-wrapper">
                                <img src="<?php echo HQRentalsAssetsHandler::getLogoForAdminArea(); ?>" alt="">
                                <?php if ($okAPI) : ?>
                                    <div id="hq-connected-indicator"
                                         style="background-color: #28a745; border: 2px solid #28a745;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                    </div>
                                <?php else : ?>
                                    <div id="hq-not-connected-indicator"
                                         style="background-color: #dc3545; border: 2px solid #dc3545;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">NOT CONNECTED</h6>
                                    </div>
                                    <div id="hq-connected-indicator"
                                         style="background-color: #28a745; border: 2px solid #28a745;"
                                         class="hq-connected-sign">
                                        <h6 class="hq-connected-sign-text">CONNECTED</h6>
                                    </div>
                                    <style>
                                        #hq-connected-indicator {
                                            display: none;
                                        }
                                    </style>
                                <?php endif; ?>
                            </div>
                            <div class="hq-title-item-tables">
                                <h1 class="hq-admin-h1">Locations</h1>
                            </div>
                            <div>
                                <table class="hq-table wp-list-table widefat fixed striped table-view-list posts">
                                    <?php echo $this->renderHeaderAndOrFooter([
                                        'Id',
                                        'Feature Image',
                                        'Name',
                                        'Updated At'
                                    ]); ?>
                                    <tbody>
                                        <?php foreach ($vehicles as $vehicle) : ?>
                                            <tr>
                                                <th><?php echo $vehicle->getId(); ?></th>
                                                <th><img class="hq-thumbnail-on-tables"
                                                    src="<?php echo $vehicle->getFeatureImage(); ?>"
                                                    alt="<?php echo $vehicle->getLabelForWebsite(); ?>"></th>
                                                <th><?php echo $vehicle->getLabelForWebsite(); ?></th>
                                                <th><?php echo $vehicle->getUpdatedAt(); ?></th>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <?php echo $this->renderHeaderAndOrFooter([
                                        'Id',
                                        'Feature Image',
                                        'Name',
                                        'Updated At'
                                    ], true); ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
    }
    private function renderHeaderAndOrFooter($items, $footer = false)
    {
        if (is_array($items)) {
            $innerHtml = '';
            foreach ($items as $item) {
                $innerHtml .= "<th>{$item}</th>";
            }
            return ($footer) ?
            "<tfoot><tr>" . $innerHtml . "</tfoot></tr>" :
            "<thead><tr>" . $innerHtml . "</thead></tr>";
        }
        return '';
    }
}
