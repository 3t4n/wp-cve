<div class="pctag-faq pctag-containter">

    <?php Pagup\Pctag\Core\Plugin::view('inc/top', compact('active_tab')); ?>

    <div class="pctag-segment">
        <div class="pctag-q">
            <?php echo esc_html__( 'What is the Pinterest conversion tag ?', "add-pinterest-conversion-tags" ); ?>
        </div>
        <div class="pctag-a">
            <p>
                <?php echo esc_html__( 'The Pinterest tag allows you to track actions people take on your website after viewing your Promoted Pin. You can use this information to measure return on ad spend (RoAS) and create audiences to target on your Promoted Pins.', "add-pinterest-conversion-tags" ); ?>
            </p>
        </div>
    </div>

    <div class="pctag-segment">
        <div class="pctag-q">
            <?php echo esc_html__( 'How to create an «event»?', "add-pinterest-conversion-tags" ); ?>
        </div>
        <div class="pctag-a">
            <p>
                <?php echo esc_html__( 'This plugins provide 2 features:', "add-pinterest-conversion-tags" ); ?>
            </p>

            <ul>
                <li><?php echo esc_html__( 'FREEversion allows to add the base code (generated in the Conversion Tag Manager) that will be perfectly placed on every page (between the <head> and </head> tags in HTML) on your website. You only need to provide your TAG ID', "add-pinterest-conversion-tags" ); ?></li>
                <li><?php echo esc_html__( 'PRO version allows to track conversions from «event» codes properly added on specific pages thanks to our META box feature. This META box allows you to create easily basic events and custom events with custom code if required.', "add-pinterest-conversion-tags" ); ?>
                    <ul>
                        <li><?php echo esc_html__( 'PageVisit: Record views of primary pages, such as product pages and article pages', "add-pinterest-conversion-tags" ); ?></li>
                        <li><?php echo esc_html__( 'ViewCategory:Record views of category pages', "add-pinterest-conversion-tags" ); ?></li>
                        <li><?php echo esc_html__( 'AddToCart:Record when items are added to shopping carts', "add-pinterest-conversion-tags" ); ?></li>
                        <li><?php echo esc_html__( 'Checkout: Record completed transactions', "add-pinterest-conversion-tags" ); ?></li>
                        <li><?php echo esc_html__( 'WatchVideo: Record video views', "add-pinterest-conversion-tags" ); ?></li>
                        <li><?php echo esc_html__( 'Signup: Record sign ups for your products or services', "add-pinterest-conversion-tags" ); ?></li>
                        <li><?php echo esc_html__( 'Lead: Record interest in product or service', "add-pinterest-conversion-tags" ); ?></li>
                        <li><?php echo esc_html__( 'Custom:to track a special event that you want to include in your conversion reporting.', "add-pinterest-conversion-tags" ); ?></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>

    <div class="pctag-segment">
        <div class="pctag-q">
            <?php echo esc_html__( 'Where to find the Pinterest tag ID?', "add-pinterest-conversion-tags" ); ?>
        </div>
        <div class="pctag-a">
            <p>
                <b><?php echo esc_html__( 'To find your Pinterest Conversion Tag, follow the steps below.', "add-pinterest-conversion-tags" ); ?></b>
            </p>

            <ol>
                <li><?php echo sprintf( wp_kses( __( 'Navigate to your Pinterest ad account at <a href="%s" target="_blank">https://ads.pinterest.com</a>.', "add-pinterest-conversion-tags" ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( "https://ads.pinterest.com" ) ); ?></li>
                <li><?php echo esc_html__( 'Hover over "Ads" in the top lefthand corner and select "Conversion tracking" from the list.', "add-pinterest-conversion-tags" ); ?></li>
                <li><?php echo esc_html__( 'Click « Create Tag ».', "add-pinterest-conversion-tags" ); ?></li>
                <li><?php echo esc_html__( 'Name your tag', "add-pinterest-conversion-tags" ); ?></li>
                <li><?php echo esc_html__( 'Click "Generate code".', "add-pinterest-conversion-tags" ); ?></li>
                <li><?php echo sprintf( wp_kses( __( 'You will see, in your code, numbers like <a href="%s" target="_blank">this</a> (in red). This is your TAG ID !.', "add-pinterest-conversion-tags" ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( "https://www.dropbox.com/s/s8j4e0kx1qtih32/pinterest-id.png?dl=0" ) ); ?></li>
            </ol> 
        </div>
    </div>

    <div class="pctag-segment">
        <div class="pctag-q">
            <?php echo esc_html__( 'How does it work?', "add-pinterest-conversion-tags" ); ?>
        </div>
        <div class="pctag-a">
            <p>
                <?php echo esc_html__( 'Add your base code (TAG ID) and your event code on your dedicated page(s) with our META box feature, give it 5 minutes andthen confirm in Conversion Manager that the tags are properly implemented.', "add-pinterest-conversion-tags" ); ?>
            </p>
            <p>
                <strong><?php echo esc_html__( 'Make sure to clear your cache if required.', "add-pinterest-conversion-tags" ); ?></strong>
            </p>

            <strong><?php echo esc_html__( '1. Track conversions—even if volume is low', "add-pinterest-conversion-tags" ); ?></strong>
            <p>
                <?php echo esc_html__( 'You’re probably not going to get a lot of immediate, direct sales from Pinterest, but the conversion data you collect can provide insight about your customers. Rather than expect to use conversion data to optimize to an efficient cost-per-conversion, use conversion data to fuel market research; and to understand your customers’ preferences, what they’re interested in, what types of imagery or messaging catches theireyes, and the sentiment that appeals to them.', "add-pinterest-conversion-tags" ); ?>
            </p>

            <strong><?php echo esc_html__( '2. Set the right Pinterest conversion tracking window', "add-pinterest-conversion-tags" ); ?></strong>
            <p>
                <?php echo esc_html__( 'Since Pinterest is often used for future planning, people don’t generally take immediate action after viewing a Pin. Most advertisers should use a longerconversion window. Pinterest allows you to set a conversion window of up to 60 days, meaning Pinterest will take credit for conversions of someone who has clicked or viewed your Pin for up to 60 days. Remember, your customers are likely to hit many touchpoints between viewing a Promoted Pin and making a conversion—interpret the data accordingly.', "add-pinterest-conversion-tags" ); ?>
            </p>

            <strong><?php echo esc_html__( '3. Take advantage of multi-device Pinterest Conversion tracking', "add-pinterest-conversion-tags" ); ?></strong>
            <p>
                <?php echo esc_html__( 'Because Pinterest has the ability to track users who are logged in, you can collect data across devices. This data could be used to help shape your multi-device strategy beyond Pinterest. For example: Do your customers browse on mobile and purchase on desktop? Do they research on desktop and then make in-app purchases? Dig into the multi-device Pinterest data to see what you can learn.', "add-pinterest-conversion-tags" ); ?>
            </p>

            <strong><?php echo esc_html__( '4. Understand post-click and view-through conversions on Pinterest', "add-pinterest-conversion-tags" ); ?></strong>
            <p>
                <?php echo esc_html__( 'It’s important to distinguish between post-click and view-through conversions when making comparisons about Pinterest performance relative to other advertising channels. Pinterest allows brands to track page visits, signups, and checkout actions after a user has clicked to your site from Pinterest—all types of post-click conversions. You can also track the above actions after someone has Repinned, viewed a closeup of your Pin, or viewed your Pin within a Pinterest feed, which are essentially view-through conversions.Take note of which conversion type you are looking at to make apples-to-apples comparisons to data from other channels like display or search.', "add-pinterest-conversion-tags" ); ?>
            </p>

            <strong><?php echo esc_html__( '5. Use third-party tracking URLs', "add-pinterest-conversion-tags" ); ?></strong>
            <p>
                <?php echo esc_html__( 'To keep all data in one place, and to understand Pinterest’s true influence compared to other touchpoints in your customer journey, implement third-party tracking such as Google Analytics using a tracking URL. Determine which tracking parameters to include in the URL based on your goals. For example, if you’re looking to understand which demographic is most engaged, include a variable to specify the targeting groups you use to Promote your pins. If you want to understand which messaging and imagery speaks to your audience, include a creative variable.Tracking URLs can be updated by “editing” a Pin to change the destination URL. Don’t worry, if you edit a Promoted Pin destination URL, it won’t change the URLs of the original organic Pin.', "add-pinterest-conversion-tags" ); ?>
            </p>
            <p>Source: http://www.boostmedia.com/blog/social/5-tips-on-pinterest-conversion-tracking/</p>

        </div>
    </div>

</div>