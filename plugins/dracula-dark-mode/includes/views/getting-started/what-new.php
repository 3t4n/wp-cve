<?php

defined( 'ABSPATH' ) || exit;

$logs = [
	'v1.2.0' => [
		'date'        => '2023-03-13',
		'new'         => [
			'Added custom dark mode color presets builder.',
			'Added excludes settings for Reading Mode.',
			'Added Exclude Taxonomies settings to exclude posts from dark-mode.',
			'Added move icon for draggable toggle.',
			'Added Reading Mode button label text show/hide option.',
			'Added option to enable/disable the auto-save settings.',
		],
		'fix'         => [
			'Fixed shortcode not rendering in the reading mode content.',
			'Fixed Menu toggle size not working in the free version',
			'Fixed Reading Mode progress bar display issue.'
		],
		'enhancement' => [
			'Improved dark mode algorithm',
            'Improved overall plugin performance & security.',
		],
	],
	'v1.0.9' => [
		'date'        => '2023-01-31',
		'new'         => [
			'Added Reading Mode',
		],
		'fix'         => [
			'Fixed scrollbar dark mode color options not working.',
			'Fixed draggable toggle button not working properly.',
		],
		'enhancement' => [
			'Improved overall plugin performance.',
		],
	],
	'v1.0.8' => [
		'date'        => '2023-12-21',
		'fix'         => [
			'Fixed Disqus comment compatibility issue.',
		],
		'enhancement' => [
			'Improved overall plugin performance.',
		],
	],
	'v1.0.7' => [
		'date' => '2023-12-21',
		'new'  => [
			'Added scrollbar dark mode color customization option.',
		],
		'fix'  => [
			'Fixed Image replacement not working properly.',
		],
	],
	'v1.0.6' => [
		'date' => '2023-12-03',
		'fix'  => [
			'Fixed Gutenberg editor dark mode switch block.',
			'Fixed cache plugin compatibility issue.',
		],
	],
	'v1.0.5' => [
		'date' => '2023-11-22',
		'new'  => [
			'Added link,button and input field dark mode color customization option.',
		],
		'fix'  => [
			'Fixed image replacement issue.',
			'Fix auto match OS theme issue.',
		],
	],
	'v1.0.4' => [
		'date' => '2023-11-12',
		'fix'  => [
			'Fixed excludes elements not working.',
			'Fixed default dark mode issue.',
		],
	],
	'v1.0.3' => [
		'date' => '2023-11-04',
		'new'  => [
			'Added 4 new dark mode toggle button styles.',
			'Added large font-size toggle button.',
		],
		'fix'  => [
			'Fixed performance mode not working properly issue.',
			'Fixed color picker issue.',
		],
	],
	'v1.0.2' => [
		'date'        => '2023-10-05',
		'fix'         => [
			'Fix gutenberg editor dark mode issue.',
			'Fix safari browser dark mode issue.',
		],
		'enhancement' => [
			'Reduced the javascript file size.',
		],
	],
	'v1.0.1' => [
		'date'        => '2023-09-23',
		'new'         => [
			'Added page-specific dark mode settings for Gutenberg, Classic Editor, and Elementor.',
			'Added dark to light mode.',
			'Added 15+ new dark mode color presets.',
			'Added page transition animation.',
			'Added dark mode toggle button Attention Effect Animation.',
			'Added dark mode usage analytics.',
			'Added user feedback for dark mode experience.',
			'Added Gutenberg dark mode toggle switch block.',
			'Added Elementor dark mode toggle switch widget.',
			'Added tooltip for dark mode toggle button.',
		],
		'enhancement' => [
			'Improved overall plugin performance.',
			'Improved toggle switch builder UI.',
		],
		'video'       => '1zjoU7i3H-w',
	],

];


?>

<div id="what-new" class="getting-started-content content-what-new">
    <div class="content-heading">
        <h2><?php esc_html_e( 'What\'s new in the latest changes', 'dracula-dark-mode' ); ?></h2>
        <p><?php esc_html_e( 'Check out the latest change logs.', 'dracula-dark-mode' ); ?></p>
    </div>

	<?php
	$i = 0;
	foreach (
		$logs

		as $v => $log
	) { ?>
        <div class="log <?php echo esc_attr( $i == 0 ? 'active' : '' ); ?>">
            <div class="log-header">
                <span class="log-version"><?php echo esc_html( $v ); ?></span>
                <span class="log-date">(<?php echo esc_html( $log['date'] ); ?>)</span>

                <i class="<?php echo esc_attr( $i == 0 ? 'dashicons-arrow-up-alt2' : 'dashicons-arrow-down-alt2' ); ?> dashicons "></i>
            </div>

            <div class="log-body">
				<?php

				if ( ! empty( $log['new'] ) ) { ?>
                    <div class="log-section new"><h3><?php esc_html_e( 'New Features', 'dracula-dark-mode' ); ?></h3>
						<?php
						foreach ( $log['new'] as $item ) {
							printf( '<div class="log-item log-item-new"><i class="dashicons dashicons-plus-alt2"></i> <span>%s</span></div>', $item );
						}
						?>
                    </div>
					<?php
				}

				if ( ! empty( $log['fix'] ) ) { ?>
                    <div class="log-section fix"><h3><?php esc_html_e( 'Fixes', 'dracula-dark-mode' ); ?></h3>
						<?php
						foreach ( $log['fix'] as $item ) {
							printf( '<div class="log-item log-item-fix"><i class="dashicons dashicons-saved"></i> <span>%s</span></div>', $item );
						}
						?>
                    </div>
				<?php }

				if ( ! empty( $log['enhancement'] ) ) { ?>
                    <div class="log-section enhancement">
                        <h3><?php esc_html_e( 'Enhancements', 'dracula-dark-mode' ); ?></h3>
						<?php
						foreach ( $log['enhancement'] as $item ) {
							printf( '<div class="log-item log-item-enhancement"><i class="dashicons dashicons-star-filled"></i> <span>%s</span></div>', $item );
						}
						?>
                    </div>
				<?php }

				if ( ! empty( $log['remove'] ) ) { ?>
                    <div class="log-section remove"><h3><?php esc_html_e( 'Removes', 'dracula-dark-mode' ); ?></h3>
						<?php
						foreach ( $log['remove'] as $item ) {
							printf( '<div class="log-item log-item-remove"><i class="dashicons dashicons-trash"></i> <span>%s</span></div>', $item );
						}
						?>
                    </div>
				<?php } ?>


				<?php if ( ! empty( $log['video'] ) ) { ?>
                    <div class="log-section video">
                        <h3><?php esc_html_e( 'Video Overview', 'dracula-dark-mode' ); ?></h3>
                        <iframe width="560" height="315"
                                src="https://www.youtube.com/embed/<?php echo esc_attr( $log['video'] ); ?>?si=qh1HTaq7Hitsi2Ld&rel=0"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    </div>
				<?php } ?>


            </div>

        </div>
		<?php
		$i ++;
	} ?>


</div>


<script>
    jQuery(document).ready(function ($) {
        $('.log-header').on('click', function () {
            $(this).next('.log-body').slideToggle();
            $(this).find('i').toggleClass('dashicons-arrow-down-alt2 dashicons-arrow-up-alt2');
            $(this).parent().toggleClass('active');
        });
    });
</script>