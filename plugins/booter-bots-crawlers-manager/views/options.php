<section id="booter-options" class="wrap booter-options">
    <h1 role="presentation" aria-hidden="true"><!-- save notification trap - notification will moved to the first h1/h2 it finds --></h1>
    <header class="options-header">
        <svg width="64" height="64" viewBox="0 0 764.86 529.38" xml:space="preserve">
            <path fill="#fab31c" d="M703.02.28c-5.57 13.06-11.02 25.83-16.47 38.61-9.33 21.89-18.61 43.82-28.06 65.66-1.41 3.25-.86 3.85 2.57 3.84 33.19-.11 66.37-.07 99.56-.04 1.34 0 3.18-.69 3.94.49 1 1.55-.87 2.73-1.62 3.9-63.14 98.69-126.34 197.34-189.54 296-13.6 21.23-27.2 42.47-40.8 63.7-.74 1.15-1.53 2.26-2.93 3.11 2.73-9.58 5.47-19.15 8.2-28.73 26.59-93.27 53.16-186.55 79.84-279.79 1.03-3.61-.22-3.75-3.15-3.74-22.73.09-45.46.05-68.19.05-5.17 0-5.21-.01-3.95-4.76C556.08 106.79 569.77 55 583.41 3.19c.55-2.1 1.26-3.2 3.84-3.19 37.79.1 75.58.07 113.37.08.65 0 1.31.11 2.4.2z"/>
            <path fill="#000000" d="M548.25 206.76c-19.8.23-39.6.02-59.39.18-3.5.03-3.75-.91-2.96-3.98 9.58-37.01 19.02-74.06 28.52-111.09 3.8-14.84 7.69-29.65 7.46-45.16-.32-22.1-10.83-36.12-32.01-42.31-10.63-3.11-21.56-4.31-32.6-4.31C320.35.06 183.44.07 46.52.07h-4.89c.97 1.76 1.59 2.96 2.28 4.13 20.73 35.23 41.44 70.47 62.25 105.65 1.33 2.25 1.54 4.23.87 6.64-1.93 6.96-3.74 13.96-5.56 20.96A2343324.9 2343324.9 0 0114.8 470.38C10 488.81 5.22 507.24.31 525.64c-.78 2.91-.24 4.09 2.88 3.64.82-.12 1.67-.02 2.51-.02h450.06c10.6 0 10.66.01 13.39-10.19 27.47-102.57 54.91-205.16 82.46-307.71.96-3.58.82-4.65-3.36-4.6zM375.13 332.84c-5.58 21.09-11.22 42.17-16.66 63.3-.61 2.38-1.45 3.07-3.85 3.07-57.17-.07-114.33-.07-171.5.01-3.08 0-3.27-.72-2.55-3.51 22.22-85.14 44.34-170.3 66.47-255.46.51-1.98.69-3.77 3.75-3.75 34.58.13 69.16.09 103.73.11.27 0 .55.09 1.24.22-6.17 22.86-12.31 45.37-18.28 67.93-.73 2.77-2.54 2.07-4.19 2.07-17.15.04-34.3.1-51.45-.05-2.77-.02-3.81.85-4.48 3.44-9.81 38.1-19.66 76.19-29.67 114.23-.9 3.41-.14 3.8 3.02 3.79 40.29-.1 80.59-.06 120.88-.06 4.77.01 4.77.01 3.54 4.66z"/>
        </svg>
        <div class="options-title">
            <h1><?php esc_html_e( 'Booter - Bots & Crawlers Manager', 'booter' ); ?></h1>
            <p><?php esc_html_e( 'The easy way to correctly manage crawlers and bots.', 'booter' ); ?></p>
        </div>
    </header>

    <form method="post" action="options.php">
	    <?php settings_fields( BOOTER_SETTINGS_KEY . '_group' ); ?>
	    <?php do_settings_sections( BOOTER_SETTINGS_KEY . '_group' ); ?>
	    <?php $settings = get_option( BOOTER_SETTINGS_KEY ); ?>
        <input type="hidden" name="booter_settings[updated_at]" value="<?php echo time(); ?>">

        <ul class="nav-tab-wrapper" role="tablist">
            <li role="presentation">
                <a href="#booter-general" role="tab" id="booter-general-tab" aria-controls="booter-general" class="nav-tab js-booter-tab nav-tab-active" aria-selected="true">
                    <?php esc_html_e( 'General', 'booter' ); ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-bad-bots" role="tab" id="booter-bad-bots-tab" aria-controls="booter-bad-bots" class="nav-tab js-booter-tab" aria-selected="false">
	                <?php esc_html_e( 'Bad Bots Blocking', 'booter' ); ?>
                    <span class="js-bad-bots-tab badge" hidden><?php esc_html_e( 'Off', 'booter' ); ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-robots" role="tab" id="booter-robots-tab" aria-controls="booter-robots" class="nav-tab js-booter-tab">
                    <?php esc_html_e( 'Robots.txt', 'booter' ); ?>
                    <span class="js-robots-tab badge" hidden><?php esc_html_e( 'Off', 'booter' ); ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-ratelimit" role="tab" id="booter-ratelimit-tab" aria-controls="booter-ratelimit" class="nav-tab js-booter-tab">
                    <?php esc_html_e( 'Rate Limit', 'booter' ); ?>
                    <span class="js-rate-tab badge" hidden><?php esc_html_e( 'Off', 'booter' ); ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-reject" role="tab" id="booter-reject-tab" aria-controls="booter-reject" class="nav-tab js-booter-tab">
                    <?php esc_html_e( 'Reject Links', 'booter' ); ?>
                    <span class="js-block-tab badge" hidden><?php esc_html_e( 'Off', 'booter' ); ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-404log" role="tab" id="booter-404log-tab" aria-controls="booter-404log" class="nav-tab js-booter-tab">
                    <?php esc_html_e( '404 Errors Log', 'booter' ); ?>
                    <span class="js-404-tab badge" hidden><?php esc_html_e( 'Off', 'booter' ); ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-disavow" role="tab" id="booter-disavow-tab" aria-controls="booter-disavow" class="nav-tab js-booter-tab">
			        <?php esc_html_e( 'Disavow Backlinks', 'booter' ); ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-help" role="tab" id="booter-help-tab" aria-controls="booter-help" class="nav-tab js-booter-tab">
			        <?php esc_html_e( 'Help', 'booter' ); ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#booter-about" role="tab" id="booter-about-tab" aria-controls="booter-about" class="nav-tab js-booter-tab">
                    <?php esc_html_e( 'About', 'booter' ); ?>
                </a>
            </li>

            <?php if ( isset( $settings['debug'] ) && \Upress\Booter\Utilities::bool_value( $settings['debug'] ) ) : ?>
                <li role="presentation">
                    <a href="#booter-debug" role="tab" id="booter-debug-tab" aria-controls="booter-debug" class="nav-tab js-booter-tab">
                        <?php esc_html_e( 'Debug', 'booter' ); ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <section id="booter-general" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-general-tab">
            <div class="inside">
		        <?php include __DIR__ . '/options-tabs/general.php'; ?>
            </div>
        </section>

        <section id="booter-bad-bots" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-bad-bots-tab" hidden>
            <div class="inside">
    		    <?php include __DIR__ . '/options-tabs/bad-bots.php'; ?>
            </div>
        </section>

        <section id="booter-robots" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-robots-tab" hidden>
            <div class="inside">
    		    <?php include __DIR__ . '/options-tabs/robots.php'; ?>
            </div>
        </section>

        <section id="booter-ratelimit" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-ratelimit-tab" hidden>
            <div class="inside">
	            <?php include __DIR__ . '/options-tabs/rate-limit.php'; ?>
            </div>
        </section>

        <section id="booter-reject" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-reject-tab" hidden>
            <div class="inside">
    		    <?php include __DIR__ . '/options-tabs/block.php'; ?>
            </div>
        </section>

        <section id="booter-404log" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-404log-tab" hidden>
            <div class="inside">
		        <?php include __DIR__ . '/options-tabs/404-log.php'; ?>
            </div>
        </section>
        <section id="booter-disavow" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-disavow-tab" hidden>
            <div class="inside">
			    <?php include __DIR__ . '/options-tabs/disavow.php'; ?>
            </div>
        </section>
        <section id="booter-help" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-help-tab" hidden>
            <div class="inside">
			    <?php include __DIR__ . '/options-tabs/help.php'; ?>
            </div>
        </section>
        <section id="booter-about" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-about-tab" hidden>
            <div class="inside">
    		    <?php include __DIR__ . '/options-tabs/about.php'; ?>
            </div>
        </section>

	    <?php if ( isset( $settings['debug'] ) && \Upress\Booter\Utilities::bool_value( $settings['debug'] ) ) : ?>
            <section id="booter-debug" role="tabpanel" class="js-booter-tabpanel postbox" aria-labelledby="booter-debug-tab" hidden>
                <div class="inside">
                    <?php include __DIR__ . '/options-tabs/debug.php'; ?>
                </div>
            </section>
        <?php endif; ?>
    </form>
</section>
