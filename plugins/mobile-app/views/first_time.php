<?php
$demo_url = 'https://www.mobiloud.com/demo/?utm_source=' . CanvasAdmin::$utm_source . '&utm_medium=admin-first-time';
?>
<div id="ml_question2" style="display:none;">
	<div class="ml_question_content">
		<p>MobiLoud is a platform and service to have a mobile app built and published for you by our team.</p>

		<p>Request a 30 minutes demo to speak with one of us about your plans for your app. We'll be happy to point you in the right direction, even if it's toward a competitor.</p>

		<div class="ml-right">
			<div class='ml-col-row ml-init-button'>
				<button type="button" name="request_close" class="welcome_question_close button button-hero button-primary button-grey">Close</button>
				<button type="button" name="request_demo" class="welcome_question_demo button button-hero button-primary"
					data-href="<?php echo esc_attr( $demo_url ); ?>">Request a demo</button>

			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$(document).on('click', '.welcome_question_close', function() {
			swal.close();
		})
		$(document).on('click', '.welcome_question_demo', function() {
			window.location = $(this).data('href');
		})
		var wrapper = document.createElement('div');
		wrapper.innerHTML = document.getElementById('ml_question2').innerHTML;

		swal({
			title: "Request a demo",
			content: wrapper,
			html: true,
			buttons: {
			}
		})
	});
</script>
