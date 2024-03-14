<?php
/**
 * $Desc
 *
 * @version    $Id$
 * @package    wpbase
 * @author     Opal  Team <opalwordpress@gmail.com>
 * @copyright  Copyright (C) 2017 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/questions/
 */
/**
 * Enable/distable share box
 */
?>
<div class="social-share-block">
 
        <a class="wpopal-social-facebook" href="http://www.facebook.com/sharer.php?s=100&p&#91;url&#93;=<?php the_permalink(); ?>&p&#91;title&#93;=<?php the_title(); ?>" target="_blank" title="<?php esc_html_e('Share on facebook', 'wpopal-core'); ?>">
            <i class="fa fa-facebook"></i>
        </a>
 

 
        <a class="wpopal-social-twitter" href="http://twitter.com/home?status=<?php the_title(); ?> <?php the_permalink(); ?>" target="_blank" title="<?php esc_html_e('Share on Twitter', 'wpopal-core'); ?>">
            <i class="fa fa-twitter"></i>
        </a>
 

 
        <a class="wpopal-social-linkedin"  href="http://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>" target="_blank" title="<?php esc_html_e('Share on LinkedIn', 'wpopal-core'); ?>">
            <i class="fa fa-linkedin"></i>
        </a>
 

 
        <a class="wpopal-social-tumblr" href="http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink()); ?>&amp;name=<?php echo urlencode(get_the_title()); ?>&amp;description=<?php echo urlencode(get_the_excerpt()); ?>" target="_blank" title="<?php esc_html_e('Share on Tumblr', 'wpopal-core'); ?>">
            <i class="fa fa-tumblr"></i>
        </a>
 

 
        <a class="wpopal-social-google" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" title="<?php esc_html_e('Share on Google plus', 'wpopal-core'); ?>">
            <i class="fa fa-google-plus"></i>
        </a>
 

 
        <a class="wpopal-social-pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;description=<?php echo urlencode(get_the_title()); ?>&amp;; ?>" target="_blank" title="<?php esc_html_e('Share on Pinterest', 'wpopal-core'); ?>">
            <i class="fa fa-pinterest"></i>
        </a>
 

 
        <a class="wpopal-social-envelope" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>" title="<?php esc_html_e('Email to a Friend', 'wpopal-core'); ?>">
            <i class="fa fa-envelope"></i>
        </a>
 
</div>
