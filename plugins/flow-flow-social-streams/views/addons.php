<?php if ( ! defined( 'WPINC' ) ) die;
$plugins_url = plugins_url() . '/' . $context['plugin_dir_name'];
/** @var array $context */
$options = $context['options'];
?>
<div class="section-content" data-tab="addons-tab">
    <div class="section" id="boosts">
        <h1 class="desc-following">Boosts<span class="boosts-beta">beta</span> <span class="desc hint-block">
                <span class="hint-link"><img src="<?php echo $plugins_url ?>/assets/info_icon.svg"></span>
                <span class="hint hint-pro">
                    <div class="ff-negative-margins"><img src="<?php echo $plugins_url ?>/assets/ezgif-boost.gif" alt=""/></div>
                    <h1>How to use</h1>
                    <p>When you have available BOOSTS drag and drop BOOST element on feed in the list or enable BOOST in feed settings.
                        <a href="http://social-streams.com/boosts" target="_blank">What is BOOST cloud service?</a></p></span>
            </span></h1>
        <div class="desc">Unlock all PRO features and offload your site by hosting feeds in the highly optimized cloud. <a href="#" class="ff-pseudo-link boosts-link">How it works?</a><br><a href="https://social-streams.com/flow#pricing" target="_blank" class="">Compare all premium options</a>. <span id="ff-cloud-status">Testing connection...</span>

        </div>
        <ul class="pricing-table">

        </ul>
        <div class="boosts_custom">
            <span>If you have coupon promo code enter it here:</span><span><input class="clearcache" type="text" id="boosts_coupon" name="boosts_coupon" placeholder="Coupon" value=""/><a class="block-controls"><a href="#" class="ff-pseudo-link coupon-apply">Apply to next checkout</a> or <a href="#" class="ff-pseudo-link coupon-clear">clear</a></span>
        </div>
        <div class="desc" style="text-align: center">* Please notice that VAT is not included in displayed prices, it will be added on checkout depending on your location.<br>Payments are processed by <a target="_blank" href="https://paddle.com/legal-buyers/">Paddle</a> — online reseller with main office based in UK.<br> Also good to know, we have 30 days guaranteed money back policy.</div>
        <div class="popup boosts-popup">

            <div class="section">
                <h1><span>Boosts explained</span></h1>

                <div class="popup-content-wrapper">

                    <h2 style="text-align: center"><strong>BOOSTS</strong> — cloud service for Flow-Flow and it works as simple as pictured below.</h2>
                    <img src="<?php echo $plugins_url ?>/assets/boosts-explained.png">
                    <h2>Why host feeds in cloud?</h2>
                    <p>See, the less plugins the better for site loading speed, which is the crucial for conversion rates. If you want to have all Flow-Flow features AND offload your site we offer you to delegate all data manipulation to the cloud plus directly embed streams to your site pages not bothering your server. Also, we will add more features to cloud streams because we are not limited by resources of single server (your site) anymore. </p>
                    <h2>I don't like subscriptions much, can I just use Flow-Flow with own site?</h2>
                    <p>According to our support statistics 97% of clients do not experience any issues with running plugin on their own site server. In rare cases server configuration is preventing plugin from functioning (CRON and issues with server jobs), sometimes hosting has some security settings or hosting IP network can be banned by social media API servers. Boosts service is the way to guarantee everything is fine and dandy with feeds.</p>
                    <h2>So, will it load faster on pages?</h2>
                    <p>Visually on page it's possible you won't notice big difference. It CAN make difference though if you have a lot of plugins installed, a lot of feeds created so there a lot of database queries happens to render single page. Cloud allows to reduce load on server from Flow-Flow side, content is added on page dynamically from cloud.</p>
                    <h2>Can I mix regular and cloud feeds in one stream?</h2>
                    <p>No, you can't. Because data is located and prepared on your server and in the cloud accordingly, it will require a lot of additional operations to synchronize these chunks of data. At the same time as all the point of cloud service is to reduce amount of computing on your server. Maybe we'll come up with some graceful solution in the future.</p>
                    <h2>What additional features you plan to add for cloud streams?</h2>
                    <p>Because now we have access to more powerful cloud computing that scales as much as needed we can implement richer features. This will include various e-commerce integrations, advanced editing of grid and posts, usage analytics etc</p>
                    <h2>What is Instagram proxy?</h2>
                    <p>Instagram proxy is bonus feature available for all boosts subscriptions. Instagram can deny frequent data requests from same IP (or whole IP network if you are using shared hosting). Some IPs are blacklisted right away. Proxy service helps in this case to get Instagram posts.</p>
                    <h2>Do I still need to add tokens or authenticate networks?</h2>
                    <p>Yes, we are getting data on your behalf, it's just difference that either your server or cloud server requests network API endpoints to get posts data. Only exception is Instagram, your login info is not being used in cloud for pulling Instagram posts.</p>
                    <h2>What about GDPR compliance?</h2>
                    <p>As plugin we operate under your site rules which most likely asks consent for EU visitors to use cookies. So far we don't gather or store any data of your site visitors. In future maybe we will add optional analytics service.</p>

                    <i class="popupclose flaticon-close-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="section" id="extensions">
        <h1 class="desc-following">Available extensions</h1>
        <p class="desc">Enhance Flow-Flow functionality with these great add-ons.</p>

        <div class="extension">
            <div class="extension__item" id="extension-ads">
                <div class="extension__image"></div>
                <div class="extension__content">
                    <a class="extension__cta" target="_blank" href="http://goo.gl/m7uFzr">Get</a>
                    <h1 class="extension__title">Advertising & Branding extension</h1>
                    <p class="extension__text">Personalize your Flow-Flow stream with custom cards. Make sticky and always show custom content: your brand advertisement with links to social profiles, custom advertisements (like AdSense), any announcements, event promotion and whatever you think of.<br>
                        <strong>Supported products:</strong> Flow-Flow PRO v 2.5+, Flow-Flow Lite v 3.0.5+</p>

                 </div>

            </div>
            <div class="extension__item" id="extension-tv">
                <div class="extension__image"></div>
                <div class="extension__content">
                    <a class="extension__cta" target="_blank" href="http://goo.gl/jWCl9T">Get</a>
                    <h1 class="extension__title">Big Screens extension</h1>
                    <p class="extension__text">Cast your social hub directly to a live TV, projector, or HDMI broadcast device with just one click! This extension comes with realtime updating and posts automatic rotation for full-screen mode. You just need to output stream page to desired screen.<br>
                        <strong>Supported products:</strong> Flow-Flow PRO v 2.8+, Flow-Flow Lite v 3.0.5+</p>
                 </div>

            </div>
        </div>
    </div>
    <div class="section" id="other_products">
        <h1 class="desc-following">Social Stream Apps</h1>
        <p class="desc">Other products built on Flow-Flow's core.</p>

        <div class="extension">
            <div class="extension__item" id="plugin-grace">
                <div class="extension__image"></div>
                <div class="extension__content">
                    <a class="extension__cta" target="_blank" href="http://go.social-streams.com/get-grace">Get</a>
                    <h1 class="extension__title">Grace — Instagram Feed Gallery for WordPress</h1>
                    <p class="extension__text">The most advanced plugin for creating graceful Instagram feed media walls of Instagram public posts. This feature-rich plugin lets you aggregate and showcase posts of Instagram accounts, hashtags and locations. And the great thing is that you can mix any of Instagram feeds in the same social media wall or carousel. Add eye-catching Instagram gallery to your site in fast and easy way!</p>
                </div>
            </div>
        </div>
    </div>
    <?php
    	/** @noinspection PhpIncludeInspection */
		include($context['root']  . 'views/footer.php');
	?>
</div>
