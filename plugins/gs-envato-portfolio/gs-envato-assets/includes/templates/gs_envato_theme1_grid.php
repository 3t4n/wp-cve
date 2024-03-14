<?php

$output .= '<div class="container">';
	$output .= '<div class="row">';

        for ($i=0; $i <$count; $i++) { 

            if (empty($gs_envato_items[$i]['live_preview_url'])) {
                return;
            }

            $output .= '<div class="col-md-'.$columns.' col-sm-6 col-xs-12">';
                $output .= '<div class="single-item">';
                    $output .= '<img src="'.$gs_envato_items[$i]['live_preview_url'].'"/>';

                    $output .= "<div class='single-envitem-title'>";
                        $output .= "<div class='gs-envitem-name'>".$gs_envato_items[$i]['item']."</div>";    
                    $output .= "</div>";
                $output .= '</div>';
            $output .= '</div>'; // end col
        }

    $output .= '</div>'; // end row
$output .= '</div>'; // end container
return $output;