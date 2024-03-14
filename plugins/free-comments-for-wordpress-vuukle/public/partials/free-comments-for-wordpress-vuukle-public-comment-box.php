<?php

/**
 * This file is used to mark up the public-facing aspects of the plugin.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/public/partials
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      5.0
 */
if ( ! empty( $this ) && $this instanceof Free_Comments_For_Wordpress_Vuukle_Public && ! empty( $post ) ) {
	?>
    <div id="sharing"></div>
    <div id="respond" style="background: transparent;padding:0;margin:0"></div>
    <div id="vuukle-comments" class="commentBoxDiv"></div>
	<?php
	if ( ! empty( $amp_src_url ) && ! empty( $amp_host ) ) {
		?>
        <amp-ad width="336"
                height="280" type="doubleclick"
                data-slot="/213794966/amp/<?= $amp_host ?>"
                data-enable-refresh="30"
                data-multi-size="1x1,200x200,312x260,250x250,320x100,320x50,300x250,336x280"
                rtc-config={"urls":[https://pb.vuukle.com/openrtb2/amp?tag_id=<?= $amp_host ?>&w=ATTR(width)&h=ATTR(height)&ow=ATTR(data-override-width)&oh=ATTR(data-override-height)&ms=ATTR(data-multi-size)&slot=ATTR(data-slot)&targeting=TGT&curl=CANONICAL_URL&timeout=TIMEOUT&adcid=ADCID&purl=HREF]}>
        </amp-ad>
        <amp-iframe width="1" title="User Sync"
                    height="1"
                    sandbox="allow-scripts allow-same-origin"
                    frameborder="0"
                    src="https://pb.vuukle.com/load-cookie-with-consent.html?max_sync_count=50&defaultGdprScope=0">
            <amp-img layout="fill"
                     src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                     placeholder>
            </amp-img>
        </amp-iframe>
        <amp-iframe width="740"
                    height="350"
                    style="clear:both"
                    layout="responsive"
                    sandbox="allow-scripts allow-same-origin allow-modals allow-popups allow-forms allow-top-navigation"
                    resizable frameborder="0"
                    src="<?= $amp_src_url ?>">
            <div overflow tabindex="0" role="button" aria-label="Show comments"
                 style="display: block;text-align: center;background: #1f87e5;color: #fff;border-radius: 4px;">Show
                comments
            </div>
        </amp-iframe>
        <amp-ad width="336"
                height="280" type="doubleclick"
                data-slot="/213794966/amp/<?= $amp_host ?>-2"
                data-enable-refresh="30"
                data-multi-size="1x1,200x200,312x260,250x250,320x100,320x50,300x250,336x280"
                rtc-config={"urls":[https://pb.vuukle.com/openrtb2/amp?tag_id=<?= $amp_host ?>&w=ATTR(width)&h=ATTR(height)&ow=ATTR(data-override-width)&oh=ATTR(data-override-height)&ms=ATTR(data-multi-size)&slot=ATTR(data-slot)&targeting=TGT&curl=CANONICAL_URL&timeout=TIMEOUT&adcid=ADCID&purl=HREF]}>
        </amp-ad>
		<?php
	}
	if ( ! empty( $this->settings['div_id'] ) && '4' === $this->settings['embed_comments'] ) : ?>
        <script data-cfasync="false">
            var str = document.getElementById("<?php echo esc_attr( $this->settings['div_id'] ); ?>");
            if (str === null) {
                console.warn("Vuukle comments post request was not completed because the divClassID for Vuukle Widgets is invalid. Please check your configuration.");
            } else {
                document.addEventListener("DOMContentLoaded", function (event) {
                    var commentBoxDiv = document.getElementById("vuukle-comments");
                    var commentBoxDivAfter = document.getElementById("<?php echo esc_attr( $this->settings['div_id'] ); ?>");
                    commentBoxDivAfter.parentNode.insertBefore(commentBoxDiv, commentBoxDivAfter.nextSibling);
                });
            }
        </script>
        <style>
            #vuukle-comments {
                position: relative !important;
            }
        </style>
	<?php endif;
	if ( ! empty( $this->settings['div_class'] ) && '3' === $this->settings['embed_comments'] ) : ?>
        <script data-cfasync="false">
            var str = document.getElementsByClassName("<?php echo esc_attr( $this->settings['div_class'] ); ?>");
            if (str === null) {
                console.warn("Vuukle comments post request was not completed because the divClass for Vuukle Widgets is invalid. Please check your configuration.");
            } else {
                document.addEventListener("DOMContentLoaded", function (event) {
                    var commentBoxDiv2 = document.getElementById("vuukle-comments");
                    var commentBoxDivAfter2 = document.getElementsByClassName("<?php echo esc_attr( $this->settings['div_class'] ); ?>")[0];
                    commentBoxDivAfter2.parentNode.insertBefore(commentBoxDiv2, commentBoxDivAfter2.nextSibling);
                });
            }
        </script>
        <style>
            #vuukle-comments {
                position: relative !important;
            }
        </style>
	<?php endif;
	if ( ! empty( $this->settings['div_id_emotes'] ) && '2' === $this->settings['embed_emotes'] ) : ?>
        <script data-cfasync="false">
            var str = document.getElementById("<?php echo esc_attr( $this->settings['div_id_emotes'] ); ?>");
            if (str === null) {
                console.warn("Vuukle emotes post request was not completed because the divClassID for Vuukle Widgets is invalid. Please check your configuration.");
            } else {
                document.addEventListener("DOMContentLoaded", function (event) {
                    var emoteBoxDiv = document.getElementById("vuukle-emote");
                    var emoteBoxDivAfter = document.getElementById("<?php echo esc_attr( $this->settings['div_id_emotes'] ); ?>");
                    emoteBoxDivAfter.parentNode.insertBefore(emoteBoxDiv, emoteBoxDivAfter.nextSibling);
                });
            }
        </script>
        <style>
            #vuukle-emote {
                position: relative !important;
            }
        </style>
	<?php endif;
	if ( ! empty( $this->settings['div_class_emotes'] ) && '1' === $this->settings['embed_emotes'] ) : ?>
        <script data-cfasync="false">
            var str = document.getElementsByClassName("<?php echo esc_attr( $this->settings['div_class_emotes'] ); ?>");
            if (str === null) {
                console.warn("Vuukle emotes post request was not completed because the divClass for Vuukle Widgets is invalid. Please check your configuration.");
            } else {
                document.addEventListener("DOMContentLoaded", function (event) {
                    var emoteBoxDiv2 = document.getElementById("vuukle-emote");
                    var emoteBoxDivAfter2 = document.getElementsByClassName("<?php echo esc_attr( $this->settings['div_class_emotes'] ); ?>")[0];
                    emoteBoxDivAfter2.parentNode.insertBefore(emoteBoxDiv2, emoteBoxDivAfter2.nextSibling);
                });
            }
        </script>
        <style>
            #vuukle-emote {
                position: relative !important;
            }
        </style>
	<?php endif;
	if ( ! empty( $this->settings['div_id_powerbar'] ) && '2' === $this->settings['embed_powerbar'] ) : ?>
        <script data-cfasync="false">
            var str = document.getElementById("<?php echo esc_attr( $this->settings['div_id_powerbar'] ); ?>");
            if (str === null) {
                console.warn("Vuukle widgets post request was not completed because the divClassID for Vuukle Widgets is invalid. Please check your configuration.");
            } else {
                document.addEventListener("DOMContentLoaded", function (event) {
                    var powerbarBoxDiv = document.getElementsByClassName("vuukle-powerbar")[0];
                    var powerbarBoxDivAfter_for = document.getElementById("<?php echo esc_attr( $this->settings['div_id_powerbar'] ); ?>");
                    powerbarBoxDivAfter_for.parentNode.insertBefore(powerbarBoxDiv, powerbarBoxDivAfter_for.nextSibling);
                });
            }
        </script>
	<?php endif;
	if ( ! empty( $this->settings['div_class_powerbar'] ) && '1' === $this->settings['embed_powerbar'] ) : ?>
        <script data-cfasync="false">
            var str = document.getElementsByClassName("<?php echo esc_attr( $this->settings['div_class_powerbar'] ); ?>");
            if (str.length === 0) {
                console.warn("Vuukle widgets post request was not completed because the divClass for Vuukle Widgets is invalid. Please check your configuration.");
            } else {
                document.addEventListener("DOMContentLoaded", function (event) {
                    var powerbarBoxDiv2 = document.getElementsByClassName("vuukle-powerbar")[0];
                    var powerbarBoxDivAfter_for2 = document.getElementsByClassName("<?php echo esc_attr( $this->settings['div_class_powerbar'] ); ?>")[0];
                    powerbarBoxDivAfter_for2.parentNode.insertBefore(powerbarBoxDiv2, powerbarBoxDivAfter_for2.nextSibling);
                });
            }
        </script>
	<?php endif;
}
?>