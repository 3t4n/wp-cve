<?php namespace Premmerce\WoocommerceMulticurrency\Legacy;

/**
 * This class is needed to fix consequences of old bug, currently fixed. This class can be removed after couple new versions will be released.
 *
 * That bug made some meta boxes on product edit page to disappear.
 *
 * NOTE: the simplest way to return a missed meta box is to move any another meta box, just drag-n-drop it to a new place.
 * Then refresh the page and you'll see the missed meta box.
 *
 * @since 2.2
 *
 * @todo: think about using this class in free version. Looks like it needed if user had premium version and then switched to free.
 */
class Legacy
{
    const META_BOXES_FIXED_OPTION_NAME = 'premmerce_multicurrency_user_meta_boxes_fixed';

    /**
     * Legacy constructor.
     */
    public function __construct()
    {
        add_action('wp', array($this, 'fixUsersMetaBoxSorting'));
    }

    /**
     * Move all users meta boxes from custom 'after_editor' context to native 'normal' context.
     */
    public function fixUsersMetaBoxSorting()
    {
        if (! get_option(self::META_BOXES_FIXED_OPTION_NAME)) {
            foreach ($this->getUserIdsToUpdateMetaBoxes() as $userId) {
                $this->processUserMetaBoxSortingOptions($userId);
            }

            update_option(self::META_BOXES_FIXED_OPTION_NAME, true);
        }
    }

    /**
     * Get id's of users with probably broken meta boxes
     *
     * @return array
     */
    private function getUserIdsToUpdateMetaBoxes()
    {
        return get_users(array(
            'fields'    => 'ID',
            'role__in'  => $this->getRolesWithEditProductCap()
        ));
    }

    /**
     * Move all meta boxes for given user from 'after_editor' context to 'normal' context
     *
     * @param string $userId
     */
    private function processUserMetaBoxSortingOptions($userId)
    {
        $userSortingOptions = get_user_option('meta-box-order_product', $userId);
        if ($userSortingOptions && isset($userSortingOptions['after_editor'])) {
            $userSortingOptions['normal'] = $userSortingOptions['after_editor'] . ',' . $userSortingOptions['normal'];
            unset($userSortingOptions['after_editor']);
            update_user_option($userId, 'meta-box-order_product', $userSortingOptions, true);
        }
    }

    /**
     * Get list of user roles with edit_product capability
     *
     * @return array
     */
    private function getRolesWithEditProductCap()
    {
        $roles = array_filter(wp_roles()->role_objects, function (\WP_Role $role) {
            return $role->has_cap('edit_product');
        });

        return wp_list_pluck($roles, 'name');
    }
}
