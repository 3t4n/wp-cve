<?php

if ( ! function_exists( 'rafflepress_media_button' ) ) {
	add_action( 'media_buttons', 'rafflepress_media_button', 15 );


	function rafflepress_media_button( $editor_id ) {

		// Provide the ability to conditionally disable the button, so it can be
		// disabled for custom fields or front-end use such as bbPress. We default
		// to only showing within the admin panel.
		if ( ! apply_filters( 'rafflepress_display_media_button', is_admin(), $editor_id ) ) {
			return;
		}

		// Setup the icon - currently using a dashicon.
		$icon = '<span class="wp-media-buttons-icon rafflepress-menu-icon" style="font-size:16px;margin-top:-2px;color:red"><svg width="18" height="18" viewBox="0 0 394 416" xmlns="http://www.w3.org/2000/svg"><path d="M161.294,281.219 C151.445,281.219 143.462,289.202 143.462,299.049 C143.462,308.896 151.445,316.878 161.294,316.878 C171.139,316.878 179.122,308.896 179.122,299.049 C179.122,289.202 171.139,281.219 161.294,281.219 Z M232.979,281.219 C223.132,281.219 215.149,289.202 215.149,299.049 C215.149,308.896 223.132,316.878 232.979,316.878 C242.826,316.878 250.806,308.896 250.806,299.049 C250.806,289.202 242.826,281.219 232.979,281.219 Z M32.608,123.757 C30.714,158.655 31.726,255.445 32.608,292.617 C32.68,295.618 34.565,297.889 37.042,299.527 C58.017,313.458 79.698,326.395 101.835,338.541 C98.77,308.445 98.261,273.714 107.731,252.542 C111.467,244.191 119.577,237.434 130.383,232.272 C111.019,204.919 98.751,172.762 95.699,143.461 C91.243,100.685 159.191,80.829 161.091,113.506 C163.202,149.839 167.026,185.74 173.214,221.056 C180.966,220.166 188.963,219.72 196.962,219.708 C205.077,219.704 213.195,220.154 221.06,221.056 C227.245,185.74 231.071,149.839 233.18,113.506 C235.079,80.829 303.03,100.685 298.574,143.461 C295.523,172.762 283.254,204.919 263.891,232.272 C274.694,237.434 282.806,244.191 286.542,252.542 C295.99,273.665 295.504,308.286 292.458,338.332 C314.469,326.252 336.023,313.381 356.885,299.527 C359.356,297.889 361.245,295.618 361.316,292.617 C362.199,255.445 363.21,158.655 361.316,123.757 C361.008,120.766 359.356,118.487 356.885,116.846 C307.739,84.205 254.723,57.023 201.025,32.736 C199.667,32.123 198.314,31.818 196.962,31.818 C195.61,31.818 194.257,32.123 192.902,32.736 C139.201,57.023 86.185,84.205 37.042,116.846 C34.565,118.487 32.913,120.766 32.608,123.757 Z M1.328,120.554 C2.595,108.178 9.333,97.499 19.644,90.651 C70.294,57.012 124.602,29.116 179.943,4.087 C190.893,-0.864 203.032,-0.864 213.981,4.087 C269.323,29.116 323.628,57.012 374.28,90.651 C384.913,97.713 392.019,109.24 392.712,122.052 C394.273,150.787 393.913,180.541 393.792,209.337 C393.674,237.33 393.416,265.374 392.75,293.359 C392.432,306.785 385.326,318.385 374.28,325.719 C323.628,359.361 269.323,387.262 213.981,412.29 C203.032,417.237 190.893,417.237 179.943,412.29 C124.602,387.262 70.294,359.361 19.644,325.719 C8.596,318.385 1.493,306.785 1.174,293.359 C0.509,265.374 0.248,237.33 0.132,209.337 C0.047,189.407 -0.464,137.991 1.328,120.554 L1.328,120.554 Z" fill="#82878c"/></svg></span>';

		printf(
			'<a href="#" class="button rafflepress-insert-giveaway-button" data-editor="%s" title="%s">%s %s</a>',
			esc_attr( $editor_id ),
			esc_attr__( 'Add Giveaway', 'rafflepress' ),
			$icon,
			__( 'Add Giveaway', 'rafflepress' )
		);

		// If we have made it this far then load the JS.
		wp_enqueue_script( 'rafflepress-editor', RAFFLEPRESS_PLUGIN_URL . 'public/js/admin-editor.js', array( 'jquery' ), RAFFLEPRESS_VERSION, true );

		add_action( 'admin_footer', 'rafflepress_lite_shortcode_modal' );
	}

	function rafflepress_lite_shortcode_modal() {
		?>
<div id="rafflepress-modal-backdrop" style="display: none"></div>
<div id="rafflepress-modal-wrap" style="display: none">
	<form id="rafflepress-modal" tabindex="-1">
		<div id="rafflepress-modal-title">
			<?php esc_html_e( 'Insert Giveaway', 'rafflepress' ); ?>
			<button type="button" id="rafflepress-modal-close"><span
					class="screen-reader-text"><?php esc_html_e( 'Close', 'rafflepress' ); ?></span></button>
		</div>
		<div id="rafflepress-modal-inner">

			<div id="rafflepress-modal-options">
				<?php
					echo '<p id="rafflepress-modal-notice" style="display:none;">';
				printf(
					wp_kses(
							/* translators: %s - RafflePress documentation link. */
						__( 'Heads up! Don\'t forget to test your form. <a href="%s" target="_blank" rel="noopener noreferrer">Check out our complete guide</a>!', 'rafflepress' ),
						array(
							'a' => array(
								'href'   => array(),
								'rel'    => array(),
								'target' => array(),
							),
						)
					),
					'https://rafflepress.com/docs/how-to-properly-test-your-wordpress-giveaways-before-launching-checklist/'
				);
				echo '</p>';
				//$args  = apply_filters('rafflepress_modal_select', array());
				global $wpdb;

				$tablename = $wpdb->prefix . 'rafflepress_giveaways';

				$sql = "SELECT id,name FROM $tablename";

				$sql .= ' WHERE deleted_at is null ORDER BY name asc ';
				//$safe_sql = $wpdb->prepare($sql);
				//var_dump($sql);
				$giveaways = $wpdb->get_results( $sql );
				if ( ! empty( $giveaways ) ) {
					printf( '<p><label for="rafflepress-modal-select-giveaway">%s</label></p>', esc_html__( 'Select a giveaway below to insert', 'rafflepress' ) );
					echo '<select id="rafflepress-modal-select-giveaway">';
					foreach ( $giveaways as $giveaway ) {
						printf( '<option value="%d">%s</option>', $giveaway->id, esc_html( $giveaway->name ) );
					}
					echo '</select><br>';
				} else {
					echo '<p>';
					printf(
						wp_kses(
								/* translators: %s - RafflePress Builder page. */
							__( 'Whoops, you haven\'t created a giveaway yet. Want to <a href="%s">give it a go</a>?', 'rafflepress' ),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						),
						admin_url( 'admin.php?page=rafflepress_lite_add_new' )
					);
					echo '</p>';
				}
				?>
			</div>
		</div>
		<div class="submitbox">
			<div id="rafflepress-modal-cancel">
				<a class="submitdelete deletion" href="#"><?php esc_html_e( 'Cancel', 'rafflepress' ); ?></a>
			</div>
			<?php if ( ! empty( $giveaways ) ) : ?>
			<div id="rafflepress-modal-update">
				<button class="button button-primary"
					id="rafflepress-modal-submit"><?php esc_html_e( 'Add Giveaway', 'rafflepress' ); ?></button>
			</div>
			<?php endif; ?>
		</div>
	</form>
</div>
<style type="text/css">
#rafflepress-modal-wrap {
	display: none;
	background-color: #fff;
	-webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
	box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
	width: 500px;
	height: 285px;
	overflow: hidden;
	margin-left: -250px;
	margin-top: -125px;
	position: fixed;
	top: 50%;
	left: 50%;
	z-index: 100105;
	-webkit-transition: height 0.2s, margin-top 0.2s;
	transition: height 0.2s, margin-top 0.2s;
}

#rafflepress-modal-backdrop {
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	min-height: 360px;
	background: #000;
	opacity: 0.7;
	filter: alpha(opacity=70);
	z-index: 100100;
}

#rafflepress-modal {
	position: relative;
	height: 100%;
}

#rafflepress-modal-title {
	background: #fcfcfc;
	border-bottom: 1px solid #dfdfdf;
	height: 36px;
	font-size: 18px;
	font-weight: 600;
	line-height: 36px;
	padding: 0 36px 0 16px;
	top: 0;
	right: 0;
	left: 0;
}

#rafflepress-modal-close {
	color: #666;
	padding: 0;
	position: absolute;
	top: 0;
	right: 0;
	width: 36px;
	height: 36px;
	text-align: center;
	background: none;
	border: none;
	cursor: pointer;
}

#rafflepress-modal-close:before {
	font: normal 20px/36px 'dashicons';
	vertical-align: top;
	speak: none;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	width: 36px;
	height: 36px;
	content: '\f158';
}

#rafflepress-modal-close:hover,
#rafflepress-modal-close:focus {
	color: #2ea2cc;
}

#rafflepress-modal-close:focus {
	outline: none;
	-webkit-box-shadow: 0 0 0 1px #5b9dd9,
		0 0 2px 1px rgba(30, 140, 190, .8);
	box-shadow: 0 0 0 1px #5b9dd9,
		0 0 2px 1px rgba(30, 140, 190, .8);
}

#rafflepress-modal-inner {
	padding: 0 16px 50px;
}

#rafflepress-modal-search-toggle:after {
	display: inline-block;
	font: normal 20px/1 'dashicons';
	vertical-align: top;
	speak: none;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	content: '\f140';
}

#rafflepress-modal-notice {
	background-color: #d9edf7;
	border: 1px solid #bce8f1;
	color: #31708f;
	padding: 10px;
}

#rafflepress-modal #rafflepress-modal-options {
	padding: 8px 0 12px;
}

#rafflepress-modal #rafflepress-modal-options .rafflepress-modal-inline {
	display: inline-block;
	margin: 0;
	padding: 0 20px 0 0;
}

#rafflepress-modal-select-giveaway {
	margin-bottom: 1em;
	max-width: 100%;
}

#rafflepress-modal .submitbox {
	padding: 8px 16px;
	background: #fcfcfc;
	border-top: 1px solid #dfdfdf;
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
}

#rafflepress-modal-cancel {
	line-height: 25px;
	float: left;
}

#rafflepress-modal-update {
	line-height: 23px;
	float: right;
}

#rafflepress-modal-submit {
	float: right;
	margin-bottom: 0;
}

@media screen and (max-width: 782px) {
	#rafflepress-modal-wrap {
		height: 280px;
		margin-top: -140px;
	}

	#rafflepress-modal-inner {
		padding: 0 16px 60px;
	}

	#rafflepress-modal-cancel {
		line-height: 32px;
	}
}

@media screen and (max-width: 520px) {
	#rafflepress-modal-wrap {
		width: auto;
		margin-left: 0;
		left: 10px;
		right: 10px;
		max-width: 500px;
	}
}

@media screen and (max-height: 520px) {
	#rafflepress-modal-wrap {
		-webkit-transition: none;
		transition: none;
	}
}

@media screen and (max-height: 290px) {
	#rafflepress-modal-wrap {
		height: auto;
		margin-top: 0;
		top: 10px;
		bottom: 10px;
	}

	#rafflepress-modal-inner {
		overflow: auto;
		height: -webkit-calc(100% - 92px);
		height: calc(100% - 92px);
		padding-bottom: 2px;
	}
}
</style>
		<?php
	}
}
