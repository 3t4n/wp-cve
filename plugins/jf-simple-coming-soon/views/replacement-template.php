<?php
/**
 * HTML Markup for the Coming Soon page
 *
 * @package   JFSimpleComingSoon
 * @author    Jerome Fitzpatrick <jerome@jeromefitzpatrick.com>
 * @license   GPL-2.0+
 * @link      http://www.jeromefitzpatrick.com
 * @copyright 2013 Jerome Fitzpatrick
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width" />
        <title><?php echo $this->title_field(); ?> <?php wp_title( '|', true, 'right' ); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php wp_head(); ?>
        
        <style>
            <?php echo JFSimpleComingSoon::preset_css(); // default css?>
            <?php echo $this->custom_css(); // custom css as specified on options page ?>
        </style>
    </head>

    <body id='jf-scs'>
        <div class='content-area-wrapper'>
            <div class='content-area'>
                <?php
                    $this->splash_page_content();
                ?>
            </div>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>