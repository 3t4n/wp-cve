<?php

/**
 * Export Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

global $wpe_prayer;
$response= array_key_exists('response', $wpe_prayer) ? $wpe_prayer['response'] : array();
?>
<div class="wrap wpr-export-page">

	 <style type="text/css">
    	/**** Reset Css  ****/
		.error,.update,.notice,.update-nag{
		    display: none;
		}

		/*.wpr-export-page {
    		padding: 1em;
       	}*/

       .wpe-form-row{
       		padding: 1em 0px ;
       }

       .wpe-form-row label {
		    cursor: default;
		    font-size: 1.25em;
		    vertical-align: text-top;
		    font-weight: 600;
		}

		.wpe_input {
		    min-width: 64px;
		    margin-left: 12px;
		}

		.wpe_export-notice{
			display: inline-block;
			width:auto;
			max-width: 100%;
		}

    </style>

   
    <div class="wpe_headline">
    	<?php echo '<h1>' . esc_html(esc_html__('Export',WPE_TEXT_DOMAIN)) . '</h1>';?>
    	 <?php
    	if( !empty($response) && $response[0] === 'error' ){

    		echo '<div class="notice notice-error is-dismissible wpe_export-notice"><p><span class="dashicons dashicons-lightbulb"></span><strong>',__($response[1],WPE_TEXT_DOMAIN),'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    	}
?>
    </div>
	<div class="wpr-export-sec">
		<?php $export_download_url=admin_url( 'admin.php?page=wpe_prayers_export'); ?>
	    <form  method="post" action="<?php echo esc_html($export_download_url)  ?>" target="_self">
			<input type="hidden" name="lxt_table" value="<?php echo  esc_html(WPE_TBL_PRAYER) ?>"/>
			<?php wp_nonce_field('_wpnonce'); ?>
	       	<div class="wpe-form-row">

		       	<l<?php echo '<h2>' . esc_html(esc_html__('Start date and End date',WPE_TEXT_DOMAIN)) . '</h2>';?>
				
		       	<input type="text" name="start_date" id="start_date" placeholder="<?php echo esc_html(_e('DD-MM-YYYY',WPE_TEXT_DOMAIN)); ?>" class="wpe_input" />

		       	<input type="text" name="end_date" id="end_date" placeholder="<?php echo esc_html(_e('DD-MM-YYYY',WPE_TEXT_DOMAIN)); ?>" class="wpe_input" />

		    </div>

		    <div class="wpe-form-row">

		       	<?php echo '<h3>' . esc_html(esc_html__('Format',WPE_TEXT_DOMAIN)) . '</h3>';?>

		       	<select name="format" class="wpe_input">

		       		<!-- <option value="pdf"><?php _e('Adobe PDF',WPE_TEXT_DOMAIN); ?></option> -->

		       		<option value="csv"><?php _e('CSV File',WPE_TEXT_DOMAIN); ?></option>

		       		<option value="xls"><?php _e('Microsoft Excel',WPE_TEXT_DOMAIN); ?></option>
		       		
		       	</select>

		    </div>

		    <div class="wpe-form-row">
		    	<!-- <input name="operation" id="" value="export" type="hidden"> -->
	       		<input type="submit" value="<?php echo esc_html(_e('Submit',WPE_TEXT_DOMAIN)); ?>" class="button" name="lxt_export_prayers" />
	       	</div>
	       </form>

	       <script type="text/javascript">
			jQuery(document).ready(function(){
			    jQuery('#start_date,#end_date').datepicker({
			        dateFormat: 'dd-mm-yy'
			    });
			});
			</script>
   </div>
</div>
<?php

