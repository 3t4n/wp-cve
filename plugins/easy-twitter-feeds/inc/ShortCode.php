<?php
class ETFShortCode{
	public function __construct(){
		add_action( 'admin_enqueue_scripts', [$this, 'enqueueScripts'] );
		add_action( 'wp_enqueue_scripts', [$this, 'enqueueScripts'] );
		add_shortcode( 'timeline', [$this, 'timelineShortCode'] );
		add_shortcode( 'follow_button', [$this, 'followButtonShortCode'] );
	}

    function enqueueScripts(){
        wp_enqueue_script( 'widget-js', ETF_DIR_URL . 'assets/js/widget.js' , array(), ETF_VERSION , false );
    }

    function timelineShortCode( $atts ){
        extract( shortcode_atts( array(
            'username' => null,
            'width' => null,
            'height' => null,				
            'theme' => 'dark',
            'title' => null,
            'lang' => null,		
            'chrome' => null,
        ), $atts ) );
        
        ob_start();
        if (!empty($username)){  ?>

<a class="twitter-timeline" data-width="<?php echo esc_attr($width); ?>" data-lang="<?php echo esc_attr($lang);  ?>"
    data-chrome="<?php echo esc_attr($chrome);  ?>" data-height="<?php echo esc_attr($height); ?>"
    data-theme="<?php echo esc_attr($theme); ?>" href="https://twitter.com/<?php echo esc_attr($username); ?>"
    rel=”nofollow”>
    <?php echo esc_html($title); ?> <?php echo esc_html($username); ?>
</a>

<?php }else{ echo '<h2>You must enter your Twitter handle in the username attribute of the shortcode.  </h2>';}

        return ob_get_clean();
    }

    function followButtonShortCode( $atts ){
        extract( shortcode_atts( array(
            'username' => null,
            'size' => null,
            'count' => null,
        ), $atts ) );
    
        ob_start();
        if (!empty($username)){ ?>

<a href="https://twitter.com/<?php echo esc_attr($username); ?>" class="twitter-follow-button"
    data-size="<?php echo esc_attr($size); ?>" data-show-count="<?php echo esc_attr($count); ?>">
    Follow @<?php echo esc_html($username); ?>
</a>

<?php }else{ echo '<h2>You must enter your Twitter handle in the username attribute of the shortcode.  </h2>';}
    
        return ob_get_clean();
    }
}
new ETFShortCode;