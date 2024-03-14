<?php
/*
 * View: Contact Card
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/contact-list-pro/contact-card.php
 *
 * @var int     $id                 Contact ID
 * @var array   $atts               Shortcode attributes
 * @var boolean $is_modal           Is this contact card opened in a modal (lightbox)
 * @var string  $contact_card_class Special classes for contact card
 *
 * @version 2.9.85
 */
?>

<li id="cl-<?php echo intval( $id ) ?>">

  <?php echo ContactListPublicHooks::get_action_content('contact_list_before_contact_card'); ?>
  
  <div class="contact-list-contact-container <?php echo esc_attr( $contact_card_class ) ?>">
  
    <div class="contact-list-main-left">
      
      <div class="contact-list-main-elements">
        
        <?php 

        // Column is not defined: get just the field(s) defined here regardless of the plugin settings
        $column = '';
        $fields = 'edit_contact_button';
        echo ContactListCard::getMarkup($id, $fields, $atts, $is_modal, 1, $column);
  
        // If content for the left column is defined in the plugin settings, get those instead of the fields defined here
        $column = 'left';
        $fields = 'full_name job_title email send_message_button phone_numbers groups address custom_fields additional_info some_icons show_contact_button';
        echo ContactListCard::getMarkup($id, $fields, $atts, $is_modal, $column);
          
        ?>
  
      </div>
    
    </div>
    
    <div class="contact-list-main-right">
  
      <?php 
    
      // If content for the right column is defined in the plugin settings, get those instead of the fields defined here
      $column = 'right';
      $fields = 'featured_image';
      echo ContactListCard::getMarkup($id, $fields, $atts, $is_modal, $column);
        
      ?>
  
    </div>

    <div class="contact-list-main-bottom">
    
      <?php 
    
      // If content for the bottom column is defined in the plugin settings, get those instead of the fields defined here
      $column = 'bottom';
      $fields = 'map';
      echo ContactListCard::getMarkup($id, $fields, $atts, $is_modal, $column);
        
      ?>
    
    </div>
  
  </div>
  
  <?php echo ContactListPublicHooks::get_action_content('contact_list_after_contact_card') ?>

</li>
