<?php
/**
 * User List Page
 *
 * @package     Users List page
 * @since       1.0.5
 */
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}
 use \EasyUserNameUpdater\EasyUsernameUpdater;
	function eup_edit_options() { ?>
		<div class="wrap userupdater">
		<p><h1><?php echo esc_html( __('Users List')) ?></h1></p>
		<?php
		     $eup = new EasyUsernameUpdater();
		     $records = $eup->eup_select();

		    if($records) {
		        ?>
		        <table class="wp-list-table widefat fixed striped users" id="eupuserlist" cellpadding="3" cellspacing="3" width="100%">
		             <thead>
			          <tr>
			            <th><strong><?php echo esc_html( __('User ID')) ?></strong></th>
			            <th><strong><?php echo esc_html( __('User Name')) ?></strong></th>
			            <th><strong><?php echo esc_html( __('Email')) ?></strong></th>
			            <th><strong><?php echo esc_html( __('Role')) ?></strong></th>
			            <th><strong><?php echo esc_html( __('Update')) ?></strong></th>
			          </tr>
			         </thead>
			         <tbody>
					    <?php
					    //loop through
					    foreach($records as $user) { 
					        $user_info = get_userdata( $user->ID );
					    ?>
			          <tr>
			            <td><?php echo esc_html($user->ID); ?></td>
			            <td><?php echo esc_html($user->user_login); ?></td>
			            <td><a href="mailto:<?php echo esc_html($user->user_email); ?>"><?php echo esc_html($user->user_email); ?></a></td>
			            <td><?php echo esc_html(implode(', ', $user_info->roles)); ?></td>
			            <td><a href="<?php echo esc_url(admin_url( 'admin.php?page=eup_username_update&update='.$user->ID )); ?>">update</a></td>
			          </tr>
			      <?php } ?>
			        </tbody>
		        </table>
		        <?php
		    }
		?>
		</div>
<?php } 