<?php 
namespace Enteraddons\Widgets\Typing_Animation\Traits;
/**
 * Enteraddons Typing Animation template class
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
        $typingSettings = self::typingSettings();
		
		?>	
			<div class="ea-at-wrapper"  data-typing-settings="<?php echo htmlspecialchars( $typingSettings, ENT_QUOTES, 'UTF-8'); ?>">
				<p class="ea-animate-typing">
					<?php 
						self::first_text(); 
						echo '<span class="ea-typed"></span>';
						self::second_text();
					?>
				</p>
			</div>
		<?php
	}

}