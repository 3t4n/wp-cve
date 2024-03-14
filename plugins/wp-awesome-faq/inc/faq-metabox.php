<?php

/*
* Add Meta Box In Colorful FAQ Post Type
*/

function jltmaf_customizer_meta_box() {
	add_meta_box( 
		'jw_faq_customizer', 
		__( 'FAQ Item Customize', MAF_TD ), 
		'jltmaf_customizer_callback', 
		'faq',
		'side',
		'high' 
	);
}
add_action( 'add_meta_boxes', 'jltmaf_customizer_meta_box' );

/*
* FAQ Customizer Fields
*/

function jltmaf_customizer_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'awesome_faq_customizer_nonce' );
	$ccr_store_data = get_post_meta( $post->ID );
	?>

	<div class="jltmaf-container">

		<h5 class="faq-customizer-title">
			<?php _e( 'Close Icon', MAF_TD )?>		
		</h5>
		
		<p class="<?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">
			<select id="jltmaf_mb_close_icon" name="close_icon" class="jltmaf-fonticon-pickers ">
				<option value=""><?php echo __('No icon', MAF_TD);?></option>
				<?php 
					$font_awesome_icons = jltmaf_fa_icons();
					foreach ( $font_awesome_icons as $key => $label ) {
			            echo sprintf( '<option value="%s"%s>%s</option>', $key, selected( isset($ccr_store_data['close_icon'][0]), $key, false ), $label );
			        }
				?>
			</select>
			<?php 	if ( !jltmaf_accordion()->can_use_premium_code() ) {
			echo '<div class="jltmaf-text-small"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
			} ?>
		</p>
		


		<h5 class="faq-customizer-title">
			<?php _e( 'Open Icon', MAF_TD )?>		
		</h5>
		
		<p class="<?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">
			<select id="jltmaf_mb_open_icon" name="open_icon" class="jltmaf-fonticon-pickers">
				<option value=""><?php echo __('No icon', MAF_TD);?></option>
				<?php 
				print_r( $ccr_store_data['open_icon'][0] );
					$font_awesome_icons = jltmaf_fa_icons();
					foreach ( $font_awesome_icons as $key => $label ) {				
			            echo sprintf( '<option value="%s"%s>%s</option>', $key, selected( isset($ccr_store_data['open_icon'][0]), $key, false ), $label );
			        }
				?>
			</select>
			<?php 	if ( !jltmaf_accordion()->can_use_premium_code() ) {
			echo '<div class="jltmaf-text-small"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
			} ?>
		</p>


		<div class="left-half">
			<h5 class="faq-customizer-title">
				<?php _e( 'Title Background Color', MAF_TD )?>		
			</h5>
		</div>

		<div class="right-half <?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">
			<p>
				<input name="faq-title-bg-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-title-bg-color'] ) ) echo $ccr_store_data['faq-title-bg-color'][0]; ?>" class="faq-color-picker" />
			</p>
		</div>
		<?php 	if ( !jltmaf_accordion()->can_use_premium_code() ) {
		echo '<div class="jltmaf-text-small"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
		} ?>		


		<div class="left-half">
			<h5 class="faq-customizer-title">
				<?php _e( 'Title Color', MAF_TD )?>
			</h5>
		</div>
		<div class="right-half <?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">
			<p>
				<input name="faq-title-text-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-title-text-color'] ) ) echo $ccr_store_data['faq-title-text-color'][0]; ?>" class="faq-color-picker" />
			</p>
		</div>
		<?php if ( !jltmaf_accordion()->can_use_premium_code() ) {
		echo '<div class="jltmaf-text-small"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
		} ?>


		<div class="left-half">
			<h5 class="faq-customizer-title">
				<?php _e( 'Content Background Color', MAF_TD )?>
			</h5>
		</div>
		<div class="right-half <?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">		
			<p>
				<input name="faq-bg-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-bg-color'] ) ) echo $ccr_store_data['faq-bg-color'][0]; ?>" class="faq-color-picker" />
			</p>
		</div>
		<?php if ( !jltmaf_accordion()->can_use_premium_code() ) {
		echo '<div class="jltmaf-text-small"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
		} ?>


		<h5 class="faq-customizer-title">
			<?php _e( 'Content Text Color', MAF_TD )?>		
		</h5>
		
		<p class="<?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">
			<input name="faq-text-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-text-color'] ) ) echo $ccr_store_data['faq-text-color'][0]; ?>" class="faq-color-picker" />
		</p>
		<?php if ( !jltmaf_accordion()->can_use_premium_code() ) {
		echo '<div class="jltmaf-text-small"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
		} ?>		

		<h5 class="faq-customizer-title">
			<?php _e( 'Content Border Color', MAF_TD )?>		
		</h5>
		
		<p class="<?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">
			<input name="faq-border-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-border-color'] ) ) echo $ccr_store_data['faq-border-color'][0]; ?>" class="faq-color-picker" />
		</p>
		<?php if ( !jltmaf_accordion()->can_use_premium_code() ) {
		echo '<div class="jltmaf-text-small"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
		} ?>		

	</div>

	<style>
		.jltmaf-container {
			/* margin: 0px auto; */
			/* padding: 5px; */
			/* display: block; */
			/* position: relative; */
		}
		.jltmaf-container .icons-selector.selector-popup-wrap, 
		.jltmaf-container .icons-selector .selector-popup-wrap{
			position: relative;
			width: 200px;
		}
		.jltmaf-container .icons-selector .selector-popup{
			width: 220px;	
		}
		/*
		.jltmaf-container {
			margin: 0px auto;
			padding: 5px;
			display: block;
		}
		.left-half {
			width: 48%;
			float: left;
		}
		.right-half {
			width: 48%;
			float: right;
		}
		*/
	</style>

	<?php
}

/*
* FAQ Customizer Data Save
*/

function jltmaf_customizer_data_save( $post_id ) {

	// Checks faq post save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'awesome_faq_customizer_nonce' ] ) && wp_verify_nonce( $_POST[ 'awesome_faq_customizer_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Close Icon for Individual Accordion
	if( isset( $_POST[ 'open_icon' ] ) ) {
		update_post_meta( $post_id, 'open_icon', $_POST[ 'open_icon' ] );
	}


	// Open Icon for Individual Accordion
	if( isset( $_POST[ 'close_icon' ] ) ) {
		update_post_meta( $post_id, 'close_icon', $_POST[ 'close_icon' ] );
	}

	
	// Checks for title background color input and saves if needed
	if( isset( $_POST[ 'faq-title-bg-color' ] ) ) {
		update_post_meta( $post_id, 'faq-title-bg-color', $_POST[ 'faq-title-bg-color' ] );
	}

	// Checks for title text color and saves if needed
	if( isset( $_POST[ 'faq-title-text-color' ] ) ) {
		update_post_meta( $post_id, 'faq-title-text-color', $_POST[ 'faq-title-text-color' ] );
	}

	// Checks for faq background and saves if needed
	if( isset( $_POST[ 'faq-bg-color' ] ) ) {
		update_post_meta( $post_id, 'faq-bg-color', $_POST[ 'faq-bg-color' ] );
	}

	// Checks for faq text color and saves if needed
	if( isset( $_POST[ 'faq-text-color' ] ) ) {
		update_post_meta( $post_id, 'faq-text-color', $_POST[ 'faq-text-color' ] );
	}

	// Checks for faq border color and saves if needed
	if( isset( $_POST[ 'faq-border-color' ] ) ) {
		update_post_meta( $post_id, 'faq-border-color', $_POST[ 'faq-border-color' ] );
	}

}
add_action( 'save_post', 'jltmaf_customizer_data_save' );


