<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class bhpcmn_admin {
    private $opt=null;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'chat_me_now__add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'chat_me_now__page_init' ) );
	}

	public function chat_me_now__add_plugin_page() {
		add_menu_page(
			'Chat Me Now!', // page_title
			'Chat Me Now!', // menu_title
			'manage_options', // capability
			'chat-me-now', // menu_slug
			array( $this, 'chat_me_now__create_admin_page' ), // function
			BH_PLGN_CMN_URL . 'assets/img/wmn-icon.png'
			//2 // position
		);
	}

	public function chat_me_now__create_admin_page() {
		$this->opt = get_option( 'chat_me_now__option_name' ); ?>

		<div class="wrap">
			<h2>Chat Me Now!</h2>
			<p>This plugins add a floating button to allow the user send a whats-app message to you directly, if you have any question or need some improvement please contact me <a href="mailto:dfortiz@gmail.com" target="_blank">dfortiz@gmail.com</a></p>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'chat_me_now__option_group' );
					do_settings_sections( 'chat-me-now-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function chat_me_now__page_init() {
		register_setting(
			'chat_me_now__option_group', // option_group
			'chat_me_now__option_name', // option_name
			array( $this, 'chat_me_now__sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'chat_me_now__setting_section', // id
			'Settings', // title
			array( $this, 'chat_me_now__section_info' ), // callback
			'chat-me-now-admin' // page
		);

		add_settings_field(
			'whatsapp1', // id
			'whatsapp1', // title
			array( $this, 'whatsapp1_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);

		add_settings_field(
			'whatsapp2', // id
			'whatsapp2', // title
			array( $this, 'whatsapp2_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);

		add_settings_field(
			'whatsapp_active_turn', // id
			'whatsapp-active-turn', // title
			array( $this, 'whatsapp_active_turn_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);

		add_settings_field(
			'schedule_turn', // id
			'schedule-turn', // title
			array( $this, 'schedule_turn_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);

		add_settings_field(
			'icon_color', // id
			'icon-color', // title
			array( $this, 'icon_color_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);

		add_settings_field(
			'background_color', // id
			'background-color', // title
			array( $this, 'background_color_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);

		add_settings_field(
			'start_message', // id
			'message', // title
			array( $this, 'start_message_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);

		add_settings_field(
			'active', // id
			'active', // title
			array( $this, 'active_callback' ), // callback
			'chat-me-now-admin', // page
			'chat_me_now__setting_section' // section
		);
	}

	public function chat_me_now__sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['whatsapp1'] ) ) {
			$sanitary_values['whatsapp1'] = sanitize_text_field( $input['whatsapp1'] );
		}

		if ( isset( $input['whatsapp2'] ) ) {
			$sanitary_values['whatsapp2'] = sanitize_text_field( $input['whatsapp2'] );
		}

		if ( isset( $input['whatsapp_active_turn'] ) ) {
			$sanitary_values['whatsapp_active_turn'] = $input['whatsapp_active_turn'];
		}

		if ( isset( $input['icon_color'] ) ) {
			$sanitary_values['icon_color'] = $input['icon_color'];
		}

		if ( isset( $input['background_color'] ) ) {
			$sanitary_values['background_color'] = $input['background_color'];
		}

		if ( isset( $input['schedule_turn'] ) ) {
			$sanitary_values['schedule_turn'] = $input['schedule_turn'];
		}

		if ( isset( $input['start_message'] ) ) {
			$sanitary_values['start_message'] = $input['start_message'];
		}

		if ( isset( $input['active'] ) ) {
			$sanitary_values['active'] = $input['active'];
		}

		return $sanitary_values;
	}

	public function chat_me_now__section_info() {
		
	}

	public function whatsapp1_callback() {
		printf(
			'<input class="regular-text" type="text" name="chat_me_now__option_name[whatsapp1]" id="whatsapp1" value="%s">',
			isset( $this->opt['whatsapp1'] ) ? esc_attr( $this->opt['whatsapp1']) : ''
		);
	}

	public function whatsapp2_callback() {
		printf(
			'<input class="regular-text" type="text" name="chat_me_now__option_name[whatsapp2]" id="whatsapp2" value="%s">',
			isset( $this->opt['whatsapp2'] ) ? esc_attr( $this->opt['whatsapp2']) : ''
		);
	}

	public function whatsapp_active_turn_callback() {
		?> <select name="chat_me_now__option_name[whatsapp_active_turn]" id="whatsapp_active_turn">
			<?php $selected = (isset( $this->opt['whatsapp_active_turn'] ) && $this->opt['whatsapp_active_turn'] === 'whatsapp1') ? 'selected' : '' ; ?>
			<option value="whatsapp1" <?php echo $selected; ?>>whatsapp1</option>
			<?php $selected = (isset( $this->opt['whatsapp_active_turn'] ) && $this->opt['whatsapp_active_turn'] === 'whatsapp2') ? 'selected' : '' ; ?>
			<option value="whatsapp2" <?php echo $selected; ?>>whatsapp2</option>
			<?php $selected = (isset( $this->opt['whatsapp_active_turn'] ) && $this->opt['whatsapp_active_turn'] === 'scheduled') ? 'selected' : '' ; ?>
			<option value="scheduled" <?php echo $selected; ?>>scheduled</option>
		</select> <?php
	}

	public function icon_color_callback() {
		?> <select name="chat_me_now__option_name[icon_color]" id="icon_color">
			<?php $selected = (isset( $this->opt['icon_color'] ) && $this->opt['icon_color'] === '#ffffff') ? 'selected' : '' ; ?>
			<option value="#4fce50" <?php echo $selected; ?>>green</option>
			<?php $selected = (isset( $this->opt['icon_color'] ) && $this->opt['icon_color'] === '#000000') ? 'selected' : '' ; ?>
			<option value="#000000" <?php echo $selected; ?>>black</option>
			<?php $selected = (isset( $this->opt['icon_color'] ) && $this->opt['icon_color'] === '#ffffff') ? 'selected' : '' ; ?>
			<option value="#ffffff" <?php echo $selected; ?>>white</option>
		</select> <?php
	}

	public function background_color_callback() {
		?> <select name="chat_me_now__option_name[background_color]" id="background_color">
			<?php $selected = (isset( $this->opt['background_color'] ) && $this->opt['background_color'] === '#000000') ? 'selected' : '' ; ?>
			<option value="#000000" <?php echo $selected; ?>>black</option>
			<?php $selected = (isset( $this->opt['background_color'] ) && $this->opt['background_color'] === '#ffffff') ? 'selected' : '' ; ?>
			<option value="#ffffff" <?php echo $selected; ?>>white</option>
		</select> <?php
	}

	public function schedule_turn_callback() {
		?> <select name="chat_me_now__option_name[schedule_turn]" id="schedule_turn">
			<?php $selected = (isset( $this->opt['schedule_turn'] ) && $this->opt['schedule_turn'] === 'whatsapp1|09|17') ? 'selected' : '' ; ?>
			<option value="whatsapp1|09:00|16:59|whatsapp2|17:00|08:59" <?php echo $selected; ?>>whatsapp1 (09:00-16:59) whatsapp2 (17:00-08:59)</option>
			<?php $selected = (isset( $this->opt['schedule_turn'] ) && $this->opt['schedule_turn'] === 'whatsapp2|17|09') ? 'selected' : '' ; ?>
			<option value="whatsapp1|09:00|20:59|whatsapp2|21:00|08:59" <?php echo $selected; ?>>whatsapp1 (09:00-20:59) whatsapp2 (21:00-08:59)</option>
		</select> <?php
	}

	public function start_message_callback() {
		printf(
			'<input class="regular-text" placeholder="Hello I need more information about @site" type="text" name="chat_me_now__option_name[start_message]" id="start_message" value="%s">
			<br/><span>You can use only this tags:
			<br/> @site (site url)
			</span>',
			isset( $this->opt['start_message'] ) ? esc_attr( $this->opt['start_message']) : ''
		);
	}

	public function active_callback() {
		printf(
			'<input type="checkbox" name="chat_me_now__option_name[active]" id="active" value="active" %s> <label for="active">If true the button is visible</label>',
			( isset( $this->opt['active'] ) && $this->opt['active'] === 'active' ) ? 'checked' : ''
		);
	}

}

