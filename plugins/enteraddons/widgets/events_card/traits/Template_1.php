<?php 
namespace Enteraddons\Widgets\Events_Card\Traits;
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
        $settings = self::getSettings();

        $imgStyle = !empty( $settings['image_style'] ) ? $settings['image_style'] : '';
        $imageHoverOverlay = !empty( $settings['image_hover_overlay'] ) ? 'enteraddons-overlay hover-overlay' : '';
        $class = implode( ' ', [$imgStyle,$imageHoverOverlay] );

        $eventStyle = !empty( $settings['event_style'] ) ? $settings['event_style'] : 'style-1';

        $styleOneWrapperClass = $styleOneInnerClass = $styleOneInfoClass = '';

        if( 'style-1' == $eventStyle ) {
            $styleOneWrapperClass = 'enteraddons-d-flex enteraddons-flex-wrap enteraddons-flex-md-nowrap media enteraddons-align-items-center';
            $styleOneInnerClass = 'media--body enteraddons-d-flex enteraddons-align-items-sm-center enteraddons-flex-column enteraddons-flex-sm-row';
            $styleOneInfoClass = 'enteraddons-pb-4 mb-4 enteraddons-pr-sm-3 enteraddons-mr-sm-3 enteraddons-pb-sm-0 enteraddons-mb-sm-0';
        } 


		?>
        <div class="enteraddons-wid-con enteraddons-event-card-<?php echo esc_attr( $eventStyle ); ?>">
            <div class="enteraddons-single-event enteraddons-single-event-card <?php echo esc_attr( $styleOneWrapperClass ); ?>">
                <div class="enteraddons-event-image <?php echo esc_attr( $class ); ?>">
                    <?php
                    // Date
                    self::eventDate();
                    // Image
                    self::image();
                    ?>
                </div>
                <div class="enteraddons-event-content <?php echo esc_attr( $styleOneInnerClass ); ?>">
                    <div class="el-event-info <?php echo esc_attr( $styleOneInfoClass ); ?>">
                        <?php
                        // Title
                        self::title();
                        // Event Type
                        self::eventType();
                        // Short Description
                        self::shortDescription();
                        ?>
                    </div>
                    <div class="el-event-location text-sm-center">
                        <?php
                        // Location
                        self::eventPlace();
                        // Time
                        self::eventTime();
                        // Price
                        self::eventPrice();
                        // Button
                        self::button();
                        ?>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

}