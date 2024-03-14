<?php /**

* WordPress Settings Page

*/

// Check the user capabilities

	global $wcdp_error_default, $wdp_dir, $wdp_pro, $wcdp_settings_saved;

	$template_dir = $wdp_dir."inc/templates/";
	

	if ( !current_user_can( 'manage_woocommerce' ) ) {

		wp_die( __( 'You do not have sufficient permissions to access this page.', "wcdp" ) );

	}

	$categories = get_categories( array(

		'orderby' => 'name',

		'order'   => 'ASC',

		'taxonomy' => 'product_cat',

		'hide_empty'       => false,

	) );	

	

	//pree($categories);
	
	$error_messages = wcdp_get_error_messages();
	
	$wcdp_cats = get_option('wcdp_cats', array());
	
	$wcdp_cats = array_filter($wcdp_cats, 'strlen');

?>



<div class="wrap wcdp">

	<div id="icon-options-general" class="icon32"></div>

	<h2><?php echo esc_html($wcdp_data['Name']); ?> <?php echo esc_html('('.$wcdp_data['Version'].($wdp_pro?') Pro':')')); ?> - <?php _e('Settings', "wcdp") ;?></h2>

    <h2 class="nav-tab-wrapper">
        <a class="nav-tab nav-tab-active general_tab"><?php _e("General Settings","wcdp"); ?></a>
        <a class="nav-tab global_tab premium-tab"><?php _e("Global Criteria","wcdp"); ?></a>
        <a class="nav-tab category_tab premium-tab"><?php _e("Category Based Criteria","wcdp"); ?></a>
        <a class="nav-tab cart_amount_tab premium-tab"><?php _e("Cart Amount Based Criteria","wcdp"); ?></a>
        <a class="nav-tab product_tab"><?php _e("Product Based Criteria","wcdp"); ?></a>
        <a class="nav-tab error_tab"><?php _e("Error Messages","wcdp"); ?></a>
    </h2>

	<?php if ( isset( $_POST['wcdp_fields_submitted'] ) && $_POST['wcdp_fields_submitted'] == 'submitted' ) { ?>

	<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', "wcdp" ); ?></strong></p></div>

	<?php } ?>


    <?php if($wcdp_settings_saved): ?>


        <div class="alert alert-success alert-dismissible mt-3 wcdp_settings_save_alert" role="alert">
            <?php _e("Your settings have been saved.","wcdp"); ?>
            <button class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <script>
            setTimeout(function(){
                jQuery('.wcdp_settings_save_alert').fadeOut();
            }, 5*1000)
        </script>

    <?php endif; ?>



    <div class="nav-tab-content general_content">

        <?php include_once realpath($template_dir."wcdp_general_settings.php"); ?>

    </div>

    <div class="nav-tab-content global_criteria_content hide">

	    <?php include_once realpath($template_dir."wcdp_global_criteria.php"); ?>

    </div>

	<div id="content" class="nav-tab-content category_content hide">

		<?php include_once realpath($template_dir."wcdp_category.php"); ?>

	</div>

    <div id="content" class="nav-tab-content cart_content hide">

        <?php include_once realpath($template_dir."wcdp_cart_criteria.php"); ?>

    </div>

    <div id="content" class="nav-tab-content cart_content hide">

        <?php include_once realpath($template_dir."wcdp_product_criteria.php"); ?>

    </div>


    <div class="nav-tab-content error_message_content hide">

	    <?php include_once realpath($template_dir."wcdp_error_message.php"); ?>

    </div>


</div>

<style type="text/css">

	.update-nag{

		display:none;

	}

</style>

<script type="text/javascript" language="javascript">

    jQuery(document).ready(function($) {

        let searchParams = new URLSearchParams(window.location.search)



		<?php if(isset($_GET['t'])): ?>

            $('.nav-tab-wrapper .nav-tab:nth-child(<?php echo esc_html(wdp_sanitize_arr_data($_GET['t'])+1); ?>)').click();

            if(searchParams.has('cart')){


                $('#woocommerce_plus_discount_type').addClass('wcpd_border_class');
                $('html').animate({scrollTop: $("#woocommerce_plus_discount_type").offset().top}, 5000);
                $('#woocommerce_plus_discount_type').focus();


                setTimeout(function(){

                    $('#woocommerce_plus_discount_type').removeClass('wcpd_border_class');


                }, 6000);



            }

		<?php endif; ?>



<?php 
	if(isset($_GET['wcdp_tab'])){
		
		if(is_numeric($_GET['wcdp_tab'])){
?>			

            var current_tab = '<?php echo esc_attr($_GET['wcdp_tab']); ?>';
			current_tab--;
			current_tab = (current_tab>=0?current_tab:0);
			
			$('.nav-tab-wrapper > a').eq(current_tab).click();

            $('.'+current_tab).click();

<?php 
		}elseif(ctype_alpha(str_replace('_', '', $_GET['wcdp_tab'])) && in_array($_GET['wcdp_tab'], array('general_tab','global_tab','category_tab','cart_amount_tab','product_tab','error_tab'))){
			
?>			

            var current_tab = '<?php echo esc_attr($_GET['wcdp_tab']); ?>';
		
			$('.nav-tab-wrapper > a').eq(current_tab).click();
			
			if($('.'+current_tab).length>0){
            	$('.'+current_tab).click();
			}

<?php 			
		}
	}
?>



    });

</script>