<?php

require_once( 'content.php' );
require_once( 'styling.php' );
require_once( 'exclusions.php' );
require_once( 'other.php' );

/*
  Create Admin Menu and Page
*/
//adds menu field under Settings
add_action('admin_menu', 'cpln_admin_menu');
function cpln_admin_menu() { 
	add_options_page('External Links Pop Up Settings', 'External Links', 'manage_options', 'cpleavenotice', 'cpln_admin_page');
}

//structures the admin page
function cpln_admin_page() { ?>
	<div class="wrap">
		<h2>External Links Pop Up Settings</h2>
        	<?php settings_errors(); ?>
            
            <?php //check for active tab
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'content_options';
			?>
            <h2 class="nav-tab-wrapper">
                <a href="?page=cpleavenotice&tab=content_options" 
                	class="nav-tab <?php echo $active_tab == 'content_options' ? 'nav-tab-active' : ''; ?>" >
                    Content
                </a>
                <!--
                <a href="?page=cpleavenotice&tab=styling_options"
                	class="nav-tab <?php echo $active_tab == 'styling_options' ? 'nav-tab-active' : ''; ?>" >
                    Styling Options
                </a>
                -->
                <a href="?page=cpleavenotice&tab=exclusions" 
                	class="nav-tab <?php echo $active_tab == 'exclusions' ? 'nav-tab-active' : ''; ?>" >
                    Exclusions
				</a>
				<a href="?page=cpleavenotice&tab=other_options"
				   class="nav-tab <?php echo $active_tab == 'other_options' ? 'nav-tab-active' : ''; ?>">
					Other Options
				</a>
            </h2>
            
			<form method="post" action="options.php">
				<?php
					if ( $active_tab == 'content_options' ) {
						settings_fields( 'cpln_content_settings' );
						do_settings_sections( 'cpln_content_settings' );
                    /*
					} else if ( $active_tab == 'styling_options' ) {
                        settings_fields( 'cpln_styling_settings' );
                        do_settings_sections( 'cpln_styling_settings' );
					*/
                    } else if ( $active_tab == 'exclusions' ) {
                        settings_fields( 'cpln_exclusions' );
						do_settings_sections( 'cpln_exclusions' );
                    } else if ( $active_tab == 'other_options' ) {
						settings_fields( 'cpln_other_settings' );
						do_settings_sections( 'cpln_other_settings' );
					}
                ?>
                <?php submit_button(); ?>
		</form>
	</div>
	<?php } //end admin_page() 