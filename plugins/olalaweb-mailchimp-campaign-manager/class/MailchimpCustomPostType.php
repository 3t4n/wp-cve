<?php

if (!class_exists('MailchimpCustomPostType')):

/**
 *
 */
  class MailchimpCustomPostType extends MailchimpCampaignsManager {

    /**
     * Holds the values to be used in the fields callbacks
     */
    public $post_type_name;

    /**
     * Start up
     */
    public function __construct() {
      parent::__construct();
      $this->post_type_name = isset($this->settings['cpt_name']) && !empty($this->settings['cpt_name']) ? $this->settings['cpt_name'] : MCC_DEFAULT_CPT;
      add_action('init', array($this, 'register_post_type'));
      // Customize admin list
      add_filter('manage_' . $this->post_type_name . '_posts_columns', array($this, 'add_admin_columns_head'));
      add_action('manage_' . $this->post_type_name . '_posts_custom_column', array($this, 'add_admin_columns_content'), 10, 2);
    }

    /**
     * Custom Post Type
     */
    public function register_post_type($cpt = false) {
      $cpt    = $cpt ? $cpt : $this->post_type_name;
      $labels = array(
        'name'                  => _x(ucfirst($cpt), 'Post type general name', MCC_TEXT_DOMAIN),
        'singular_name'         => _x($cpt, 'Post type singular name', MCC_TEXT_DOMAIN),
        'menu_name'             => _x(ucfirst($cpt), 'Admin Menu text', MCC_TEXT_DOMAIN),
        'name_admin_bar'        => _x(ucfirst($cpt), 'Add New on Toolbar', MCC_TEXT_DOMAIN),
        'add_new'               => __('Add New', MCC_TEXT_DOMAIN),
        'add_new_item'          => __('Add New ' . $cpt, MCC_TEXT_DOMAIN),
        'new_item'              => __('New ' . $cpt, MCC_TEXT_DOMAIN),
        'edit_item'             => __('Edit ' . $cpt, MCC_TEXT_DOMAIN),
        'view_item'             => __('View ' . $cpt, MCC_TEXT_DOMAIN),
        'all_items'             => __('All ' . $cpt, MCC_TEXT_DOMAIN),
        'search_items'          => __('Search ' . $cpt, MCC_TEXT_DOMAIN),
        'parent_item_colon'     => __('Parent ' . $cpt . ':', MCC_TEXT_DOMAIN),
        'not_found'             => __('No ' . $cpt . ' found.', MCC_TEXT_DOMAIN),
        'not_found_in_trash'    => __('No ' . $cpt . ' found in Trash.', MCC_TEXT_DOMAIN),
        'featured_image'        => _x(ucfirst($cpt) . ' cover image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', MCC_TEXT_DOMAIN),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', MCC_TEXT_DOMAIN),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', MCC_TEXT_DOMAIN),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', MCC_TEXT_DOMAIN),
        'archives'              => _x(ucfirst($cpt) . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', MCC_TEXT_DOMAIN),
        'insert_into_item'      => _x('Insert into ' . $cpt, 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', MCC_TEXT_DOMAIN),
        'uploaded_to_this_item' => _x('Uploaded to this ' . $cpt, 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', MCC_TEXT_DOMAIN),
        'filter_items_list'     => _x('Filter ' . $cpt . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', MCC_TEXT_DOMAIN),
        'items_list_navigation' => _x(ucfirst($cpt) . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', MCC_TEXT_DOMAIN),
        'items_list'            => _x(ucfirst($cpt) . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', MCC_TEXT_DOMAIN),
      );

      $args = array(
        'labels'          => $labels,
        'public'          => true,
        //'publicly_queryable' => true,
        'has_archive'     => true,
        'show_ui'         => true,
        'show_in_menu'    => true,
        'query_var'       => true,
        'rewrite'         => array('slug' => $cpt),
        'capability_type' => array($cpt, $cpt . 's'),
        'map_meta_cap'    => true,
        'hierarchical'    => false,
        'menu_icon'       => 'dashicons-email',
        'supports'        => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields'),
      );
      register_post_type($cpt, $args);

      $metas_fields = mailchimp_campaigns_manager_get_meta_fields();
      foreach ($metas_fields as $name => $description) {
        $args = array(
          // 'sanitize_callback' => 'sanitize_text_field',
          // 'auth_callback' => 'authorize_my_meta_key',
          // 'single' => true,
          'type'         => 'string',
          'description'  => $description,
          'show_in_rest' => true,
        );

        $meta_key = MCC_META_PRE . $name;
        register_meta($cpt, $meta_key, $args);
      }
    }

    /*
     * FIlter out admin columns
     */
    public function add_admin_columns_head($columns) {
      $c = $columns;
      return array(
        'cb'               => '<input type="checkbox" />',
        'title'            => __('Title'),
        'type'             => __('Type'),
        'status'           => __('Status'),
        'send_time'        => __('Sent on'),
        'emails_sent'      => __('Emails sent'),
        'long_archive_url' => __('Achive URL'),
        'shortcode'        => __('Shortcode'),
        'comments'         => __('Comments'),
      );
    }
    /**
     * Admin columns content
     */
    function add_admin_columns_content($column_name, $post_ID) {
      $metas  = get_post_meta($post_ID);
      $output = isset($metas[MCC_META_PRE . $column_name][0]) ? $metas[MCC_META_PRE . $column_name][0] : null;

      // Use the meta key to get posts by Mailchimp campaign ID.
      $remote_id_metakey = MCC_META_PRE . 'id';

      if ($column_name == 'shortcode') {
        if (isset($metas[$remote_id_metakey])) {
          $output = '<code>[campaign id="' . $metas[$remote_id_metakey][0] . '"]</code>';
        }
      }

      if ($column_name == 'long_archive_url') {
        if (isset($metas[MCC_META_PRE . $column_name])) {
          $url    = $metas[MCC_META_PRE . $column_name][0];
          $output = '<a href="' . $url . '" title="' . __('Open archive page', MCC_TEXT_DOMAIN) . '" target="_blank">';
          $output .= '<span class="dashicons dashicons-external"></span>' . __('Open', MCC_TEXT_DOMAIN);
          $output .= '</a>';
        }
      }

      if ($column_name == 'send_time') {
        $output = '<span class="dashicons dashicons-calendar-alt"></span>';
        $output .= date_i18n(get_option('date_format', 'l, F jS, Y'), strtotime($output));
      }

      if ($column_name == 'emails_sent') {
        $output = '<span class="dashicons dashicons-universal-access"></span>' . $output;
      }

      echo $output;
    }

  }
endif;
