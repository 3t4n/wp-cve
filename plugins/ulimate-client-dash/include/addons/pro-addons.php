<?php

/* This file consist of free features that have been converted to pro.
*  To allow old users to be grandfathered in to the pro features the functions must stay in the free version.
*  All other pro features should be added to Ultimate Client Dash Pro.
*/

// Add Google Analytics Tracking code to header
function ucd_google_analytics_tracking_code(){
$propertyID = get_option('ucd_tracking_google_analytics'); // GA Property ID
    if (!empty($propertyID)) { ?>
        <!-- Ultimate Client Dashboard Google Analytics -->
        <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', '<?php echo $propertyID; ?>']);
          _gaq.push(['_trackPageview']);
          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>
    <?php }
}
add_action('wp_head', 'ucd_google_analytics_tracking_code');


// Add Facebook Pixel code to header
function ucd_facebook_pixel_code(){
$ucd_facebook_pixel = get_option('ucd_tracking_facebook_pixel');
    if (!empty($ucd_facebook_pixel)) { ?>
        <!-- Ultimate Client Dashboard Facebook Pixel -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo $ucd_facebook_pixel; ?>');
            fbq('track', 'PageView');
        </script>
        <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $ucd_facebook_pixel; ?>&ev=PageView&noscript=1"/>
        </noscript>
    <?php }
}
add_action('wp_head', 'ucd_facebook_pixel_code');


// Landing Page meta data
add_action( 'ucd_landing_page_meta', 'ucd_pro_landing_page_meta' );
function ucd_pro_landing_page_meta() {
    $ucd_landing_meta_title = get_option('ucd_under_construction_meta_title');
    $ucd_landing_meta_description = get_option('ucd_under_construction_meta_description');
    $ucd_landing_meta_description_striped = str_replace('"','',$ucd_landing_meta_description);
    $ucd_landing_meta_body= get_option('ucd_under_construction_body');
    $ucd_landing_meta_body_striped = str_replace('"','',$ucd_landing_meta_body); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php if (!empty($ucd_landing_meta_title)) { echo get_option('ucd_under_construction_meta_title'); } else { echo get_option('ucd_under_construction_title'); } ?></title>
    <meta name="description" content="<?php if (!empty($ucd_landing_meta_description)) { echo $ucd_landing_meta_description_striped; } else { echo $ucd_landing_meta_body_striped; } ?>">

<?php }
