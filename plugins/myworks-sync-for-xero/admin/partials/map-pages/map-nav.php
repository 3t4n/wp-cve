<?php
if(!defined( 'ABSPATH' )){
	exit;
}

$a_class = 'active';
?>

<nav class="mw-qbo-sync-grey">
	<div class="nav-wrapper">
		<a class="brand-logo left" href="javascript:void(0)">
			<img alt="" src="<?php echo esc_url(plugins_url(MW_WC_XERO_SYNC_PLUGIN_NAME.'/admin/image/mwd-logo.png'));?>">
		</a>
		<ul class="hide-on-med-and-down right">
			<li class="cust-icon <?php if($tab=='customer') echo esc_attr($a_class);?>">				
				<a href="<?php echo esc_url($UP).'customer';?>"><?php _e('Customer','myworks-sync-for-xero');?></a>
			</li>
			
			<li class="pro-icon <?php if($tab=='product') echo esc_attr($a_class);?>">				
				<a href="<?php echo esc_url($UP).'product';?>"><?php _e('Product','myworks-sync-for-xero');?></a>
			</li>
			
			<li class="vari-icon <?php if($tab=='variation') echo esc_attr($a_class);?>">				
				<a href="<?php echo esc_url($UP).'variation';?>"><?php _e('Variation','myworks-sync-for-xero');?></a>
			</li>
			
			<li class="cat-icon <?php if($tab=='category') echo esc_attr($a_class);?>">				
				<a href="<?php echo esc_url($UP).'category';?>"><?php _e('Category','myworks-sync-for-xero');?></a>
			</li>
			
			<li class="pay-icon <?php if($tab=='payment-method') echo esc_attr($a_class);?>">				
				<a href="<?php echo esc_url($UP).'payment-method';?>"><?php _e('Payment Method','myworks-sync-for-xero');?></a>
			</li>
			
			<li class="tax-icon <?php if($tab=='tax-class') echo esc_attr($a_class);?>">				
				<a href="<?php echo esc_url($UP).'tax-class';?>"><?php _e('Tax Rate','myworks-sync-for-xero');?></a>
			</li>
		</ul>
	</div>
</nav>

<?php require_once dirname(plugin_dir_path( __FILE__ )).DIRECTORY_SEPARATOR.'guidelines.php';?>