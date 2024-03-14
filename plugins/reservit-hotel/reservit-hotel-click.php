<?php
/*
*      Reservit Hotel Click
*      Version: 1.2
*      By Reservit
*
*      Contact: http://www.reservit.com/hebergement
*      Created: 2017
*
*      Copyright (c) 2017, Reservit. All rights reserved.
*
*      Licensed under the GPLv2 license - https://www.gnu.org/licenses/gpl-2.0.html
*
*/
    function set_reservit_window_closed(){
        /*if( !session_id() ){
            session_start();
        }*/
    
        $reservitsafepost = stripslashes( strip_tags($_POST['RsvitWidgetboxClosed']));
        if( $reservitsafepost =="yes"){
            $_SESSION['RsvitWidgetboxClosed']="$reservitsafepost";
        }
    }

    
    if (isset($_POST['RsvitWidgetboxClosed'])){
        set_reservit_window_closed();
    }
    //this variable will be used to check if the user already closed the widget window one time
