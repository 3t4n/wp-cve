<div class="wrap container-fluid atp-container">

    <?php Pagup\Twitter\Core\Plugin::view('inc/top', compact('active_tab')); ?>
    
    <div class="row">

        <div id="atp-app" class="col-xs-12 col-main">

            <div class="atp-segment">

                <h2><?php echo __('How to check if your Twitter pixel is properly installed', $text_domain); ?></h2>

                <p><?php echo sprintf( wp_kses( __( 'Use <a href="%s" target="_blank">Twitter pixel helper</a>: You can use Twitter Pixel Helper to check if your landing page is installed correctly and if the conversion event is triggered.. Enjoy.', $text_domain ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://chrome.google.com/webstore/detail/twitter-pixel-helper/jepminnlebllinfmkhfbkpckogoiefpd?hl=en-US' ) ); ?></p>

            </div>

            <div class="atp-segment">

                <h2><?php echo __('How to create a Tracking Pixel with Twitter', $text_domain); ?></h2>

                <p>
                    <ol>
                        <li>Log in to your Twitter Ads account.</li>
                        <li>Select Tools.</li>
                        <li>Click Conversion Tracking.</li>
                        <li>Page Pixel (Universal Website Tag)</li>
                        <li>Generate a Universal Website Tag.</li>
                        <li>Select Universal Website Tag.</li>
                        <li>Save your Pixel ID.</li>
                    </ol>
                    More details <a href="https://www.eventbrite.com/support/articles/en_US/Multi_Group_How_To/how-to-create-a-tracking-pixel-with-twitter?lg=en_US">HERE</a>
                </p>

            </div>

        </div>

    </div>

</div>