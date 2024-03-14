<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ytpp_settings() {
    global $wpdb;
    ?>
    <div class="wrap">
        <h2><?php _e( 'YouTube Playlist Player Settings', 'youtube-playlist-player' ); ?></h2>

        <?php $tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'dashboard'; ?>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url( 'admin.php?page=ytpp&tab=dashboard' ); ?>" class="nav-tab <?php echo $tab === 'dashboard' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Dashboard', 'youtube-playlist-player' ); ?></a>
            <a href="<?php echo admin_url( 'admin.php?page=ytpp&tab=settings' ); ?>" class="nav-tab <?php echo $tab === 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Settings', 'youtube-playlist-player' ); ?></a>
            <a href="<?php echo admin_url( 'admin.php?page=ytpp&tab=api' ); ?>" class="nav-tab <?php echo $tab === 'api' ? 'nav-tab-active' : ''; ?>"><?php _e( 'YouTube API', 'youtube-playlist-player' ); ?></a>
            <a href="<?php echo admin_url( 'admin.php?page=ytpp&tab=help' ); ?>" class="nav-tab <?php echo $tab === 'help' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Help/Usage', 'youtube-playlist-player' ); ?></a>
        </h2>

        <?php if ( (string) $tab === 'dashboard' ) { ?>
            <div id="poststuff">
                <div class="gb-ad">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 68"><defs/><rect width="100%" height="100%" fill="none"/><g class="currentLayer"><path fill="#313457" d="M34.76 33C22.85 21.1 20.1 13.33 28.23 5.2 36.37-2.95 46.74.01 50.53 3.8c3.8 3.8 5.14 17.94-5.04 28.12-2.95 2.95-5.97 5.84-5.97 5.84L34.76 33"/><path fill="#313457" d="M43.98 42.21c5.54 5.55 14.59 11.06 20.35 5.3 5.76-5.77 3.67-13.1.98-15.79-2.68-2.68-10.87-5.25-18.07 1.96-2.95 2.95-5.96 5.84-5.96 5.84l2.7 2.7m-1.76 1.75c5.55 5.54 11.06 14.59 5.3 20.35-5.77 5.76-13.1 3.67-15.79.98-2.69-2.68-5.25-10.87 1.95-18.07 2.85-2.84 5.84-5.96 5.84-5.96l2.7 2.7"/><path fill="#313457" d="M33 34.75c-11.9-11.9-19.67-14.67-27.8-6.52-8.15 8.14-5.2 18.5-1.4 22.3 3.8 3.79 17.95 5.13 28.13-5.05 3.1-3.11 5.84-5.97 5.84-5.97L33 34.75"/></g></svg> Thank you for using YouTube Playlist Player!</h3>
                    <p>If you enjoy this plugin, do not forget to <a href="https://wordpress.org/support/plugin/youtube-playlist-player/reviews/?filter=5" rel="external">rate it</a>! We work hard to update it, fix bugs, add new features and make it compatible with the latest web technologies.</p>
                    <p></p>
                    <p style="font-size:14px">
                        <b>Featured plugins:</b>&#32;
                        🔥 <a href="https://getbutterfly.com/wordpress-plugins/active-analytics/" target="_blank" rel="external noopener">Active Analytics</a> and&#32;
                        🚀 <a href="https://getbutterfly.com/wordpress-plugins/lighthouse/" target="_blank" rel="external noopener">WP Lighthouse</a>&#32;
                        Have you tried our other <a href="https://getbutterfly.com/wordpress-plugins/">WordPress plugins</a>?
                    </p>
                </div>

                <h3><?php _e( 'About YouTube Playlist Player', 'youtube-playlist-player' ); ?></h3>
                <p>Display a YouTube player (with an optional playlist) on any post or page using a simple shortcode. The plugin supports a static YouTube player (no video title) and a dynamic one (video title) using the YouTube Data API v3.</p>

                <p>Embedded players must have a viewport that is at least 200px by 200px. If the player displays controls, it must be large enough to fully display the controls without shrinking the viewport below the minimum size. We recommend 16:9 players be at least 480 pixels wide and 270 pixels tall.</p>
                <p>By embedding YouTube videos on your site, you are agreeing to <a href="https://developers.google.com/youtube/terms/api-services-terms-of-service" rel="external">YouTube API Terms of Service</a>.</p>

                <hr>
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#252323" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 661 81" style="height:32px"><path d="M487.23 58.97c.6-1.57.9-4.14.9-7.71V36.24c0-3.46-.3-6-.9-7.6s-1.67-2.4-3.19-2.4c-1.47 0-2.5.8-3.1 2.4-.61 1.6-.91 4.14-.91 7.6v15.02c0 3.57.29 6.14.86 7.7.58 1.58 1.63 2.37 3.15 2.37s2.58-.79 3.19-2.36zm-12.2 7.55c-2.17-1.47-3.72-3.75-4.64-6.84-.92-3.1-1.37-7.21-1.37-12.35v-7c0-5.2.52-9.36 1.57-12.51s2.69-5.44 4.92-6.88c2.22-1.44 5.15-2.17 8.77-2.17 3.56 0 6.42.74 8.57 2.2 2.15 1.48 3.72 3.77 4.72 6.89s1.5 7.28 1.5 12.47v7c0 5.14-.5 9.27-1.46 12.39-.97 3.12-2.55 5.4-4.72 6.84-2.18 1.44-5.13 2.16-8.85 2.16-3.83 0-6.83-.73-9-2.2m168.43-39.37c-.55.68-.92 1.8-1.1 3.34a66.8 66.8 0 0 0-.27 7.04v3.46h7.94v-3.46c0-3.1-.1-5.44-.31-7.04s-.6-2.72-1.14-3.38c-.56-.66-1.4-.98-2.56-.98s-2 .34-2.56 1.02zm-1.37 20.3v2.43c0 3.1.09 5.42.27 6.96.18 1.55.56 2.68 1.14 3.38.58.71 1.47 1.07 2.68 1.07 1.62 0 2.74-.63 3.34-1.9.6-1.25.93-3.35.98-6.28l9.36.54c.05.42.08 1 .08 1.74 0 4.45-1.22 7.78-3.66 9.98s-5.88 3.31-10.34 3.31c-5.35 0-9.1-1.68-11.25-5.04-2.15-3.35-3.22-8.54-3.22-15.57v-8.42c0-7.23 1.11-12.51 3.34-15.85 2.23-3.33 6.04-5 11.45-5 3.72 0 6.58.7 8.57 2.06s3.4 3.48 4.2 6.37c.82 2.88 1.23 6.87 1.23 11.95v8.26H642.1m-193.5-.9-12.35-44.6h10.78l4.32 20.21a191.23 191.23 0 0 1 2.44 12.75h.32c.36-2.52 1.18-6.74 2.44-12.67l4.48-20.29h10.78l-12.51 44.6v21.4h-10.7v-21.4m85.51-26.82v48.21h-8.5l-.94-5.9h-.23c-2.31 4.46-5.77 6.7-10.39 6.7-3.2 0-5.56-1.06-7.08-3.16s-2.28-5.37-2.28-9.83V19.72h10.86v35.4c0 2.14.23 3.68.7 4.6a2.47 2.47 0 0 0 2.36 1.37c2-.08 3.79-1.26 4.64-3.07v-38.3h10.86m55.68 0v48.21h-8.5l-.94-5.9h-.24c-2.3 4.46-5.77 6.7-10.38 6.7-3.2 0-5.56-1.06-7.08-3.16s-2.28-5.37-2.28-9.83V19.72h10.86v35.4c0 2.14.23 3.68.7 4.6a2.47 2.47 0 0 0 2.36 1.37 5.36 5.36 0 0 0 4.64-3.07v-38.3h10.86"/><path d="M563.6 10.67h-10.77v57.26h-10.62V10.67h-10.77V1.94h32.16v8.73m52.16 36.43c0 3.5-.14 6.26-.43 8.25-.29 2-.77 3.41-1.46 4.25a3.36 3.36 0 0 1-2.75 1.26 4.74 4.74 0 0 1-4.33-2.52V30.97c.37-1.31 1-2.39 1.9-3.23a4.17 4.17 0 0 1 2.9-1.25c1.1 0 1.96.43 2.56 1.3.6.86 1.02 2.31 1.26 4.36.23 2.04.35 4.95.35 8.73zm9.95-19.67c-.65-3.04-1.72-5.25-3.18-6.61-1.47-1.36-3.5-2.05-6.06-2.05a10 10 0 0 0-5.58 1.7 11.5 11.5 0 0 0-4.02 4.44h-.08V-.5h-10.46v68.42h8.97l1.1-4.56h.24a9.2 9.2 0 0 0 3.77 3.86 11.2 11.2 0 0 0 5.59 1.41c3.67 0 6.37-1.69 8.1-5.07s2.6-8.67 2.6-15.85v-7.63c0-5.4-.33-9.62-.99-12.66M-.5 67.14V20.5h13.03q5.73 0 10.28 2.63 4.54 2.59 7.07 7.46 2.57 4.87 2.57 11.08v4.29q0 6.24-2.53 11.08t-7.15 7.46q-4.58 2.63-10.53 2.63zm3.93-43.27v39.94h8.84q7.37 0 11.82-4.87 4.45-4.9 4.45-13.23v-4.1q0-8.03-4.39-12.87T12.6 23.87zM54.3 67.78q-4.42 0-8-2.18-3.56-2.18-5.54-6.05-1.99-3.9-1.99-8.74v-1.38q0-5 1.92-9 1.96-4 5.42-6.28 3.45-2.3 7.49-2.3 6.3 0 10 4.32 3.7 4.3 3.7 11.75v2.15H42.59v.74q0 5.89 3.37 9.83 3.4 3.9 8.51 3.9 3.08 0 5.42-1.12 2.37-1.12 4.29-3.58l2.4 1.82q-4.23 6.12-12.27 6.12zm-.7-32.68q-4.32 0-7.3 3.17-2.95 3.17-3.59 8.52H63.5v-.41q-.16-5-2.85-8.14-2.7-3.14-7.05-3.14zm30.89 26.75L95.03 32.5h3.93L86.03 67.14h-3.11l-13-34.65h3.94zm33.1 5.93q-4.42 0-8-2.18-3.56-2.18-5.55-6.05-1.98-3.9-1.98-8.74v-1.38q0-5 1.92-9 1.95-4 5.4-6.28 3.47-2.3 7.5-2.3 6.31 0 10 4.32 3.71 4.3 3.71 11.75v2.15h-24.72v.74q0 5.89 3.36 9.83 3.4 3.9 8.52 3.9 3.07 0 5.41-1.12 2.37-1.12 4.3-3.58l2.4 1.82q-4.23 6.12-12.27 6.12zm-.7-32.67q-4.33 0-7.3 3.17-2.96 3.17-3.6 8.52h20.79v-.41q-.16-5-2.85-8.14-2.7-3.14-7.05-3.14zm24.8 32.03h-3.85v-49.2h3.85zm7.64-17.84q0-5 1.92-9 1.95-4 5.47-6.21 3.56-2.24 8.04-2.24 6.92 0 11.21 4.86 4.3 4.84 4.3 12.85v.8q0 5.03-1.96 9.06-1.92 4-5.45 6.18-3.52 2.18-8.03 2.18-6.89 0-11.21-4.84-4.3-4.86-4.3-12.87zm3.84 1.06q0 6.21 3.2 10.21 3.24 3.97 8.46 3.97 5.18 0 8.39-3.97 3.23-4 3.23-10.53v-.74q0-3.97-1.47-7.27-1.47-3.3-4.13-5.1-2.66-1.82-6.09-1.82-5.12 0-8.36 4.04-3.23 4-3.23 10.5zm62.21-.19q0 8.1-3.59 12.87-3.58 4.74-9.6 4.74-7.12 0-10.9-5v17.68h-3.8V32.5h3.55l.2 4.9q3.74-5.54 10.85-5.54 6.21 0 9.73 4.7 3.56 4.71 3.56 13.07zm-3.84-.68q0-6.63-2.73-10.47-2.72-3.84-7.59-3.84-3.52 0-6.05 1.7t-3.87 4.93v16.62q1.37 2.98 3.93 4.55 2.57 1.56 6.06 1.56 4.83 0 7.52-3.84 2.73-3.87 2.73-11.2zm25.09 18.29q-4.42 0-8-2.18-3.56-2.18-5.55-6.05-1.98-3.9-1.98-8.74v-1.38q0-5 1.92-9 1.95-4 5.4-6.28 3.47-2.3 7.5-2.3 6.31 0 10 4.32 3.71 4.3 3.71 11.75v2.15h-24.72v.74q0 5.89 3.36 9.83 3.4 3.9 8.52 3.9 3.07 0 5.41-1.12 2.37-1.12 4.3-3.58l2.4 1.82q-4.23 6.12-12.27 6.12zm-.7-32.67q-4.33 0-7.3 3.17-2.95 3.17-3.6 8.52h20.79v-.41q-.16-5-2.85-8.14-2.7-3.14-7.05-3.14zM254.9 49.5q0-8.08 3.59-12.85 3.62-4.8 9.8-4.8 7 0 10.7 5.54V17.95h3.8v49.19h-3.58l-.16-4.61q-3.69 5.25-10.83 5.25-5.99 0-9.67-4.8-3.65-4.84-3.65-13.04zm3.87.67q0 6.62 2.66 10.47 2.66 3.8 7.5 3.8 7.07 0 10.05-6.24V41.93q-2.98-6.75-9.99-6.75-4.84 0-7.53 3.8-2.69 3.79-2.69 11.19zm57.04 8.99.58 2.98.77-3.14 8.32-26.5h3.27l8.23 26.29.9 3.65.74-3.36 7.1-26.58h3.98l-10.1 34.64h-3.26l-8.9-27.48-.42-1.89-.41 1.92-8.75 27.45h-3.26l-10.06-34.65h3.94zm44.79 7.98h-3.84V32.49h3.84zm-4.51-44.68q0-1.09.7-1.82.7-.77 1.92-.77t1.92.77q.74.73.74 1.82 0 1.1-.74 1.83-.7.74-1.92.74t-1.92-.74q-.7-.74-.7-1.83zm20.22 1.16v8.87h7.17v3.14h-7.17v23.09q0 2.88 1.02 4.29 1.06 1.4 3.5 1.4.96 0 3.1-.31l.16 3.13q-1.5.55-4.1.55-3.94 0-5.73-2.27-1.8-2.31-1.8-6.76V35.63h-6.37v-3.14h6.38v-8.87zm18.27 14.73q1.89-3.1 4.84-4.8 2.94-1.7 6.43-1.7 5.58 0 8.3 3.14 2.72 3.13 2.75 9.41v22.74h-3.8V44.37q-.04-4.65-2-6.92-1.92-2.27-6.17-2.27-3.56 0-6.31 2.24-2.73 2.2-4.04 5.99v23.73h-3.81v-49.2h3.81z"/></svg>
                </p>

                <hr>
                <p>For support, feature requests and bug reporting, please visit the <a href="https://getbutterfly.com/wordpress-plugins/youtube-playlist-player/" rel="external">official website</a>. If you enjoy this plugin, don't forget to rate it. Also, try our other WordPress plugins at <a href="https://getbutterfly.com/wordpress-plugins/" rel="external" target="_blank">getButterfly.com</a>.</p>
                <p>&copy;<?php echo gmdate( 'Y' ); ?> <a href="https://getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <small>Code wrangling since 2005</small></p>
            </div>
            <?php
        } elseif ( (string) $tab === 'settings' ) {
            if ( isset( $_POST['info_update1'] ) && wp_verify_nonce( $_POST['yypp_save_settings'], 'yypp_save_settings' ) && current_user_can( 'manage_options' ) ) {
                if ( isset( $_POST['ytpp_rel'] ) ) {
                    update_option( 'ytpp_rel', (int) sanitize_text_field( $_POST['ytpp_rel'] ) );
                } else {
                    update_option( 'ytpp_rel', 0 );
                }

                if ( isset( $_POST['ytpp_info'] ) ) {
                    update_option( 'ytpp_info', (int) sanitize_text_field( $_POST['ytpp_info'] ) );
                } else {
                    update_option( 'ytpp_info', 0 );
                }

                if ( isset( $_POST['ytpp_controls'] ) ) {
                    update_option( 'ytpp_controls', (int) sanitize_text_field( $_POST['ytpp_controls'] ) );
                } else {
                    update_option( 'ytpp_controls', 0 );
                }

                if ( isset( $_POST['ytpp_privacy'] ) ) {
                    update_option( 'ytpp_privacy', (int) sanitize_text_field( $_POST['ytpp_privacy'] ) );
                } else {
                    update_option( 'ytpp_privacy', 0 );
                }

                if ( isset( $_POST['ytpp_iframe_fix'] ) ) {
                    update_option( 'ytpp_iframe_fix', (int) sanitize_text_field( $_POST['ytpp_iframe_fix'] ) );
                } else {
                    update_option( 'ytpp_iframe_fix', 0 );
                }

                echo '<div class="updated notice is-dismissible"><p>Settings updated!</p></div>';
            }
            ?>
            <form method="post" action="">
                <?php wp_nonce_field( 'yypp_save_settings', 'yypp_save_settings' ); ?>

                <h3><?php _e( 'Player Settings', 'youtube-playlist-player' ); ?></h3>

                <p>
                    <input type="checkbox" class="wppd-ui-toggle" name="ytpp_rel" id="ytpp_rel" value="1" <?php checked( 1, (int) get_option( 'ytpp_rel' ) ); ?>> <label for="ytpp_rel">Show suggested videos when the video finishes</label>
                </p>
                <p>
                    <input type="checkbox" class="wppd-ui-toggle" name="ytpp_info" id="ytpp_info" value="1" <?php checked( 1, (int) get_option( 'ytpp_info' ) ); ?>> <label for="ytpp_info">Show video title and player actions</label>
                </p>
                <p>
                    <input type="checkbox" class="wppd-ui-toggle" name="ytpp_controls" id="ytpp_controls" value="1" <?php checked( 1, (int) get_option( 'ytpp_controls' ) ); ?>> <label for="ytpp_controls">Show player controls</label>
                </p>
                <p>
                    <input type="checkbox" class="wppd-ui-toggle" name="ytpp_privacy" id="ytpp_privacy" value="1" <?php checked( 1, (int) get_option( 'ytpp_privacy' ) ); ?>> <label for="ytpp_privacy">Enable privacy-enhanced mode</label>
                    <br><small>When you turn on privacy-enhanced mode, YouTube won't store information about visitors on your website unless they play the video.</small>
                </p>

                <h3><?php _e( 'Display Settings', 'youtube-playlist-player' ); ?></h3>

                <p>
                    <input type="checkbox" class="wppd-ui-toggle" name="ytpp_iframe_fix" id="ytpp_iframe_fix" value="1" <?php checked( 1, (int) get_option( 'ytpp_iframe_fix' ) ); ?>> <label for="ytpp_iframe_fix">Enable fix for older browsers</label>
                    <br><small>Use this option to fix player height on older browsers, or browsers not supporting the <code>aspect-ratio</code> CSS property.</small>
                </p>                

                <p><input type="submit" name="info_update1" class="button button-primary" value="<?php _e( 'Save Changes', 'youtube-playlist-player' ); ?>"></p>
            </form>
            <?php
        } elseif ( (string) $tab === 'api' ) {
            if ( isset( $_POST['info_update1'] ) && wp_verify_nonce( $_POST['yypp_save_settings'], 'yypp_save_settings' ) && current_user_can( 'manage_options' ) ) {
                update_option( 'ytppYouTubeApi', (string) sanitize_text_field( $_POST['ytppYouTubeApi'] ) );

                echo '<div class="updated notice is-dismissible"><p>Settings updated!</p></div>';
            }
            ?>
            <form method="post" action="">
                <?php wp_nonce_field( 'yypp_save_settings', 'yypp_save_settings' ); ?>

                <h3><?php _e( 'YouTube API Settings', 'youtube-playlist-player' ); ?></h3>

                <p>
                    <input type="text" name="ytppYouTubeApi" id="ytppYouTubeApi" value="<?php echo get_option( 'ytppYouTubeApi' ); ?>" class="regular-text" placeholder="YouTube API"> <label for="ytppYouTubeApi">YouTube API</label>
                    <br><small>See the <a href="https://developers.google.com/youtube/v3/docs/" rel="external">YouTube API documentation here</a>.</small>
                </p>

                <p><input type="submit" name="info_update1" class="button button-primary" value="<?php _e( 'Save Changes', 'youtube-playlist-player' ); ?>"></p>
            </form>
            <?php
        } elseif ( (string) $tab === 'help' ) {
            ?>
            <div id="poststuff">
                <h3><?php _e( 'Help &amp; Usage Details', 'youtube-playlist-player' ); ?></h3>
                <h4>Use one of the shortcodes below to add the YouTube player</h4>
                <p>Static YouTube player: <code>[yt_playlist mainid="xcJtL7QggTI" vdid="xcJtL7QggTI,AheYbU8J5Tc,X0zGS4-UKgg,74SZXCQb44s,2M0XCH9q3YI"]</code></p>
                <p>Dynamic YouTube player (YouTube Data API v3): <code>[yt_playlist_v3 mainid="xcJtL7QggTI" vdid="xcJtL7QggTI,AheYbU8J5Tc,X0zGS4-UKgg,74SZXCQb44s,2M0XCH9q3YI"]</code></p>
                <p>YouTube channel feed with lightbox player: <code>[yt_feed channels="UCpVm7bg6pXKo1Pr6k5kxG9A" results="9"]</code></p>

                <p>or use the shortcode in one of your theme templates using the code below:</p>
                <p><code>&lt;?php echo do_shortcode( '[yt_playlist mainid="xcJtL7QggTI" vdid="xcJtL7QggTI,AheYbU8J5Tc,X0zGS4-UKgg,74SZXCQb44s,2M0XCH9q3YI"]' ); ?&gt;</code></p>
                <p><code>&lt;?php echo do_shortcode( '[yt_playlist_v3 mainid="xcJtL7QggTI" vdid="xcJtL7QggTI,AheYbU8J5Tc,X0zGS4-UKgg,74SZXCQb44s,2M0XCH9q3YI"]' ); ?&gt;</code></p>
                <p><code>&lt;?php echo do_shortcode( '[yt_feed channels="UCpVm7bg6pXKo1Pr6k5kxG9A" results="9"]' ); ?&gt;</code></p>

                <p><b>Note:</b> Shortcodes can be added to posts, pages, custom post types, widgets or reusable blocks.</p>

                <hr>
                <p><code>mainid</code> is the main video ID and <code>vdid</code> is the list of playlist videos (also include the main video ID).</p>
                <p>Style the <code>.ytpp-main</code> element to change the videos (and playlist) container.</p>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
