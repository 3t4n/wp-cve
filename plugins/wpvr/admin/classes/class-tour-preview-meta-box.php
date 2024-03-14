<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Preview meta box related functionalities
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Tour_Preview extends WPVR_Meta_Box
{

    /**
     * @var string
     * @since 8.0.0
     */
    protected $title = '';

    /**
     * Metabox ID
     * 
     * @var string
     * @since 8.0.0
     */
    protected $slug = '';

    /**
     * @var string
     * @since 8.0.0
     */
    protected $post_type = '';

    /**
     * Metabox context
     * 
     * @var string
     * @since 8.0.0
     */
    protected $context = '';

    /**
     * Metabox priority
     * 
     * @var string
     * @since 8.0.0
     */
    protected $priority = '';


    public function __construct($slug, $title, $post_type, $context, $priority)
    {
        if ($slug == '' || $context == '' || $priority == '') {
            return;
        }

        if ($title == '') {
            $this->title = ucfirst($slug);
        }

        if (empty($post_type)) {
            return;
        }

        $this->title     = $title;
        $this->slug      = $slug;
        $this->post_type = $post_type;
        $this->context   = $context;
        $this->priority  = $priority;

        add_action('add_meta_boxes', array($this, 'register'));
    }


    /**
     * Register review custom meta box
     * 
     * @param string $post_type
     * 
     * @return void
     * @since 8.0.0
     */
    public function register($post_type)
    {
        if ($post_type == $this->post_type) {
            add_meta_box($this->slug, $this->title, array($this, 'render'), $post_type, $this->context, $this->priority);
        }
    }


    /**
     * Render custom meta box
     * 
     * @param object $post
     * 
     * @return void
     * @since 8.0.0
     */
    public function render($post)
    {
        $post = '';
        $id = '';
        $postdata = array();
        $post = get_post();
        $id = $post->ID;

        $postdata = get_post_meta($id, 'panodata', true);
        $panoid = 'pano' . $id;

        if (isset($postdata['vidid'])) {
            ob_start();
?>
            <div class="iframe-wrapper">
                <i class="fa fa-times" id="cross"></i>
                <div id="custom-ifram" style="display: none;"></div>
                <div id="<?php echo 'pano' . $id; ?>" class="pano-wrap dfgsdg" style="height: 100%;">
                    <?php echo $postdata['panoviddata']; ?>
                    <?php if ($postdata['vidtype'] == 'selfhost') { ?>
                        <script>
                            videojs(<?php echo $postdata['vidid']; ?>, {
                                plugins: {
                                    pannellum: {}
                                }
                            });
                        </script>
                    <?php } ?>
                </div>
            </div>
        <?php
            ob_end_flush();
        } elseif (isset($postdata['streetviewdata'])) {
            ob_start();
        ?>
            <div class="iframe-wrapper">
                <i class="fa fa-times" id="cross"></i>
                <div id="custom-ifram" style="display: none;">

                </div>
                <div id="<?php echo 'pano' . $id; ?>" class="pano-wrap" style="height: 100%;">
                    <?php
                    echo $postdata['streetviewdata'];
                    ?>
                </div>
            </div>

            <div class="rex-add-coordinates" style="text-align: center;">
                <ul>
                    <li>
                        <div id="panodata" style="text-align: center; font-weight: bold;">
                        </div>
                    </li>
                    <li class="rex-hide-coordinates add-pitch">
                        <span class="rex-tooltiptext">Add This Position into active Hotspot</span>
                        <i class="fa fa-plus toppitch"></i>
                    </li>
                </ul>
            </div>
        <?php
            ob_end_flush();
        } elseif (isset($postdata['flat_image'])) {
            ob_start();
        ?>
            <div class="iframe-wrapper">
                <i class="fa fa-times" id="cross"></i>
                <div id="custom-ifram" style="display: none;">

                </div>
                <div id="<?php echo 'pano' . $id; ?>" class="pano-wrap" style="height: 100%;">
                    <img loading="lazy" src="<?php echo $postdata['flat_image_url']; ?>" style="width: 600px">
                </div>
            </div>

            <div class="rex-add-coordinates" style="text-align: center;">
                <ul>
                    <li>
                        <div id="panodata" style="text-align: center; font-weight: bold;">
                        </div>
                    </li>
                    <li class="rex-hide-coordinates add-pitch">
                        <span class="rex-tooltiptext">Add This Position into active Hotspot</span>
                        <i class="fa fa-plus toppitch"></i>
                    </li>
                </ul>
            </div>
        <?php
            ob_end_flush();
        } else {

            $control = false;
            if (isset($postdata['showControls'])) {
                $control = $postdata['showControls'];
            }

            $compass = false;
            if (isset($postdata['compass'])) {
                $compass = $postdata['compass'] == 'on' || $postdata['compass'] != null ? true : false;
            }
            $mouseZoom = true;
            if (isset($postdata['mouseZoom'])) {
                $mouseZoom = $postdata['mouseZoom'];
            }
            $draggable = true;
            if (isset($postdata['draggable'])) {
                $draggable = $postdata['draggable'] == 'on' || $postdata['draggable'] != null ? true : false;
            }
            $diskeyboard = false;
            if (isset($postdata['diskeyboard'])) {
                $diskeyboard = $postdata['diskeyboard'] == 'off' || $postdata['diskeyboard'] == null ? false : true;
            }
            $keyboardzoom = true;
            if (isset($postdata['keyboardzoom'])) {
                $keyboardzoom = $postdata['keyboardzoom'] == 'on' || $postdata['keyboardzoom'] != null ? true : false;
            }
            $autoload = false;
            if (isset($postdata['autoLoad'])) {
                $autoload = $postdata['autoLoad'];
            }

            $default_scene = '';
            if (isset($postdata['defaultscene'])) {
                $default_scene = $postdata['defaultscene'];
            }

            $preview = '';
            if (isset($postdata['preview'])) {
                $preview = $postdata['preview'];
            }

            $autorotation = '';
            if (isset($postdata["autoRotate"])) {
                $autorotation = $postdata["autoRotate"];
            }
            $autorotationinactivedelay = '';
            if (isset($postdata["autoRotateInactivityDelay"])) {
                $autorotationinactivedelay = $postdata["autoRotateInactivityDelay"];
            }
            $autorotationstopdelay = '';
            if (isset($postdata["autoRotateStopDelay"])) {
                $autorotationstopdelay = $postdata["autoRotateStopDelay"];
            }

            $scene_fade_duration = '';
            if (isset($postdata['scenefadeduration'])) {
                $scene_fade_duration = $postdata['scenefadeduration'];
            }

            $default_global_zoom = '';
            if (isset($postdata['hfov'])) {
                $default_global_zoom = $postdata['hfov'];
            }

            $max_global_zoom = '';
            if (isset($postdata['maxHfov'])) {
                $max_global_zoom = $postdata['maxHfov'];
            }

            $min_global_zoom = '';
            if (isset($postdata['minHfov'])) {
                $min_global_zoom = $postdata['minHfov'];
            }

            $panodata = '';
            if (isset($postdata['panodata'])) {
                $panodata = $postdata['panodata'];
            }

            $floor_map_image = '';
            $is_floor_enable = 'off';
            if (isset($postdata['floor_plan_tour_enabler'])) {
                $is_floor_enable = $postdata['floor_plan_tour_enabler'];
                if ($is_floor_enable == 'on') {
                    $floor_map_image = $postdata['floor_plan_attachment_url'];
                }
            }

            $default_data = array();
            if ($default_global_zoom != '' && $max_global_zoom != '' && $min_global_zoom != '') {
                $default_data = array("firstScene" => $default_scene, "sceneFadeDuration" => $scene_fade_duration, "hfov" => $default_global_zoom, "maxHfov" => $max_global_zoom, "minHfov" => $min_global_zoom);
            } else {
                $default_data = array("firstScene" => $default_scene, "sceneFadeDuration" => $scene_fade_duration);
            }

            $scene_data = array();

            if (!empty($panodata)) {
                foreach ($panodata["scene-list"] as $panoscenes) {

                    $scene_ititle = '';
                    if (isset($panoscenes["scene-ititle"])) {
                        $scene_ititle = sanitize_text_field($panoscenes["scene-ititle"]);
                    }

                    $scene_author = '';
                    if (isset($panoscenes["scene-author"])) {
                        $scene_author = sanitize_text_field($panoscenes["scene-author"]);
                    }

                    $scene_vaov = 180;
                    if (isset($panoscenes["scene-vaov"])) {
                        $scene_vaov = (float)$panoscenes["scene-vaov"];
                    }

                    $scene_haov = 360;
                    if (isset($panoscenes["scene-haov"])) {
                        $scene_haov = (float)$panoscenes["scene-haov"];
                    }


                    $scene_vertical_offset = 0;
                    if (isset($panoscenes["scene-vertical-offset"])) {
                        $scene_vertical_offset = (float)$panoscenes["scene-vertical-offset"];
                    }

                    $default_scene_pitch = null;
                    if (isset($panoscenes["scene-pitch"])) {
                        $default_scene_pitch = (float)$panoscenes["scene-pitch"];
                    }

                    $default_scene_yaw = null;
                    if (isset($panoscenes["scene-yaw"])) {
                        $default_scene_yaw = (float)$panoscenes["scene-yaw"];
                    }

                    $scene_max_pitch = '';
                    if (isset($panoscenes["scene-maxpitch"])) {
                        $scene_max_pitch = (float)$panoscenes["scene-maxpitch"];
                    }


                    $scene_min_pitch = '';
                    if (isset($panoscenes["scene-minpitch"])) {
                        $scene_min_pitch = (float)$panoscenes["scene-minpitch"];
                    }


                    $scene_max_yaw = '';
                    if (isset($panoscenes["scene-maxyaw"])) {
                        $scene_max_yaw = (float)$panoscenes["scene-maxyaw"];
                    }


                    $scene_min_yaw = '';
                    if (isset($panoscenes["scene-minyaw"])) {
                        $scene_min_yaw = (float)$panoscenes["scene-minyaw"];
                    }


                    $default_zoom = 100;
                    if (isset($panoscenes["scene-zoom"]) && $panoscenes["scene-zoom"] != '') {
                        $default_zoom = (int)$panoscenes["scene-zoom"];
                    } else {
                        if ($default_global_zoom != '') {
                            $default_zoom = (int)$default_global_zoom;
                        }
                    }



                    $max_zoom = 120;
                    if (isset($panoscenes["scene-maxzoom"]) && $panoscenes["scene-maxzoom"] != '') {
                        $max_zoom = (int)$panoscenes["scene-maxzoom"];
                    } else {
                        if ($max_global_zoom != '') {
                            $max_zoom = (int)$max_global_zoom;
                        }
                    }




                    $min_zoom = 50;
                    if (isset($panoscenes["scene-minzoom"]) && $panoscenes["scene-minzoom"] != '') {
                        $min_zoom = (int)$panoscenes["scene-minzoom"];
                    } else {
                        if ($min_global_zoom != '') {
                            $min_zoom = (int)$min_global_zoom;
                        }
                    }

                    $hotspot_datas = array();
                    if (isset($panoscenes["hotspot-list"])) {
                        $hotspot_datas = $panoscenes["hotspot-list"];
                    }

                    $hotspots = array();
                    foreach ($hotspot_datas as $hotspot_data) {

                        $hotspot_scene_pitch = '';
                        if (isset($hotspot_data["hotspot-scene-pitch"])) {
                            $hotspot_scene_pitch = $hotspot_data["hotspot-scene-pitch"];
                        }
                        $hotspot_scene_yaw = '';
                        if (isset($hotspot_data["hotspot-scene-yaw"])) {
                            $hotspot_scene_yaw = $hotspot_data["hotspot-scene-yaw"];
                        }

                        $hotspot_type = $hotspot_data["hotspot-type"] !== 'scene' ? 'info' : $hotspot_data["hotspot-type"];
                        $hotspot_content = '';

                        ob_start();
                        do_action('wpvr_hotspot_content', $hotspot_data);
                        $hotspot_content = ob_get_clean();

                        if (!$hotspot_content) $hotspot_content = $hotspot_data["hotspot-content"];


                        $hotspot_info = array(
                            "text" => $hotspot_data["hotspot-title"],
                            "pitch" => $hotspot_data["hotspot-pitch"],
                            "yaw" => $hotspot_data["hotspot-yaw"],
                            "type" => $hotspot_type,
                            "URL" => $hotspot_data["hotspot-url"],
                            "clickHandlerArgs" => $hotspot_content,
                            "createTooltipArgs" => $hotspot_data["hotspot-hover"],
                            "sceneId" => $hotspot_data["hotspot-scene"],
                            "targetPitch" => (float)$hotspot_scene_pitch,
                            "targetYaw" => (float)$hotspot_scene_yaw
                        );
                        array_push($hotspots, $hotspot_info);
                        if (empty($hotspot_data["hotspot-scene"])) {
                            unset($hotspot_info['targetPitch']);
                            unset($hotspot_info['targetYaw']);
                        }
                    }

                    $scene_info = array();


                    if ($panoscenes["scene-type"] == 'cubemap') {
                        $pano_type = 'cubemap';
                        $pano_attachment = array(
                            $panoscenes["scene-attachment-url-face0"],
                            $panoscenes["scene-attachment-url-face1"],
                            $panoscenes["scene-attachment-url-face2"],
                            $panoscenes["scene-attachment-url-face3"],
                            $panoscenes["scene-attachment-url-face4"],
                            $panoscenes["scene-attachment-url-face5"]
                        );

                        $scene_info = array("type" => $panoscenes["scene-type"], "cubeMap" => $pano_attachment, "pitch" => $default_scene_pitch, "maxPitch" => $scene_max_pitch, "minPitch" => $scene_min_pitch, "maxYaw" => $scene_max_yaw, "minYaw" => $scene_min_yaw, "yaw" => $default_scene_yaw, "hfov" => $default_zoom, "maxHfov" => $max_zoom, "minHfov" => $min_zoom, "title" => $scene_ititle, "author" => $scene_author, "vaov" => $scene_vaov, "haov" => $scene_haov, "vOffset" => $scene_vertical_offset, "hotSpots" => $hotspots);
                    } else {
                        $scene_info = array("type" => $panoscenes["scene-type"], "panorama" => $panoscenes["scene-attachment-url"], "pitch" => $default_scene_pitch, "maxPitch" => $scene_max_pitch, "minPitch" => $scene_min_pitch, "maxYaw" => $scene_max_yaw, "minYaw" => $scene_min_yaw, "yaw" => $default_scene_yaw, "hfov" => $default_zoom, "maxHfov" => $max_zoom, "minHfov" => $min_zoom, "title" => $scene_ititle, "author" => $scene_author, "vaov" => $scene_vaov, "haov" => $scene_haov, "vOffset" => $scene_vertical_offset, "hotSpots" => $hotspots);
                    }



                    if (isset($panoscenes["ptyscene"])) {
                        if ($panoscenes["ptyscene"] == "off") {
                            unset($scene_info['pitch']);
                            unset($scene_info['yaw']);
                        }
                    }

                    if (empty($panoscenes["scene-ititle"])) {
                        unset($scene_info['title']);
                    }
                    if (empty($panoscenes["scene-author"])) {
                        unset($scene_info['author']);
                    }

                    if (empty($scene_vaov)) {
                        unset($scene_info['vaov']);
                    }

                    if (empty($scene_haov)) {
                        unset($scene_info['haov']);
                    }

                    if (empty($scene_vertical_offset)) {
                        unset($scene_info['vOffset']);
                    }

                    if (isset($panoscenes["cvgscene"])) {
                        if ($panoscenes["cvgscene"] == "off") {
                            unset($scene_info['maxPitch']);
                            unset($scene_info['minPitch']);
                        }
                    }

                    if (empty($panoscenes["scene-maxpitch"])) {
                        unset($scene_info['maxPitch']);
                    }

                    if (empty($panoscenes["scene-minpitch"])) {
                        unset($scene_info['minPitch']);
                    }

                    if (isset($panoscenes["chgscene"])) {
                        if ($panoscenes["chgscene"] == "off") {
                            unset($scene_info['maxYaw']);
                            unset($scene_info['minYaw']);
                        }
                    }
                    if (empty($panoscenes["scene-maxyaw"])) {
                        unset($scene_info['maxYaw']);
                    }

                    if (empty($panoscenes["scene-minyaw"])) {
                        unset($scene_info['minYaw']);
                    }

                    // if (isset($panoscenes["czscene"])) {
                    //   if ($panoscenes["czscene"] == "off") {
                    //       unset($scene_info['hfov']);
                    //       unset($scene_info['maxHfov']);
                    //       unset($scene_info['minHfov']);
                    //     }
                    // }

                    $scene_array = array();
                    $scene_array = array(
                        $panoscenes["scene-id"] => $scene_info
                    );
                    $scene_data[$panoscenes["scene-id"]] = $scene_info;
                }
            }

            $pano_id_array = array();
            $pano_id_array = array("panoid" => $panoid);
            $pano_response = array();
            $pano_response = array("autoLoad" => $autoload, "showControls" => $control, 'compass' => $compass, 'mouseZoom' => $mouseZoom, 'draggable' => $draggable, 'disableKeyboardCtrl' => $diskeyboard, 'keyboardZoom' => $keyboardzoom, "preview" => $preview, "autoRotate" => $autorotation, "autoRotateInactivityDelay" => $autorotationinactivedelay, "autoRotateStopDelay" => $autorotationstopdelay, "default" => $default_data, "scenes" => $scene_data);

            if (empty($autorotation)) {
                unset($pano_response['autoRotate']);
                unset($pano_response['autoRotateInactivityDelay']);
                unset($pano_response['autoRotateStopDelay']);
            }
            if (empty($autorotationinactivedelay)) {
                unset($pano_response['autoRotateInactivityDelay']);
            }
            if (empty($autorotationstopdelay)) {
                unset($pano_response['autoRotateStopDelay']);
            }

            $response = array();
            $response = array($pano_id_array, $pano_response);
            if (!empty($response)) {
                $response = json_encode($response);
            }
            $floor_list_pointer_position = isset($postdata['floor_plan_pointer_position']) ? $postdata['floor_plan_pointer_position'] : '';
            $calltoaction = isset($postdata['calltoaction']) ? $postdata['calltoaction']: 'off';
            $buttontext = isset($postdata['buttontext']) ? $postdata['buttontext']: 'Click Here';
            $buttonurl = isset($postdata['buttonurl']) ? $postdata['buttonurl']: '';
            $calltoactionbutton_css = isset($postdata['calltoactionbutton']) ? $postdata['calltoactionbutton']: '';
            ob_start();
        ?>

            <div class="iframe-wrapper">
                <i class="fa fa-times" id="cross"></i>
                <div id="custom-ifram" style="display: none;"></div>
                <div id="<?php echo 'pano' . $id; ?>" class="pano-wrap" style="direction:ltr; height: 100%">
                    <?php if ($is_floor_enable == 'on') { ?>
                        <div class="wpvr-floor-preview outfit">
                            <div class="wpvr-floor-preview-inner">
                                <img loading="lazy" src="<?php echo $floor_map_image; ?>" alt="">
                                <?php foreach($floor_list_pointer_position as $pointer_position){
                                    echo '<div class="floor-plan-pointer ui-draggable ui-draggable-handle" id="'.$pointer_position->id.'" data-top="'.$pointer_position->data_top.'" data-left="'.$pointer_position->data_left.'" style="'.$pointer_position->style.'">'. $pointer_position->text .'</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <button class="flooplan-toggle">
                            <i class="far fa-map"></i>
                        </button>

                        <!-- <div class="wpvr-floor-preview outfit"><img src="https://i.picsum.photos/id/464/1280/800.jpg?hmac=2QzNeP3LYZ72BNsa-mB0sPYXdI-dACGeT04TMJi58TU" /></div> -->

                    <?php } ?>
                </div>

            </div>

            <div class="rex-add-coordinates" style="text-align: center;">
                <ul>
                    <li>
                        <div id="panodata" style="text-align: center; font-weight: bold;">

                        </div>
                    </li>
                    <li class="rex-hide-coordinates add-pitch">
                        <span class="rex-tooltiptext">Add This Position into active Hotspot</span>
                        <i class="fa fa-plus toppitch"></i>
                    </li>
                </ul>

                <div class="scene-gallery vrowl-carousel" style="direction:ltr;">

                </div>

            </div>

            <?php
            ob_end_flush();
            /**
             * Nasim
             * include alert modal
             */

            $is_pro = apply_filters('is_wpvr_pro_active',false);

            if($is_pro) {
                $pro = 'true';
            }
            else {
                $pro = 'false';
            }
            ?>

            <script>
                var response = <?php echo $response; ?>;
                var scenes = response[1];
                var is_pro =  <?php echo $pro; ?>;   

                if (scenes) {
                    jQuery.each(scenes.scenes, function(i) {
                        jQuery.each(scenes.scenes[i]['hotSpots'], function(key, val) {
                            if (val["clickHandlerArgs"] != "") {
                                val["clickHandlerFunc"] = wpvrhotspot;
                            }
                            if (val["createTooltipArgs"] != "") {
                                val["createTooltipFunc"] = wpvrtooltip;
                            }
                        });
                    });
                }
                if (scenes) {
                    jQuery('.scene-gallery').empty();

                    jQuery.each(scenes.scenes, function(key, val) {
                        if (val.type == 'cubemap') {
                            var img_data = val.cubeMap[0];
                        } else {
                            var img_data = val.panorama;
                        }
                        jQuery('.scene-gallery').append('<ul style="width:150px;"><li class="owlscene owl' + key + '">' + key + '</li><li title="Double click to view scene"><img loading="lazy" class="scctrl" id="' + key + '_gallery" src="' + img_data + '"></li></ul>');
                    });
                }

                if (response[1]['scenes'] != "") {
                    var panoshow = pannellum.viewer(response[0]["panoid"], scenes);

                    if (scenes.autoRotate) {
                        panoshow.on('load', function() {
                            setTimeout(function() {
                                panoshow.startAutoRotate(scenes.autoRotate, 0);
                            }, 3000);
                        });
                        panoshow.on('scenechange', function() {
                            setTimeout(function() {
                                panoshow.startAutoRotate(scenes.autoRotate, 0);
                            }, 3000);
                        });
                    }

                    var touchtime = 0;
                    if (scenes) {
                        jQuery.each(scenes.scenes, function(key, val) {
                            document.getElementById('' + key + '_gallery').addEventListener('click', function(e) {
                                if (touchtime == 0) {
                                    touchtime = new Date().getTime();
                                } else {
                                    if (((new Date().getTime()) - touchtime) < 800) {
                                        panoshow.loadScene(key);
                                        touchtime = 0;
                                    } else {
                                        touchtime = new Date().getTime();
                                    }
                                }
                            });
                        });
                    }

                }

                function wpvrhotspot(hotSpotDiv, args) {
                    var argst = args.replace(/\\/g, '');
                    jQuery("#custom-ifram").html(argst);
                    jQuery("#custom-ifram").fadeToggle();
                    jQuery(".iframe-wrapper").toggleClass("show-modal");
                    jQuery('button.ff-btn.ff-btn-submit.ff-btn-md').prop('disabled', true);

                    //------add to cart button------
                    jQuery('.wpvr-product-container p.add_to_cart_inline a.button').wrap('<span class="wpvr-cart-wrap"></span>');
                }

                function wpvrtooltip(hotSpotDiv, args) {
                    hotSpotDiv.classList.add('custom-tooltip');
                    var span = document.createElement('span');
                    if (args != null) {
                        args = args.replace(/\\/g, "");
                    }
                    span.innerHTML = args;
                    hotSpotDiv.appendChild(span);
                    span.style.marginLeft = -(span.scrollWidth - hotSpotDiv.offsetWidth) / 2 + 'px';
                    span.style.marginTop = -span.scrollHeight - 12 + 'px';
                }

                jQuery(document).ready(function($) {
                    jQuery("#cross").on("click", function(e) {
                        e.preventDefault();
                        jQuery("#custom-ifram").fadeOut();
                        jQuery(".iframe-wrapper").removeClass("show-modal");
                        jQuery('iframe').attr('src', $('iframe').attr('src'));
                    });
                });





                jQuery(document).ready(function($) {
                    $(document).on("click",".outfit img",function(e) {
                        // var sceneinfo = jQuery(".hotspotscene").html();
                            var dot_count = $(".floor-plan-pointer").length;
                            // var pano_scenes = scenes.scenes;
                            var pano_scenes = jQuery('.scene-setup').repeaterVal();
                            pano_scenes = pano_scenes['scene-list']

                            var top_offset = $(this).offset().top - $(window).scrollTop();
                            var left_offset = $(this).offset().left - $(window).scrollLeft();

                            var top_px = Math.round((e.clientY - top_offset - 12));
                            var left_px = Math.round((e.clientX - left_offset - 12));

                            var top_perc = top_px / $(this).height() * 100;
                            var left_perc = left_px / $(this).width() * 100;
                            var options = "";
                            for(var i in pano_scenes) {
                                var sceneId = pano_scenes[i]['scene-id']
                                if(sceneId != ''){
                                    options +=   '<option value="'+sceneId+'">'+sceneId+'</option>';
                                }
                            }
                            var is_backgroud = $(".floor-plan-background-custom-color").val();
                            var background_color
                            if(is_backgroud != ''){
                                background_color = is_backgroud;
                            }else{
                                background_color = '#cca92c'
                            }

                            var dot = '<div class="floor-plan-pointer" id="pointer-'+(dot_count + 1)+'" data-top="'+top_perc+'%" data-left="'+ left_perc +'%" style="background : '+background_color+';top: ' + top_perc + '%; left: ' + left_perc + '%; ">' + (dot_count + 1) + '</div>';

                            var li_content = '<li><label for="floor-plan">Pointer - '+(dot_count + 1)+':</label><select name="plan'+(dot_count + 1)+'" class="floor_plan_scene_option">'+options+'</select><button class="plan-delete" data-id="'+(dot_count + 1)+'"><i class="fas fa-trash-alt"></i></button></li>';
                            
                            $(dot).hide().appendTo($(this).parent()).fadeIn(350);
                            $(li_content).hide().appendTo('.floor-plan-pointer-list > ul').fadeIn(350);

                            if(is_pro) {
                                $(".floor-plan-pointer").draggable({
                                    containment: ".outfit",
                                    stop: function(event, ui) {
                                        var new_left_perc = parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%";
                                        var new_top_perc = parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%";
                                        var output = 'Top: ' + parseInt(new_top_perc) + '%, Left: ' + parseInt(new_left_perc) + '%';

                                        $(this).css("left", parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%");
                                        $(this).css("top", parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%");

                                        $(this).attr('data-top', parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%");
                                        $(this).attr('data-left', parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%");
                                    }
                                });
                            }
   
                });
                if(is_pro) {
                    $(".floor-plan-pointer").draggable({
                        containment: ".outfit",
                        stop: function(event, ui) {
                            var new_left_perc = parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%";
                            var new_top_perc = parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%";
                            var output = 'Top: ' + parseInt(new_top_perc) + '%, Left: ' + parseInt(new_left_perc) + '%';

                            $(this).css("left", parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%");
                            $(this).css("top", parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%");

                            $(this).attr('data-top', parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%");
                            $(this).attr('data-left', parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%");
                        }
                    });
                }
            });

            
            jQuery(document).on("click",".flooplan-toggle",function(e) {
                e.preventDefault();
                jQuery('.wpvr-floor-preview').slideToggle();
            });

            jQuery(document).on("click",".plan-delete",function(e) {
                e.preventDefault();
                var data = jQuery(this).attr("data-id");
                jQuery('#pointer-'+ data).remove();
                jQuery(this).parent().remove();
                jQuery(".floor-plan-pointer").each(function(index, element) {
                    jQuery( this ).text(index + 1 );
                    var number = index + 1;
                    jQuery( this ).attr("id","pointer-"+ number );
                });
                jQuery(".floor-plan-pointer-list ul li").each( function(index, element){
                    var number = index + 1;
                    jQuery( this).children("label").text('Pointer - '+ number);
                    jQuery( this).children("select").attr('name',"plan-"+number);
                    jQuery( this).children(".plan-delete").attr('data-id',number);
                });
            });

                //jQuery(document).ready(function (){
                //    var codeMirro = jQuery(".CodeMirror.cm-s-default.CodeMirror-wrap").length
                //    if( !codeMirro){
                //        var editorContainer = document.querySelector('#code-mirror-editor')
                //        // buttonEditor =  CodeMirror.fromTextArea(document.getElementById("code-mirror-editor"), {
                //        var buttonEditor = CodeMirror(editorContainer, {
                //            lineNumbers: true,
                //            mode: "htmlmixed",
                //            theme: "default",
                //            styleActiveLine: true,
                //            styleSelectedText: true,
                //            lineWrapping: true,
                //            width: "500px",
                //            height: "300px",
                //            'viewportMargin': Infinity,
                //        });
                //        buttonEditor.setValue('shahin');
                //
                //        buttonEditor.setCursor({ line: 10, ch: 10 });
                //        buttonEditor.setValue(`<?php //= $calltoactionbutton_css; ?>//`);
                //    }
                //})



            </script>
        <?php } ?>


        <div class="wpvr-use-shortcode">
            <?php
            $post = get_post();
            $id = $post->ID;
            $slug = $post->post_name;
            $postdata = get_post_meta($post->ID, 'panodata', true);
            ?>
            <div class="shortcode-wrapper">

                <div class="single-shortcode classic">

                    <div class="field-wapper">
                        <!-- Start preview button  -->
                        <button id="panolenspreview" class="panolenspreview wpvr_preview_button"><?php echo __('Preview', 'wpvr'); ?></button>
<!--                        <button id="panolenssave" class="panolenssave wpvr_preview_button">--><?php //echo __('Save', 'wpvr'); ?><!--</button>-->
                        <!-- End preview button -->
                        <div class="shortcode-field">

                            <p class="copycode" id="copy-shortcode">[wpvr id="<?php echo $id; ?>"]</p>

                            <span id="wpvr-copy-shortcode" class="wpvr-copy-shortcode">
                                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/copy.png'; ?>" alt="icon" />
                            </span>
                            
                        </div>

                        <span id="wpvr-copied-notice" class="wpvr-copied-notice"></span>

                    </div>
                </div>

            </div>
        </div>
<?php }
}
