<?php
/*
|-------------------------------------------------------------
| Function to generate HTML
|-------------------------------------------------------------
*/
function ecsa_generate_html($placeholder, $show_events, $disable_past, $content_type, $layout)
{
    wp_enqueue_style('ecsa-styles');
    wp_enqueue_script('ecsa-typeahead');
    wp_enqueue_script('ecsa-handlebars');
    wp_enqueue_script('ecsa-script');

    $no_up_result = __('There is no Upcoming Event', 'ecsa');
    $no_past_result = ($disable_past == 'true') ? '' : __('There is no Past Event', 'ecsa');

    $html = '';
    $up_ev_heading = __('Upcoming Events', 'ecsa');
    $past_ev_heading = __('Past Events', 'ecsa');

    $html .= '<div class="ecsa-search-box-skelton layout-' . esc_attr($layout) . '" id="ecsa-search">
        <input id="ecsa-search-box" type="text" disabled="disabled">
        <div class="ecsa-search-icon"><div class="icon-img"><img src="' . ECSA_URL . '/assets/images/search_icon.png"></div></div>
    </div>';

    $html .= '<div style="display:none" data-style-full="' . esc_attr($content_type) . '" data-no-up-result="' . esc_attr($no_up_result) . '" data-no-past-result="' . esc_attr($no_past_result) . '" data-show-events="' . esc_attr($show_events) . '" data-disable-past="' . esc_attr($disable_past) . '" data-up-ev-heading="' . esc_attr($up_ev_heading) . '" data-past-ev-heading="' . esc_attr($past_ev_heading) . '" class="ecsa-search-field  ecsa-search-load layout-' . esc_attr($layout) . '" id="ecsa-search">
        <input id="ecsa-search-box" class="typeahead" type="text" placeholder="' . esc_attr($placeholder) . '" >
        <div class="ecsa-search-icon"><div class="icon-img"><img src="' . ECSA_URL . '/assets/images/search_icon.png"></div></div>
    </div>';

    // Check content_type and add the appropriate script tag
    if ($content_type === 'basic') {
        $html .= '<script id="ecsa-search_temp_short" type="text/x-handlebars-template">
            <div class="ecsa-search-sugestions">
                <a href="{{url}}">
                    <div class="ecsa-info">
                        <span class="ecsa-event-name">{{name}}</span>
                    </div>
                </a>
            </div>
        </script>';
    } else {
        $html .= '<script id="ecsa-search_temp_full" type="text/x-handlebars-template">
            <div class="ecsa-search-sugestions">
                <a href="{{url}}">
                    <div class="ecsa-img">
                        <img src="{{fimg}}">
                    </div>
                    <div class="ecsa-info">
                        <span class="ecsa-event-name">{{name}}</span>
                        <span class="ecsa-event-date">{{StartDate}}</span>
                        <span class="ecsa-venue">{{EventVenue}}</span>
                    </div>
                </a>
            </div>
        </script>';
    }

    return $html;
}

/*
|-------------------------------------------------------------
| Get all Events (The Events Calendar)
|-------------------------------------------------------------
*/
function ecsa_get_searchdata()
{
    $atts = '';
    if (!function_exists('tribe_get_events')) {
        return;
    }
    global $post;

    $url = '';
    $event_venue = '';
    $event_title = '';
    $feat_img_url = '';
    $event_search_arr = array();
    $event_search_arr_future = array();

    if (!check_ajax_referer("ajax-nonce", 'nonce_val', false)) {
        wp_send_json_error('Invalid security token.');
    }

    if (isset($_REQUEST['display']) && $_REQUEST['display'] == 'upcoming') {
        $list_order = 'ASC';
    } elseif (isset($_REQUEST['display']) && $_REQUEST['display'] == 'past') {
        $list_order = 'DESC';
    }

    $all_events = tribe_get_events(
        apply_filters(
            'ect_args_filter',
            array(
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'eventDisplay' => 'custom',
                'tax_query' => 'slug',
                'orderby' => 'event_date',
                'order' => $list_order,
            ),
            $atts
        )
    );

    $i = 0;
    if ($all_events) {
        foreach ($all_events as $post) :
            setup_postdata($post);
            $url = esc_url(tribe_get_event_link());
            $event_ID = $post->ID;

            $event_title = html_entity_decode(get_the_title());
            $feat_img_url = wp_get_attachment_image_src(get_post_thumbnail_id($event_ID), 'thumbnail', false);

            if (is_array($feat_img_url) && !empty($feat_img_url)) {
                $event_img = $feat_img_url[0];
            } else {
                $event_img = '' . ECSA_URL . 'assets/images/event-template-bg.png';
            }

            $event_st_dt = tribe_get_start_date($event_ID, false, 'D, d F, Y h:i A');
            $event_start_date = strtotime(tribe_get_start_date($event_ID, false, 'Y-m-dTg:i'));
            $event_end_date = strtotime(tribe_get_end_date($event_ID, false, 'Y-m-dTg:i'));
            $current_date = strtotime(gmdate('Y-m-dTg:i'));
            $venue_details = tribe_get_venue_details($event_ID);
            $event_venue = strip_tags($venue_details['address']);

            if ($event_start_date < $current_date && $current_date > $event_end_date) {
                $event_search_arr[] = array(
                    'url' => $url,
                    'name' => $event_title,
                    'fimg' => $event_img,
                    'StartDate' => $event_st_dt,
                    'EventVenue' => $event_venue,
                );
            } else {
                $event_search_arr_future[] = array(
                    'url' => $url,
                    'name' => $event_title,
                    'fimg' => $event_img,
                    'StartDate' => $event_st_dt,
                    'EventVenue' => $event_venue,
                );
            }

        endforeach;
        wp_reset_postdata();
        if ($_REQUEST['display'] == 'upcoming') {
            die(json_encode($event_search_arr_future, JSON_UNESCAPED_SLASHES));
        } elseif ($_REQUEST['display'] == 'past') {
            die(json_encode($event_search_arr, JSON_UNESCAPED_SLASHES));
        }
    }
}
