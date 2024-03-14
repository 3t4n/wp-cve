<?php  

/**
 * summary
 */
class elfiPermalink
{
    /**
     * summary
     */
    public function __construct()
    {
     
     add_action('load-options-permalink.php', array($this,'fancy_cpt_load_permalinks'));  

     
     add_action('edit_form_after_title', array($this,'in_admin_header_lw')); 
    }

    function in_admin_header_lw() {
       $screen = get_current_screen();
        $value = get_option( 'elfi__cpt_base' ); 

       $tiembav =  $value ? $value : 'elfi';
       $current_status = get_post_status (get_the_ID());
       if($screen->post_type=='elfi' && $screen->id=='elfi' && ($current_status == 'publish')) {
          echo "<em style='color:#878787;padding-top: 10px;display: block;padding-left: 10px;'>Change the '".$tiembav."' from the <a href='options-permalink.php' style=' color: #8c8f94;'>Permalinks page</a></em>";
       }
    }
   
   function fancy_cpt_load_permalinks()
   {
      if( isset( $_POST['elfi__cpt_base'] ) )
      {
         update_option( 'elfi__cpt_base', sanitize_title_with_dashes( $_POST['elfi__cpt_base'] ) );
      }
      
      // Add a settings field to the permalink page
      add_settings_field( 'elfi__cpt_base', __( 'Elfi Custom Permalink' ), array($this ,'elfi_cpt_field_callback'), 'permalink', 'optional', array('class' => 'fancyfiklteraaddon') );

   }

   function elfi_cpt_field_callback()
   {
      $value = get_option( 'elfi__cpt_base' ); 

     $tiembav =  $value ? $value : 'elfi';
      echo '<input type="text" value="' . esc_attr( $tiembav ) . '" name="elfi__cpt_base" id="elfi__cpt_base" class="regular-text" /><p class="description"><em>Change the permalink of the Elfi</em></p>';
   }
}
if(class_exists('elfiPermalink')){

	new elfiPermalink();
}