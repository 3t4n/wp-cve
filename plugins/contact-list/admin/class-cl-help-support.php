<?php

class ContactListHelpSupport {

  public function register_support_page() {
    add_submenu_page(
      'edit.php?post_type=' . CONTACT_LIST_CPT,
      sanitize_text_field( __('How to use Contact List', 'contact-list') ),
      sanitize_text_field( __('Help / Support', 'contact-list') ),
      'manage_options',
      'contact-list-support',
      [ $this, 'register_support_page_callback' ]
    );
  }

  public function register_support_page_callback() {
    ?>
    
    <?php $num = 0 ?>

    <div class="wrap">

      <h1><?php echo esc_html__('How to use Contact List', 'contact-list'); ?></h1>

      <div class="contact-list-examples">

          <p><?php echo esc_html__('Some examples on how you can use different views available at', 'contact-list') ?> <a href="https://www.contactlistpro.com/contact-list/?utm_source=plugin-examples" target="_blank"><?php echo esc_html__('contactlistpro.com', 'contact-list') ?></a>.</p>

        <?php if (contact_list_fs()->can_use_premium_code()): ?>

          <p><?php echo esc_html__('Any kind of feedback is welcome. You may contact the author at', 'contact-list') ?> <a href="https://www.contactlistpro.com/support/?utm_source=plugin-feedback" target="_blank">contactlistpro.com/support/</a>.</p>
          
        <?php else: ?>

          <p>
          <?php
          $url = 'https://wordpress.org/support/plugin/contact-list/';
          echo sprintf(
            wp_kses(
              /* translators: %s: link to the support forum */
              __('Any kind of feedback is welcome. You may contact the author at <a href="%s" target="_blank">the support forum</a>.', 'contact-list'),
              array('a' => array('href' => array(), 'target' => array()))
            ),
            esc_url($url) 
          );
          ?>
          </p>
                
        <?php endif; ?>

      </div>

      <div class="contact-list-admin-section">
        <h2><?php echo esc_html__('Instructions for basic usage', 'contact-list') ?></h2>
        <ol>
          <li>
            <?php
            $url = esc_url_raw( get_admin_url() . 'edit.php?post_type=contact' );
            echo sprintf(
              wp_kses(
                /* translators: %s: link to file management */
                __('Add the contacts from the <a href="%s" target="_blank">contact management</a>. You can add categories, country, state and city directly to the contact.', 'contact-list'),
                array('a' => array('href' => array(), 'target' => array()))
              ),
              esc_url( $url ) 
            );
            ?>
          </li>
          <li>
            <?php echo esc_html__('Insert either one of these shortcodes to any page or post:', 'contact-list'); ?><br />
        
            <ul class="contact-list-admin-ul-v2">
              <li><?php echo esc_html__('The default contact list:', 'contact-list') ?><br /><?php $num++ ?><span class="contact-list-shortcode contact-list-shortcode-only contact-list-shortcode-<?php echo $num ?>" data-tooltip-class="contact-list-shortcode-<?php echo $num ?>">[contact_list]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-<?php echo $num ?>"><?php echo esc_html__('Copy', 'contact-list') ?></button></li>
              <li><?php echo esc_html__('A simpler list of contacts:', 'contact-list') ?><br /><?php $num++ ?><span class="contact-list-shortcode contact-list-shortcode-only contact-list-shortcode-<?php echo $num ?>" data-tooltip-class="contact-list-shortcode-<?php echo $num ?>">[contact_list_simple]</span><button class="contact-list-copy" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-<?php echo $num ?>"><?php echo esc_html__('Copy', 'contact-list') ?></button></li>
            </ul>
  
            <strong>
            <?php
            $url = esc_url_raw( get_admin_url() . 'edit.php?post_type=contact&page=contact-list-shortcodes' );
            echo sprintf(
              wp_kses(
                /* translators: %s: link to the list of available shortcodes */
                __('A complete list of available shortcodes can be found <a href="%s">here</a>.', 'contact-list'),
                array('a' => array('href' => array(), 'target' => array()))
              ),
              esc_url( $url ) 
            );
            ?>
            </strong>
            
          </li>
        </ol>
      </div>

      <div class="contact-list-admin-section">
      
        <h2><?php echo esc_html__('Ratings & Reviews', 'contact-list') ?></h2>
      
        <p>
          <?php
          echo sprintf(
            wp_kses(
              __('If you like <strong>Contact List</strong> please consider leaving a ★★★★★ rating.', 'contact-list'),
              array('strong' => array())
            )
          );
          ?>
      
        </p>
        <p>
          <?php echo esc_html__('A huge thanks in advance!', 'contact-list'); ?>
        </p>
      
        <a href="https://wordpress.org/support/view/plugin-reviews/contact-list/reviews/#new-post" target="_blank" class="button-primary"><?php echo esc_html__('Leave a rating', 'contact-list'); ?></a>
        
      </div>

      <div class="contact-list-admin-section">
      
        <h2><?php echo esc_html__('Debug Info', 'contact-list'); ?> <button class="contact-list-toggle-debug-info"><?php echo esc_html__('Open', 'contact-list'); ?></button></h2>
      
        <div class="contact-list-debug-info-container">
      
          <div class="contact-list-info-small">
            <p><?php echo esc_html__('This section contains some debug info, which may be useful when trying to solve abnormal behaviour of the plugin.', 'contact-list') ?></a></p>
          </div>
      
          <?php

          global $wpdb;
          $msg = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}contact_list_log ORDER BY id DESC LIMIT 200");

          ?>
          
          <table class="contact-list-mail-log" style="min-width: 400px;">

          <tr>
            <th><?php echo esc_html__('Date', 'contact-list') ?></th>
            <th><?php echo esc_html__('Title', 'contact-list') ?></th>
            <th><?php echo esc_html__('Message', 'contact-list') ?></th>
          </tr>
          
          <?php if (sizeof($msg) > 0): ?>

            <?php foreach ($msg as $row): ?>
              <tr>

                <td style="white-space: nowrap;">
                  <?php echo esc_html( $row->created_at ) ?>
                </td>

                <td>
                  <?php echo esc_html( $row->title ) ?><br />
                </td>

                <td>
                  <?php if (isset($row->message)): ?>
                    <?php echo nl2br( esc_html( $row->message ) ) ?>
                  <?php endif; ?>
                </td>

              </tr>
            <?php endforeach; ?>

          <?php else: ?>

            <tr>
              <td colspan="3">
                <?php echo esc_html__('No data logged yet.', 'contact-list') ?>
              </td>
            </tr>

          <?php endif; ?>
      
      
      
        </div>
        
      </div>

    </div>
    <?php
  }

}
