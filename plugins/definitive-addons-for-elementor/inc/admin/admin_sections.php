<?php
/**
 * Definitive Addons Admin Section
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://wordpress.org/support/article/administration-screens/
 */
namespace Definitive_Addons_Elementor\Elements;

use Definitive_Addons_Elementor\Elements\Definitive_Addon_Elements;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Definitive_Addons_Admin_Section
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://wordpress.org/support/article/administration-screens/
 */
class Definitive_Addons_Admin_Section
{
    /**
     * Class constructor
     *
     * @since Definitive Addons for Elementor 1.5.13
     *
     * @access public
     */
    public function __construct()
    {
    }
    
    /**
     * Admin dashboard support
     *
     * @since Definitive Addons for Elementor 1.5.13
     *
     * @return void.
     */
    public static function dafe_get_support()
    {
        ?>
    <div class="dafe-admin-tab-bar" id="dafe-definitive-addons-welcome">
    
        
            <div class="dafe-admin-support-container">
                    
                    <div class="item support_padd_margin">
                        <h3 class="support-item"><?php echo esc_html('Installation & How to start', 'definitive-addons-for-elementor'); ?></h3>
                        <p><?php esc_html_e('Installation detail of plugins is available.', 'definitive-addons-for-elementor') ?></p>
                        <p><a href="<?php echo esc_url('https://definitive-docs.softfirm.net/'); ?>"  target="_blank" class="button"><?php esc_html_e('Install & Start', 'definitive-addons-for-elementor'); ?></a></p>
                    </div>
                    
                    <div class="item support_padd_margin">
                        <h3 class="support-item"><?php esc_html_e('Elements Demos', 'definitive-addons-for-elementor'); ?></h3>
                        <p><?php esc_html_e('Demos of 33 elements are avaible right now.', 'definitive-addons-for-elementor') ?></p>
                        <p><a href="<?php echo esc_url('https://softfirm.net/demos/'); ?>"  target="_blank" class="button"><?php esc_html_e('Plugin Demos', 'definitive-addons-for-elementor'); ?></a></p>
                    </div>
  
                    <div class="item support_padd_margin">
                        <h3 class="support-item"><?php esc_html_e('Documentation', 'definitive-addons-for-elementor'); ?></h3>
                        <p><?php esc_html_e('Please view our documentation page to setup your website.', 'definitive-addons-for-elementor') ?></p>
                        <p><a href="<?php echo esc_url('https://definitive-docs.softfirm.net/'); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('Documentation', 'definitive-addons-for-elementor'); ?></a></p>
                    </div>

                    <div class="item support_padd_margin">
                        <h3 class="support-item"><?php esc_html_e('Plugin Support', 'definitive-addons-for-elementor'); ?></h3>
                        <p><?php esc_html_e('Please put it in our dedicated support forum.', 'definitive-addons-for-elementor') ?></p>
                        <p><a href="<?php echo esc_url('https://support.themenextlevel.com/'); ?>"  target="_blank" class="button"><?php esc_html_e('Plugin Support', 'definitive-addons-for-elementor'); ?></a></p>
                    </div>

            </div>
  
	</div>
        <?php
    }
    
    /**
     * Admin dashboard navigation
     *
     * @since Definitive Addons for Elementor 1.5.13
     *
     * @return void.
     */
    public static function dafe_get_admin_nav()
    {
        ?>

<ul class="definitive-admin-tab-nav">

    
        <li>
            <a href="#dafe-definitive-addons-welcome">
        <?php _e('Welcome', 'definitive-addons-for-elementor'); ?>
            </a>
        </li>

        <li>
            <a href="#dafe-definitive-addons-addons">
        <?php _e('Addons', 'definitive-addons-for-elementor'); ?>
            </a>
        </li>


</ul>
        <?php
    }
    
}
$definitive_section = new Definitive_Addons_Admin_Section();