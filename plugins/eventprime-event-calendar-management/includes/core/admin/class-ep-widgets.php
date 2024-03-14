<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class for admin menus
 */
class EventM_Widgets {
    public function __construct() {    
        include_once  __DIR__ . '/widgets/class-popular-event-types.php';
        include_once  __DIR__ . '/widgets/class-featured-event-types.php';
        include_once  __DIR__ . '/widgets/class-popular-event-venues.php';
        include_once  __DIR__ . '/widgets/class-featured-event-venues.php';
        include_once  __DIR__ . '/widgets/class-popular-event-organizers.php';
        include_once  __DIR__ . '/widgets/class-featured-event-organizers.php';
        include_once  __DIR__ . '/widgets/class-popular-event-performers.php';
        include_once  __DIR__ . '/widgets/class-featured-event-performers.php';
        //include_once  __DIR__ . '/widgets/class-event-venue-map.php';
        include_once  __DIR__ . '/widgets/class-event-countdown.php';
        include_once  __DIR__ . '/widgets/class-event-calendar.php';
        include_once  __DIR__ . '/widgets/class-event-slider.php';
    }

}

return new EventM_Widgets();
