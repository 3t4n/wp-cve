<style type="text/css">
	.wtbpDeactivateDescShell {
		display: none;
		margin-left: 25px;
		margin-top: 5px;
	}
	.wtbpDeactivateReasonShell {
		display: block;
		margin-bottom: 10px;
	}
	#wtbpDeactivateWnd {
		clear: both;
		display: none;
	}
	#wtbpDeactivateWnd input[type="text"],
	#wtbpDeactivateWnd textarea {
		width: 100%;
	}
	#wtbpDeactivateWnd h4 {
		line-height: 1.53em;
	}
	#wtbpDeactivateWnd + .ui-dialog-buttonpane .ui-dialog-buttonset {
		float: none;
	}
	.wtbpDeactivateSkipDataBtn {
		float: right;
		margin-top: 15px;
		text-decoration: none;
		color: #777 !important;
	}
</style>
<div id="wtbpDeactivateWnd" title="<?php echo esc_attr(__('Your Feedback', 'woo-product-tables')); ?>">
	<h4>
	<?php
	/* translators: %s: plugin_name */
	echo esc_html(sprintf(__('If you have a moment, please share why you are deactivating %s', 'woo-product-tables'), WTBP_WP_PLUGIN_NAME));
	?>
	</h4>
	<form id="wtbpDeactivateForm">
		<label class="wtbpDeactivateReasonShell">
			<?php 
				HtmlWtbp::radiobutton('deactivate_reason', array(
				'value' => 'not_working',
				));
				?>
			<?php esc_html_e('Couldn\'t get the plugin to work', 'woo-product-tables'); ?>
			<div class="wtbpDeactivateDescShell">
				<?php 
				/* translators: %s: url */
				echo sprintf(esc_html__('If you have a question, %s and will do our best to help you', 'woo-product-tables'), '<a href="https://woobewoo.com/contact-us/" target="_blank">' . esc_html__('contact us', 'woo-product-tables') . '</a>'); 
				?>
			</div>
		</label>
		<label class="wtbpDeactivateReasonShell">
			<?php 
				HtmlWtbp::radiobutton('deactivate_reason', array(
				'value' => 'found_better',
				));
				?>
			<?php esc_html_e('I found a better plugin', 'woo-product-tables'); ?>
			<div class="wtbpDeactivateDescShell">
				<?php 
					HtmlWtbp::text('better_plugin', array(
					'placeholder' => __('If it\'s possible, specify plugin name', 'woo-product-tables'),
					));
					?>
			</div>
		</label>
		<label class="wtbpDeactivateReasonShell">
			<?php 
				HtmlWtbp::radiobutton('deactivate_reason', array(
				'value' => 'not_need',
				));
				?>
			<?php esc_html_e('I no longer need the plugin', 'woo-product-tables'); ?>
		</label>
		<label class="wtbpDeactivateReasonShell">
			<?php 
				HtmlWtbp::radiobutton('deactivate_reason', array(
				'value' => 'temporary',
				));
				?>
			<?php esc_html_e('It\'s a temporary deactivation', 'woo-product-tables'); ?>
		</label>
		<label class="wtbpDeactivateReasonShell">
			<?php 
				HtmlWtbp::radiobutton('deactivate_reason', array(
				'value' => 'other',
				));
				?>
			<?php esc_html_e('Other', 'woo-product-tables'); ?>
			<div class="wtbpDeactivateDescShell">
				<?php 
					HtmlWtbp::text('other', array(
					'placeholder' => __('What is the reason?', 'woo-product-tables'),
					));
					?>
			</div>
		</label>
		<?php HtmlWtbp::hidden('mod', array('value' => 'promo')); ?>
		<?php HtmlWtbp::hidden('action', array('value' => 'saveDeactivateData')); ?>
	</form>
	<a href="" class="wtbpDeactivateSkipDataBtn"><?php esc_html_e('Skip & Deactivate', 'woo-product-tables'); ?></a>
</div>
