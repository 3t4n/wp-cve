<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $wpsy_data, $wpsy_pro, $wpsy_premium_copy;
	$wpsy_db_data = get_option('wpsy_db_data');
	$store_data = wpsy_graphql_central(array('query'=>'shop'), true);

	
	$shop_name = (!empty($store_data)?'<span title="'.$store_data->shop->description.'"><i class="fas fa-crown"></i> '.$store_data->shop->name.'</span>':'');
	
?>

<div class="wrap wpsy_settings_div">

        

<h2><?php echo $wpsy_data['Name']; ?> <?php echo '('.$wpsy_data['Version'].($wpsy_pro?') Pro':')'); ?> - <?php _e('Settings','wp-shopify'); ?> <?php echo $shop_name; ?></h2> 


    <h2 class="nav-tab-wrapper">

        <a class="nav-tab nav-tab-active" data-tab="config-settings"><?php _e("Configuration",'wp-shopify'); ?> <i class="fas fa-tools"></i></a>
        
        <a class="nav-tab" data-tab="shortcodes"><?php _e("Shortcodes",'wp-shopify'); ?> <i class="fas fa-code"></i></a>

        <a class="nav-tab" style="float:right" data-tab="help"><?php _e("Help",'wp-shopify'); ?> <i class="fas fa-question-circle"></i></a>

    </h2>
    
    <div class="nav-tab-content container-fluid tab-config-settings" data-content="config-settings">
            
    <form action="options.php" method="post" class="ignore">

    <?php
			settings_fields('wpsy_settings_page');
			do_settings_sections('wpsy_settings_page');
			submit_button();
    ?>
        
    </form>
<?php if(!empty($wpsy_db_data)): ?>
<ul class="wpsy-useful-links">
<li><a href="https://<?php echo $wpsy_db_data['wpsy_url']; ?>" target="_blank" aria-label="<?php _e('Go to Store', 'wp-shopify'); ?> (Opens in a new window)"><i style="color:#96BF48;" class="fab fa-shopify"></i> <?php _e("Go to Store",'wp-shopify'); ?></a></li>
<li><a href="https://accounts.shopify.com/store-login" target="_blank" aria-label="<?php _e('Store Login', 'wp-shopify'); ?> (Opens in a new window)"><i style="color:#F93;" class="fas fa-user-lock"></i> <?php _e("Store Login",'wp-shopify'); ?></a></li>
<li><a href="https://<?php echo $wpsy_db_data['wpsy_url']; ?>/admin/apps/development" target="_blank" aria-label="<?php _e('Application / API Credentials', 'wp-shopify'); ?> (Opens in a new window)"><i style="color:#369;" class="fas fa-rocket"></i> <?php _e("Application / API Credentials",'wp-shopify'); ?></a></li>
<li><a href="https://<?php echo $wpsy_db_data['wpsy_url']; ?>/admin/collections" target="_blank" aria-label="<?php _e('Create Collections', 'wp-shopify'); ?> (Opens in a new window)"><i style="color:#C3C;" class="fas fa-boxes"></i> <?php _e("Create Collections",'wp-shopify'); ?></a></li>

</ul>
<?php endif; ?>
    

            
    </div>      
    
   	 <div class="nav-tab-content container-fluid hides tab-shortcodes" data-content="shortcodes">
    
        <div class="row mt-3">
         

            <ol class="wp-shopify-shortcodes">
            <li>[wp-shopify]</li>
            <li>[wp-shopify-product]
            <br /><br />
			<small><strong><?php echo __('OR'); ?></strong> <br />[wp-shopify-product id="<?php echo __('some product ID', 'wp-shopify'); ?>" button_type="default"]</small>
            <br /><br />
			<small><strong><?php echo __('OR'); ?></strong> <br />[wp-shopify-product id="<?php echo __('some product ID', 'wp-shopify'); ?>" <i class="green">button_type="js"</i> <i class="red">template="bello-sole"</i>] <b class="red">(<?php echo __('Premium Version', 'wp-shopify'); ?>)</b><br />
<br />
add_action('wp-shopify-product-after-description', 'callback_func'); //FOR EXTRA HTML BASED CONTENT
</small>

            <br /><br /></li>
            <li>[wp-shopify type="collection" id="141421936749" limit="4" searchfilter="yes" url-type="default|shopify" thumb-size="default|300"]</li>
            <li>[wp-shopify type="products" limit="100"]</li>
            </ol>

		</div>    
	
    </div>        
    
    <div class="nav-tab-content container-fluid hides tab-help" data-content="help">
    
        <div class="row mt-3">
        
            <ul class="position-relative">
                <li><a class="btn btn-sm btn-info" href="https://wordpress.org/support/plugin/wp-shopify/" target="_blank" aria-label="<?php _e('Open a Ticket on Support Forums', 'wp-shopify'); ?> (Opens in a new window)"><?php _e('Open a Ticket on Support Forums', 'wp-shopify'); ?> &nbsp;<i class="fas fa-tag"></i></a></li>
                <li><a class="btn btn-sm btn-warning" href="http://demo.androidbubble.com/contact/" target="_blank" aria-label="<?php _e('Contact Developer', 'wp-shopify'); ?> (Opens in a new window)"><?php _e('Contact Developer', 'wp-shopify'); ?> &nbsp;<i class="fas fa-headset"></i></a></li>
                <li><a class="btn btn-sm btn-secondary" href="<?php echo $wpsy_premium_copy; ?>/?help" target="_blank" aria-label="<?php _e('Need Urgent Help?', 'wp-shopify'); ?> (Opens in a new window)"><?php _e('Need Urgent Help?', 'wp-shopify'); ?> &nbsp;<i class="fas fa-phone"></i></i></a></li>
                <li><iframe width="560" height="315" src="https://www.youtube.com/embed/grnbmlLhkJE?t=<?php date('d'); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
            </ul>                
        </div>
    
    </div>  

</div>