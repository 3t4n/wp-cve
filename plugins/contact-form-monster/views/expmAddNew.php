<?php
	if(!isset($formId)) {
		$formId = 0;
	}
?>
<form method="POST" action="<?php echo admin_url();?>admin-post.php?action=ycf_save_data" id="contact-form-save">
<?php
if (function_exists('wp_nonce_field')) {
	wp_nonce_field('ycf_nonce_check');
}
?>
<?php if(isset($_GET['saved'])) {?>
<div id="default-message" class="updated notice notice-success is-dismissible">
	<p>Form updated.</p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>
<?php }?>
<div class="ycf-wrapper">
	<div class="ycf-header">
		<h3 class="ycf-edit-title">Create Your Form</h3>
        <div class="ycf-submit-wrapper">
            <input type="submit" class="button-primary"  value="<?php echo 'Save Changes'; ?>">
        </div>

	</div>
    <input type="text" name="ycf-form-title" placeholder="Enter title here" class="form-control" value="<?php echo esc_html($formTitle);?>">
	<div class="ycf-left-section">
		<div class="ycf-form-wrapper">
		<?php
			$a = get_option('YcfFormElements');

			$formData = $formBuilderObj->createFormAdminElement();
		?>
			<textarea name="hidden-form-content" style="display: none;"><?php  echo $hiddenInputContent;?></textarea>
		</div>
	</div>
	<div class="">
		<h3>Form Options</h3>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#home">Home</a></li>
			<li><a href="#menu1">Submit Options</a></li>
			<li><a href="#menu2">Design</a></li>
<!--			<li><a href="#menu3">Menu 3</a></li>-->
		</ul>
		<div class="tab-content ycf-tab-content">
			<div id="home" class="tab-pane fade in active">
				<?php
				if(file_exists(YCF_VIEWS.'tab_option/ysfFormFields.php')) {
					require_once(YCF_VIEWS.'tab_option/ysfFormFields.php');
				}
				?>

			</div>
			<div id="menu1" class="tab-pane fade">
                <?php
                    if(file_exists(YCF_VIEWS.'tab_option/ycfFormOptions.php')) {
                        require_once(YCF_VIEWS.'tab_option/ycfFormOptions.php');
                    }
                ?>
			</div>
			<div id="menu2" class="tab-pane fade">
				<?php
				if(file_exists(YCF_VIEWS.'tab_option/ysfFormDesign.php')) {
					require_once(YCF_VIEWS.'tab_option/ysfFormDesign.php');
				}
				?>
			</div>
			<div id="menu3" class="tab-pane fade">
				<h3>Menu 3</h3>
			</div>
		</div>
	</div>
</div>

<input type="hidden" name="ycf-form-id" id="ycf-form-id" value="<?php echo $formId;?>">
</form>