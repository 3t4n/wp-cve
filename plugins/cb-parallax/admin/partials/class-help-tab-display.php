<?php
namespace CbParallax\Admin\Partials;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for creating and displaying the help tab.
 *
 * @since             0.9.0
 * @package           bonaire
 * @subpackage        bonaire/admin/partials
 * @author            Demis Patti <demis@demispatti.ch>
 */
class cb_parallax_help_tab_display {
	
	/**
	 * Returns a string containing the 'Help Tab' content.
	 *
	 * @param string $domain
	 *
	 * @since 0.9.0
	 * @return string $html
	 */
	public static function help_tab_display( $domain ) {
		
		ob_start();
		?>

        <div id="cb-parallax-help-tabs">
            <ul class="nav">
                <li><a href="#tabs-1"><?php echo __( 'General Settings', $domain ) ?></a></li>
                <li><a href="#tabs-2"><?php echo __( 'Scroll Directions', $domain ) ?></a></li>
                <li><a href="#tabs-3"><?php echo __( 'Plugin Information and Privacy Notices', $domain ) ?></a></li>
            </ul>
            <div id="tabs-1" class="cb-parallax-help-tabs"><?php echo self::tab_content_general_settings( $domain ) ?></div>
            <div id="tabs-2" class="cb-parallax-help-tabs"><?php echo self::tab_content_scroll_directions( $domain ) ?></div>
            <div id="tabs-3" class="cb-parallax-help-tabs"><?php echo self::tab_content_plugin_information_and_privacy_notices( $domain ) ?></div>
        </div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
	 * Returns a string containing the content of this 'Help Tab' tab.
	 *
	 * @param string $domain
	 *
	 * @since 0.9.0
	 * @return string $html
	 */
	public static function tab_content_general_settings( $domain ) {
		
		/**
		 * @todo: extract texts from quickfix-images into html
		 */
	    $img_postfix = 'de_DE' === get_locale() ? 'de' : 'default';
		ob_start();
		?>

        <div class="item-container">
            <h4 class="item-title">
				<?php echo __( 'Background Image Settings', $domain ) ?>
            </h4>
            <div class="image-holder">
                <img src="<?php echo CBPARALLAX_ROOT_URL . 'admin/images/contextual-help/tab-1/settings-page-' . $img_postfix . '.png'?>" alt="Contextual Help Image"/>
            </div>
            <div class="item-description">
				<?php /*echo __( 'Soon, there will be more.', $domain )*/ ?>
            </div>

            <h4 class="item-title">
		        <?php echo __( 'Manual adjustments', $domain ) ?>
            </h4>
            <div class="item-description">
		        <?php echo __( 'Since WordPress themes differ in structure, this step is not (yet) automated: It will most likely be necessary to set a "boxed" layout or to add opacity to container(s) which conceal the background image.', $domain ) ?>
            </div>
        </div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
	 * Returns a string containing the content of this 'Help Tab' tab.
	 *
	 * @param string $domain
	 *
	 * @since 0.9.0
	 * @return string $html
	 */
	public static function tab_content_scroll_directions( $domain ) {
		
		$img_postfix = 'de_DE' === get_locale() ? 'de' : 'default';
		ob_start();
		?>

        <div class="item-container">
            <h4 class="item-title">
				<?php echo __( 'Vertical Image Direction', $domain ) ?>
            </h4>
            <div class="image-holder">
                <img src="<?php echo CBPARALLAX_ROOT_URL . 'admin/images/contextual-help/tab-2/vertical-' . $img_postfix . '.png' ?> " alt="Contextual Help Image"/>
            </div>
            <div class="item-description">
				<?php /*echo __( 'Soon, there will be more.', $domain )*/ ?>
            </div>


            <h4 class="item-title">
				<?php echo __( 'To Top', $domain ) ?>
            </h4>
            <div class="image-holder">
                <img src="<?php echo CBPARALLAX_ROOT_URL . 'admin/images/contextual-help/tab-2/vertical-to-top-' . $img_postfix . '.png' ?> " alt="Contextual Help Image"/>
            </div>
            <div class="item-description">
				<?php /*echo __( 'Soon, there will be more.', $domain )*/ ?>
            </div>


            <h4 class="item-title">
				<?php echo __( 'To Bottom', $domain ) ?>
            </h4>
            <div class="image-holder">
                <img src="<?php echo CBPARALLAX_ROOT_URL . 'admin/images/contextual-help/tab-2/vertical-to-bottom-' . $img_postfix . '.png' ?> " alt="Contextual Help Image"/>
            </div>
            <div class="item-description">
				<?php /*echo __( 'Soon, there will be more.', $domain )*/ ?>
            </div>
        </div>


        <div class="item-container">
            <h4 class="item-title">
				<?php echo __( 'Horizontal Image Direction', $domain ) ?>
            </h4>
            <div class="image-holder">
                <img src="<?php echo CBPARALLAX_ROOT_URL . 'admin/images/contextual-help/tab-2/horizontal-' . $img_postfix . '.png' ?> " alt="Contextual Help Image"/>
            </div>
            <div class="item-description">
				<?php /*echo __( 'Soon, there will be more.', $domain )*/ ?>
            </div>


            <h4 class="item-title">
				<?php echo __( 'To The Left', $domain ) ?>
            </h4>
            <div class="image-holder">
                <img src="<?php echo CBPARALLAX_ROOT_URL . 'admin/images/contextual-help/tab-2/horizontal-to-the-left-' . $img_postfix . '.png' ?> " alt="Contextual Help Image"/>
            </div>
            <div class="item-description">
				<?php /*echo __( 'Soon, there will be more.', $domain )*/ ?>
            </div>


            <h4 class="item-title">
				<?php echo __( 'To The Right', $domain ) ?>
            </h4>
            <div class="image-holder">
                <img src="<?php echo CBPARALLAX_ROOT_URL . 'admin/images/contextual-help/tab-2/horizontal-to-the-right-' . $img_postfix . '.png' ?> " alt="Contextual Help Image"/>
            </div>
            <div class="item-description">
            </div>
        </div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
	 * Returns a string containing the content of this 'Help Tab' tab.
	 *
	 * @param string $domain
	 *
	 * @since 0.9.0
	 * @return string $html
	 */
	public static function tab_content_plugin_information_and_privacy_notices( $domain ) {
		
		ob_start();
		?>

        <div class="item-container">
            <div class="item-description">
                <h4><?php echo __( 'Privacy notices', $domain ) ?></h4>
                <span><?php echo __( 'This plugin does not:', $domain ) ?></span>
                <ul class="list">
                    <li>1. <?php echo __( 'Track users', $domain ) ?></li>
                    <li>2. <?php echo __( 'Process personal user data', $domain ) ?></li>
                    <li>3. <?php echo __( 'Use cookies', $domain ) ?></li>
                </ul>
            </div>
        </div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
}
