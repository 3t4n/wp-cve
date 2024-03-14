<?php defined( 'ABSPATH' ) or die; ?>
<?php
	$location = ( int ) $this->get_option( 'location' );
?>
<style type="text/css">
	.flowcharts-ai-wrap, .flowcharts-ai-wrap * {box-sizing: border-box}
	.flowcharts-ai-wrap h1 {font-size:16px}
	.flowcharts-ai-wrap h1 a {font-size:10px !important; display: inline !important; min-height: auto !important; padding: 4px !important; font-weight: bold !important}
	.flowcharts-ai-wrap ul.disc-bullet {list-style-type: disc; margin-left:20px}
	#flowcharts-metabox-1 h2 {margin-left: 20px}
	.flowcharts-ai-wrap .boxed {border: 1px solid #333; padding: 3px; white-space: nowrap}
	.flowcharts-ai-wrap textarea {width:100%}
	.flowcharts-ai-wrap input[type="radio"]:not(:first-child) {margin-left: 10px}
	.flowcharts-ai-wrap .postbox .m-left {padding: 0px 20px !important}
	.flowcharts-ai-wrap #flowcharts-metabox-2 h2, .flowcharts-ai-wrap #flowcharts-metabox-3 h2 {padding: 0px 20px !important; justify-content: normal}
	.flowcharts-ai-wrap #flowcharts-metabox-2 .inside, .flowcharts-ai-wrap #flowcharts-metabox-3 .inside {padding: 0px 20px !important}
	.flowcharts-ai-wrap form {display: flex}
	.flowcharts-ai-wrap .wrap-left {flex: 80; width: auto}
	.flowcharts-ai-wrap .wrap-right {flex: 20; width: auto; padding-left:3%}
	.flowcharts-ai-wrap .wrap-right .handle-actions {display: none}
	.flowcharts-ai-wrap .wrap-right a {margin-top:7px}
	.flowcharts-ai-wrap .shortcode-def {display:none}
	.flowcharts-ai-wrap .ib-label {display: inline-block; margin-bottom: 7px}
	.flowcharts-ai-wrap .heart {display: inline-block; width:20px; height: 20px; background: url(<?php esc_attr_e( FLOWCHARTS_AI_IMG_URL . 'heart.gif' ); ?>); background-size: 100% 100%; margin-left: 3px}
	.flowcharts-ai-wrap .btn-green, .flowcharts-ai-wrap .btn-green:hover, .flowcharts-ai-wrap .btn-green:active, .flowcharts-ai-wrap .btn-green:visited {background-color: #218946 !important; color: #fff}
	.flowcharts-ai-wrap ol li {line-height: 200%}
	@media all and (max-width: 760px) {
		.flowcharts-ai-wrap form {display: block}
		.flowcharts-ai-wrap .wrap-left, .flowcharts-ai-wrap .wrap-right{width: 100%; margin-left: 0}
	}
</style>
<div class="wrap flowcharts-ai-wrap">
<img src="<?php esc_attr_e( FLOWCHARTS_AI_IMG_URL . 'logo.png' ); ?>" />
<?php settings_errors(); ?>
	<h1><?php _e( 'FlowCharts.ai - Website Chat Bot & Widget for Forms, Surveys, Decision Trees, Questionnaires, Workflow, Support.', 'flowcharts-ai' ); ?> <a href="https://app.flowcharts.ai/signup" class="button button-secondary" target="_new"><?php _e( 'Get Started', 'flowcharts-ai' ); ?></a> <a href="https://www.youtube.com/watch?v=RukQxIl7DIQ" class="button button-secondary" target="_new"><?php _e( 'Watch tutorial', 'flowcharts-ai' ); ?></a></h1>
	<form method="post" action="options.php">
		<?php settings_fields( $this->optsgroup_name ); ?>
		<?php do_settings_sections( $this->optsgroup_name ); ?>
		<div class="wrap-left">
			<div>
				<?php do_meta_boxes( $this->pagehook . '-1', 'normal', $data ); ?>
			</div>
			<div class="postbox">
				<div class="m-left">
					<h3><?php _e( 'Instructions:', 'flowcharts-ai' ); ?></h3>
					<ol>
						<li><?php echo sprintf( __( 'If you are not an existing FlowCharts.ai user %sClick here to register%s', 'flowcharts-ai' ), '<a class="button-secondary btn-green" target="_blank" href="https://app.flowcharts.ai/signup">', '</a>' ); ?></li>
						<li><?php _e( 'Then on FlowCharts.ai, you can effortlessly craft your own forms, surveys, decision trees, questionnaires, workflows perfect for sales/support. Your chatbot smartly guides users to the next query based on their responses.', 'flowcharts-ai' ); ?></li>
						<li><?php _e( 'To activate on your website, simply go to FlowCharts.ai and navigate to SHARE -> WEBSITE WIDGET. Copy the provided code snippet and paste it here. If you would also like the feature of two-way SMS texting with visitors even after they leave your site, just copy and paste the widget code available in your FlowCharts.ai account right here.', 'flowcharts-ai' ); ?></li>
					</ol>
					<h3><?php _e( 'Chatbot snippet:', 'flowcharts-ai' ); ?></h3>
					<textarea rows="7" name="<?php esc_attr_e( $this->options_name . '[snippet]' ); ?>"><?php echo esc_textarea( $this->get_option( 'snippet' ) ); ?></textarea>
					<h3><?php _e( 'Show Above Chatbot On:', 'flowcharts-ai' ); ?></h3>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="0" <?php echo $location == 0 ? 'checked' : ''; ?>> <?php _e( 'All pages, posts and homepage', 'flowcharts-ai' ); ?></span>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="1" <?php echo $location == 1 ? 'checked' : ''; ?>> <?php _e( 'All pages and homepage', 'flowcharts-ai' ); ?></span>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="2" <?php echo $location == 2 ? 'checked' : ''; ?>> <?php _e( 'All posts and homepage', 'flowcharts-ai' ); ?></span>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="3" <?php echo $location == 3 ? 'checked' : ''; ?>> <?php _e( 'Only homepage', 'flowcharts-ai' ); ?></span>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="4" <?php echo $location == 4 ? 'checked' : ''; ?>> <?php _e( 'All pages', 'flowcharts-ai' ); ?></span>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="5" <?php echo $location == 5 ? 'checked' : ''; ?>> <?php _e( 'All posts', 'flowcharts-ai' ); ?></span>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="6" <?php echo $location == 6 ? 'checked' : ''; ?>> <?php _e( 'No pages', 'flowcharts-ai' ); ?></span>
					<span class="ib-label"><input type="radio" name="<?php esc_attr_e( $this->options_name . '[location]' ); ?>" value="7" <?php echo $location == 7 ? 'checked' : ''; ?>> <?php _e( 'Via Shortcode [flowcharts]', 'flowcharts-ai' ); ?></span>
					<?php submit_button(); ?>
				</div>
			</div>
		</div>
		<div class="wrap-right">
			<?php do_meta_boxes( $this->pagehook . '-2', 'normal', $data ); ?>
			<?php do_meta_boxes( $this->pagehook . '-3', 'normal', $data ); ?>
		</div>
	</form>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php esc_attr_e( $this->pagehook ); ?>');
		});
		//]]>
	</script>
</div>