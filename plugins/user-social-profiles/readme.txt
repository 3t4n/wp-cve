=== User Social Profiles ===
Contributors: limestreet
Tags: social profiles, social accounts, user social profiles, social user fields
Requires at least: 4.0
Tested up to: 5.0
Stable tag: 0.1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin adds social fields to user profile in admin panel (Dashboard > Users).

== Description ==

To use this plugin - your theme (or child theme) must support it.
To display additional profiles for user you just need to use native WordPress function `the_author_meta( 'profile-name' )` where 'profile name' can be 'twitter', 'facebook', 'googleplus', 'instagram', 'pinterest'.

Code example (with Font Awesome Icons):

`<?php
    $prefix_twitter_url = get_the_author_meta( 'twitter' );
    $prefix_facebook_url = get_the_author_meta( 'facebook' );
    $prefix_googleplus_url = get_the_author_meta( 'googleplus' );
    $prefix_instagram_url = get_the_author_meta( 'instagram' );
    $prefix_pinterest_url = get_the_author_meta( 'pinterest' );
?>
<?php if ( ! empty( $prefix_twitter_url ) ) : ?><a href="<?php the_author_meta( 'twitter' ) ?>"><span class="fa fa-twitter"></span></a><?php endif; ?>
<?php if ( ! empty( $prefix_facebook_url ) ) : ?><a href="<?php the_author_meta( 'facebook' ) ?>"><span class="fa fa-facebook"></span></a><?php endif; ?>
<?php if ( ! empty( $prefix_googleplus_url ) ) : ?><a href="<?php the_author_meta( 'googleplus' ) ?>"><span class="fa fa-google-plus"></span></a><?php endif; ?>
<?php if ( ! empty( $prefix_instagram_url ) ) : ?><a href="<?php the_author_meta( 'instagram' ) ?>"><span class="fa fa-instagram"></span></a><?php endif; ?>
<?php if ( ! empty( $prefix_pinterest_url ) ) : ?><a href="<?php the_author_meta( 'pinterest' ) ?>"><span class="fa fa-pinterest"></span></a><?php endif; ?>`

You can see it in action here: https://pencil1.blogonyourown.com/author/robertsummer/

== Installation ==

1. Upload `user-social-profiles.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place in your template:
`<?php if ( class_exists( 'UserSocialProfiles' ) ) : ?>
<?php
    $prefix_twitter_url = get_the_author_meta( 'twitter' );
    $prefix_facebook_url = get_the_author_meta( 'facebook' );
    $prefix_googleplus_url = get_the_author_meta( 'googleplus' );
    $prefix_instagram_url = get_the_author_meta( 'instagram' );
    $prefix_pinterest_url = get_the_author_meta( 'pinterest' );
?>
    <?php if ( ! empty( $prefix_twitter_url ) ) : ?><a href="<?php the_author_meta( 'twitter' ) ?>"><span class="fa fa-twitter"></span></a><?php endif; ?>
    <?php if ( ! empty( $prefix_facebook_url ) ) : ?><a href="<?php the_author_meta( 'facebook' ) ?>"><span class="fa fa-facebook"></span></a><?php endif; ?>
    <?php if ( ! empty( $prefix_googleplus_url ) ) : ?><a href="<?php the_author_meta( 'googleplus' ) ?>"><span class="fa fa-google-plus"></span></a><?php endif; ?>
    <?php if ( ! empty( $prefix_instagram_url ) ) : ?><a href="<?php the_author_meta( 'instagram' ) ?>"><span class="fa fa-instagram"></span></a><?php endif; ?>
    <?php if ( ! empty( $prefix_pinterest_url ) ) : ?><a href="<?php the_author_meta( 'pinterest' ) ?>"><span class="fa fa-pinterest"></span></a><?php endif; ?>
<?php endif; ?>`

== Changelog ==

= 0.1.4 =
*21 Dec 2015 - WordPress 4.4 tested
*Example improved.

= 0.1.3 =
*16 Nov 2015 
*pot file added

= 0.1.2 =
*13 Nov 2015 
*load_plugin_texdomain added

= 0.1.1 =
*12 Nov 2015 
*text-domain updated

= 0.1 =
*12 Nov 2015 
*initial release.
