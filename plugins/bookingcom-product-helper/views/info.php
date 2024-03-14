<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="right-column info-column">

	<h2 class="info-column__header">
		<?php
		echo esc_html__(
			'How to use the Booking.com Product Helper',
			'bookingcom-product-helper'
		);
		?>
	</h2>

	<!-- <div class="info-column__video">
		<iframe
			width="420"
			height="235"
			src="https://www.youtube.com/embed/_jLx_Z8mV2g?controls=0"
			frameborder="0"
			allow="autoplay; encrypted-media"
			allowfullscreen>
		</iframe>
	</div> -->

	<div class="description">
		<ol class="description-list">
			<li class="description-list__item">
				<h3 class="list-item__header">
					<span class="list-item__header-step">
						<?php
						echo esc_html__(
							'STEP 1',
							'bookingcom-product-helper'
						);
						?>
					</span>
					<?php
					echo esc_html__(
						'Get your product embed code',
						'bookingcom-product-helper'
					);
					?>
				</h3>
				<p class="list-item__text">
					<?php
					echo sprintf(

						// translators: %1$s: Link on affiliate partner centre; %2$s: Link on sign up form in Partner affiliate centre.
						esc_html__(
							'Copy the embed code of the product you want to add (eg Map Widget, Search Box, etc) from the Booking.com %1$s. If you are not an affiliate partner yet, %2$s to sign up for the Affiliate Partner Programme.',
							'bookingcom-product-helper'
						),
						'<a href="https://spadmin.booking.com/partner/login.html" target="_blank" class="list-item__text--link">Affiliate Partner Centre</a>',
						'<a href="https://www.booking.com/affiliate-program/v2/index.html" target="_blank" class="list-item__text--link">Affiliate Partner Programme</a>'
					);
					?>
				</p>
				<p class="list-item__secondary-text">
					<a href="https://spadmin.booking.com/partner/login.html" target="_blank" class="list-item__text--link">
						<?php
						echo esc_html__(
							'Visit Affiliate Partner Centre',
							'bookingcom-product-helper'
						);
						?>
					</a>
				</p>
			</li>
			<li class="description-list__item">
				<h3 class="list-item__header">
					<span class="list-item__header-step">
						<?php
						echo esc_html__(
							'STEP 2',
							'bookingcom-product-helper'
						);
						?>
					</span>
					<?php
					echo esc_html__(
						'Create a shortcode',
						'bookingcom-product-helper'
					);
					?>
				</h3>
				<p class="list-item__text">
					<?php
					echo esc_html__(
						'Click on the "new product shortcode" button, give it a name and paste the embed code into the "product code" field. Click on "create product shortcode" to create the shortcode.',
						'bookingcom-product-helper'
					);
					?>
				</p>
			</li>
			<li class="description-list__item">
				<h3 class="list-item__header">
					<span class="list-item__header-step">
						<?php
						echo esc_html__(
							'STEP 3',
							'bookingcom-product-helper'
						);
						?>
					</span>
					<?php
					echo esc_html__(
						'Add your shortcode',
						'bookingcom-product-helper'
					);
					?>
				</h3>
				<p class="list-item__text">
					<?php
					echo sprintf(

						// translators: %s: Code example.
						esc_html__(
							'Copy the shortcode you generated eg %s and add it to the desired part of your WordPress website (page, post or sidebar).',
							'bookingcom-product-helper'
						),
						'<span class="code-example--orange">' .
						esc_html( '[booking_product_helper shortname="<the_shortname/>"]' ) .
						'</span>'
					);
					?>
				</p>
				<p class="list-item__secondary-text">
					<?php
					echo esc_html__(
						'Start Earning!',
						'bookingcom-product-helper'
					);
					?>
				</p>
			</li>
		</ol>
	</div> <!-- .description (end) -->
</div> <!-- .right-column (end) -->
