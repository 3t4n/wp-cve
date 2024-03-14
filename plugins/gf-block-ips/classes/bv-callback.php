<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class BV_Callback {

	public function __construct() {}

	public function bv_gravity_ip_bulk_import_menu_callback() {

		if ( isset( $_POST['ips'] ) ) {
			bv_gravity_ip_process_bulk_import( $_POST['ips'],$_POST['bv_bulk_ip_nonce'] );
        
            ?>
   
            <div class="notice notice-success is-dismissible">
                <p>Ips imported successfuly!</p>
            </div><?php
}
		?><form method="post">
            <div class="wrap">
                <h1>Bulk IP Import</h1>
                <p>Please paste your ip lists, one ip per line, and click "import" to bulk add them.</p>
                <p>
                    <label for="tag-description">Description</label> <br/>
                    <?php wp_nonce_field( 'bv-bulk-ip-import', 'bv_bulk_ip_nonce' );?>
                    <textarea name="ips" id="tag-description" cols="50" rows="10"></textarea><br/>
                    <input id="gravity_ips_send" class="button button-large button-primary" tabindex="4" value="Import" name="gravity_ips_send" type="submit">

                </p>
            </div>
        </form><?php
}

	/**
	 * @param $post
	 */
	public function bv_gravity_ip_custom_box_html( $post ) {

		$ip = get_post_meta( $post->ID, '_gravity_ips_ip', true );?>
        <label for="wporg_field">Add an ip to block : </label>
        <input type="text" style="margin: auto;" class="postbox" name="gravity_ips_ip" id="gravity_ips_ip" value="<?=$ip;?>" /><?php
}
}