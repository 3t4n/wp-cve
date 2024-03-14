<?php 
namespace Enteraddons\Widgets\Content_Ticker\Traits;
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
        $settings = self::getDisplaySettings();
        $tickerSettings = self::ticketSettings();
		?>
		<div class="enteraddons-news-ticker-wrap">
            <?php 
            self::title();
            ?>
            <div class="enteraddons-news-ticker-box">
                <ul class="enteraddons-news-ticker"  data-tickersettings="<?php echo htmlspecialchars( $tickerSettings, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php 
                        if( !empty( $settings['content_ticker'] ) ) {
                            foreach( $settings['content_ticker'] as $item ) { 
                           echo '<li>';
                            echo self::linkOpen( $item );
                                if(!empty( $item['content_ticker_news']) ){
                                    echo esc_html($item['content_ticker_news']);
                                }
                            echo self::linkClose();
                           echo '</li>';
                        }}
                    ?>
                </ul>
            </div>
            <div class="enteraddons-news-ticker-controls enteraddons-news-ticker-horizontal-controls">
                <?php
                self::button();
                 ?>
            </div>
        </div>    
		<?php
	}

}