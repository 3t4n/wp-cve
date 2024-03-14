<?php
if ( ! defined( 'WPINC' ) ) die;

$settings = get_option('sform_settings');
$admin_notices = ! empty( $settings['admin_notices'] ) ? esc_attr($settings['admin_notices']) : 'false';
$color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
$notice = '';
?>

<div id="sform-wrap" class="sform">

<div id="new-release" class="<?php if ( $admin_notices == 'true' ) {echo 'invisible';} ?>"><?php echo apply_filters( 'sform_update', $notice ); ?>&nbsp;</div>
	
<div class="full-width-bar <?php echo $color ?>"><h1 class="title <?php echo $color ?>"><span class="dashicons dashicons-info responsive"></span><?php _e( 'Forms', 'simpleform' );
?>

<a href="<?php echo esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=sform-new')) ?>"><span class="dashicons dashicons-plus-alt icon-button admin <?php echo $color ?>"></span><span class="wp-core-ui button admin back-list <?php echo $color ?>"><?php _e( 'Add New', 'simpleform' ) ?></span></a></h1></div>

<div id="page-description" class="submissions-list overview">

<?php
$table = new SimpleForm_Forms_List();
$table->prepare_items();
$table->display_notice();
$table->views(); 
?>

<form id="forms-table" method="get">
<input type="hidden" name="page" value="<?php echo sanitize_key($_REQUEST['page']) ?>" />
<?php $table->display() ?>
</form>
        
</div>

</div>