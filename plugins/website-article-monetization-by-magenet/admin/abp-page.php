<?php

defined( 'ABSPATH' ) || die( 'Bye.' );

if ( ! is_admin() ) {
	die( 'Bye.' );
}

$abp_author_id  = (int) get_option( 'abp_author_id', 0 );
$abp_auth_key   = get_option( 'abp_auth_key', '' );
$abp_categories = get_option( 'abp_categories', false );

$plugin_activate = false;
$api_activate    = true;
if ( $abp_author_id > 0 && ! empty( $abp_auth_key ) && strlen( $abp_auth_key ) === 32 ) {
	if ( ! abp_exist_api() ) {
		$api_activate = false;
	} else {
		$response = Requests::post( ABP_MAGENET_API_URL . '/article/is_active_plugin', array(),
			array( 'auth_key' => $abp_auth_key, 'host' => ABP_HOST_SITE )
		);
		if ( isset( $response->body ) && ! empty( $response->body ) ) {
			$response = json_decode( $response->body );
			if ( isset( $response->plugin_active ) && $response ) {
				$plugin_activate = (bool) $response->plugin_active;
			}
		}
	}
}

?>

<style>
    .abp-button-success {
        background-color: #0c8c00 !important;
        border-color: #0c8c00 !important;
    }
</style>

<div class="wrap">
	<?php if ( ! $api_activate ) { ?>
        <h2><span style="color: red;">Api server is not working</span></h2>
	<?php } else { ?>
        <form method="post" id="abp_form_settings">
            <h3>Article Plugin Settings</h3>
            <label style="padding-bottom: 10px; display: block; cursor: auto;">Login to your MageNet account to get the
                <a href="<?php echo ABP_CP_HOST; ?>/sites?site_host=<?php echo ABP_HOST_SITE; ?>#install_artcle_plugin" target="_blank">Authorization Key</a>
            </label>
            <div>
                <input type="text" name="abp_auth_key" id="abp_auth_key" style="width: 400px" maxlength="32" minlength="1" value="<?php echo $abp_auth_key; ?>" placeholder="Please enter your Authorization Key"/>
            </div>
            <h4>Plugin status: <?php echo $plugin_activate ? '<span style="color: green;">Activated</span>' : '<a href="' . ABP_CP_HOST . '/sites/default/fix-article-plugin?site_host=' . ABP_HOST_SITE . '" target="_blank" style="color: red;">Error -> learn more</a>' ?></h4>

            <hr>
            <h3>Settings of Articles Placement</h3>

            <label style="padding-bottom: 10px; display: block; cursor: auto;"> In whose name do you wish to publish the article?</label>
            <select name="abp_author_id" style="width: 400px; display: block;margin-bottom: 15px;" id="abp_author_id">
				<?php
				$users             = get_users( array( 'role' => 'administrator' ) );
				$count_user_access = 0;
				?>

				<?php foreach ( $users as $user ) { ?>
					<?php
					if ( ! user_can( (int) $user->ID, 'publish_posts' ) || ! user_can( (int) $user->ID, 'edit_posts' ) ) {
						continue;
					}
					?>
                    <option value="<?php echo $user->ID; ?>" <?php echo( $abp_author_id === (int) $user->ID ? ' selected="selected" ' : '' ); ?>><?php echo $user->display_name; ?></option>
					<?php
					$count_user_access ++;
				}
				?>
            </select>
			<?php if ( $count_user_access == 0 ) { ?>
                <div style="color: red; margin-bottom: 20px;">You don't have users to publish or edit posts. Please,
                    <a href="<?= admin_url( 'users.php' ) ?>">create an admin user</a> with the right to manage posts
                </div>
			<?php } else if ( ( ! user_can( $abp_author_id, 'publish_posts' ) || ! user_can( $abp_author_id, 'edit_posts' ) ) && $abp_author_id > 0 ) { ?>
                <div style="color: red; margin-bottom: 20px;">
                    This user doesn't have the right to publish or edit posts. Please, choose another one from the list.
                </div>
			<?php } ?>

            <label style="cursor: auto;">Select the category in which you want to publish your articles.
                <b>If you don’t indicate the category, your article will be placed in the "All categories"</b>
            </label>

            <ul>
				<?php wp_terms_checklist( 0, array( 'selected_cats' => $abp_categories ) ); ?>
            </ul>
            <div class="submit">
				<?php if ( ! $plugin_activate && ! empty( $abp_auth_key ) ) { ?>
                    <input type="submit" class="button button-primary abp-button-success" name="abp_save" value="Save & Activate"/>
				<?php } else { ?>
                    <input type="submit" class="button button-primary" name="abp_save" value="Save"/>
				<?php } ?>
            </div>
            <input type="hidden" value="update_settings" name="action"/>
        </form>
	<?php } ?>
</div>

<script>
    function abpFormSubmit(event) {
        var e = document.getElementById("abp_author_id");
        var user_id = e.options[e.selectedIndex].value;

        var key = document.getElementById("abp_auth_key");

        if (user_id < 1) {
            alert('You must choose a user');
            event.preventDefault();
        }

        if (key.value.length !== 32) {
            alert('Your key is not correct');
            event.preventDefault();
        }
    }

    const abpForm = document.getElementById('abp_form_settings');
    abpForm.addEventListener('submit', abpFormSubmit);
</script>
