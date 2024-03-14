<?php

if (!class_exists('MailChimpCampaign')):
/**
 * MailChimp Campaigns API
 *
 * Make use of the simplistic WordPress Post API
 * to save Mailchimp campaigns as Custom Posts.
 *
 * @author Matthieu Scarset <m@matthieuscarset.com>
 * @see http://matthieuscarset.com/
 * @version 1.0.0
 */

  class MailChimpCampaign extends MailchimpCampaignsManager {
    // Settings
    public $campaign;
    public $post;
    public $post_exists = false;
    public $post_type;
    public $post_metas;
    public $settings;

    /**
     * Start up
     */
    public function __construct($campaign) {
      parent::__construct();

      $this->campaign  = $campaign;
      $this->post_type = empty($this->settings->cpt_name) ? MCC_DEFAULT_CPT : $this->settings->cpt_name;
      $this->find(); // get exisitng $post or create a new empty Post object
      $this->post_metas = array();
    }

    /**
     * Find exisitng campaign in database
     * This function populates $this->post with the WP Post object
     * @return $this
     */
    public function find() {
      $args = array(
        'post_type'      => $this->post_type,
        'posts_per_page' => 1,
        'meta_query'     => array(
          array(
            'key'   => MCC_META_KEY_ID,
            'value' => $this->campaign->id,
          ),
        ),
      );
      // Query the dabatase
      $posts = get_posts($args);

      // Populate $this->post with the $post
      if (count($posts) > 0) {
        $this->post        = $posts[0];
        $this->post_exists = true;
        $this->post_metas  = get_post_meta($this->post->ID);
      }
      // Populate $this->post with a new defautl post object
    else {
        $this->post = new WP_Post((object) array(
          'post_author' => get_current_user_id(),
          'post_type'   => $this->post_type,
        ));
      }
      return $this;
    }

    /**
     * Instanciate a new Campaign Post object
     */
    public function init() {
      // Populate required fields
      $title   = !empty($this->campaign->settings->title) ? $this->campaign->settings->title : null; // Empty title
      $excerpt = !empty($this->campaign->settings->subject) ? $this->campaign->settings->subject : ''; // Empty exceprt

      // Create a new WP_Post
      $this->post->post_type    = $this->post_type;
      $this->post->post_name    = $title;
      $this->post->post_title   = $title;
      $this->post->post_excerpt = $excerpt;
      $this->post->post_date    = str_replace('T', ' ', $this->campaign->create_time);
      // $this->post->post_date_gmt = str_replace('T', ' ',  $campaign->create_time);
      // $this->post->post_content = '';
      $this->post->post_status    = MCC_DEFAULT_CPT_STATUS;
      $this->post->comment_status = 'open';
      // $this->post->post_modified = '2016-08-15 14:53:24',
      // $this->post->post_modified_gmt = '2016-08-15 14:53:24';

      // Custom metadata
      foreach ($this->campaign as $meta_key => $meta_value) {
        $this->post_metas[MCC_META_PRE . $meta_key] = $meta_value;
      }

      return $this;
    }

    /**
     * Get a Mailchimp campaign content
     * or any other scope available throught the API
     * @return mixed object || false
     * @see https://developer.mailchimp.com/documentation/mailchimp/reference/campaigns/
     */
    public function fetch($scopes = false) {
      if (!$scopes) {
        // Currently only fetching content scope.
        $scopes = array('content');
        // See Mailchimp doc (link above).
        // array('content', 'feedback', 'send-checklist');
      }

      if (!is_array($scopes)) {
        $scopes = array($scopes);
      }

      foreach ($scopes as $scope) {
        try {
          // Save specific data for each activated scope.
          $data = $this->call('campaigns/' . $this->campaign->id . '/' . $scope)->get();
          if (!$this->is_error) {
            $this->meta($scope, $data);
          }
        } catch (Exception $e) {
          $this->admin_notice(__('Error during import of campaign (' . $this->campaign->id . '):' . $e->getMessage() . "\n", MCC_TEXT_DOMAIN));
        }
      }
      return $this;
    }

    /**
     * Save specific data for each scope
     */
    public function meta($meta_key, $meta_value) {
      $scope    = $meta_key;
      $meta_key = MCC_META_PRE . $meta_key;
      switch ($scope) {
      default:
        $this->post_metas[$meta_key] = $meta_value;
        break;
      case 'content':
        $this->post_metas[$meta_key . '_plain_text'] = isset($meta_value->plain_text) ? $meta_value->plain_text : null;
        $this->post_metas[$meta_key . '_html']       = isset($meta_value->html) ? $meta_value->html : null;
        $this->post_metas[$meta_key . '_links']      = isset($meta_value->_links) ? $meta_value->_links : null;
        break;
      case 'feedback':
        // TODO
        break;
      case 'send-checklist':
        // TODO
        break;
      }
      return $this;
    }

    /**
     * Insert post in database
     */
    public function save() {
      if (!$this->post_exists) {
        // Set new posts' content;
        $this->populatePostContent();
      }

      // Save || Update post
      $post_id = $this->post_exists ? wp_update_post($this->post, true) : wp_insert_post($this->post, true);

      // Save || Update post metas
      foreach ($this->post_metas as $meta_key => $meta_value) {
        $unique     = ($meta_key == MCC_META_PRE . 'id') ? true : false;
        $post_metas = $this->post_exists ?
        update_post_meta($post_id, $meta_key, $meta_value) : add_post_meta($post_id, $meta_key, $meta_value, $unique);
      }
      return $this;
    }

    /**
     * Add default content inside Newsletter WP Post.
     */
    public function populatePostContent() {
      // We use our custom shortcode to display campaign HTML content on front.
      $cid_meta_key = MCC_META_PRE . 'id';
      if (isset($this->post_metas[$cid_meta_key]) && !empty($this->post_metas[$cid_meta_key])) {
        $this->post->post_content = '[campaign id="' . $this->post_metas[$cid_meta_key] . '"]';
      }
    }

    /**
     *
     */
    public function delete() {
    }

    /**
     *
     */
    public function edit() {
    }

    /**
     *
     */
    public function send() {
    }

    /**
     *
     */
    public function schedule() {
    }

  }
endif;
