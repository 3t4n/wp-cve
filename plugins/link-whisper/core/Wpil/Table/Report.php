<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Wpil_Table_Report extends WP_List_Table
{

    function __construct()
    {
        parent::__construct(array(
            'singular' => __('Linking Stats', 'wpil'),
            'plural' => __('Linking Stats', 'wpil'),
            'ajax' => false
        ));

        $this->prepare_items();
    }

    function column_default($item, $column_name)
    {
        if ($column_name == 'post_type') {
            return $item['post']->getType();
        }

        if (!array_key_exists($column_name, $item)) {
            return "<i>(not set)</i>";
        }

        $v = $item[$column_name];
        if (!$v) {
            $v = 0;
        }

        $v_num = $v;

        $post_id = $item['post']->id;
        $post_type = $item['post']->type;
        if (in_array($column_name, Wpil_Report::$meta_keys)) {
            $opts = [];
            $opts['target'] = '_blank';
            $opts['style'] = 'text-decoration: underline';

            $opts['data-wpil-report-post-id'] = $post_id;
            $opts['data-wpil-report-type'] = $column_name;
            $opts['data-wpil-report'] = 1;

            $v = "<span class='wpil_ul'>$v</span>";

            switch ($column_name) {
                case WPIL_LINKS_INBOUND_INTERNAL_COUNT:
                    $v = "<div class='inbound-link-count'>&#x2799;";
                    $links_data = $item['post']->getInboundInternalLinks();
                    $title = __('Inbound Internal Links', 'wpil');
                    break;

                case WPIL_LINKS_OUTBOUND_EXTERNAL_COUNT:
                    $v = "&#x279A;";
                    $links_data = $item['post']->getOutboundExternalLinks();
                    $title = __('Outbound External Links', 'wpil');
                    break;

                case WPIL_LINKS_OUTBOUND_INTERNAL_COUNT:
                    $v = "<div class='outbound-internal-link-count'>&#x2799;";
                    $links_data = $item['post']->getOutboundInternalLinks();
                    $title = __('Outbound Internal Links', 'wpil');
                    break;
            }


            if ($v_num > 0 || WPIL_LINKS_INBOUND_INTERNAL_COUNT == $column_name || WPIL_LINKS_OUTBOUND_INTERNAL_COUNT == $column_name) {

            } else {
                $v = "<div title='" . esc_attr($title) . "' style='margin:0px; text-align: center; padding: 5px'>0 $v</div>";
            }

            if ($v_num > 0 || WPIL_LINKS_INBOUND_INTERNAL_COUNT == $column_name || WPIL_LINKS_OUTBOUND_INTERNAL_COUNT == $column_name) {
                $rep = '';

                if (is_array($links_data)) {
                    $rep .= '<ul class="report_links">';

                    switch ($column_name) {
                        case 'wpil_links_inbound_internal_count':
                            $count = 0;
                            foreach ($links_data as $link) {
                                    $count++;
                                    if($count > 100){
                                        continue;
                                    }
                                    if (!empty($link->post)) {
                                        $rep .= '<li>
                                                    <div>
                                                        <div style="margin: 3px 0;"><b>Origin Post Title:</b> ' . esc_html($link->post->getTitle()) . '</div>
                                                        <div style="margin: 3px 0;"><b>Anchor Text:</b> ' . esc_html(strip_tags($link->anchor)) . '</div>';
                                        $rep .=         Wpil_Report::get_dropdown_icons($link->post, $link->url, 'inbound-internal');
                                        $rep .=         '<a href="' . admin_url('post.php?post=' . $link->post->id . '&action=edit') . '" target="_blank">[edit]</a> 
                                                        <a href="' . esc_url($link->post->getLinks()->view) . '" target="_blank">[view]</a>
                                                        <br>
                                                    </div>
                                                </li>';
                                    } else {
                                        $rep .= '<li><div style="margin: 3px 0;"><b>Anchor Text:</b> ' . esc_html(strip_tags($link->anchor)) . '</div></li>';
                                    }
                            }
                            $v .= '<span class="wpil_ul">' . $count . '</span></div>';
                            break;
                        case 'wpil_links_outbound_internal_count':
                            $count = 0;
                            foreach ($links_data as $link) {
                                $count++;
                                if($count > 100){
                                    continue;
                                }
                                $rep .= '<li>
                                            <div>
                                                <div style="margin: 3px 0;"><b>Link:</b> <a href="' . esc_url($link->url) . '" target="_blank" style="text-decoration: underline">' . esc_html($link->url) . '</a></div>
                                                <div style="margin: 3px 0;"><b>Anchor Text:</b> ' . esc_html(strip_tags($link->anchor)) . '</div>';
                                $rep .=         Wpil_Report::get_dropdown_icons($item['post'], $link->url, 'outbound-internal');
                                $rep .=     '</div>
                                        </li>';
                            }
                            $edit_link = '<a class="add-outbound-internal-links" href="javascript:void(window.open(\''. esc_url($item['post']->getLinks()->edit) .'\'))" style="text-decoration: underline;">Add</a>';
                            $v .= '<span class="wpil_ul">' . $count . '</span></div>' . $edit_link;
                            break;
                        case 'wpil_links_outbound_external_count':
                            $count = 0;
                            foreach ($links_data as $link) {
                                    $count++;
                                    if($count > 100){
                                        continue;
                                    }
                                    $rep .= '<li>
                                                <div>
                                                    <div style="margin: 3px 0;"><b>Link:</b> <a href="' . esc_url($link->url) . '" target="_blank" style="text-decoration: underline">' . esc_html($link->url) . '</a></div>
                                                    <div style="margin: 3px 0;"><b>Anchor Text:</b> ' . esc_html(strip_tags($link->anchor)) . '</div>';
                                    $rep .=         Wpil_Report::get_dropdown_icons($item['post'], $link->url, 'outbound-external');
                                    $rep .=     '</div>
                                            </li>';
                            }
                            $v = '<span class="wpil_ul">' . $count . '</span> ' . $v;
                            break;
                    }

                    $rep .= '</ul>';
                }

                $e_rt = esc_attr($column_name);
                $e_p_id = esc_attr($post_id);

                $atts = Wpil_Toolbox::output_dropdown_wrapper_atts(array('report_type' => 'links', 'post_id' => $e_p_id, 'post_type' => $post_type, 'nonce' => wp_create_nonce(wp_get_current_user()->ID . 'wpil-collapsible-nonce')));
                $v = "<div class='wpil-collapsible-wrapper' {$atts}>
  			            <div class='wpil-collapsible wpil-collapsible-static wpil-links-count' title='" . esc_attr($title) . "' data-wpil-report-type='$e_rt' data-wpil-report-post-id='$e_p_id'>$v</div>
  				        <div class='wpil-content'>
          			        $rep
  				        </div>
  				    </div>";
            }

        }

        return $v;
    }

    function get_columns()
    {
        $columns = ['post_title' => __('Title', 'wpil')];
        $options = get_user_meta(get_current_user_id(), 'report_options', true);

        if (!empty($options['show_date']) && $options['show_date'] == 'on') {
            $columns['date'] = __('Published', 'wpil');
        }

        if (!empty($options['show_type']) && $options['show_type'] == 'on') {
            $columns['post_type'] = __('Type', 'wpil');
        }

        $inbound = '<div class="wpil-report-header-container">' . 
                        __('Inbound Internal Links', 'wpil') . 
                        '<div class="wpil-report-header-tooltip">
                            <div class="wpil_help">
                                <i class="dashicons dashicons-editor-help"></i>
                                <div class="wpil-help-text" style="display: none;">' . sprintf(__('Inbound Internal Links are links on %s on this site that are pointing to %s.', 'wpil'), '<span style="font-style: italic;float: none;">' . __('other pages', 'wpil') . '</span>', '<span style="text-decoration: underline;float: none;">' . __('this page', 'wpil') . '</span>') . '</div>
                            </div>
                        </div>
                    </div>';

        $outbound = '<div class="wpil-report-header-container">' . 
                        __('Outbound Internal Links', 'wpil') . 
                        '<div class="wpil-report-header-tooltip">
                            <div class="wpil_help">
                                <i class="dashicons dashicons-editor-help"></i>
                                <div class="wpil-help-text" style="display: none;">' . sprintf(__('Outbound Internal Links are links that are on %s and are pointing to %s on this site.', 'wpil'), '<span style="font-style: italic;float: none;">' . __('this page', 'wpil') . '</span>',  '<span style="text-decoration: underline;float: none;">' . __('other pages', 'wpil') . '</span>') . '</div>
                            </div>
                        </div>
                    </div>';

        $external = '<div class="wpil-report-header-container">' . 
                        __('Outbound External Links', 'wpil') . 
                        '<div class="wpil-report-header-tooltip">
                            <div class="wpil_help">
                                <i class="dashicons dashicons-editor-help"></i>
                                <div class="wpil-help-text" style="display: none;">' . sprintf(__('Outbound External Links are links that are on %s and are pointing to pages on %s.', 'wpil'),  '<span style="font-style: italic;float: none;">' . __('this page', 'wpil') . '</span>', '<span style="text-decoration: underline;float: none;">' . __('other sites', 'wpil') . '</span>') . '</div>
                            </div>
                        </div>
                    </div>';

        $columns = array_merge($columns, [
            WPIL_LINKS_INBOUND_INTERNAL_COUNT => $inbound,
            WPIL_LINKS_OUTBOUND_INTERNAL_COUNT => $outbound,
            WPIL_LINKS_OUTBOUND_EXTERNAL_COUNT => $external,
        ]);

        return $columns;
    }

    function column_post_title($item)
    {
        $post = $item['post'];

        $actions = [];

        $title = '<div class="wpil-report-row-title-container"><a href="' . esc_url($post->getLinks()->edit) . '" class="row-title">' . esc_attr($post->getTitle()) . '</a><div class="wpil-row-title-icon-container">' . $this->get_title_icons($post) . '</div></div>';
        $actions['view'] = '<a target="_blank" href="' . esc_url($post->getLinks()->view) . '">View</a>';
        $actions['edit'] = '<a target="_blank" href="' . esc_url($post->getLinks()->edit) . '">Edit / Add outbound links</a>';
        $actions['export'] = '<a target="_blank" href="' . esc_url($post->getLinks()->export) . '">Export data for support</a>';
        $actions['excel_export'] = '<a target="_blank" href="' . esc_url($post->getLinks()->excel_export) . '">Export Post Data to Excel</a>';
        $actions['refresh'] = '<a href="' . esc_url($post->getLinks()->refresh) . '">Refresh links count</a>';

        if($post->type === 'post' && !empty(EMPTY_TRASH_DAYS)){
            $name = get_post_type_labels(get_post_type_object(get_post_type($post->id)));
            $name = (!empty($name) && isset($name->singular_name)) ? $name->singular_name: 'Post';
            $actions['trash'] = '<a href="' . esc_url(get_delete_post_link($post->id)) . '" class="wpil-trash-post-link">' . sprintf(__('Trash %s', 'wpil'), $name) . '</a>';
        }

        if(isset($_GET['orphaned'])){
            $actions['ignore-orphaned'] = '<a href="#" class="wpil-ignore-orphaned-post" data-post-id="' . $post->id . '" data-type="' . $post->type . '" data-nonce="'. wp_create_nonce('ignore-orphaned-post-' . $post->id) .'">Ignore orphaned post</a>';
        }

        return $title . $this->row_actions($actions);
    }

    /**
     * Gets the icons that we'll be appending to the post titles in the report for quick information
     **/
    function get_title_icons($post){
        $icons = '';

        if($post->type === 'post'){
            $redirected_post_url = Wpil_Link::get_url_redirection($post->getViewLink());

            // if the current post has had it's URL redirected
            if(!empty($redirected_post_url)){
                // check if the redirect is pointing to a different post
                $new_post = Wpil_Post::getPostByLink($redirected_post_url);
                // if it is, or the redirect is pointing to the home url
                if(!empty($new_post) && $post->id !== $new_post->id || Wpil_Link::url_points_home($redirected_post_url)){
                    $icons .= '<div class="wpil_help">';
                    $icons .= '<i class="dashicons dashicons-hidden"></i>';
                    $icons .= '<div class="wpil-help-text" style="display: none; top: 6px; left: -81px;">' . __('Hidden by redirect', 'wpil') . '</div>';
                    $icons .= '</div>';
                }
            }
        }

        if($post->type === 'post'){
            $is_pillar = false;
            if(class_exists('WPSEO_Meta') && method_exists('WPSEO_Meta', 'get_value')){
                $is_pillar = (WPSEO_Meta::get_value('is_cornerstone', $post->id) === '1');
            }

            if(empty($is_pillar) && defined('RANK_MATH_VERSION')){
                $is_pillar = Wpil_Toolbox::check_pillar_content_status($post->id);
            }

            if(!empty($is_pillar)){
                $icons .= '<div class="wpil_help">';
                $icons .= '<i class="dashicons dashicons-media-text"></i>';
                $icons .= '<div class="wpil-help-text" style="display: none;">' . __('Pillar Content', 'wpil') . '</div>';
                $icons .= '</div>';
            }
        }

        return $icons;
    }

    function get_sortable_columns()
    {
        $cols = $this->get_columns();

        $sortable_columns = [];

        foreach ($cols as $col_k => $col_name) {
            $sortable_columns[$col_k] = [$col_k, false];
        }

        return $sortable_columns;
    }

    function prepare_items()
    {
        define('WPIL_LOADING_REPORT', true);
        $options = get_user_meta(get_current_user_id(), 'report_options', true);
        $per_page = !empty($options['per_page']) ? $options['per_page'] : 20;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $start = isset($_REQUEST['paged']) ? (int)$_REQUEST['paged'] : 0;
        $orderby = (isset($_REQUEST['orderby']) && !empty($_REQUEST['orderby'])) ? sanitize_text_field($_REQUEST['orderby']) : '';
        $order = (!empty($_REQUEST['order'])) ? sanitize_text_field($_REQUEST['order']) : 'DESC';
        $search = (!empty($_REQUEST['s'])) ? sanitize_text_field($_REQUEST['s']) : '';
        $orphaned = !empty($_REQUEST['orphaned']);

        if (empty($orderby)) {
            $saved_order = get_transient('wpil_link_report_order');
            if (!empty($saved_order)) {
                $saved_order = explode(';', $saved_order);
                if (count($saved_order) == 2) {
                    $orderby = !empty($saved_order[0]) ? $saved_order[0] : '';
                    $order = !empty($saved_order[1]) ? $saved_order[1] : 'DESC';
                }
            }
        }

        if (!empty($orderby)) {
            set_transient('wpil_link_report_order', $orderby . ';' . $order);
        }

        $data = Wpil_Report::getData($start, $orderby, $order, $search, $per_page, $orphaned);

        $total_items = $data['total_items'];
        $data = $data['data'];

        $this->items = $data;

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

    /**
     * Displays the search box.
     *
     * @param string $text     The 'submit' button label.
     * @param string $input_id ID attribute value for the search input field.
     */
    public function search_box( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
            return;
        }

        $input_id = $input_id . '-search-input';

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['order'] ) ) {
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['detached'] ) ) {
            echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
        }
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" placeholder="Keyword or URL" />
            <?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <?php
    }
}
