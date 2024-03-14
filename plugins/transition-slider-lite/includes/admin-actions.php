<?php

    $current_action = $current_id = '';

    if (isset($_GET['action']) ) {
        $current_action = sanitize_text_field($_GET['action']);
    }

    if (isset($_GET['id']) ) {
        $current_id = sanitize_text_field($_GET['id']);
    }

    $slider_ids = get_option('transitionslider_ids');

    if(!$slider_ids){
        $slider_ids = array();
    }
    $sliders = array();

    foreach ($slider_ids as $id) {
        $slider = get_option('transitionslider_'.$id);
        if($slider){
            $sliders[$id] = $slider;
        }else{
            $slider_ids = array_diff($slider_ids, array($id));
        }
    }

    update_option('transitionslider_ids', $slider_ids);

    switch( $current_action ) {

        case 'edit':

            include("edit-slider.php");

            break;

        case "add_new":

            $new_id = 0;
            $highest_id = 0;

            foreach ($slider_ids as $id) {
                if((int)$id > $highest_id) {
                    $highest_id = (int)$id;
                }
            }

            $current_id = $highest_id + 1;
            $slider = array(
                "id" => $current_id,
                "name" => "slider " . $current_id,
                "instanceName" => "slider " . $current_id,
                "date" => current_time( 'mysql' ),
                "status" => "draft"
            );
            $sliders[$current_id] = $slider;

            include("edit-slider.php");

            break;

        case 'import_from_json_confirm':
            $json = stripslashes($_POST['sliders']);

            $newSliders = slider_objectToArray(json_decode($json));

            if((string)$json != "" && is_array($newSliders)){
                foreach ($newSliders as $s) {
                    $new_id = 1;
                    foreach ($slider_ids as $id) {
                        if((int)$id >= $new_id) {
                            $new_id = (int)$id + 1;
                        }
                    }
                    $s["id"] = $new_id;
                    $s["date"] = current_time( 'mysql' );
                    add_option('transitionslider_'.(string)$new_id, $s);
                    array_push($slider_ids,(string)$new_id);
                    $sliders[(string)$new_id] = $s;
                }
                update_option('transitionslider_ids', $slider_ids);
            }

            include("sliders.php");

            break;

        case 'dashboard':

            include("sliders.php");

            break;

       case 'getting_started':

            include("getting-started.php");

            break;

        case 'getting_started_dont_show':

            update_option('transitionslider_getting_started_dont_show', true);
            include("sliders.php");

            break;

        default:

            if(!get_option('transitionslider_getting_started_dont_show'))

                include("getting-started.php");

            else

                include("sliders.php");

            break;

    }

if(!function_exists("slider_objectToArray")){

    function slider_objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
		}

		if (is_array($d)) {
			return array_map(__FUNCTION__, $d);
		}
		else {
			return $d;
		}
	}

}


