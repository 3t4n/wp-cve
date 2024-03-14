<?php 
namespace Enteraddons\Widgets\Team\Traits;
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

trait Template_2 {
	
	public static function markup_style_2() {
		?>
            <div class="ea-hcard-content">
                <div class="ea-hcard-image">
                    <?php self::image(); ?>
                    <div class="ea-hcard-hover">
                        <?php 
                            self::hover_title();
                            self::hover_subtitle();
                            self::descriptions();
                            self::socialIcons();
                            self::link();
                        ?>   
                    </div>
                    <?php
                    echo '<div class="ea-hteam-content">';
                        self::name2(); 
                        self::designation2();  
                    echo '</div>';
                    ?>
                </div>
            </div>
		<?php
	}

}