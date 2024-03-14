<?php
/*
Settings page
Plugin: Recent Posts Widget Advanced
Since: 1.0.1
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class kgmarp {
	private $kgmarp_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'kgmarp_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'kgmarp_page_init' ) );
	}

	public function kgmarp_add_plugin_page() {
		add_management_page(
			'Recent Post Widget', 
			'Recent Post Widget',  
			'manage_options', 
			'kgmarp', 
			array( $this, 'kgmarp_create_admin_page' )
		);
	}

	public function kgmarp_create_admin_page() {
		$this->kgmarp_options = get_option( 'kgmarp_option_name' ); ?>

		<div class="wrap">
			<h2>Recent Post Widget Advanced</h2>
			<?php settings_errors(); ?>
			
			<form method="post" action="options.php">
				<?php
					settings_fields( 'kgmarp_option_group' );
					do_settings_sections( 'kgmarp-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function kgmarp_page_init() {
		register_setting(
			'kgmarp_option_group',
			'kgmarp_option_name', 
			array( $this, 'kgmarp_sanitize' ) 
		);

		add_settings_section(
			'kgmarp_setting_section', 
			'Settings', 
			array( $this, 'kgmarp_section_info' ),
			'kgmarp-admin' 
		);

		add_settings_field(
			'arp_id',
			'Disable column ID for post, page, taxonomy and author.', 
			array( $this, 'arp_id_callback' ),
			'kgmarp-admin', 
			'kgmarp_setting_section' 
		);
	}

	public function kgmarp_sanitize( $input ) {
		$sanitary_values = array();
		if ( isset( $input['arp_id'] ) ) {
			$sanitary_values['arp_id'] = $input['arp_id'];
		}

		return $sanitary_values;
	}

	public function kgmarp_section_info() {
		
	}

	public function arp_id_callback() {
		printf(
			'<input type="checkbox" name="kgmarp_option_name[arp_id]" id="arp_id" value="arp_id" %s>',
			( isset( $this->kgmarp_options['arp_id'] ) && $this->kgmarp_options['arp_id'] === 'arp_id' ) ? 'checked' : ''
		);
	}

}
if ( is_admin() )
	$kgmarp = new kgmarp();
