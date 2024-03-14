<?php

if (!class_exists('MailchimpCampaigns')):
  class MailchimpCampaigns extends MailchimpCampaignsManager {
    // Properties
    protected $campaigns;

    // Constructor
    public function __construct($args = array()) {
      parent::__construct();

      // Fetch data and set $this->campaigns.
      $total = $this->getTotal();
      $this->pull(array('count' => $total));

      if ($this->is_error) {
        $error_message = $this->last_call->get_error_message();
        $this->admin_notice(__('Error during pull operation:' . $error_message . "\n", MCC_TEXT_DOMAIN), 'error');
        return;
      }
    }

    /**
     *
     */
    public function getCampaigns() {
      return $this->campaigns->campaigns;
    }

    /**
     *
     */
    public function count() {
      return count($this->campaigns->campaigns);
    }

    /**
     * Get total items
     */
    public function getTotal() {
      $total_items = 0;
      $args        = $this->args();
      $results     = $this->call('campaigns', $args);

      if (!$this->is_error) {
        $total_items = json_decode($results->last_call['body'])->total_items;
      }

      return $total_items;
    }

    /**
     *
     */
    public function args($args = array()) {
      $default_args = array(
        // 'count' => 5,
        // 'status' => 'sent',
        // 'fields' => array('id', 'type'),
      );
      $args = array_merge_recursive($default_args, $args);
      return $args;
    }

    /**
     * Retrieve Campaigns from MailChimp.
     */
    public function pull($args = array()) {
      // Fetch data and set $this->campaigns.
      $this->call('campaigns', $args);

      if ($this->is_error) {
        return;
      }

      // Retrieve data.
      $this->campaigns = json_decode($this->last_call['body']);

      // Update the time
      $this->last_updated = current_time('mysql');

      return $this->campaigns;
    }

    /**
     * Actually do the import of Mailchimp campaigns.
     */
    public function import() {
      if ($this->is_error) {
        return;
      }

      $x = 0;
      $campaigns = $this->getCampaigns();
      foreach ($campaigns as $i => $campaign) {
        try {
          // Get content and save this campaign.
          $mcc = new MailchimpCampaign($campaign);
          $mcc->init()->fetch()->save();
          $this->admin_notice(
            ($i+1) . '/' . count($campaigns) . __(' campaign imported.', MCC_TEXT_DOMAIN),
            'updated'
          );

        } catch (Exception $e) {
          $this->admin_notice(
            __('Error during import after ' . $x . 'campaign:' . $e->getMessage() . "\n", MCC_TEXT_DOMAIN),
            'error'
          );
          break;
        }
        $x++;
      }

      // Display result
      $cpt_name = empty($this->settings['cpt_name']) ? MCC_DEFAULT_CPT : $this->settings['cpt_name'];
      $this->admin_notice(__($x . '/' . $this->count() . ' campaigns have been imported.', MCC_TEXT_DOMAIN));
    }

    /**
     * Miscellaneous
     */
    public function admin_notice($message, $status = 'updated') {
      mailchimp_campaigns_admin_notice($message, $status);
    }

  }
endif;
