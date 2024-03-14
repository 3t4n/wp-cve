<?php

if (!class_exists('MailchimpCampaignMetabox')):
/**
 * Register a meta box using a class.
 */
  class MailchimpCampaignMetabox {

    // Settings
    public $settings;
    public $post;
    public $post_type;
    public $post_metas;

    /**
     * Constructor.
     */
    public function __construct() {
      $this->settings  = get_option('mailchimp_campaigns_manager_settings', []);
      $this->post_type = mailchimp_campaigns_manager_get_post_type();
      $this->init_metabox();
    }

    /**
     * Meta box initialization.
     */
    public function init_metabox() {
      add_action('add_meta_boxes', array($this, 'add_metabox'));
      add_action('save_post', array($this, 'save_metabox'), 10, 2);

    }

    /**
     * Adds the meta box.
     */
    public function add_metabox() {
      add_meta_box(
        'mailchimp_campaigns_manager-stats',
        __('Campaign statistics', MCC_TEXT_DOMAIN),
        array($this, 'render_metabox'),
        $this->post_type,
        'side',
        'high',
        'stats'
      );
      add_meta_box(
        'mailchimp_campaigns_manager-list',
        __('Campaign list', MCC_TEXT_DOMAIN),
        array($this, 'render_metabox'),
        $this->post_type,
        'side',
        'high',
        'list'
      );

      if (isset($this->settings['show_preview']) && $this->settings['show_preview'] === true) {
        add_meta_box(
          'mailchimp_campaigns_manager-preview',
          __('Campaign preview', MCC_TEXT_DOMAIN),
          array($this, 'render_metabox'),
          $this->post_type,
          'normal',
          'high',
          'preview'
        );
      }

    }

    /**
     * Renders the meta box.
     */
    public function render_metabox($post, $box) {
      // Save the $post object now
      // because was not available anytime before
      if (!$this->post) {
        $this->post = new MailchimpPost($post);
      }

      // Switch over metaboxes ID
      $output = '';
      switch ($box['args']) {
      default:
        break;
      case 'preview':
        // Experimental
        // $output = get_post_embed_html( '600', '800', $this->post);
        $output = $this->post->get_meta('content_html', true);
        break;
      case 'stats':
        if ($this->post->post_metas) {
          foreach ($this->post->post_metas as $meta_key => $meta_value) {
            $real_meta_key  = $this->post->get_meta_key($meta_key);
            $meta_key_label = $this->post->get_meta_label($meta_key);
            $stats_keys     = array('id', 'type', 'create_time', 'archive_url', 'status', 'send_time', 'emails_sent', 'content_type');
            if (in_array($real_meta_key, $stats_keys)) {
              echo $this->post->display_meta($meta_key_label, current($meta_value));
            }

          }
        }
        break;
      case 'list':
        $list_data = $this->post->get_meta('recipients', true);
        if ($list_data) {
          $labels = get_option('mailchimp_campaigns_manager_labels', mailchimp_campaigns_manager_register_labels());
          foreach ($list_data as $meta_key => $meta_value) {
            if (isset($labels[$meta_key])) {
              echo $this->post->display_meta($labels[$meta_key], $meta_value);
            }
          }
        }
        break;
      case 'tracking':
        var_dump($this->post_metas->tracking);
        break;
      }

      // For security check
      $output .= wp_nonce_field('mailchimp_campaigns_manager_nonce_action', 'mailchimp_campaigns_manager_nonce');

      echo $output;
    }

    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox($post_id, $post) {
      // Add nonce for security and authentication.
      $nonce_name   = isset($_POST['mailchimp_campaigns_manager_nonce']) ? $_POST['mailchimp_campaigns_manager_nonce'] : '';
      $nonce_action = 'mailchimp_campaigns_manager_nonce_action';

      // Check if nonce is set.
      if (!isset($nonce_name)) {
        return;
      }

      // Check if nonce is valid.
      if (!wp_verify_nonce($nonce_name, $nonce_action)) {
        return;
      }

      // Check if user has permissions to save data.
      if (!current_user_can('edit_post', $post_id)) {
        return;
      }

      // Check if not an autosave.
      if (wp_is_post_autosave($post_id)) {
        return;
      }

      // Check if not a revision.
      if (wp_is_post_revision($post_id)) {
        return;
      }
    }

  }
endif;
