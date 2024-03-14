<?php
/**
 * @var $url_exist bool
 * @var $options array
 * @var $data array
 */
?>

<div class="wrap getsitecontrol getsitecontrol-view-loading" data-manage="">
	<div class="block-login-form">
		<section class="sign-up-form ">
			<h1 class="manage__title">Manage widgets</h1>
			<p class="manage__text">Open your Getsitecontrol dashboard to create and edit widgets for your website.</p>
			<div class="form-contents">
				<div class="select-website-block">
					<select id="widget" name="gsc_widget" class="select-widget selected-toggled-block form-control" disabled="" required></select>
				</div>

				<div class="gotodashboard-block">
					<a href="javascript:void(0);"
					   class="gotodashboard-block button-submit disabled manage-widget-link manage__button-text" target="_blank">
						Go to Dashboard →
				</a>
				</div>

			</div>
		</section>
	</div>
</div>


<script>
	var GSC_OPTIONS = <?php echo wp_json_encode( $options ); ?>;
</script>
