<?php

/**
 * Class Fontiran_Upload_Page.
 */
class Fontiran_Upload_Page extends WP_Fontiran_Admin_Page
{

	protected $max_file_size = 1.00;
	protected $font = array();
	private $upload_report = array();
	private $post = null;
	private $file = null;
	private $messages = array();


	public function on_load()
	{

		$this->messages = array(
			'general_fail' => 'یک چیزی اشتباه است. لطفا دوباره تلاش کنید.',
			'File_notZip' => 'لطفا فایل خود را بگونه فشرده و با پسوند zip بارگذاری کنید.',
			'has_font' => 'این فونت موجود است.',
			'zip_error' => 'مشکلی برای استخراج فایل فشرده پیش آمده است',
			'scan_success' => 'فایل ها استخراج شدند.',
			'scan_failed' => 'فایل ها فونت را در مسیر نصب شده پیدا نکردیم، لطفا فایل خود را بررسی کنید.',
			'font_installed' => 'فونت به درستی نصب شد.',
		);

		$this->check_change_data();
		$this->send_notices();
	}



	protected function render_inner_content()
	{
		$this->view($this->slug . '-page');
	}

	protected function set_notices($ms = array())
	{
		$i = count($this->upload_report);
		return $this->upload_report[$i] = $ms;
	}

	protected function send_notices()
	{
		return $this->set_all_notices($this->upload_report);
	}

	// Set target information
	protected function set_information()
	{
		if (!is_array($_POST["fn"]))
			return;
		$this->font['active'] = '1';
		$this->font['name'] = (!empty(sanitize_text_field($_POST["fn"]['font_name']))) ? wp_strip_all_tags($_POST["fn"]['font_name']) : null;
		$this->font['weight'] = (!empty(sanitize_text_field($_POST["fn"]['font_weight']))) ? $this->fontWeight($_POST["fn"]['font_weight']) : 'normal';
		$this->font['style'] = (!empty(sanitize_text_field($_POST["fn"]['font_style']))) ? $this->fontStyle($_POST["fn"]['font_style']) : 'normal';
	}

	// check weight
	private function fontWeight($w = null)
	{

		$ex = array('100', '200', '300', '400', '500', '600', '700', '800', '900', 'bold');
		if (in_array($w, $ex))
			return $w;

		return 'normal';
	}


	// check style
	private function fontStyle($s = null)
	{

		$ex = array('normal', 'italic', 'oblique');
		if (in_array($s, $ex))
			return $s;

		return 'normal';
	}


	public function check_change_data()
	{

		if (!isset($_POST['fi_ul_font']) || !isset($_POST['fn']))
			return;


		if (!isset($_POST['fiwp_nonce']) || !wp_verify_nonce($_POST['fiwp_nonce'], 'fiwp')) {
			return $this->set_notices(array('type' => 'error', 'ms' => 'یک چیزی درست نیست!'));
		}
		$fileInfo = wp_check_filetype(basename($_FILES["package_file"]['name']));
		if ($fileInfo['ext'] == "zip") {
			$this->file = $_FILES["package_file"];
		} else {
			return $this->set_notices(array('type' => 'error', 'ms' => $this->messages['File_notZip']));
		}

		// set information
		$this->set_information();

		if (!$this->font['name'] || empty($this->font['name'])) {
			return $this->set_notices(array('type' => 'error', 'ms' => 'لطفا یک نام فونت وارد کنید.'));
		}

		if(!isset($this->errors)) $this->errors = null;
		if ($this->errors == null && ($this->bytes_to_mb($this->file['size']) < $this->max_file_size)) {


			// get all fonts
			$font_list = $this->fonts;
			if (empty($font_list)) $font_list = array();

			// check font name and font weight in database
			if ($this->has_font()) {
				$this->set_notices(array('type' => 'error', 'ms' => $this->messages['has_font']));
			} else {
				$this->extracto();
				array_push($font_list, $this->font);
				ksort($font_list);
				$font_list = array_values($font_list);
				update_option('fontiran', $font_list);
				$this->fonts = $font_list;
				fi_create_css();
				$this->set_notices(array('type' => 'success', 'ms' => $this->messages['font_installed']));
			}
		} else {
			$this->set_notices(array('type' => 'error', 'ms' => $this->messages['general_fail']));
		}
	}


	// Extract File to /fonts
	public function extracto()
	{

		$tmp =  $this->file['tmp_name'];
		$dir = FIRAN_DATA . 'fonts/' . $this->font['name'];

		$zip = new ZipArchive;
		if ($zip->open($tmp) === TRUE) {

			// get file name & extension file
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$file_name[$i] =  $zip->getNameIndex($i);

				$font_info[$i] = pathinfo($file_name[$i]);
				$font_ext[$i] = (array_key_exists('extension', $font_info[$i])) ? $font_info[$i]['extension'] : '';

				$ext = array('ttf', 'eot', 'woff', 'woff2', 'svg');
				if (in_array($font_ext[$i], $ext)) {
					$this->font['files'][$font_ext[$i]] = $file_name[$i];
					//$this->font[$font_ext[$i]] = basename( $file_name[$i] );
				}
			}

			$i = 0;
			foreach ($this->font['files'] as $file) {
				$tt[$i] = $file;
				$i++;
			}

			$zip->extractTo($dir, $tt);
			$zip->close();

			// scan fonts folder for file exists
			//$this->scanFiles();
			$this->set_notices(array('type' => 'success', 'ms' => $this->messages['scan_success']));
		} else {
			$this->set_notices(array('type' => 'error', 'ms' => $this->messages['zip_error']));
		}

		//return $files_name;
	}


	protected function has_font()
	{
		if (isset($this->fonts[0])) {
			foreach ($this->fonts as $key => $font_pack) {
				if ($font_pack['name'] == strtolower($this->font['name']) && $font_pack['weight'] == $this->font["weight"])
					return true;
			}
		}
	}

	protected function bytes_to_mb($bytes)
	{
		return round(($bytes / 1048576), 2);
	}
}
