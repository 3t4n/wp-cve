<?php

namespace FluentSupport\App\Services;

use FluentSupport\App\Models\Customer;
use FluentSupport\App\Models\Product;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\EmailNotification\Settings;

class ProfileInfoService
{
    public static function getProfileExtraWidgets( $customer )
    {
        $widgets = [];
        /*
         * Filter customer profile widgets
         *
         * @since v1.0.0
         * @param array $widgets
         * @param object|array  $customer
         *
         * @return void
         */
        $widgets = apply_filters('fluent_support/customer_extra_widgets', $widgets, $customer);
        return $widgets;
    }

    // This method is linked with 'profile_update' action & it will trigger when user update profile
    public function onWPProfileUpdate( $userId, $userOldData, $userUpdatedData )
    {
        if ( !$userId ) {
            return false;
        }

        $customer = Customer::where( 'user_id', $userId );

        if ( $customer->count() == 0 ) {
            return false;
        }

        $keys = ['first_name', 'last_name', 'user_email'];
        $data = [];

        if( !array_diff_key( array_flip($keys), $userUpdatedData ) ) {
            $data = [
                'first_name' => $userUpdatedData['first_name'],
                'last_name' => $userUpdatedData['last_name'],
                'email' => $userUpdatedData['user_email'],
            ];
        };

        if ( count($data) > 0 ) {
            $customer->update($data);
        }

    }


    /**
     * This `me` method will return the current user profile info
     * @param array $settings
     * @param arrau withPortalSettings
     * @return array $settings
     */
    public function me( $settings, $withPortalSettings )
    {
        if ( $withPortalSettings ) {
            $this->portalSettings(  $settings );
        }

        return $settings;
    }

    /**
     * This `portalSettings` method is supporting the `me` method
     * @param array $settings
     * @return array $settings
     */
    private function portalSettings ( $settings )
    {
        $mimeHeadings = Helper::getAcceptedMimeHeadings();
        $businessSettings = (new Settings())->globalBusinessSettings();
        $maxFileSize = absint($businessSettings['max_file_size']);

        $portalSettings = [
            'support_products'           => Product::select(['id', 'title'])->get(),
            'customer_ticket_priorities' => Helper::customerTicketPriorities(),
            'has_file_upload'            => !!Helper::ticketAcceptedFileMiles(),
            'has_rich_text_editor'       => true,
            'max_file_size' => $maxFileSize,
            'mime_headings' => $mimeHeadings
        ];
        /**
         * Filter customer portal settings
         *
         * @since v1.0.0
         * @param array $portalSettings
         */
        $portalSettings = apply_filters('fluent_support/customer_portal_vars', $portalSettings);

        $settings['portal_settings'] = $portalSettings;

        return $settings;
    }
}
