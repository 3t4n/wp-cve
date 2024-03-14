<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    NL_Zalo_Official_Chat
 * @subpackage NL_Zalo_Official_Chat/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php 

	if(isset($_POST["submit"])){			
		$zoa_id = sanitize_key($_POST["zalo_oa_id"]);
		$this->data['zalo_oa_id'] = isset($zoa_id) ? $zoa_id : '';

		$zoa_mess = sanitize_text_field($_POST["zalo_hello_message"]);
		$this->data['zalo_hello_message'] = isset($zoa_mess) ? $zoa_mess : "Rất vui khi được hỗ trợ bạn!";
		
		$zoa_time = sanitize_text_field($_POST["zalo_popup_time"]);
		if(isset($zoa_time) && is_numeric($zoa_time)){
			$this->data['zalo_popup_time'] = $zoa_time;	
		} else {
			$this->data['zalo_popup_time'] = 0;
		}

		$zoa_height = sanitize_text_field($_POST["zalo_data_height"]);
		if(isset($zoa_height) && is_numeric($zoa_height)){
			$this->data['zalo_data_height'] = $zoa_height;	
		} else {
			$this->data['zalo_data_height'] = 420;
		}

		$zoa_width = sanitize_text_field($_POST["zalo_data_width"]);
		if(isset($zoa_width) && is_numeric($zoa_width)){
			$this->data['zalo_data_width'] = $zoa_width;	
		} else {
			$this->data['zalo_data_width'] = 350;
		}
		
		$this->save_setting();
		echo '<div class="notify-status">Đã cập nhật!</div>';
	}
?>

<div class="wrapper">
	<h1><?php esc_html_e( 'Cấu Hình Hộp Chat Zalo', 'zalooachat' ); ?></h1>
	<div>
		<div class="setting-form">
			<form method="POST">
				<div class="zoa-row">
					<div class="col-3">
						<label class="label">Zalo Official Account ID </label>
						<input type="text" name="zalo_oa_id" value="<?php echo esc_html($this->data['zalo_oa_id']); ?>">
						<a href="https://oa.zalo.me/home/blog/huong-dan-dang-ky-tai-khoan-zalo-official-account-doanh-nghiep-article61" id="zalo-help-btn" target="_blank" class="btn btn-primary" style="padding: 4px;border: 1px solid #584bf5;border-radius: 5px;border-color: #584bf5;cursor: pointer;"><i class="dashicons dashicons-editor-help" style="color: #584bf5;"></i></a> 
					</div>
				</div>
				<div class="zoa-row">
					<div class="col-3">
						<label class="label">Câu chào</label>
						<input type="text" name="zalo_hello_message" value="<?php echo esc_html($this->data['zalo_hello_message']); ?>">
					</div>
				</div>
				<div class="zoa-row">
					<div class="col-3">
						<label class="label">Thời gian hiển thị cửa sổ chat (giây)</label>
						<input type="number" name="zalo_popup_time" min="0" value="<?php echo esc_html($this->data['zalo_popup_time']); ?>">
					</div>
				</div>
				<div class="zoa-row">
					<div class="col-3">
						<label class="label">Width (chiều rộng)</label>
						<input type="number" name="zalo_data_width" min="0" placeholder="Min 300 to max 500" value="<?php echo esc_html($this->data['zalo_data_width']); ?>">
					</div>
				</div>
				<div class="zoa-row">
					<div class="col-3">
						<label class="label">Height (chiều cao)</label>
						<input type="number" name="zalo_data_height" min="0" placeholder="Min 300 to max 500" value="<?php echo esc_html($this->data['zalo_data_height']); ?>">
					</div>
				</div>
				<div class="zoa-row">
					<div class="col-3">
						<input type="submit" class="button action" name="submit" value="Cập Nhật" style="float: right">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>