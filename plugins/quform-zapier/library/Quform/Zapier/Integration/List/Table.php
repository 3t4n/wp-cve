<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Integration_List_Table extends WP_List_Table
{
    /**
     * @var Quform_Zapier_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Repository
     */
    protected $repository;

    /**
     * @var Quform_Options
     */
    protected $options;

    /**
     * @var string|null
     */
    protected $view;

    /**
     * @param  Quform_Zapier_Integration_Repository  $integrationRepository
     * @param  Quform_Repository                        $repository
     * @param  Quform_Options                           $options
     */
    public function __construct(
        Quform_Zapier_Integration_Repository $integrationRepository,
        Quform_Repository $repository,
        Quform_Options $options
    ) {
        parent::__construct(array(
            'singular' => 'qfb-zapier-integration',
            'plural' => 'qfb-zapier-integrations'
        ));

        $this->integrationRepository = $integrationRepository;
        $this->repository = $repository;
        $this->options = $options;
    }

    /**
     * Prepares the list of items for displaying
     */
    public function prepare_items()
    {
        $this->view = Quform::get($_GET, 'view');
        $perPage = $this->get_items_per_page('quform_zapier_integrations_per_page');

        $args = array(
            'active' => null,
            'orderby' => $this->getOrderBy(strtolower((string) Quform::get($_GET, 'orderby'))),
            'order' => $this->getOrder(strtolower((string) Quform::get($_GET, 'order'))),
            'trashed' => false,
            'limit' => $perPage,
            'offset' => ($this->get_pagenum() - 1) * $perPage,
            'search' => isset($_GET['s']) && Quform::isNonEmptyString($_GET['s']) ? wp_unslash($_GET['s']) : ''
        );

        switch ($this->view) {
            case 'active':
                $args['active'] = true;
                break;
            case 'inactive':
                $args['active'] = false;
                break;
            case 'trashed':
                $args['trashed'] = true;
                break;
        }

        $this->items = $this->integrationRepository->getIntegrations($args);

        $foundItems = $this->integrationRepository->getFoundRows();

        $this->set_pagination_args(array(
            'total_items' => $foundItems,
            'total_pages' => ceil($foundItems / $args['limit']),
            'per_page' => $args['limit']
        ));
    }

    /**
     * Display the list of views available on this table
     */
    public function views()
    {
        $views = $this->get_views();

        if (empty($views)) {
            return;
        }

        echo '<div class="qfb-sub-nav qfb-cf">';
        echo '<ul class="qfb-sub-nav-ul">';

        foreach ($views as $class => $view) {
            printf('<li class="qfb-view-%s">%s</li>', $class, $view);
        }

        echo '</ul>';
        echo '</div>';
    }

    /**
     * Get an associative array ( id => link ) with the list of views available on this table
     *
     * @return array
     */
    protected function get_views()
    {
        $isSearch = isset($_GET['s']) && Quform::isNonEmptyString($_GET['s']);
        $views = array();

        $views['all'] = sprintf(
            '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>',
            esc_url(admin_url('admin.php?page=quform.zapier')),
            $this->view === null && !$isSearch ? 'qfb-current' : '',
            esc_html__('All', 'quform-zapier'),
            number_format_i18n($this->integrationRepository->count())
        );

        $views['active'] = sprintf(
            '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>',
            esc_url(admin_url('admin.php?page=quform.zapier&view=active')),
            $this->view === 'active' && !$isSearch ? 'qfb-current' : '',
            esc_html__('Active', 'quform-zapier'),
            number_format_i18n($this->integrationRepository->count(true))
        );

        $views['inactive'] = sprintf(
            '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>',
            esc_url(admin_url('admin.php?page=quform.zapier&view=inactive')),
            $this->view === 'inactive' && !$isSearch ? 'qfb-current' : '',
            esc_html__('Inactive', 'quform-zapier'),
            number_format_i18n($this->integrationRepository->count(false))
        );

        $views['trash'] = sprintf(
            '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>',
            esc_url(admin_url('admin.php?page=quform.zapier&view=trashed')),
            $this->view === 'trashed' && !$isSearch ? 'qfb-current' : '',
            esc_html__('Trash', 'quform-zapier'),
            number_format_i18n($this->integrationRepository->count(null, true))
        );

        if ($isSearch) {
            $views['search'] = sprintf(
                '<a class="qfb-current">%s <span class="count">(%s)</span></a>',
                /* translators: %s: the search query */
                esc_html(sprintf(__('Search results for &#8220;%s&#8221;', 'quform-zapier'), wp_unslash($_GET['s']))),
                number_format_i18n($this->_pagination_args['total_items'])
            );
        }

        return $views;
    }

    /**
     * Get the list of columns
     *
     * @return array
     */
    public function get_columns()
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'name' => esc_html__('Name', 'quform-zapier'),
            'form_name' => esc_html__('Form', 'quform-zapier'),
            'active' => esc_html__('Active', 'quform-zapier'),
            'updated_at' => esc_html__('Last modified', 'quform-zapier')
        );
    }

    /**
     * Get the checkbox column content for the given item
     *
     * @param   array   $item
     * @return  string
     */
    protected function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="ids[]" value="%s" />', $item['id']);
    }

    /**
     * Get the name column content for the given item
     *
     * @param   array   $item
     * @return  string
     */
    protected function column_name($item)
    {
        $output = '<strong>';

        if (current_user_can('quform_zapier_edit_integrations') && $item['trashed'] != '1') {
            $output .= sprintf(
                '<a href="%s" aria-label="%s">%s</a>',
                esc_url(add_query_arg(array('id' => $item['id']), admin_url('admin.php?page=quform.zapier&sp=edit'))),
                /* translators: %s: the integration name */
                sprintf(esc_attr__('Edit integration &#8220;%s&#8221;', 'quform-zapier'), Quform::escape($item['name'])),
                Quform::escape($item['name'])
            );
        } else {
            $output .= Quform::escape($item['name']);
        }

        $output .= '</strong>';

        return $output;
    }

    /**
     * Generates and display row actions links for the list table
     *
     * @param   array   $item         The item being acted upon
     * @param   string  $column_name  Current column name
     * @param   string  $primary      Primary column name
     * @return  string                The row actions HTML, or an empty string if the current column is not the primary column
     */
    protected function handle_row_actions($item, $column_name, $primary)
    {
        if ($column_name != $primary) {
            return '';
        }

        $actions = array();

        if ($item['trashed'] == '0') {
            if (current_user_can('quform_zapier_edit_integrations')) {
                $actions['edit'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    esc_url(add_query_arg(array('id' => $item['id']), admin_url('admin.php?page=quform.zapier&sp=edit'))),
                    /* translators: %s: the integration name */
                    sprintf(esc_attr__('Edit integration &#8220;%s&#8221;', 'quform-zapier'), Quform::escape($item['name'])),
                    esc_html__('Edit', 'quform-zapier')
                );
            }

            if (current_user_can('quform_zapier_edit_integrations')) {
                if ($item['active'] == '1') {
                    $deactivateUrl = admin_url('admin.php?page=quform.zapier&action=deactivate');
                    $deactivateNonce = wp_create_nonce('quform_zapier_deactivate_integration_' . $item['id']);

                    $actions['deactivate'] = sprintf(
                        '<a href="%s" aria-label="%s">%s</a>',
                        esc_url(add_query_arg(array('id' => $item['id'], '_wpnonce' => $deactivateNonce), $deactivateUrl)),
                        /* translators: %s: the integration name */
                        sprintf(esc_attr__('Deactivate integration &#8220;%s&#8221;', 'quform-zapier'), Quform::escape($item['name'])),
                        esc_html__('Deactivate', 'quform-zapier')
                    );
                } else {
                    $activateUrl = admin_url('admin.php?page=quform.zapier&action=activate');
                    $activateNonce = wp_create_nonce('quform_zapier_activate_integration_' . $item['id']);

                    $actions['activate'] = sprintf(
                        '<a href="%s" aria-label="%s">%s</a>',
                        esc_url(add_query_arg(array('id' => $item['id'], '_wpnonce' => $activateNonce), $activateUrl)),
                        /* translators: %s: the integration name */
                        sprintf(esc_attr__('Activate integration &#8220;%s&#8221;', 'quform-zapier'), Quform::escape($item['name'])),
                        esc_html__('Activate', 'quform-zapier')
                    );
                }
            }

            if (current_user_can('quform_zapier_add_integrations')) {
                $duplicateUrl = admin_url('admin.php?page=quform.zapier&action=duplicate');
                $duplicateNonce = wp_create_nonce('quform_zapier_duplicate_integration_' . $item['id']);

                $actions['duplicate'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    esc_url(add_query_arg(array('id' => $item['id'], '_wpnonce' => $duplicateNonce), $duplicateUrl)),
                    /* translators: %s: the integration name */
                    sprintf(esc_attr__('Duplicate integration &#8220;%s&#8221;', 'quform-zapier'), Quform::escape($item['name'])),
                    esc_html__('Duplicate', 'quform-zapier')
                );
            }

            if (current_user_can('quform_zapier_delete_integrations')) {
                $trashUrl = admin_url('admin.php?page=quform.zapier&action=trash');
                $trashNonce = wp_create_nonce('quform_zapier_trash_integration_' . $item['id']);

                $actions['trash'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    esc_url(add_query_arg(array('id' => $item['id'], '_wpnonce' => $trashNonce), $trashUrl)),
                    /* translators: %s: the integration name */
                    sprintf(esc_attr__('Move integration &#8220;%s&#8221; to the Trash', 'quform-zapier'), Quform::escape($item['name'])),
                    esc_html__('Trash', 'quform-zapier')
                );
            }
        } else {
            if (current_user_can('quform_zapier_delete_integrations')) {
                $untrashUrl = admin_url('admin.php?page=quform.zapier&action=untrash');
                $untrashNonce = wp_create_nonce('quform_zapier_untrash_integration_' . $item['id']);

                $actions['untrash'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    esc_url(add_query_arg(array('id' => $item['id'], '_wpnonce' => $untrashNonce), $untrashUrl)),
                    /* translators: %s: the integration name */
                    sprintf(esc_attr__('Restore integration &#8220;%s&#8221; from the Trash', 'quform-zapier'), Quform::escape($item['name'])),
                    esc_html__('Restore', 'quform-zapier')
                );

                $deleteUrl = admin_url('admin.php?page=quform.zapier&action=delete');
                $deleteNonce = wp_create_nonce('quform_zapier_delete_integration_' . $item['id']);

                $actions['delete'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    esc_url(add_query_arg(array('id' => $item['id'], '_wpnonce' => $deleteNonce), $deleteUrl)),
                    /* translators: %s: the integration name */
                    sprintf(esc_attr__('Delete integration &#8220;%s&#8221; permanently', 'quform-zapier'), Quform::escape($item['name'])),
                    esc_html__('Delete permanently', 'quform-zapier')
                );
            }
        }

        return $this->row_actions($actions);
    }

    /**
     * Get the list name column content for the given item
     *
     * @param   array   $item
     * @return  string
     */
    protected function column_form_name($item)
    {
        return Quform::isNonEmptyString($item['form_name']) ? Quform::escape($item['form_name']) : esc_html__('(not configured)', 'quform-zapier');
    }

    /**
     * Get the active column content for the given item
     *
     * @param   array   $item
     * @return  string
     */
    protected function column_active($item)
    {
        return $item['active'] == '1' ? esc_html__('Yes', 'quform-zapier') : esc_html__('No', 'quform-zapier');
    }

    /**
     * Get the updated_at column content for the given item
     *
     * @param   array   $item
     * @return  string
     */
    protected function column_updated_at($item)
    {
        return esc_html($this->options->formatDate($item['updated_at'], true));
    }

    /**
     * Get the list of sortable columns
     *
     * @return array
     */
    protected function get_sortable_columns()
    {
        $orderBy = $this->getOrderBy();
        $isAsc = $this->getOrder() == 'asc';

        return array(
            'name' => array('name', $orderBy == 'name' && $isAsc),
            'form_name' => array('form_name', $orderBy == 'form_name' && $isAsc),
            'active' => array('active', $orderBy == 'active' && $isAsc),
            'updated_at' => array('updated_at', ! ($orderBy == 'updated_at' && ! $isAsc)) // Default desc
        );
    }

    /**
     * Get an associative array ( option_name => option_title ) with the list
     * of bulk actions available on this table
     *
     * @return array
     */
    protected function get_bulk_actions()
    {
        $actions = array();

        if ($this->view == 'trashed') {
            if (current_user_can('quform_zapier_delete_integrations')) {
                $actions['untrash'] = __('Restore', 'quform-zapier');
                $actions['delete'] = __('Delete permanently', 'quform-zapier');
            }
        } else {
            if (current_user_can('quform_zapier_edit_integrations')) {
                $actions['activate'] = __('Activate', 'quform-zapier');
                $actions['deactivate'] = __('Deactivate', 'quform-zapier');
            }

            if (current_user_can('quform_zapier_add_integrations')) {
                $actions['duplicate'] = __('Duplicate', 'quform-zapier');
            }

            if (current_user_can('quform_zapier_delete_integrations')) {
                $actions['trash'] = __('Move to Trash', 'quform-zapier');
            }
        }

        return $actions;
    }

    /**
     * Message to be displayed when there are no integrations
     */
    public function no_items() {
        if (isset($_GET['s']) && Quform::isNonEmptyString($_GET['s'])) {
            esc_html_e('Your search did not match any integrations.', 'quform-zapier');
        } else {
            if (current_user_can('quform_zapier_add_integrations')) {
                printf(
                    /* translators: %1$s: open link tag, %2$s: close link tag */
                    esc_html__('No integrations found, %1$sclick here%2$s to create one.', 'quform-zapier'),
                    sprintf('<a href="%s">', esc_url(admin_url('admin.php?page=quform.zapier&sp=add'))),
                    '</a>'
                );
            } else {
                esc_html_e('No integrations found.', 'quform-zapier');
            }
        }
    }

    /**
     * Displays the search box
     *
     * Duplicate of the parent function, but still shows the search box if there are no items
     *
     * @param string $text     The 'submit' button label.
     * @param string $input_id ID attribute value for the search input field.
     */
    public function search_box( $text, $input_id ) {
        $input_id = $input_id . '-search-input';

        if ( ! empty( $_REQUEST['orderby'] ) )
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
        if ( ! empty( $_REQUEST['order'] ) )
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
        if ( ! empty( $_REQUEST['post_mime_type'] ) )
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
        if ( ! empty( $_REQUEST['detached'] ) )
            echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <?php
    }

    /**
     * Get the order by value
     *
     * Gets the user meta setting if a value is saved
     *
     * @param   string  $requestedOrderBy  The requested order by from $_GET
     * @return  string
     */
    protected function getOrderBy($requestedOrderBy = '')
    {
        $currentUserId = get_current_user_id();
        $userOrderBy = get_user_meta($currentUserId, 'quform_zapier_integrations_order_by', true);

        if (Quform::isNonEmptyString($requestedOrderBy)) {
            $orderBy = $requestedOrderBy;

            if ($requestedOrderBy != $userOrderBy) {
                update_user_meta($currentUserId, 'quform_zapier_integrations_order_by', $requestedOrderBy);
            }
        } elseif (Quform::isNonEmptyString($userOrderBy)) {
            $orderBy = $userOrderBy;
        } else {
            $orderBy = 'updated_at';
        }

        return $orderBy;
    }

    /**
     * Get the order value ('asc' or 'desc')
     *
     * Gets the user meta setting if a value is saved
     *
     * @param   string  $requestedOrder  The requested order from $_GET
     * @return  string
     */
    protected function getOrder($requestedOrder = '')
    {
        $currentUserId = get_current_user_id();
        $userOrder = get_user_meta($currentUserId, 'quform_zapier_integrations_order', true);

        if (Quform::isNonEmptyString($requestedOrder)) {
            $order = $requestedOrder;

            if ($requestedOrder != $userOrder) {
                update_user_meta($currentUserId, 'quform_zapier_integrations_order', $requestedOrder);
            }
        } elseif (Quform::isNonEmptyString($userOrder)) {
            $order = $userOrder;
        } else {
            $order = 'desc';
        }

        return $order;
    }
}
