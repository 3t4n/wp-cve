<?php
/**
 * Taslak Ekranını Oluşturan Klastır
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://yemlihakorkmaz.com
 * @since      1.0.0
 *
 * @package    Korkmaz_contract-taslak-ekrani
 * @subpackage Korkmaz_contract-taslak-ekrani/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Korkmaz_contract-taslak-ekrani
 * @subpackage Korkmaz_contract-taslak-ekrani/includes
 * @author     Yemliha KORKMAZ <yemlihakorkmaz@hotmail.com>
 */
class korkmaz_woo_sales_contract_taslak_ekrani
{
	/**
	 * taslak ekrani burada bulunur
	 *
	 * @since    1.0.0
	 */
	public function taslak_ekrani($editor_adi)
	{

		if (isset($_POST['submit'])) {

			$gelenmetin = wp_filter_post_kses(stripslashes($_POST[$editor_adi]));
			update_option($editor_adi, $gelenmetin);
		}
		?>
        <form method="POST">
			<?php $settings = [
				'wpautop'       => false,
				'textarea_name' => $editor_adi
			];
			wp_editor((stripslashes(get_option($editor_adi))), $editor_adi, $settings);
			submit_button();
			?>
        </form>
		<?php
	}
}
