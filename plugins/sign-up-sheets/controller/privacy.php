<?php
/**
 * Privacy Class
 */

namespace FDSUS\Controller;

use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\SignupCollection;

class Privacy
{
    /** @var array list of meta_key/labels for personal data signups */
    protected $fields;

    public function __construct()
    {
        $this->fields = array(
            'dlssus_email'     => __('Email', 'fdsus'),
            'dlssus_firstname' => __('First Name', 'fdsus'),
            'dlssus_lastname'  => __('Last Name', 'fdsus'),
            'dlssus_phone'     => __('Phone', 'fdsus'),
            'dlssus_address'   => __('Address', 'fdsus'),
            'dlssus_city'      => __('City', 'fdsus'),
            'dlssus_state'     => __('State', 'fdsus'),
            'dlssus_zip'       => __('Zip', 'fdsus'),
        );

        add_filter('wp_privacy_personal_data_exporters', array(&$this, 'registerUserDataExporters'));
        add_filter('wp_privacy_personal_data_erasers', array(&$this, 'registerUserDataErasers'));
    }

    /**
     * Registers all data exporters
     *
     * @param array $exporters
     *
     * @return mixed
     * @since 2.2.3
     */
    public function registerUserDataExporters($exporters)
    {
        $exporters['sign-up-sheets'] = array(
            'exporter_friendly_name' => __('Sign-up Sheets Plugin', 'fdsus'),
            'callback'               => array(&$this, 'exportUserDataByEmail'),
        );
        return $exporters;
    }

    /**
     * Registers all data erasers
     *
     * @param array $erasers
     *
     * @return mixed
     */
    public function registerUserDataErasers($erasers)
    {
        $erasers['sign-up-sheets'] = array(
            'eraser_friendly_name' => __('Sign-up Sheets Plugin', 'fdsus'),
            'callback'             => array(&$this, 'removeUserDataByEmail'),
        );
        return $erasers;
    }

    /**
     * Export user meta for a user using the supplied email
     *
     * @param string $email email address to manipulate
     * @param int    $page  pagination
     *
     * @return array
     * @since 2.2.3
     */
    public function exportUserDataByEmail($email, $page = 1)
    {
        $perPage = 20; // Limit us to avoid timing out
        $page = (int)$page;
        $exportItems = array();

        $fields = array('ID' => __('Sign-up ID', 'fdsus')) + $this->fields;

        $signupCollection = new SignupCollection();
        $signups = $signupCollection->getByEmail(
            $email,
            array(
                'posts_per_page' => $perPage,
                'paged' => $page
            )
        );

        foreach ($signups as $signup) {
            $data = array();
            $itemId = SignupModel::POST_TYPE . '-' . $signup->ID;
            $groupId = 'fdsus-signups';
            $groupLabel = __('Sign-ups', 'fdsus');

            foreach ($fields as $key => $name) {
                if (!empty($signup->{$key})) {
                    $data[] = array(
                        'name'  => $name,
                        'value' => $signup->{$key}
                    );
                }
            }

            /**
             * Filter for user data export
             *
             * @param array       $data
             * @param SignupModel $signup
             *
             * @return array
             * @since 2.2.3
             */
            $data = apply_filters('fdsus_privacy_export_data', $data, $signup);

            $exportItems[] = array(
                'group_id'    => $groupId,
                'group_label' => $groupLabel,
                'item_id'     => $itemId,
                'data'        => $data,
            );
        }

        return array(
            'data' => $exportItems,
            'done' => $signupCollection->getMaxNumPages() <= $page || empty($signups)
        );
    }

    /**
     * Erase meta for a user using the supplied email
     *
     * @param string $email email address to manipulate
     * @param int    $page  pagination
     *
     * @return array
     * @since 2.2.3
     */
    public function removeUserDataByEmail($email, $page = 1)
    {
        $perPage = 20; // Limit us to avoid timing out
        $page = 1; // Set to 1 since signups are being fully removed
        $itemsRemoved = false;
        $failedSignups = array();

        $signupCollection = new SignupCollection();
        $signups = $signupCollection->getByEmail(
            $email,
            array(
                'posts_per_page' => $perPage,
                'paged'          => $page
            )
        );

        foreach ($signups as $signup) {
            $id = $signup->ID;
            $result = $signup->delete();
            if ($result) {
                $itemsRemoved = true;
            } else {
                $failedSignups[$id] = $signup;
            }
        }

        return array(
            'items_removed'  => $itemsRemoved,
            'items_retained' => !empty($failedSignups),
            'messages'       => !empty($failedSignups)
                ? array('IDs failed: ' . implode(', ', array_keys($failedSignups))) : array(),
            'done'           => $signupCollection->getMaxNumPages() <= $page || empty($signups),
        );
    }
}
