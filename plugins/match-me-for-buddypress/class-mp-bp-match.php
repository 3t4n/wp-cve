<?php
class Mp_BP_Match {

  function __construct(){

      add_action('wp_enqueue_scripts', array($this, 'mp_load_scripts'), 10);
      add_action('bp_include', array($this, 'mp_bp_match_init'), 100);

   }

  /**
  * Load the matching stuff only if BuddyPress is active.
  */
  function mp_bp_match_init() {

      require_once('src/class.settings-api.php');
  		require_once('mp-settings.php');

      new WeDevs_Settings_API_Match();

      add_action('wp_footer', array($this, 'hmk_script'), 100);

      add_action('bp_profile_header_meta', array($this, 'hmk_show_matching_percentage'), 10);
      add_action('bp_directory_members_item', array($this, 'hmk_matching_percentage_button'), 10);

      add_shortcode( 'mp_match_percentage', array($this, 'mp_match_percentage_function'));

      add_action('wp_ajax_hmk_get_percentage', array($this, 'hmk_get_percentage_function'), 100);
      add_action('wp_ajax_nopriv_hmk_get_percentage', array($this, 'hmk_get_percentage_function'), 100);


   }

  /**
  * Add Shortcode
  */
  function mp_match_percentage_function() {

      $this->hmk_show_matching_percentage();

  }


  /**
  * Load css
  */
  function mp_load_scripts() {
      wp_enqueue_style( 'hmk-style', plugins_url('src/css/style.css', __FILE__) );

   }


   function hmk_get_percentage_function() {

     $user_displayed = $_POST['hmk_uid'];
     $user_logged_in = get_current_user_id();

     $hmk_match_percentage = $this->hmk_get_matching_percentage_number($user_displayed,$user_logged_in);
     if($hmk_match_percentage >=0 ) {
       echo '<span class="hmk-member-match-percent">'.round($hmk_match_percentage,2).__('% MATCH TO YOU','bp-match').'</span>';
     }

     die;
   }


   function hmk_matching_percentage_button() {

      if(!is_user_logged_in()) return;

      $user_displayed_id =  bp_get_member_user_id();
      $user_logged_in = get_current_user_id();

      if($user_displayed_id == $user_logged_in) return;

      echo "<div class='hmk-trigger-match'><div id='user-$user_displayed_id' class='hmk-get-percent generic-button'>".__('% Calculate Match','bp-match')."</div></div>";

   }

  /**
  * Calculates the match percentage based on number of xprofile fields matched and their percentage value
  */
  function hmk_get_matching_percentage_number( $user_displayed = '' , $user_logged_in = '' ) {

  		global $wpdb;

  		if(empty($user_logged_in)) {
  			$user_logged_in = get_current_user_id();
  		}

  		if(empty($user_displayed)) {
  			$user_displayed = bp_displayed_user_id();
  		}

  		$percentage_class = new WeDevs_Settings_API;

  		$xprofile_table =  $wpdb->prefix.'bp_xprofile_fields';
  		$sql = "SELECT id,name,type FROM $xprofile_table WHERE type !='option'";
  		$result = $wpdb->get_results($sql) or die(mysql_error());
  		$percentage = 0;

      foreach( $result as $results ) {

  			$fd_id = $results->id;
  			$fd_type = $results->type;
  			$key = 'hmk_field_percentage_'.$fd_id;
  			$field_percentage_value = $percentage_class->get_option( "$key" ,'hmk_percentages' );

  			if($fd_type == 'checkbox' || $fd_type == 'multiselectbox') {
  					$field1 = xprofile_get_field_data($fd_id, $user_logged_in);
  					$field2 = xprofile_get_field_data($fd_id, $user_displayed);
  					if ( $field1 && $field2 )
  					{
  						$intersect = array_intersect((array)$field1,(array)$field2);
  						if ( count($intersect) >= 1 ) {
                            $field_percentage_value = absint($field_percentage_value);
  							$percentage += $field_percentage_value;
  						}
  					}

  			}elseif (xprofile_get_field_data($fd_id,$user_logged_in) != '' && xprofile_get_field_data($fd_id,$user_logged_in) == xprofile_get_field_data($fd_id,$user_displayed) ){
  				$field_percentage_value = $percentage_class->get_option( "$key" ,'hmk_percentages' );
                $field_percentage_value = absint($field_percentage_value);
  				$percentage += $field_percentage_value;
  			}



  		}

  		if ($percentage == 0) $percentage = 5;

  		return $percentage;

  }

  /**
  * gets the match percentage and draws circle.
  */
  function hmk_show_matching_percentage( ) {

    if(bp_is_my_profile()) return;

    if(!is_user_logged_in()) return;

  	echo '<div class="c100 p'.$this->hmk_get_matching_percentage_number().' small hmk-percentage blue">
  		<span class="hmk-match-inside">Match</span>
  		<span>'.$this->hmk_get_matching_percentage_number().'%</span>
  		<div class="slice">
  			<div class="bar"></div>
  			<div class="fill"></div>
  		</div>
  	</div>';


  }


  function hmk_script() {
      ?>
      <script>
          jQuery(document).ready(function($) {


              console.log('here');

              jQuery(document).on('click', '.hmk-get-percent', function(event){


              var uid = event.target.id;
              uid    = uid.split('-');
              uid    = uid[1];

              jQuery('#user-'+uid).html('Please wait..');

              jQuery.post( ajaxurl, {
                action: 'hmk_get_percentage',
                'hmk_uid': uid,
              },
              function(response) {
                jQuery('#user-'+uid).html(response);

              });



            });

      });
      </script>


      <?php
  }


}
