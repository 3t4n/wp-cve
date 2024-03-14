<?php 
namespace Enteraddons\Widgets\Progressbar\Traits;
/**
 * Enteraddons team template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {
	
	public static function markup_style_1() {
        $settings   = self::getSettings();

        $newClass = implode(' ', [ $settings['percentage_position'],$settings['title_position'] ]);
		?>
        <div class="enteraddons-process-wrapper">
            <div class="enteraddons-process-bar-wrapper">
                
                <span class="process-bar <?php echo esc_attr( $newClass ); ?>" data-process-width="<?php echo esc_attr( $settings['progress']['size'] ); ?>">
                <?php 
                self::name();
                self::progress();
                ?>
                </span>
            </div>
        </div>
		<?php
	}

}