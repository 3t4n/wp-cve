<?php
Class YCFContactFormAdminPost {

	public function __construct() {

		$this->actions();
	}

	public function actions() {

		add_action('admin_post_ycf_save_data', array($this, 'ycfSaveData'));
		//add_action('admin_post_delete_readmore', array($this, 'expmDeleteData'));
	}

	public function ycfSanitizeDate($optionName, $textField = false) {

		if(!isset($_POST[$optionName])) {
			return '';
		}

		if($textField) {
			return sanitize_text_field($_POST[$optionName]);
		}

		return sanitize_text_field($_POST[$optionName]);
	}

	public function expmDeleteData() {

		global $wpdb;
		$id = (int)sanitize_text_field($_GET['readMoreId']);
		$wpdb->delete($wpdb->prefix.'ycf_form', array('id'=>$id), array('%d'));
		wp_redirect(admin_url()."admin.php?page=ExpMaker");
	}

	public function ycfSaveData() {
		
		global $wpdb;
		
		check_admin_referer('ycf_nonce_check');



		$options = array(
			'contact-form-send-to-email' => $this->ycfSanitizeDate('contact-form-send-to-email'),
			'contact-form-send-from-email' => $this->ycfSanitizeDate('contact-form-send-from-email'),
			'contact-form-send-email-subject' => $this->ycfSanitizeDate('contact-form-send-email-subject'),
			'ycf-message' => $this->ycfSanitizeDate('ycf-message', true),
			'contact-form-width' => $this->ycfSanitizeDate('contact-form-width'),
			'contact-form-width-measure' => $this->ycfSanitizeDate('contact-form-width-measure'),
		);
		$options = json_encode($options);

		$title = $this->ycfSanitizeDate('ycf-form-title');
		$id = $this->ycfSanitizeDate('ycf-form-id');
		$type = '1';
	
		$data = array(
			'title' => $title,
			'type' => $type,
			'options' => $options
		);

		$format = array(
			'%s',
			'%d',
			'%s',
		);

		$fieldsFormat = array(
			'%d',
			'%s'
		);

		$formFields = json_encode(get_option('YcfFormDraft'));

		if(!$id) {
			$wpdb->insert($wpdb->prefix.'ycf_form', $data, $format);
			$contactId = $wpdb->insert_id;

			$inserToFieldsQuery = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."ycf_fields (form_id, fields_data) VALUES (%d, %s)", $contactId, $formFields);
			$res = $wpdb->query($inserToFieldsQuery);
		}
		else {
			$data['form_id'] = $id;
			$wpdb->update($wpdb->prefix.'ycf_form', $data, array('form_id'=>$id), $format, array('%d'));

			$fieldsUpdateSql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."ycf_fields SET fields_data=%s WHERE form_id=%d",$formFields, $id);
			$wpdb->query($fieldsUpdateSql);
			$contactId = $id;
		}
		
		wp_redirect(admin_url()."admin.php?page=addNewForm&formId=".$contactId."&saved=1");
	}

}

$ycfContactFormObj = new YCFContactFormAdminPost();