<?php
/** @wordpress-plugin
 * Author:            Masjidal
 * Author URI:        http://www.masjidal.com/
 */
/* Function for session message */

error_reporting(0);
if (!function_exists("mptsi_do_account_redirect")) {
    function mptsi_do_account_redirect($url)
    {
        global $post, $wp_query;

        if (have_posts()) {
            include $url;
            die();
        } else {
            $wp_query->is_404 = true;
        }
    }
}

if (!function_exists("mptsi_custom_header_script")) {
    function mptsi_custom_header_script()
    {
        global $post, $api_data_value, $date;

        if (
            has_shortcode($post->post_content, "masjidal_salah_sunrise") ||
            has_shortcode($post->post_content, "single_view_calendar") ||
            has_shortcode($post->post_content, "masjidal_salah_sunset") ||
            has_shortcode($post->post_content, "masjidal_hijri_date") ||
            has_shortcode($post->post_content, "masjidal_jummah1") ||
            has_shortcode($post->post_content, "masjidal_jummah2") ||
            has_shortcode($post->post_content, "masjidal_salah_fajr") ||
            has_shortcode($post->post_content, "masjidal_salah_zuhr") ||
            has_shortcode($post->post_content, "masjidal_salah_asr") ||
            has_shortcode($post->post_content, "masjidal_salah_maghrib") ||
            has_shortcode($post->post_content, "masjidal_salah_isha") ||
            has_shortcode($post->post_content, "masjidal_iqamah_fajr") ||
            has_shortcode($post->post_content, "masjidal_iqamah_zuhr") ||
            has_shortcode($post->post_content, "masjidal_iqamah_asr") ||
            has_shortcode($post->post_content, "masjidal_iqamah_maghrib") ||
            has_shortcode($post->post_content, "masjidal_iqamah_isha") ||
            has_shortcode($post->post_content, "masjidal_iqamah_jummah")
        ) {
            if (empty($api_data_value)) {
                $ip = $_SERVER["REMOTE_ADDR"];
                $ipInfo = file_get_contents("http://ip-api.com/json/" . $ip);
                $ipInfo = json_decode($ipInfo);
                $timezone = $ipInfo->timezone;
                //date_default_timezone_set($timezone);
                //$today_date=date("Y-m-d");
                $date = new DateTime("now", new DateTimeZone($timezone));
                $today_date = $date->format("Y-m-d");
                $newdate = strtotime($today_date);
                $newdate = strtotime("+6 day", $newdate);
                $next_date = date("Y-m-d", $newdate);
                $masjid_id = get_option("masjid_id");
                //$url='https://masjidal.com/api/v1/time/range?masjid_id='.$masjid_id.'&from_date='.$today_date.'&to_date='.$next_date;
                $url =
                    "https://masjidal.com/api/v2/masjids/" .
                    $masjid_id .
                    "?expand=times&date_start=" .
                    $today_date .
                    "&date_end=" .
                    $next_date;
                $file = wp_safe_remote_get($url);
                $results = json_decode($file["body"]);
                $api_data_value = $results;
                //echo_log($file);
            }
        }
    }
}
add_action("template_redirect", "mptsi_custom_header_script");

// Creating the widget
class MPSTI_wpb_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            "MPSTI_wpb_widget",

            // Widget name will appear in UI
            __("Current day masjid timing", "wpb_widget_domain"),

            // Widget description
            [
                "description" => __(
                    "Display Single Day Masjid Timing",
                    "masjidal"
                ),
            ]
        );
    }

    public function widget($args, $instance)
    {
        $title = apply_filters("widget_title", $instance["title"]);

        // before and after widget arguments are defined by themes
        echo $args["before_widget"];
        if (!empty($title)) {
            echo $args["before_title"] . $title . $args["after_title"];
        }

        $ip = $_SERVER["REMOTE_ADDR"];
        $ipInfo = file_get_contents("http://ip-api.com/json/" . $ip);
        $ipInfo = json_decode($ipInfo);
        $timezone = $ipInfo->timezone;
        //date_default_timezone_set($timezone);
        //$today_date=date("Y-m-d");
        $date = new DateTime("now", new DateTimeZone($timezone));
        $today_date = $date->format("Y-m-d");
        //$date = "Mar 03, 2011";
        $newdate = strtotime($today_date);
        $newdate = strtotime("+6 day", $newdate);
        $next_date = date("Y-m-d", $newdate);
        $masjid_id = get_option("masjid_id");
        //  $url='https://masjidal.com/api/v1/time/range?masjid_id='.$masjid_id.'&from_date='.$today_date.'&to_date='.$next_date;
        $url =
            "https://masjidal.com/api/v2/masjids/" .
            $masjid_id .
            "?expand=times&date_start=" .
            $today_date .
            "&date_end=" .
            $next_date;
        $file = wp_safe_remote_get($url);
        $results = json_decode($file["body"]);
        //echo_log($results);

        $newArray = [];
        //foreach($results->data as $key => $subarray){
        $newArray = $results->times;
        ?>
 <div class="slideshow-container">
<?php
$counts = 1;
//foreach ($newArray as $alldate) {
for ($i = 0, $size = count($newArray); $i < $size; $i++) {
    //$salah = $alldate["salah"];
    //$iqamah = $alldate["iqamah"];
    $salah = $newArray[$i]->salah;
    $iqamah = $newArray[$i]->iqamah;
    //$today_day= date("l");
    $today_day = $date->format("l");
    $salahday = str_replace(" ", "", $salah->day);
    $today_daya = str_replace(" ", "", $today_day);
    //$current_time=strtotime(date('g:i A'));
    $current_time = strtotime($date->format("g:i A"));

    $timeformat_24 = get_option("timeformat_24");
    $fajra = strtotime($iqamah->fajr);
    $zuhra = strtotime($iqamah->zuhr);
    $asrs = strtotime($iqamah->asr);
    $maghribs = strtotime($iqamah->maghrib);
    $ishas = strtotime($iqamah->isha);
    $color = get_option("highlighted_color");
    $text_color = get_option("highlighted_text_color");
    if ($current_time < $fajra) {
        $fajr_class = "active_new";
    } elseif ($current_time < $zuhra) {
        $zuhr_class = "active_new";
    } elseif ($current_time < $asrs) {
        $asr_class = "active_new";
    } elseif ($current_time < $maghribs) {
        $maghrib_class = "active_new";
    } elseif ($current_time < $ishas) {
        $isha_class = "active_new";
    }
    $date_curret = strtotime($newArray[$i]->date); //strtotime($iqamah->date);
    $today_view_date = date("l, M d", $date_curret);
    echo "<style>.mySlides.count_1 li.active_new {background-color: " .
        $color .
        "!important;color: " .
        $text_color .
        !important;
    "} .mySlides.count_1 li.active_new h2{color:" .
        $text_color .
        "!important;}.table_new.mySlides_new.count_1 tr.active_new td {background-color: " .
        $color .
        " !important;color: " .
        $text_color .
        "!important;}</style>";
    ?>
  <div class="mySlides fade count_<?php echo $counts; ?>">
   <div class="main-center">
			<div class="inner-center-back">
			 <div style="margin-bottom: 7px;"><?php esc_html_e(
        "Prayer Timings",
        "masjidal"
    ); ?></div>
			 <div style=" font-weight: normal; font-size: 14px;"><?php echo $today_view_date; ?></div>
			  <div id="hijri-date_new"><?php echo $newArray[$i]->hijri_month .
         "  " .
         $newArray[$i]->hijri_date; ?></div>
			 <?php
    $iqamahfajr = substr($iqamah->fajr, -2);
    $iqamahfajr2 = str_replace($iqamahfajr, "", $iqamah->fajr);
    $iqamah_fajr = $iqamahfajr2 . " " . $iqamahfajr;
    if ($timeformat_24 == "yes") {
        $iqamah_fajr = date("H:i", strtotime($iqamah_fajr));
    }
    $iqamahzuhr = substr($iqamah->zuhr, -2);
    $iqamahzuhr2 = str_replace($iqamahzuhr, "", $iqamah->zuhr);
    $iqamah_zuhr = $iqamahzuhr2 . " " . $iqamahzuhr;
    if ($timeformat_24 == "yes") {
        $iqamah_zuhr = date("H:i", strtotime($iqamah_zuhr));
    }
    $iqamahasr = substr($iqamah->asr, -2);
    $iqamahasr2 = str_replace($iqamahasr, "", $iqamah->asr);
    $iqamah_asr = $iqamahasr2 . " " . $iqamahasr;
    if ($timeformat_24 == "yes") {
        $iqamah_asr = date("H:i", strtotime($iqamah_asr));
    }
    $iqamahmaghrib = substr($iqamah->maghrib, -2);
    $iqamahmaghrib2 = str_replace($iqamahmaghrib, "", $iqamah->maghrib);
    $iqamah_maghrib = $iqamahmaghrib2 . " " . $iqamahmaghrib . "";
    if ($timeformat_24 == "yes") {
        $iqamah_maghrib = date("H:i", strtotime($iqamah_maghrib));
    }
    $iqamahisha = substr($iqamah->isha, -2);
    $iqamahisha2 = str_replace($iqamahisha, "", $iqamah->isha);
    $iqamah_isha = $iqamahisha2 . " " . $iqamahisha;
    if ($timeformat_24 == "yes") {
        $iqamah_isha = date("H:i", strtotime($iqamah_isha));
    }
    $iqamahjummah2 = substr($iqamah->jummah1, -2);
    $iqamahjummah1 = str_replace($iqamahjummah2, "", $iqamah->jummah1);
    $iqamah_jummah1 = $iqamahjummah1 . " " . $iqamahjummah2;
    if ($timeformat_24 == "yes") {
        $iqamah_jummah1 = date("H:i", strtotime($iqamah_jummah1));
    }
    $iqamahjummah_2 = substr($iqamah->jummah2, -2);
    $iqamahjummah_1 = str_replace($iqamahjummah_2, "", $iqamah->jummah2);
    $iqamah_jummah2 = $iqamahjummah_1 . " " . $iqamahjummah_2;
    if ($timeformat_24 == "yes") {
        $iqamah_jummah2 = date("H:i", strtotime($iqamah_jummah2));
    }
    $salahfajr = substr($salah->fajr, -2);
    $salahfajr2 = str_replace($salahfajr, "", $salah->fajr);
    $salah_fajr = $salahfajr2 . " " . $salahfajr;
    if ($timeformat_24 == "yes") {
        $salah_fajr = date("H:i", strtotime($salah_fajr));
    }
    $salahzuhr = substr($salah->zuhr, -2);
    $salahzuhr2 = str_replace($salahzuhr, "", $salah->zuhr);
    $salah_zuhr = $salahzuhr2 . " " . $salahzuhr;
    if ($timeformat_24 == "yes") {
        $salah_zuhr = date("H:i", strtotime($salah_zuhr));
    }
    $salahasr = substr($salah->asr, -2);
    $salahasr2 = str_replace($salahasr, "", $salah->asr);
    $salah_asr = $salahasr2 . " " . $salahasr;
    if ($timeformat_24 == "yes") {
        $salah_asr = date("H:i", strtotime($salah_asr));
    }
    $salahmaghrib = substr($salah->maghrib, -2);
    $salahmaghrib2 = str_replace($salahmaghrib, "", $salah->maghrib);
    $salah_maghrib = $salahmaghrib2 . " " . $salahmaghrib;
    if ($timeformat_24 == "yes") {
        $salah_maghrib = date("H:i", strtotime($salah_maghrib));
    }
    $salahisha = substr($salah->isha, -2);
    $salahisha2 = str_replace($salahisha, "", $salah->isha);
    $salah_isha = $salahisha2 . " " . $salahisha;
    if ($timeformat_24 == "yes") {
        $salah_isha = date("H:i", strtotime($salah_isha));
    }
    $salahsunrise = substr($salah->sunrise, -2);
    $salahsunrise2 = str_replace($salahsunrise, "", $salah->sunrise);
    $salah_sunrise = $salahsunrise2 . " " . $salahsunrise;
    if ($timeformat_24 == "yes") {
        $salah_sunrise = date("H:i", strtotime($salah_sunrise));
    }
    $starts_lable = get_option("starts_lable");
    $iqamah_lable = get_option("iqamah_lable");
    $fajr_lable = get_option("fajr_lable");
    $dhuhr_lable = get_option("dhuhr_lable");
    $asr_lable = get_option("asr_lable");
    $maghrib_lable = get_option("maghrib_lable");
    $isha_lable = get_option("isha_lable");
    $jumuah_header = get_option("jumuah_header");
    $jumuah1_lable = get_option("jumuah1_lable");
    $jumuah2_lable = get_option("jumuah2_lable");
    $jumuah3_lable = get_option("jumuah3_lable");
    $jumuah3_time = $iqamah->jummah3; //get_option("jumuah3_time");

    $khutbah_label = get_option("khutbah_label");
    if (empty($khutbah_label)) {
        $khutbah_label = "Khutbah";
    }
    $khutbah_time = get_option("khutbah_time1");

    $khutbah_time_1 = substr($khutbah_time, -2);
    $khutbah_timeh_1 = str_replace($khutbah_time_1, "", $khutbah_time);
    $khutbah_time3 = $khutbah_timeh_1 . " " . $khutbah_time_1;
    if ($timeformat_24 == "yes") {
        $khutbah_time3 = date("H:i", strtotime($khutbah_time3));
    }
    $jumuah3_time_2 = substr($jumuah3_time, -2);
    $jumuah3_timeh_1 = str_replace($jumuah3_time_2, "", $jumuah3_time);
    $jumuah3 = $jumuah3_timeh_1 . " " . $jumuah3_time_2;
    if ($timeformat_24 == "yes") {
        $jumuah3 = date("H:i", strtotime($jumuah3));
    }
    if (empty($starts_lable)) {
        $starts_lable = "STARTS";
    }
    if (empty($iqamah_lable)) {
        $iqamah_lable = "IQAMAH";
    }
    if (empty($fajr_lable)) {
        $fajr_lable = "FAJR";
    }
    if (empty($dhuhr_lable)) {
        $dhuhr_lable = "DHUHR";
    }
    if (empty($asr_lable)) {
        $asr_lable = "ASR";
    }
    if (empty($maghrib_lable)) {
        $maghrib_lable = "MAGHRIB";
    }
    if (empty($isha_lable)) {
        $isha_lable = "ISHA";
    }
    if (empty($jumuah1_lable)) {
        $jumuah1_lable = "JUMU'AH";
    }
    if (empty($jumuah2_lable)) {
        $jumuah2_lable = "JUMU'AH";
    }
    if (empty($jumuah3_lable)) {
        $jumuah2_lable = "JUMU'AH";
    }

    echo "<ul>";
    echo '<li><div class="time_namze_heading"><span class="text-right start_time">' .
        $starts_lable .
        ' </span><span class="text-center" style="font-weight: normal;border: none;">' .
        $iqamah_lable .
        "</span></div></li>";

    //  $html.='<li class="'. $fajr_class .' li-style"><div class="spannn"><span class="span-img"><img src="'. plugin_dir_url(dirname(dirname(__FILE__))) . 'public/assets/images/d_fajr.png" alt=""></span> <span>FAJR</span> <span>'. $salah_fajr .'</span></div><div class="second-span"><span class="text-center">'.  $iqamah_fajr .'</span></div></li>';
    echo '<li class="' .
        $fajr_class .
        '" style="padding: 15px 15px;"><div class="image_and_text_namze"><img src="' .
        plugin_dir_url(dirname(dirname(__FILE__))) .
        'public/assets/images/d_fajr.png" alt=""><span class="namze_name"> ' .
        $fajr_lable .
        ' </span></div><div class="time_namze"><span>' .
        $salah_fajr .
        '</span><span class="text-center">' .
        $iqamah_fajr .
        "</span></div></li>";
    echo '<li class="' .
        $zuhr_class .
        '" style= "padding: 15px 15px;"><div class="image_and_text_namze"><img src="' .
        plugin_dir_url(dirname(dirname(__FILE__))) .
        'public/assets/images/d_dhuhr.png" alt=""><span class="namze_name"> ' .
        $dhuhr_lable .
        ' </span></div><div class="time_namze"><span>' .
        $salah_zuhr .
        '</span><span class="text-center">' .
        $iqamah_zuhr .
        "</span></div></li>";
    echo '<li class="' .
        $asr_class .
        '" style="padding: 15px 15px;' .
        "background-color:" .
        $asr .
        '"><div class="image_and_text_namze"><img src="' .
        plugin_dir_url(dirname(dirname(__FILE__))) .
        'public/assets/images/d_asr.png" alt=""> <span class="namze_name">' .
        $asr_lable .
        ' </span></div><div class="time_namze"><span>' .
        $salah_asr .
        '</span><span class="text-center">' .
        $iqamah_asr .
        "</span></div></li>";
    echo ' <li class="' .
        $maghrib_class .
        '" style="padding: 15px 15px;" ><div class="image_and_text_namze"><img src="' .
        plugin_dir_url(dirname(dirname(__FILE__))) .
        'public/assets/images/d_maghrib.png" alt=""><span class="namze_name"> ' .
        $maghrib_lable .
        ' </span></div><div class="time_namze"><span>' .
        $salah_maghrib .
        '</span><span class="text-center">' .
        $iqamah_maghrib .
        "</span></div></li>";
    echo ' <li class="isha_li ' .
        $isha_class .
        '" style="padding: 15px 15px;"><div class="image_and_text_namze"><img src="' .
        plugin_dir_url(dirname(dirname(__FILE__))) .
        'public/assets/images/d_isha.png" alt=""> <span class="namze_name">' .
        $isha_lable .
        ' </span></div><div class="time_namze"><span>' .
        $salah_isha .
        '</span><span class="text-center">' .
        $iqamah_isha .
        "</span></div></li>";
    echo "</ul> ";
    ?>
                <div class="jamu-sec">
               <?php
               if ($iqamah->jummah2 == "-") {
                   $jummah1 = "JUMU'AH";
               } else {
                   $jummah1 = "JUMU'AH";
               }

               if (empty($khutbah_time) || $khutbah_time == "12:undefined AM") {
                   //echo "<style>.jamu-sec .col-6:nth-child(2) {border-right: none;}</style>";
               } else {
                   if (
                       empty($iqamah->jummah2) ||
                       $iqamah->jummah2 == "-"
                   ) { ?><style>.jamu-sec .col-6:nth-child(1) {border-right: 2px #e3e3e3 solid;} </style><?php } ?>
					  <div class="col-6 text-center" style="border-right:2px #e3e3e3 solid"><h1><?php echo $khutbah_time3; ?></h1><span><?php echo $khutbah_label; ?></span></div> 
				 <?php
               }
               ?>
				<?php
    if (empty($iqamah->jummah1) || $iqamah->jummah1 == "-") {
    } else {
         ?>
                <div class="col-6 text-center"><h1><?php echo $iqamah_jummah1; ?></h1><span><?php echo $jumuah1_lable; ?></span></div>
				<?php
    }
    if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
        echo "<style>.jamu-sec .col-6:nth-child(1) {border-right: none;}</style>";
    } else {
         ?>
                <div class="col-6 text-center"><h1><?php echo $iqamah_jummah2; ?></h1><span><?php echo $jumuah2_lable; ?></span></div>
				<?php
    }
    if (empty($jumuah3_time) || $iqamah->jummah3 == "-" || $jumuah3_time == "12:undefined AM") {
        echo "<style>.jamu-sec .col-6:nth-child(2) {border-right: none;}</style>";
    } else {
         ?>
					  <div class="col-6 text-center"><h1><?php echo $jumuah3_lable; ?></h1><span><?php echo $jumuah3; ?></span></div> 
				 <?php
    }
    ?>
				
				
                </div>
                <div class="am-pm">
                <div class="col-6 text-left"><span><img src="<?php echo plugin_dir_url(
                    dirname(dirname(__FILE__))
                ) .
                    "public/assets/"; ?>images/am-icon.jpg" alt=""> <?php echo $salah_sunrise; ?></span></div>
                <div class="col-6 text-right"><span><?php echo $salah_maghrib; ?> <img src="<?php echo plugin_dir_url(
     dirname(dirname(__FILE__))
 ) .
     "public/assets/"; ?>images/sunset.png" alt="" style="margin: 0 0 0 10px"></span></div>
                </div>
            </div>
    </div>
</div>   
	<?php $counts++;
}
$masjid_calendar_type = get_option("masjid_calendar_type");
$masjid_id = get_option("masjid_id");
if ($masjid_calendar_type == "v2" || $masjid_calendar_type == "v3") {
    $monthly_view =
        "https://masjidal.com/widget/monthly/" .
        $masjid_calendar_type .
        "/?masjid_id=" .
        $masjid_id;
} elseif ($masjid_calendar_type == "Custom_url") {
    $monthly_view = get_option("montly_pdf_url");
} else {
    $monthly_view =
        "https://masjidal.com/widget/monthly/?masjid_id=" . $masjid_id;
}
if (
    $masjid_calendar_type == "v2" ||
    $masjid_calendar_type == "v3" ||
    $masjid_calendar_type == "v1" ||
    $masjid_calendar_type == "Custom_url"
) { ?>
 <a class="montyly_view" style="float: right;" target="_blank" href="<?php echo $monthly_view; ?>"><?php esc_html_e(
    "View Monthly Calendar",
    "masjidal"
); ?></a>
<?php }
?>
<div class="prowred_by"><span><?php esc_html_e(
    "Powered by",
    "masjidal"
); ?><a href="http://www.masjidal.com/"><?php esc_html_e("www.masjidal.com", "masjidal"); ?></a></span></div>
  </div>
  <div style="text-align:center">
  <?php
  $count = 1;
  foreach ($newArray as $alldate) { ?>
  <span class="dot" onclick="currentSlide(<?php echo $count; ?>)"></span> 
  <?php $count++;}
  ?>
</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>
 
<?php echo $args["after_widget"];
    }

    public function form($instance)
    {
        if (isset($instance["title"])) {
            $title = $instance["title"];
        } else {
            $title = __("New title", "masjidal");
        }// Widget admin form
        ?>
<p>
<label for="<?php echo $this->get_field_id(
    "title"
); ?>"><?php _e("Title:"); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id(
    "title"
); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance["title"] = !empty($new_instance["title"])
            ? strip_tags($new_instance["title"])
            : "";
        return $instance;
    }
}

if (!function_exists("mptsi_wpb_load_widget")) {
    function mptsi_wpb_load_widget()
    {
        register_widget("MPSTI_wpb_widget");
    }
}
add_action("widgets_init", "mptsi_wpb_load_widget");

/*end widgets*/

/*shortcode*/
if (!function_exists("mptsi_single_view_calendar")) {
    function mptsi_single_view_calendar()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        //foreach($results->data as $key => $subarray){
        //foreach($results->times as $key => $subarray){
        //	foreach($subarray as $j => $k){
        //	$newArray[$j][$key] = $k;
        //	}
        //}
        $newArray = $results->times;
        //echo_log($newArray[0]);

        $bannerText = "";
        $bannerDate = "";
        $bannerDayChange = 0;
        $premium = $results->premium_features->slideshow;

        if ($premium && !get_option("iqamahChange")) {
            if ($newArray[0]->iqamah->fajr != $newArray[1]->iqamah->fajr) {
                $bannerText = "Fajr at " . $newArray[1]->iqamah->fajr . ", ";
                $bannerDate = " from tomorrow";
                $bannerDayChange = 1;
            }
            if ($newArray[0]->iqamah->zuhr != $newArray[1]->iqamah->zuhr) {
                $bannerText .= "Dhuhr at " . $newArray[1]->iqamah->zuhr . ", ";
                $bannerDate = " from tomorrow";
                $bannerDayChange = 1;
            }
            if ($newArray[0]->iqamah->asr != $newArray[1]->iqamah->asr) {
                $bannerText .= "Asr at " . $newArray[1]->iqamah->asr . ", ";
                $bannerDate = " from tomorrow";
                $bannerDayChange = 1;
            }
            if ($newArray[0]->iqamah->isha != $newArray[1]->iqamah->isha) {
                $bannerText = "Isha at " . $newArray[1]->iqamah->isha . ", ";
                $bannerDate = " from tomorrow";
                $bannerDayChange = 1;
            }
            if ($bannerDayChange == 0) {
                if ($newArray[0]->iqamah->fajr != $newArray[2]->iqamah->fajr) {
                    $bannerText =
                        "Fajr at " . $newArray[2]->iqamah->fajr . ", ";
                    $bannerDate = " from " . strtok($newArray[2]->date, ",");
                    $bannerDayChange = 2;
                }
                if ($newArray[0]->iqamah->zuhr != $newArray[2]->iqamah->zuhr) {
                    $bannerText .=
                        "Dhuhr at " . $newArray[2]->iqamah->zuhr . ", ";
                    $bannerDate = " from " . strtok($newArray[2]->date, ",");
                    $bannerDayChange = 2;
                }
                if ($newArray[0]->iqamah->asr != $newArray[2]->iqamah->asr) {
                    $bannerText .= "Asr at " . $newArray[2]->iqamah->asr . ", ";
                    $bannerDate = " from " . strtok($newArray[2]->date, ",");
                    $bannerDayChange = 2;
                }
                if ($newArray[0]->iqamah->isha != $newArray[2]->iqamah->isha) {
                    $bannerText =
                        "Isha at " . $newArray[2]->iqamah->isha . ", ";
                    $bannerDate = " from " . strtok($newArray[2]->date, ",");
                    $bannerDayChange = 2;
                }
            }
            if ($bannerDayChange == 0) {
                if ($newArray[0]->iqamah->fajr != $newArray[3]->iqamah->fajr) {
                    $bannerText =
                        "Fajr at " . $newArray[3]->iqamah->fajr . ", ";
                    $bannerDate = " from " . strtok($newArray[3]->date, ",");
                    $bannerDayChange = 3;
                }
                if ($newArray[0]->iqamah->zuhr != $newArray[3]->iqamah->zuhr) {
                    $bannerText .=
                        "Dhuhr at " . $newArray[3]->iqamah->zuhr . ", ";
                    $bannerDate = " from " . strtok($newArray[3]->date, ",");
                    $bannerDayChange = 3;
                }
                if ($newArray[0]->iqamah->asr != $newArray[3]->iqamah->asr) {
                    $bannerText .= "Asr at " . $newArray[3]->iqamah->asr . ", ";
                    $bannerDate = " from " . strtok($newArray[3]->date, ",");
                    $bannerDayChange = 3;
                }
                if ($newArray[0]->iqamah->isha != $newArray[3]->iqamah->isha) {
                    $bannerText =
                        "Isha at " . $newArray[3]->iqamah->isha . ", ";
                    $bannerDate = " from " . strtok($newArray[3]->date, ",");
                    $bannerDayChange = 3;
                }
            }

            if ($bannerDayChange > 0) {
                $bannerText = substr($bannerText, 0, -2);
            }
        }

        $masjid_calendar_layout = get_option("masjid_calendar_layout");
        if ($masjid_calendar_layout == "Layout1") {
            $html = '<div class="prayer-time-cont box-prayer layout1">';
            $counts = 1;
            //foreach($newArray as $alldate){
            for ($i = 0, $size = count($newArray); $i < $size; $i++) {
                //echo_log($newArray[$i]);
                //$salah=$alldate['salah'];
                $salah = $newArray[$i]->salah;
                //$iqamah=$alldate['iqamah'];
                $iqamah = $newArray[$i]->iqamah;
                //$today_day= date("l");

                //$today_day= $newArray[$i]->date; //->format('l');
                //$today_day= date("l");
                //echo_log($newArray[$i]->date);
                //$salahday=str_replace(' ', '', $salah->day);
                //$today_daya=str_replace(' ', '', $today_day);
                //$current_time=strtotime(date('g:i A'));
                $current_time = strtotime($date->format("g:i A"));
                $fajra = strtotime($iqamah->fajr);
                $zuhra = strtotime($iqamah->zuhr);
                $asrs = strtotime($iqamah->asr);
                $maghribs = strtotime($iqamah->maghrib);
                $ishas = strtotime($iqamah->isha);
                $color = get_option("highlighted_color");
                $text_color = get_option("highlighted_text_color");
                if ($current_time < $fajra) {
                    $fajr_class = "active_new";
                } elseif ($current_time < $zuhra) {
                    $zuhr_class = "active_new";
                } elseif ($current_time < $asrs) {
                    $asr_class = "active_new";
                } elseif ($current_time < $maghribs) {
                    $maghrib_class = "active_new";
                } elseif ($current_time < $ishas) {
                    $isha_class = "active_new";
                }
                $timeformat_24 = get_option("timeformat_24");
                $top_heading = get_option("top_heading");
                $montly_text = get_option("montly_text");
                if (empty($top_heading)) {
                    $top_heading = "Prayer Timings";
                }
                if (empty($montly_text)) {
                    $montly_text = "View Monthly Calendar";
                }
                $html .=
                    "<style>.table_new.mySlides_new.count_1 tr.active_new {background-color: " .
                    $color .
                    " !important;color: " .
                    $text_color .
                    "!important;} .table_new.mySlides_new.count_1 tr.active_new td {background-color: " .
                    $color .
                    " !important;color: " .
                    $text_color .
                    "!important;}</style>";
                $html .=
                    '<div class="table_new mySlides_new fade count_' .
                    $counts .
                    '">';
                $html .= '<table id="timetable" class="table-prayer">';
                $html .= "<tbody>";
                $html .=
                    '<tr><th class="prayer-time-caption" colspan="5"><span class="weekly-pr-time-icon"></span>' .
                    $top_heading .
                    '</th></tr>
			<tr style="height:3px"><td colspan="5">';
                //$salah=$alldate['salah'];
                $hijri_date = $newArray[$i]->hijri_date; //$salah->hijri_date;
                $new_date = explode(", ", $hijri_date);
                $date_curret = strtotime($newArray[$i]->date); //$iqamah->date);
                $today_view_date = date("l, M d", $date_curret);
                $html .=
                    '<div id="hijri-date_new">' .
                    $newArray[$i]->hijri_month .
                    "  " .
                    $new_date[0] .
                    ", " .
                    $new_date[1] .
                    "</div>";
                $html .=
                    '<div id="gregorian-date_new">' .
                    $newArray[$i]->date .
                    '</div>
			</td></tr>';
                $iqamahfajr = substr($iqamah->fajr, -2);
                $iqamahfajr2 = str_replace($iqamahfajr, "", $iqamah->fajr);
                $iqamah_fajr = $iqamahfajr2 . " " . $iqamahfajr;
                if ($timeformat_24 == "yes") {
                    $iqamah_fajr = date("H:i", strtotime($iqamah_fajr));
                }
                $iqamahzuhr = substr($iqamah->zuhr, -2);
                $iqamahzuhr2 = str_replace($iqamahzuhr, "", $iqamah->zuhr);
                $iqamah_zuhr = $iqamahzuhr2 . " " . $iqamahzuhr;
                if ($timeformat_24 == "yes") {
                    $iqamah_zuhr = date("H:i", strtotime($iqamah_zuhr));
                }
                $iqamahasr = substr($iqamah->asr, -2);
                $iqamahasr2 = str_replace($iqamahasr, "", $iqamah->asr);
                $iqamah_asr = $iqamahasr2 . " " . $iqamahasr;
                if ($timeformat_24 == "yes") {
                    $iqamah_asr = date("H:i", strtotime($iqamah_asr));
                }
                $iqamahmaghrib = substr($iqamah->maghrib, -2);
                $iqamahmaghrib2 = str_replace(
                    $iqamahmaghrib,
                    "",
                    $iqamah->maghrib
                );
                $iqamah_maghrib = $iqamahmaghrib2 . " " . $iqamahmaghrib;
                if ($timeformat_24 == "yes") {
                    $iqamah_maghrib = date("H:i", strtotime($iqamah_maghrib));
                }
                $iqamahisha = substr($iqamah->isha, -2);
                $iqamahisha2 = str_replace($iqamahisha, "", $iqamah->isha);
                $iqamah_isha = $iqamahisha2 . " " . $iqamahisha;
                if ($timeformat_24 == "yes") {
                    $iqamah_isha = date("H:i", strtotime($iqamah_isha));
                }
                $iqamahjummah2 = substr($iqamah->jummah1, -2);
                $iqamahjummah1 = str_replace(
                    $iqamahjummah2,
                    "",
                    $iqamah->jummah1
                );
                $iqamah_jummah1 = $iqamahjummah1 . " " . $iqamahjummah2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah1 = date("H:i", strtotime($iqamah_jummah1));
                }
                $iqamahjummah_2 = substr($iqamah->jummah2, -2);
                $iqamahjummah_1 = str_replace(
                    $iqamahjummah_2,
                    "",
                    $iqamah->jummah2
                );
                $iqamah_jummah2 = $iqamahjummah_1 . " " . $iqamahjummah_2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah2 = date("H:i", strtotime($iqamah_jummah2));
                }
                $salahfajr = substr($salah->fajr, -2);
                $salahfajr2 = str_replace($salahfajr, "", $salah->fajr);
                $salah_fajr = $salahfajr2 . " " . $salahfajr;
                if ($timeformat_24 == "yes") {
                    $salah_fajr = date("H:i", strtotime($salah_fajr));
                }
                $salahzuhr = substr($salah->zuhr, -2);
                $salahzuhr2 = str_replace($salahzuhr, "", $salah->zuhr);
                $salah_zuhr = $salahzuhr2 . " " . $salahzuhr;
                if ($timeformat_24 == "yes") {
                    $salah_zuhr = date("H:i", strtotime($salah_zuhr));
                }
                $salahasr = substr($salah->asr, -2);
                $salahasr2 = str_replace($salahasr, "", $salah->asr);
                $salah_asr = $salahasr2 . " " . $salahasr;
                if ($timeformat_24 == "yes") {
                    $salah_asr = date("H:i", strtotime($salah_asr));
                }
                $salahmaghrib = substr($salah->maghrib, -2);
                $salahmaghrib2 = str_replace(
                    $salahmaghrib,
                    "",
                    $salah->maghrib
                );
                $salah_maghrib = $salahmaghrib2 . " " . $salahmaghrib;
                if ($timeformat_24 == "yes") {
                    $salah_maghrib = date("H:i", strtotime($salah_maghrib));
                }
                $salahisha = substr($salah->isha, -2);
                $salahisha2 = str_replace($salahisha, "", $salah->isha);
                $salah_isha = $salahisha2 . " " . $salahisha;
                if ($timeformat_24 == "yes") {
                    $salah_isha = date("H:i", strtotime($salah_isha));
                }
                $salahsunrise = substr($salah->sunrise, -2);
                $salahsunrise2 = str_replace(
                    $salahsunrise,
                    "",
                    $salah->sunrise
                );
                $salah_sunrise = $salahsunrise2 . " " . $salahsunrise;
                if ($timeformat_24 == "yes") {
                    $salah_sunrise = date("H:i", strtotime($salah_sunrise));
                }
                $starts_lable = get_option("starts_lable");
                $iqamah_lable = get_option("iqamah_lable");
                $fajr_lable = get_option("fajr_lable");
                $dhuhr_lable = get_option("dhuhr_lable");
                $asr_lable = get_option("asr_lable");
                $maghrib_lable = get_option("maghrib_lable");
                $isha_lable = get_option("isha_lable");
                $jumuah1_lable = get_option("jumuah1_lable");
                $jumuah2_lable = get_option("jumuah2_lable");
                $jumuah3_lable = get_option("jumuah3_lable");
                $khutbah_label = get_option("khutbah_label");
                if (empty($khutbah_label)) {
                    $khutbah_label = "Khutbah";
                }
                $khutbah_time = get_option("khutbah_time1");
                $jumuah3_time = $iqamah->jummah3; //, -2); $jumuah3_time = str_replace($jumuah3_time,"",$iqamah->jummah3);//get_option('jumuah3_time');
                $khutbah_time_1 = substr($khutbah_time, -2);
                $khutbah_timeh_1 = str_replace(
                    $khutbah_time_1,
                    "",
                    $khutbah_time
                );
                $jumuah3_time_2 = substr($jumuah3_time, -2);
                $jumuah3_timeh_1 = str_replace(
                    $jumuah3_time_2,
                    "",
                    $jumuah3_time
                );
                $khutbah_time3 = $khutbah_timeh_1 . " " . $khutbah_time_1;
                if ($timeformat_24 == "yes") {
                    $khutbah_time3 = date("H:i", strtotime($khutbah_time3));
                }
                $jumuah3 = $jumuah3_timeh_1 . " " . $jumuah3_time_2;
                if ($timeformat_24 == "yes") {
                    $jumuah3 = date("H:i", strtotime($jumuah3));
                }
                if (empty($starts_lable)) {
                    $starts_lable = "STARTS";
                }
                if (empty($iqamah_lable)) {
                    $iqamah_lable = "IQAMAH";
                }
                if (empty($fajr_lable)) {
                    $fajr_lable = "Fajr";
                }
                if (empty($dhuhr_lable)) {
                    $dhuhr_lable = "Dhuhr";
                }
                if (empty($asr_lable)) {
                    $asr_lable = "Asr";
                }
                if (empty($maghrib_lable)) {
                    $maghrib_lable = "Maghrib";
                }
                if (empty($isha_lable)) {
                    $isha_lable = "Isha";
                }
                if (empty($jumuah1_lable)) {
                    $jumuah1_lable = "Jumu'ah";
                }
                if (empty($jumuah2_lable)) {
                    $jumuah2_lable = "Jumu'ah";
                }
                if (empty($jumuah3_lable)) {
                    $jumuah3_lable = "Jumu'ah";
                }
                $html .=
                    '<tr class="red-background">
				<th></th><th></th>
				<th class="left-align">' .
                    $starts_lable .
                    ' </th>
				<th></th>
				<th class="left-align">' .
                    $iqamah_lable .
                    '</th>
			</tr>';
                $html .= '<tr class="' . $fajr_class . '">';
                $html .=
                    '<td class="prayer-name">' .
                    $fajr_lable .
                    '</td>
				<td colspan="2" class="athan-time">' .
                    $salah_fajr .
                    '</td> 
				<td colspan="2" class="iqama-time">' .
                    $iqamah_fajr .
                    '</td>
			</tr>';
                $html .=
                    '<tr><td class="prayer-name">Sunrise</td><td style="" colspan="4" class="athan-time"><div class="clock-sign"></div>' .
                    $salah_sunrise .
                    "</td></tr>";
                $html .=
                    '<tr class="' .
                    $zuhr_class .
                    ' displayTableRow">
				<td class="prayer-name current-time">' .
                    $dhuhr_lable .
                    '</td>
				<td colspan="2" class="athan-time current-time">' .
                    $salah_zuhr .
                    '</td>
				<td colspan="2" class="iqama-time current-time">' .
                    $iqamah_zuhr .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="' .
                    $asr_class .
                    '">
				<td class="prayer-name">' .
                    $asr_lable .
                    '</td> 
				<td colspan="2" class="athan-time">' .
                    $salah_asr .
                    '</td>
				<td colspan="2" class="iqama-time">' .
                    $iqamah_asr .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="' .
                    $maghrib_class .
                    '">
				<td class="prayer-name">' .
                    $maghrib_lable .
                    '</td>
				<td colspan="2" class="athan-time">' .
                    $salah_maghrib .
                    '</td> 
				<td colspan="2" class="iqama-time">' .
                    $iqamah_maghrib .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="' .
                    $isha_lable .
                    '">
				<td class="prayer-name">' .
                    $isha_lable .
                    '</td>
				<td colspan="2" class="athan-time">' .
                    $salah_isha .
                    '</td>
				<td colspan="2" class="iqama-time">' .
                    $iqamah_isha .
                    '</td>
			</tr>';
                if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                    if (!empty($khutbah_time)) {
                        $html .=
                            '<tr><td class="prayer-name jumaTime-cell ">' .
                            $khutbah_label .
                            '</td><td style="text-align:left;" colspan="4">' .
                            $khutbah_time3 .
                            "</td></tr>";
                    }
                }
                $html .=
                    '<tr>
				<td class="prayer-name jumaTime-cell">' .
                    $jumuah1_lable .
                    '</td>
				<td style="text-align:left;" colspan="4">' .
                    $iqamah_jummah1 .
                    '</td>
			</tr>';
                if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                    //	$html.='<tr><td class="prayer-name jumaTime-cell">'. $khutbah_label .'</td><td style="text-align:left;" colspan="4">'.$khutbah_time3.'</td></tr>';
                } else {
                    $html .=
                        '<tr><td class="prayer-name jumaTime-cell">' .
                        $jumuah2_lable .
                        '</td><td style="text-align:left;" colspan="4">' .
                        $iqamah_jummah2 .
                        "</td></tr>";
                }
                if (empty($iqamah->jummah3) || $iqamah->jummah3 == "-") {
                //
                    
                } else {
                    
                    $html .=
                        '<tr><td class="prayer-name jumaTime-cell">' .
                        $jumuah3_lable .
                        '</td><td style="text-align:left;" colspan="4">' .
                        $jumuah3 .
                        "</td></tr>";
                }
                if ($counts < 2 && $bannerDayChange > 0) {
                    if ($bannerDayChange == 1) {
                        $html .=
                            '<tr><td colspan="5"><div class="iqamah-change day1">' .
                            $bannerText .
                            $bannerDate .
                            "</div></td></tr>";
                    } elseif ($bannerDayChange == 2) {
                        $html .=
                            '<tr><td colspan="5"><div class="iqamah-change day2">' .
                            $bannerText .
                            $bannerDate .
                            "</div></td></tr>";
                    } elseif ($bannerDayChange == 3) {
                        $html .=
                            '<tr><td colspan="5"><div class="iqamah-change day3">' .
                            $bannerText .
                            $bannerDate .
                            "</div></td></tr>";
                    }
                }
                $html .= " </tbody></table></div>";

                $counts++;
            }
            $html .= '<div style="text-align:center">';
            $count = 1;
            $masjid_calendar_type = get_option("masjid_calendar_type");
            $masjid_id = get_option("masjid_id");
            if (
                $masjid_calendar_type == "v2" ||
                $masjid_calendar_type == "v3"
            ) {
                $monthly_view =
                    "https://masjidal.com/widget/monthly/" .
                    $masjid_calendar_type .
                    "/?masjid_id=" .
                    $masjid_id;
            } elseif ($masjid_calendar_type == "Custom_url") {
                $monthly_view = get_option("montly_pdf_url");
            } else {
                $monthly_view =
                    "https://masjidal.com/widget/monthly/?masjid_id=" .
                    $masjid_id;
            }
            if (
                $masjid_calendar_type == "v2" ||
                $masjid_calendar_type == "v3" ||
                $masjid_calendar_type == "v1" ||
                $masjid_calendar_type == "Custom_url"
            ) {
                $html .=
                    '  <a class="read-more" target="_blank" href="' .
                    $monthly_view .
                    '">' .
                    $montly_text .
                    "</a>";
            }
            $html .= "</div>";
            $html .= '<div style="text-align:center;margin-top: 10px">';
            //$count=1;
            foreach ($newArray as $alldate) {
                $html .=
                    '<span class="dots" onclick="currentSlide_new(' .
                    $count .
                    ')"></span> ';
                $count++;
            }
            $html .= "</div>";
        } elseif ($masjid_calendar_layout == "Layout2") {
            $html = '<div class="prayer-time-cont box-prayer layout_2">';
            $html .=
                '<div style="text-align:center;margin-top: 144px;position: absolute;z-index: 99;width: 400px;">';
            $count = 1;
            //foreach($newArray as $alldate){
            for ($i = 0, $size = count($newArray); $i < $size; $i++) {
                $html .=
                    '<span class="dots" onclick="currentSlide_new(' .
                    $count .
                    ')"></span> ';
                $count++;
            }
            $html .= '<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
			<a class="next" onclick="plusSlides(1)">&#10095;</a>
		</div>';
            $counts = 1;
            //foreach($newArray as $alldate){
            for ($i = 0, $size = count($newArray); $i < $size; $i++) {
                //$salah=$alldate['salah'];
                //$iqamah=$alldate['iqamah'];
                //$today_day= date("l");
                //$today_day= $date->format("l");
                //$salahday=str_replace(' ', '', $salah->day);
                //$today_daya=str_replace(' ', '', $today_day);
                //$current_time=strtotime(date('g:i A'));
                $salah = $newArray[$i]->salah;
                $iqamah = $newArray[$i]->iqamah;
                $current_time = strtotime($date->format("g:i A"));
                $fajra = strtotime($iqamah->fajr);
                $zuhra = strtotime($iqamah->zuhr);
                $asrs = strtotime($iqamah->asr);
                $maghribs = strtotime($iqamah->maghrib);
                $ishas = strtotime($iqamah->isha);
                $color = get_option("highlighted_color");
                $text_color = get_option("highlighted_text_color");
                if ($current_time < $fajra) {
                    $fajr_class = "active_new";
                    //echo_log('F');
                } elseif ($current_time < $zuhra) {
                    $zuhr_class = "active_new";
                    //echo_log('D');
                } elseif ($current_time < $asrs) {
                    $asr_class = "active_new";
                    //echo_log('A');
                } elseif ($current_time < $maghribs) {
                    //echo_log('M');
                    $maghrib_class = "active_new";
                } elseif ($current_time < $ishas) {
                    $isha_class = "active_new";
                    //echo_log('I');
                }
                $timeformat_24 = get_option("timeformat_24");
                $top_heading = get_option("top_heading");
                $montly_text = get_option("montly_text");
                if (empty($top_heading)) {
                    $top_heading = "Prayer Timings";
                }
                if (empty($montly_text)) {
                    $montly_text = "View Monthly Calendar";
                }
                $html .=
                    "<style>.table_new.mySlides_new.count_1 tr.active_new {background-color: " .
                    $color .
                    " !important;color: " .
                    $text_color .
                    "!important;} .table_new.mySlides_new.count_1 tr.active_new td {background-color: " .
                    $color .
                    " !important;color: " .
                    $text_color .
                    "!important;}</style>";
                $html .=
                    '<div class="table_new mySlides_new fade count_' .
                    $counts .
                    '">';
                $html .= '<table id="timetable" class="table-prayer">';
                $html .= "<tbody>";
                $html .=
                    '<tr style="border: none;"><th class="prayer-time-caption_layout2" colspan="5"><span class="weekly-pr-time-icon"></span>' .
                    $top_heading .
                    '</th></tr>
			<tr class="date_section">
				<td colspan="5">';
                //$salah=$alldate['salah'];
                $hijri_date = $newArray[$i]->hijri_date; //$salah->hijri_date;
                $new_date = explode(", ", $hijri_date);
                $date_curret = strtotime($newArray[$i]->date); //$iqamah->date);
                $today_view_date = date("l, M d, Y", $date_curret);
                $html .=
                    '<div id="gregorian-date_new">' .
                    $today_view_date .
                    "</div>";
                $html .=
                    '<div id="hijri-date_new">' .
                    $newArray[$i]->hijri_month .
                    "  " .
                    $new_date[0] .
                    ", " .
                    $new_date[1] .
                    '</div>
				</td> 
			</tr>';
                $iqamahfajr = substr($iqamah->fajr, -2);
                $iqamahfajr2 = str_replace($iqamahfajr, "", $iqamah->fajr);
                $iqamah_fajr = $iqamahfajr2 . " " . $iqamahfajr;
                if ($timeformat_24 == "yes") {
                    $iqamah_fajr = date("H:i", strtotime($iqamah_fajr));
                }
                $iqamahzuhr = substr($iqamah->zuhr, -2);
                $iqamahzuhr2 = str_replace($iqamahzuhr, "", $iqamah->zuhr);
                $iqamah_zuhr = $iqamahzuhr2 . " " . $iqamahzuhr;
                if ($timeformat_24 == "yes") {
                    $iqamah_zuhr = date("H:i", strtotime($iqamah_zuhr));
                }
                $iqamahasr = substr($iqamah->asr, -2);
                $iqamahasr2 = str_replace($iqamahasr, "", $iqamah->asr);
                $iqamah_asr = $iqamahasr2 . " " . $iqamahasr;
                if ($timeformat_24 == "yes") {
                    $iqamah_asr = date("H:i", strtotime($iqamah_asr));
                }
                $iqamahmaghrib = substr($iqamah->maghrib, -2);
                $iqamahmaghrib2 = str_replace(
                    $iqamahmaghrib,
                    "",
                    $iqamah->maghrib
                );
                $iqamah_maghrib = $iqamahmaghrib2 . " " . $iqamahmaghrib;
                if ($timeformat_24 == "yes") {
                    $iqamah_maghrib = date("H:i", strtotime($iqamah_maghrib));
                }
                $iqamahisha = substr($iqamah->isha, -2);
                $iqamahisha2 = str_replace($iqamahisha, "", $iqamah->isha);
                $iqamah_isha = $iqamahisha2 . " " . $iqamahisha;
                if ($timeformat_24 == "yes") {
                    $iqamah_isha = date("H:i", strtotime($iqamah_isha));
                }
                $iqamahjummah2 = substr($iqamah->jummah1, -2);
                $iqamahjummah1 = str_replace(
                    $iqamahjummah2,
                    "",
                    $iqamah->jummah1
                );
                $iqamah_jummah1 = $iqamahjummah1 . " " . $iqamahjummah2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah1 = date("H:i", strtotime($iqamah_jummah1));
                }
                $iqamahjummah_2 = substr($iqamah->jummah2, -2);
                $iqamahjummah_1 = str_replace(
                    $iqamahjummah_2,
                    "",
                    $iqamah->jummah2
                );
                $iqamah_jummah2 = $iqamahjummah_1 . " " . $iqamahjummah_2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah2 = date("H:i", strtotime($iqamah_jummah2));
                }
                $salahfajr = substr($salah->fajr, -2);
                $salahfajr2 = str_replace($salahfajr, "", $salah->fajr);
                $salah_fajr = $salahfajr2 . " " . $salahfajr;
                if ($timeformat_24 == "yes") {
                    $salah_fajr = date("H:i", strtotime($salah_fajr));
                }
                $salahzuhr = substr($salah->zuhr, -2);
                $salahzuhr2 = str_replace($salahzuhr, "", $salah->zuhr);
                $salah_zuhr = $salahzuhr2 . " " . $salahzuhr;
                if ($timeformat_24 == "yes") {
                    $salah_zuhr = date("H:i", strtotime($salah_zuhr));
                }
                $salahasr = substr($salah->asr, -2);
                $salahasr2 = str_replace($salahasr, "", $salah->asr);
                $salah_asr = $salahasr2 . " " . $salahasr;
                if ($timeformat_24 == "yes") {
                    $salah_asr = date("H:i", strtotime($salah_asr));
                }
                $salahmaghrib = substr($salah->maghrib, -2);
                $salahmaghrib2 = str_replace(
                    $salahmaghrib,
                    "",
                    $salah->maghrib
                );
                $salah_maghrib = $salahmaghrib2 . " " . $salahmaghrib;
                if ($timeformat_24 == "yes") {
                    $salah_maghrib = date("H:i", strtotime($salah_maghrib));
                }
                $salahisha = substr($salah->isha, -2);
                $salahisha2 = str_replace($salahisha, "", $salah->isha);
                $salah_isha = $salahisha2 . " " . $salahisha;
                if ($timeformat_24 == "yes") {
                    $salah_isha = date("H:i", strtotime($salah_isha));
                }
                $salahsunrise = substr($salah->sunrise, -2);
                $salahsunrise2 = str_replace(
                    $salahsunrise,
                    "",
                    $salah->sunrise
                );
                $salah_sunrise = $salahsunrise2 . " " . $salahsunrise;
                if ($timeformat_24 == "yes") {
                    $salah_sunrise = date("H:i", strtotime($salah_sunrise));
                }

                $starts_lable = get_option("starts_lable");
                $iqamah_lable = get_option("iqamah_lable");
                $sunrise_lable = get_option("sunrise_lable");
                $fajr_lable = get_option("fajr_lable");
                $dhuhr_lable = get_option("dhuhr_lable");
                $asr_lable = get_option("asr_lable");
                $maghrib_lable = get_option("maghrib_lable");
                $isha_lable = get_option("isha_lable");
                $jumuah_header = get_option("jumuah_header");
                $jumuah1_lable = get_option("jumuah1_lable");
                $jumuah2_lable = get_option("jumuah2_lable");
                $jumuah3_lable = get_option("jumuah3_lable");
                $khutbah_label = get_option("khutbah_label");
                if (empty($khutbah_label)) {
                    $khutbah_label = "Khutbah";
                }
                $khutbah_time = get_option("khutbah_time1");
                $jumuah3_time = $iqamah->jummah3; //get_option('jumuah3_time');
                $khutbah_time_1 = substr($khutbah_time, -2);
                $khutbah_timeh_1 = str_replace(
                    $khutbah_time_1,
                    "",
                    $khutbah_time
                );
                $jumuah3_time_2 = substr($jumuah3_time, -2);
                $jumuah3_timeh_1 = str_replace(
                    $jumuah3_time_2,
                    "",
                    $jumuah3_time
                );
                $khutbah_time3 = $khutbah_timeh_1 . " " . $khutbah_time_1;
                if ($timeformat_24 == "yes") {
                    $khutbah_time3 = date("H:i", strtotime($khutbah_time3));
                }
                $jumuah3 = $jumuah3_timeh_1 . " " . $jumuah3_time_2;
                if ($timeformat_24 == "yes") {
                    $jumuah3 = date("H:i", strtotime($jumuah3));
                }
                if (empty($starts_lable)) {
                    $starts_lable = "STARTS";
                }
                if (empty($iqamah_lable)) {
                    $iqamah_lable = "IQAMAH";
                }
                if (empty($sunrise_lable)) {
                    $sunrise_lable = "Sunrise";
                }
                /* if(empty($fajr_lable)){ $fajr_lable='FAJR';  }
			if(empty($dhuhr_lable)){ $dhuhr_lable='DHUHR';  }
			if(empty($asr_lable)){ $asr_lable='ASR';  }
			if(empty($maghrib_lable)){ $maghrib_lable='MAGHRIB';  }
			if(empty($isha_lable)){ $isha_lable='ISHA';  }
			if(empty($jumuah1_lable)){ $jumuah1_lable="JUMU'AH";  }
			if(empty($jumuah2_lable)){ $jumuah2_lable="JUMU'AH";  }
			if(empty($jumuah3_lable)){ $jumuah3_lable="JUMU'AH";  } */
                if (empty($fajr_lable)) {
                    $fajr_lable = "Fajr";
                }
                if (empty($dhuhr_lable)) {
                    $dhuhr_lable = "Dhuhr";
                }
                if (empty($asr_lable)) {
                    $asr_lable = "Asr";
                }
                if (empty($maghrib_lable)) {
                    $maghrib_lable = "Maghrib";
                }
                if (empty($isha_lable)) {
                    $isha_lable = "Isha";
                }
                if (empty($jumuah1_lable)) {
                    $jumuah1_lable = "Jumu'ah";
                }
                if (empty($jumuah2_lable)) {
                    $jumuah2_lable = "Jumu'ah";
                }
                if (empty($jumuah3_lable)) {
                    $jumuah3_lable = "Jumu'ah";
                }
                $html .=
                    '<tr class="red-background">
				<th></th><th></th>
				<th class="left-align">' .
                    $starts_lable .
                    ' </th>
				<th></th>
				<th class="left-align">' .
                    $iqamah_lable .
                    '</th>
			</tr>';
                $html .=
                    '<tr class="' .
                    $fajr_class .
                    '">
				<td class="prayer-name"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/layout_2/Icon1_Fajar.png" alt="">' .
                    $fajr_lable .
                    "</td>";
                $html .=
                    ' <td colspan="2" class="athan-time">' .
                    $salah_fajr .
                    '</td> 
				<td colspan="2" class="iqama-time">' .
                    $iqamah_fajr .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="text-red">
				<td class="prayer-name"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/layout_2/Icon2_Sunrise.png" alt="">' .
                    $sunrise_lable .
                    '</td>
				<td style="" colspan="4" class=""><div class="clock-sign"></div>' .
                    $salah_sunrise .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="' .
                    $zuhr_class .
                    ' displayTableRow"><td class="prayer-name current-time"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/layout_2/Icon3_Dhuhur.png" alt="">' .
                    $dhuhr_lable .
                    '</td>
				<td colspan="2" class="athan-time current-time">' .
                    $salah_zuhr .
                    '</td>
				<td colspan="2" class="iqama-time current-time">' .
                    $iqamah_zuhr .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="' .
                    $asr_class .
                    '">
				<td class="prayer-name"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/layout_2/Icon4_Asar.png" alt="">' .
                    $asr_lable .
                    '</td> 
				<td colspan="2" class="athan-time">' .
                    $salah_asr .
                    '</td>
				<td colspan="2" class="iqama-time">' .
                    $iqamah_asr .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="' .
                    $maghrib_class .
                    '">
				<td class="prayer-name"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/layout_2/Icon5_Maghrib.png" alt="">' .
                    $maghrib_lable .
                    '</td>
				<td colspan="2" class="athan-time">' .
                    $salah_maghrib .
                    '</td> 
				<td colspan="2" class="iqama-time">' .
                    $iqamah_maghrib .
                    '</td>
			</tr>';
                $html .=
                    '<tr class="' .
                    $isha_lable .
                    '">
				<td class="prayer-name"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/layout_2/Icon6_Isha.png" alt="">' .
                    $isha_lable .
                    '</td>
				<td colspan="2" class="athan-time">' .
                    $salah_isha .
                    '</td>
				<td colspan="2" class="iqama-time">' .
                    $iqamah_isha .
                    '</td>
			</tr>';
                if ($counts < 2 && $bannerDayChange > 0) {
                    if ($bannerDayChange == 1) {
                        $html .=
                            '<tr><td colspan="5"><div class="iqamah-change day1">' .
                            $bannerText .
                            $bannerDate .
                            "</div></td></tr>";
                    } elseif ($bannerDayChange == 2) {
                        $html .=
                            '<tr><td colspan="5"><div class="iqamah-change day2">' .
                            $bannerText .
                            $bannerDate .
                            "</div></td></tr>";
                    } elseif ($bannerDayChange == 3) {
                        $html .=
                            '<tr><td colspan="5"><div class="iqamah-change day3">' .
                            $bannerText .
                            $bannerDate .
                            "</div></td></tr>";
                    }
                }
                $html .=
                    '<tr class="jummah">
				<td colspan="5"class="prayer-name"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/layout_2/Icon7_Jumuah.png" alt="">' .
                    $jumuah_header .
                    '</td>
			</tr>';
                $html .= " </tbody></table>";
                $html .= '<div class="jamu_sec_layout2">';
                if ($iqamah->jummah2 == "-") {
                    $jummah1 = "JUMU'AH";
                } else {
                    $jummah1 = "JUMU'AH";
                }
                if (
                    empty($khutbah_time) ||
                    $khutbah_time == "12:undefined AM"
                ) {
                    //$html.=' <style>.jamu-sec .col-6:nth-child(2) {border-right: none;}</style>';
                } else {
                    if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                        $html .=
                            ' <div class="layout_jumah_inner"><h1>' .
                            $khutbah_time3 .
                            "</h1><span>" .
                            $khutbah_label .
                            "</span></div>";
                    }
                }
                if (empty($iqamah->jummah1) || $iqamah->jummah1 == "-") {
                } else {
                    $html .=
                        '<div class="layout_jumah_inner"><h1>' .
                        $iqamah_jummah1 .
                        "</h1><span>" .
                        $jumuah1_lable .
                        "</span></div>";
                }
                if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                } else {
                    $html .=
                        ' <div class="layout_jumah_inner"><h1>' .
                        $iqamah_jummah2 .
                        "</h1><span>" .
                        $jumuah2_lable .
                        "</span></div>";
                }
                if (empty($iqamah->jummah3) || $iqamah->jummah3 == "-") {
                //
                    
                } else {
                    $html .=
                        ' <div class="layout_jumah_inner"><h1>' .
                        $jumuah3 .
                        "</h1><span>" .
                        $jumuah3_lable .
                        "</span></div>";
                }
                $html .= "</div>";
                $html .= "</div>";

                $counts++;
            }
            $html .= '<div style="text-align:center">';
            $masjid_calendar_type = get_option("masjid_calendar_type");
            $masjid_id = get_option("masjid_id");
            if (
                $masjid_calendar_type == "v2" ||
                $masjid_calendar_type == "v3"
            ) {
                $monthly_view =
                    "https://masjidal.com/widget/monthly/" .
                    $masjid_calendar_type .
                    "/?masjid_id=" .
                    $masjid_id;
            } elseif ($masjid_calendar_type == "Custom_url") {
                $monthly_view = get_option("montly_pdf_url");
            } else {
                $monthly_view =
                    "https://masjidal.com/widget/monthly/?masjid_id=" .
                    $masjid_id;
            }
            if (
                $masjid_calendar_type == "v2" ||
                $masjid_calendar_type == "v3" ||
                $masjid_calendar_type == "v1" ||
                $masjid_calendar_type == "Custom_url"
            ) {
                $html .=
                    '  <a class="read-more" target="_blank" href="' .
                    $monthly_view .
                    '">' .
                    $montly_text .
                    "</a>";
            }
            $html .= "</div>";
        } else {
            $html = '<div class="slideshow-container layout_default">';
            $counts = 1;
            //foreach($newArray as $alldate){
            for ($i = 0, $size = count($newArray); $i < $size; $i++) {
                //$salah=$alldate['salah'];
                //$iqamah=$alldate['iqamah'];
                //$today_day= date("l");
                //$today_day= $date->format('l');
                //$salahday=str_replace(' ', '', $salah->day);
                //$today_daya=str_replace(' ', '', $today_day);
                //$current_time=strtotime(date('g:i A'));
                $salah = $newArray[$i]->salah;
                $iqamah = $newArray[$i]->iqamah;
                $current_time = strtotime($date->format("g:i A"));
                $fajra = strtotime($iqamah->fajr);
                $zuhra = strtotime($iqamah->zuhr);
                $asrs = strtotime($iqamah->asr);
                $maghribs = strtotime($iqamah->maghrib);
                $ishas = strtotime($iqamah->isha);
                $date_curret = strtotime($newArray[$i]->date); //$iqamah->date);
                $today_view_date = date("l, M d", $date_curret);
                $color = get_option("highlighted_color");
                $text_color = get_option("highlighted_text_color");
                if ($current_time < $fajra) {
                    $fajr_class = "active_new";
                } elseif ($current_time < $zuhra) {
                    $zuhr_class = "active_new";
                } elseif ($current_time < $asrs) {
                    $asr_class = "active_new";
                } elseif ($current_time < $maghribs) {
                    $maghrib_class = "active_new";
                } elseif ($current_time < $ishas) {
                    $isha_class = "active_new";
                }
                $timeformat_24 = get_option("timeformat_24");
                $top_heading = get_option("top_heading");
                $montly_text = get_option("montly_text");
                if (empty($top_heading)) {
                    $top_heading = "Prayer Timings";
                }
                if (empty($montly_text)) {
                    $montly_text = "View Monthly Calendar";
                }
                $html .=
                    "<style>.mySlides_new.count_1 li.active_new {background-color: " .
                    $color .
                    "!important;color: " .
                    $text_color .
                    "!important;}.table_new.mySlides_new.count_1 tr.active_new td {background-color: " .
                    $color .
                    " !important;color: " .
                    $text_color .
                    "!important;}</style>";
                $html .=
                    '<div class="mySlides_new fade count_' . $counts . '">';
                $html .= ' <div class="main-center">';
                $html .= '<div class="inner-center-back">';
                $html .=
                    '<div class="heading_paryer">' . $top_heading . "</div>";
                $html .=
                    '<div class="heading_date" style=" font-weight: normal; font-size: 14px;">' .
                    $today_view_date .
                    " | " .
                    $newArray[$i]->hijri_month .
                    "  " .
                    $newArray[$i]->hijri_date .
                    "</div>";
                $iqamahfajr = substr($iqamah->fajr, -2);
                $iqamahfajr2 = str_replace($iqamahfajr, "", $iqamah->fajr);
                $iqamah_fajr = $iqamahfajr2 . " " . $iqamahfajr;
                if ($timeformat_24 == "yes") {
                    $iqamah_fajr = date("H:i", strtotime($iqamah_fajr));
                }
                $iqamahzuhr = substr($iqamah->zuhr, -2);
                $iqamahzuhr2 = str_replace($iqamahzuhr, "", $iqamah->zuhr);
                $iqamah_zuhr = $iqamahzuhr2 . " " . $iqamahzuhr;
                if ($timeformat_24 == "yes") {
                    $iqamah_zuhr = date("H:i", strtotime($iqamah_zuhr));
                }
                $iqamahasr = substr($iqamah->asr, -2);
                $iqamahasr2 = str_replace($iqamahasr, "", $iqamah->asr);
                $iqamah_asr = $iqamahasr2 . " " . $iqamahasr;
                if ($timeformat_24 == "yes") {
                    $iqamah_asr = date("H:i", strtotime($iqamah_asr));
                }
                $iqamahmaghrib = substr($iqamah->maghrib, -2);
                $iqamahmaghrib2 = str_replace(
                    $iqamahmaghrib,
                    "",
                    $iqamah->maghrib
                );
                $iqamah_maghrib = $iqamahmaghrib2 . " " . $iqamahmaghrib;
                if ($timeformat_24 == "yes") {
                    $iqamah_maghrib = date("H:i", strtotime($iqamah_maghrib));
                }
                $iqamahisha = substr($iqamah->isha, -2);
                $iqamahisha2 = str_replace($iqamahisha, "", $iqamah->isha);
                $iqamah_isha = $iqamahisha2 . " " . $iqamahisha;
                if ($timeformat_24 == "yes") {
                    $iqamah_isha = date("H:i", strtotime($iqamah_isha));
                }
                $iqamahjummah2 = substr($iqamah->jummah1, -2);
                $iqamahjummah1 = str_replace(
                    $iqamahjummah2,
                    "",
                    $iqamah->jummah1
                );
                $iqamah_jummah1 = $iqamahjummah1 . " " . $iqamahjummah2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah1 = date("H:i", strtotime($iqamah_jummah1));
                }
                $iqamahjummah_2 = substr($iqamah->jummah2, -2);
                $iqamahjummah_1 = str_replace(
                    $iqamahjummah_2,
                    "",
                    $iqamah->jummah2
                );
                $iqamah_jummah2 = $iqamahjummah_1 . " " . $iqamahjummah_2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah2 = date("H:i", strtotime($iqamah_jummah2));
                }
                $salahfajr = substr($salah->fajr, -2);
                $salahfajr2 = str_replace($salahfajr, "", $salah->fajr);
                $salah_fajr = $salahfajr2 . " " . $salahfajr;
                if ($timeformat_24 == "yes") {
                    $salah_fajr = date("H:i", strtotime($salah_fajr));
                }
                $salahzuhr = substr($salah->zuhr, -2);
                $salahzuhr2 = str_replace($salahzuhr, "", $salah->zuhr);
                $salah_zuhr = $salahzuhr2 . " " . $salahzuhr;
                if ($timeformat_24 == "yes") {
                    $salah_zuhr = date("H:i", strtotime($salah_zuhr));
                }
                $salahasr = substr($salah->asr, -2);
                $salahasr2 = str_replace($salahasr, "", $salah->asr);
                $salah_asr = $salahasr2 . " " . $salahasr;
                if ($timeformat_24 == "yes") {
                    $salah_asr = date("H:i", strtotime($salah_asr));
                }
                $salahmaghrib = substr($salah->maghrib, -2);
                $salahmaghrib2 = str_replace(
                    $salahmaghrib,
                    "",
                    $salah->maghrib
                );
                $salah_maghrib = $salahmaghrib2 . " " . $salahmaghrib;
                if ($timeformat_24 == "yes") {
                    $salah_maghrib = date("H:i", strtotime($salah_maghrib));
                }
                $salahisha = substr($salah->isha, -2);
                $salahisha2 = str_replace($salahisha, "", $salah->isha);
                $salah_isha = $salahisha2 . " " . $salahisha;
                if ($timeformat_24 == "yes") {
                    $salah_isha = date("H:i", strtotime($salah_isha));
                }
                $salahsunrise = substr($salah->sunrise, -2);
                $salahsunrise2 = str_replace(
                    $salahsunrise,
                    "",
                    $salah->sunrise
                );
                $salah_sunrise = $salahsunrise2 . " " . $salahsunrise;
                if ($timeformat_24 == "yes") {
                    $salah_sunrise = date("H:i", strtotime($salah_sunrise));
                }
                $starts_lable = get_option("starts_lable");
                $iqamah_lable = get_option("iqamah_lable");
                $fajr_lable = get_option("fajr_lable");
                $dhuhr_lable = get_option("dhuhr_lable");
                $asr_lable = get_option("asr_lable");
                $maghrib_lable = get_option("maghrib_lable");
                $isha_lable = get_option("isha_lable");
                $jumuah1_lable = get_option("jumuah1_lable");
                $jumuah2_lable = get_option("jumuah2_lable");
                $jumuah3_lable = get_option("jumuah3_lable");
                $khutbah_label = get_option("khutbah_label");
                if (empty($khutbah_label)) {
                    $khutbah_label = "Khutbah";
                }
                $khutbah_time = get_option("khutbah_time1");
                $jumuah3_time = $iqamah->jummah3; //get_option('jumuah3_time');
                $khutbah_time_1 = substr($khutbah_time, -2);
                $khutbah_timeh_1 = str_replace(
                    $khutbah_time_1,
                    "",
                    $khutbah_time
                );
                $jumuah3_time_2 = substr($jumuah3_time, -2);
                $jumuah3_timeh_1 = str_replace(
                    $jumuah3_time_2,
                    "",
                    $jumuah3_time
                );
                $khutbah_time3 = $khutbah_timeh_1 . " " . $khutbah_time_1;
                if ($timeformat_24 == "yes") {
                    $khutbah_time3 = date("H:i", strtotime($khutbah_time3));
                }
                $jumuah3 = $jumuah3_timeh_1 . " " . $jumuah3_time_2;
                if ($timeformat_24 == "yes") {
                    $jumuah3 = date("H:i", strtotime($jumuah3));
                }
                if (empty($starts_lable)) {
                    $starts_lable = "STARTS";
                }
                if (empty($iqamah_lable)) {
                    $iqamah_lable = "IQAMAH";
                }
                if (empty($fajr_lable)) {
                    $fajr_lable = "Fajr";
                }
                if (empty($dhuhr_lable)) {
                    $dhuhr_lable = "Dhuhr";
                }
                if (empty($asr_lable)) {
                    $asr_lable = "Asr";
                }
                if (empty($maghrib_lable)) {
                    $maghrib_lable = "Maghrib";
                }
                if (empty($isha_lable)) {
                    $isha_lable = "Isha";
                }
                if (empty($jumuah1_lable)) {
                    $jumuah1_lable = "Jumu'ah";
                }
                if (empty($jumuah2_lable)) {
                    $jumuah2_lable = "Jumu'ah";
                }
                if (empty($jumuah3_lable)) {
                    $jumuah3_lable = "Jumu'ah";
                }
                $html .= "<ul>";
                $html .=
                    '<li><div class="time_namze_heading"><span class="text-right start_time">' .
                    $starts_lable .
                    ' </span><span class="text-center" style="font-weight: normal;border: none;">' .
                    $iqamah_lable .
                    "</span></div></li>";
                $html .=
                    '<li class="' .
                    $fajr_class .
                    '" style="padding: 15px 15px;"><div class="image_and_text_namze"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/d_fajr.png" alt=""><span class="namze_name"> ' .
                    $fajr_lable .
                    ' </span></div><div class="time_namze"><span>' .
                    $salah_fajr .
                    '</span><span class="text-center">' .
                    $iqamah_fajr .
                    "</span></div></li>";
                $html .=
                    '<li class="' .
                    $zuhr_class .
                    '" style= "padding: 15px 15px;"><div class="image_and_text_namze"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/d_dhuhr.png" alt=""><span class="namze_name"> ' .
                    $dhuhr_lable .
                    ' </span></div><div class="time_namze"><span>' .
                    $salah_zuhr .
                    '</span><span class="text-center">' .
                    $iqamah_zuhr .
                    "</span></div></li>";
                $html .=
                    '<li class="' .
                    $asr_class .
                    '" style="padding: 15px 15px;' .
                    "background-color:" .
                    $asr .
                    '"><div class="image_and_text_namze"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/d_asr.png" alt=""> <span class="namze_name">' .
                    $asr_lable .
                    ' </span></div><div class="time_namze"><span>' .
                    $salah_asr .
                    '</span><span class="text-center">' .
                    $iqamah_asr .
                    "</span></div></li>";
                $html .=
                    ' <li class="' .
                    $maghrib_class .
                    '" style="padding: 15px 15px;" ><div class="image_and_text_namze"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/d_maghrib.png" alt=""><span class="namze_name"> ' .
                    $maghrib_lable .
                    ' </span></div><div class="time_namze"><span>' .
                    $salah_maghrib .
                    '</span><span class="text-center">' .
                    $iqamah_maghrib .
                    "</span></div></li>";
                $html .=
                    ' <li class="isha_li ' .
                    $isha_class .
                    '" style="padding: 15px 15px;"><div class="image_and_text_namze"><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/d_isha.png" alt=""> <span class="namze_name">' .
                    $isha_lable .
                    ' </span></div><div class="time_namze"><span>' .
                    $salah_isha .
                    '</span><span class="text-center">' .
                    $iqamah_isha .
                    "</span></div></li>";
                $html .= "</ul> ";
                if ($counts < 2 && $bannerDayChange > 0) {
                    if ($bannerDayChange == 1) {
                        $html .=
                            '<div class="iqamah-change day1">' .
                            $bannerText .
                            $bannerDate .
                            "</div>";
                    } elseif ($bannerDayChange == 2) {
                        $html .=
                            '<div class="iqamah-change day2">' .
                            $bannerText .
                            $bannerDate .
                            "</div>";
                    } elseif ($bannerDayChange == 3) {
                        $html .=
                            '<div class="iqamah-change day3">' .
                            $bannerText .
                            $bannerDate .
                            "</div>";
                    }
                }
                $html .= '<div class="jamu-sec">';
                if ($iqamah->jummah2 == "-") {
                    $jummah1 = "JUMU'AH";
                } else {
                    $jummah1 = "JUMU'AH";
                }
                if (
                    empty($khutbah_time) ||
                    $khutbah_time == "12:undefined AM"
                ) {
                    //$html.=' <style>.jamu-sec .col-6:nth-child(2) {border-right: none;}</style>';
                } else {
                    if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                        //$html.='<style>.jamu-sec .col-6:nth-child(1) {border-right: 2px #e3e3e3 solid;} </style>';
                        $html .=
                            ' <div class="col-6 text-center" style="border-right: 2px #e3e3e3 solid;"><h1>' .
                            $khutbah_time3 .
                            "</h1><span>" .
                            $khutbah_label .
                            "</span></div>";
                    }
                }
                if (empty($iqamah->jummah1) || $iqamah->jummah1 == "-") {
                } else {
                    $html .=
                        '<div class="col-6 text-center"><h1>' .
                        $iqamah_jummah1 .
                        "</h1><span>" .
                        $jumuah1_lable .
                        "</span></div>";
                }
                if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                    $html .=
                        " <style>.jamu-sec .col-6:nth-child(1) {border-right: none !important;}</style>";
                } else {
                    $html .=
                        ' <div class="col-6 text-center"><h1>' .
                        $iqamah_jummah2 .
                        "</h1><span>" .
                        $jumuah2_lable .
                        "</span></div>";
                }
                if (empty($iqamah->jummah3) || $iqamah->jummah3 == "-") {
                    $html .=
                        " <style>.jamu-sec .col-6:nth-child(2) {border-right: none !important;}</style>";
                } else {
                    if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                        $html .=
                            "<style>.jamu-sec .col-6:nth-child(1) {border-right: 2px #e3e3e3 solid;} </style>";
                    }
                    $html .=
                        ' <div class="col-6 text-center"><h1>' .
                        $jumuah3 .
                        "</h1><span>" .
                        $jumuah3_lable .
                        "</span></div>";
                }
                $html .= "</div>";
                $html .= '<div class="am-pm">';
                $html .=
                    '<div class="col-6 text-left"><h1><img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/am-icon.jpg" alt="">' .
                    $salah_sunrise .
                    "</h1></div>";
                $html .=
                    '<div class="col-6 text-right"><h1>' .
                    $salah_maghrib .
                    ' <img src="' .
                    plugin_dir_url(dirname(dirname(__FILE__))) .
                    'public/assets/images/sunset.png" alt="" style="margin: 0 0 0 10px"></h1></div>';
                $html .= "</div>";
                $html .= " </div>";
                $html .= "</div>";
                $html .= "</div>";
                $counts++;
            }
            $html .= '<div style="text-align:center">';
            $count = 1;
            foreach ($newArray as $alldate) {
                $html .=
                    '<span class="dots" onclick="currentSlide_new(' .
                    $count .
                    ')"></span> ';
                $count++;
            }
            $html .= "</div>";
            $masjid_calendar_type = get_option("masjid_calendar_type");
            $masjid_id = get_option("masjid_id");
            if (
                $masjid_calendar_type == "v2" ||
                $masjid_calendar_type == "v3"
            ) {
                $monthly_view =
                    "https://masjidal.com/widget/monthly/" .
                    $masjid_calendar_type .
                    "/?masjid_id=" .
                    $masjid_id;
            } elseif ($masjid_calendar_type == "Custom_url") {
                $monthly_view = get_option("montly_pdf_url");
            } else {
                $monthly_view =
                    "https://masjidal.com/widget/monthly/?masjid_id=" .
                    $masjid_id;
            }
            if (
                $masjid_calendar_type == "v2" ||
                $masjid_calendar_type == "v3" ||
                $masjid_calendar_type == "v1" ||
                $masjid_calendar_type == "Custom_url"
            ) {
                $html .=
                    ' <a class="montyly_view" style="float: right;" target="_blank" href="' .
                    $monthly_view .
                    '">' .
                    $montly_text .
                    "</a>";
            }
            $html .=
                '<div class="prowred_by"><a target="_blank" href="http://www.masjidal.com/">Powered by Masjidal</a></div>';
        }

        $html .= "</div>";
        $masjid_calendar_layout = get_option("masjid_calendar_layout");
        if (
            $masjid_calendar_layout == "Layout1" ||
            $masjid_calendar_layout == "Layout2"
        ) {
            $html .=
                '<div class="prowred_by layout_1"><a target="_blank" href="http://www.masjidal.com/">Powered by Masjidal</a></div>';
        }
        /*if($masjid_calendar_layout == 'Layout2'){
 $html.='<div class="prowred_by layout_1"><a target="_blank" href="http://www.masjidal.com/">Powered by Masjidal</a></div>';
}*/

        $html .= '<script>
var slideIndex = 1;
showSlides_new(slideIndex);

function plusSlides(n) {
  showSlides_new(slideIndex += n);
}

function currentSlide_new(n) {
  showSlides_new(slideIndex = n);
}

function showSlides_new(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides_new");
  var dots = document.getElementsByClassName("dots");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
 for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>';

        return $html;
    }
}
add_shortcode("single_view_calendar", "mptsi_single_view_calendar");

/*salah_fajr*/
if (!function_exists("mptsi_far_salah_masjidal")) {
    function mptsi_far_salah_masjidal()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        //foreach($results->data as $key => $subarray){
        //	foreach($subarray as $j => $k){
        //		$newArray[$j][$key] = $k;
        //	}
        //}
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            //$salah=$alldate['salah'];
            $salah = $newArray[$i]->salah;
            $date_api_today = strtotime($newArray[$i]->date);
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= '<div class="salahfajr">';
                $salahfajr = substr($salah->fajr, -2);
                $salahfajr2 = str_replace($salahfajr, "", $salah->fajr);
                $salah_fajr = $salahfajr2 . " " . $salahfajr;
                if ($timeformat_24 == "yes") {
                    $salah_fajr = date("H:i", strtotime($salah_fajr));
                }
                $html .= " <span>" . $salah_fajr . "</span>";
                $html .= "</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_salah_fajr", "mptsi_far_salah_masjidal");

/*salah_zuhr*/
if (!function_exists("mptsi_zuhr_salah_masjidal")) {
    function mptsi_zuhr_salah_masjidal()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        //foreach($results->data as $key => $subarray){
        //	foreach($subarray as $j => $k){
        //		$newArray[$j][$key] = $k;
        //	}
        //}
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            //$salah=$alldate['salah'];
            $salah = $newArray[$i]->salah;
            $date_api_today = strtotime($newArray[$i]->date);
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= ' <div class="salahzuhr">';
                $salahzuhr = substr($salah->zuhr, -2);
                $salahzuhr2 = str_replace($salahzuhr, "", $salah->zuhr);
                $salah_zuhr = $salahzuhr2 . " " . $salahzuhr;
                if ($timeformat_24 == "yes") {
                    $salah_zuhr = date("H:i", strtotime($salah_zuhr));
                }
                $html .= " <span>" . $salah_zuhr . "</span>";
                $html .= " </div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_salah_zuhr", "mptsi_zuhr_salah_masjidal");

/*salah_asr*/
if (!function_exists("mptsi_asr_salah_masjidal")) {
    function mptsi_asr_salah_masjidal()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html .= '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            //$salah=$alldate['salah'];
            $salah = $newArray[$i]->salah;
            $date_api_today = strtotime($newArray[$i]->date);
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= '<div class="salah_asr">';
                $salahasr = substr($salah->asr, -2);
                $salahasr2 = str_replace($salahasr, "", $salah->asr);
                $salah_asr = $salahasr2 . " " . $salahasr;
                if ($timeformat_24 == "yes") {
                    $salah_asr = date("H:i", strtotime($salah_asr));
                }
                $html .= " <span>" . $salah_asr . "</span>";
                $html .= "</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_salah_asr", "mptsi_asr_salah_masjidal");

/*maghrib_salah*/
if (!function_exists("mptsi_maghrib_salah_masjidal")) {
    function mptsi_maghrib_salah_masjidal()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html .= '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            //$salah=$alldate['salah'];
            $salah = $newArray[$i]->salah;
            $date_api_today = strtotime($newArray[$i]->date);
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= '<div class="salah_maghrib">';
                $salahmaghrib = substr($salah->maghrib, -2);
                $salahmaghrib2 = str_replace(
                    $salahmaghrib,
                    "",
                    $salah->maghrib
                );
                $salah_maghrib = $salahmaghrib2 . " " . $salahmaghrib;
                if ($timeformat_24 == "yes") {
                    $salah_maghrib = date("H:i", strtotime($salah_maghrib));
                }
                $html .= " <span>" . $salah_maghrib . "</span>";
                $html .= "</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_salah_maghrib", "mptsi_maghrib_salah_masjidal");

/*isha_salah*/
if (!function_exists("mptsi_isha_salah_masjidal")) {
    function mptsi_isha_salah_masjidal()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html .= '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            //$salah=$alldate['salah'];
            $salah = $newArray[$i]->salah;
            $date_api_today = strtotime($newArray[$i]->date);
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= '<div class="salah_isha">';
                $salahisha = substr($salah->isha, -2);
                $salahisha2 = str_replace($salahisha, "", $salah->isha);
                $salah_isha = $salahisha2 . " " . $salahisha;
                if ($timeformat_24 == "yes") {
                    $salah_isha = date("H:i", strtotime($salah_isha));
                }
                $html .= " <span>" . $salah_isha . "</span>";
                $html .= " </div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_salah_isha", "mptsi_isha_salah_masjidal");

/*IQAMAH single short code*/

/*iqamah_fajr*/
if (!function_exists("mptsi_masjidal_iqamah_fajr")) {
    function mptsi_masjidal_iqamah_fajr()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= ' <div class="iqamah_fajr">';
                $iqamahfajr = substr($iqamah->fajr, -2);
                $iqamahfajr2 = str_replace($iqamahfajr, "", $iqamah->fajr);
                $iqamah_fajr = $iqamahfajr2 . " " . $iqamahfajr;
                if ($timeformat_24 == "yes") {
                    $iqamah_fajr = date("H:i", strtotime($iqamah_fajr));
                }
                $html .= "<span>" . $iqamah_fajr . "</span>";
                $html .= " </div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_iqamah_fajr", "mptsi_masjidal_iqamah_fajr");

/*iqamah_zuhr*/
if (!function_exists("mptsi_masjidal_iqamah_zuhr")) {
    function mptsi_masjidal_iqamah_zuhr()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= '<div class="iqamah_zuhr">';
                $iqamahzuhr = substr($iqamah->zuhr, -2);
                $iqamahzuhr2 = str_replace($iqamahzuhr, "", $iqamah->zuhr);
                $iqamah_zuhr = $iqamahzuhr2 . " " . $iqamahzuhr;
                if ($timeformat_24 == "yes") {
                    $iqamah_zuhr = date("H:i", strtotime($iqamah_zuhr));
                }
                $html .= "<span>" . $iqamah_zuhr . "</span>";
                $html .= "</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_iqamah_zuhr", "mptsi_masjidal_iqamah_zuhr");

/*iqamah_asr*/
if (!function_exists("mptsi_masjidal_iqamah_asr")) {
    function mptsi_masjidal_iqamah_asr()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= ' <div class="iqamah_asr">';
                $iqamahasr = substr($iqamah->asr, -2);
                $iqamahasr2 = str_replace($iqamahasr, "", $iqamah->asr);
                $iqamah_asr = $iqamahasr2 . " " . $iqamahasr;
                if ($timeformat_24 == "yes") {
                    $iqamah_asr = date("H:i", strtotime($iqamah_asr));
                }
                $html .= "<span>" . $iqamah_asr . "</span>";
                $html .= "  </div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_iqamah_asr", "mptsi_masjidal_iqamah_asr");

/*iqamah_maghrib*/
if (!function_exists("mptsi_masjidal_iqamah_maghrib")) {
    function mptsi_masjidal_iqamah_maghrib()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="slideshow-container single_time">';

        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= '<div class="iqamah_maghrib">';
                $iqamahmaghrib = substr($iqamah->maghrib, -2);
                $iqamahmaghrib2 = str_replace(
                    $iqamahmaghrib,
                    "",
                    $iqamah->maghrib
                );
                $iqamah_maghrib = $iqamahmaghrib2 . " " . $iqamahmaghrib;
                if ($timeformat_24 == "yes") {
                    $iqamah_maghrib = date("H:i", strtotime($iqamah_maghrib));
                }
                $html .= "<span>" . $iqamah_maghrib . "</span>";
                $html .= " </div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_iqamah_maghrib", "mptsi_masjidal_iqamah_maghrib");

/*iqamah_isha*/
if (!function_exists("mptsi_masjidal_iqamah_isha")) {
    function mptsi_masjidal_iqamah_isha()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="slideshow-container single_time">';
        //foreach($newArray as $alldate){
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .= '<div class="iqamah_isha">';
                $iqamahisha = substr($iqamah->isha, -2);
                $iqamahisha2 = str_replace($iqamahisha, "", $iqamah->isha);
                $iqamah_isha = $iqamahisha2 . " " . $iqamahisha;
                if ($timeformat_24 == "yes") {
                    $iqamah_isha = date("H:i", strtotime($iqamah_isha));
                }
                $html .= " <span>" . $iqamah_isha . "</span>";
                $html .= "</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_iqamah_isha", "mptsi_masjidal_iqamah_isha");

/*ALL jummah*/
if (!function_exists("mptsi_masjidal_iqamah_jummah")) {
    function mptsi_masjidal_iqamah_jummah()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $html = '<div class="jamuah">';
        $timeformat_24 = get_option("timeformat_24");
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $jumuah1_lable = get_option("jumuah1_lable");
                $jumuah2_lable = get_option("jumuah2_lable");
                $jumuah3_lable = get_option("jumuah3_lable");
                $jumuah3_time = get_option("jumuah3_time");
                $jumuah3_time_2 = substr($jumuah3_time, -2);
                $jumuah3_timeh_1 = str_replace(
                    $jumuah3_time_2,
                    "",
                    $jumuah3_time
                );
                $jumuah3 = $jumuah3_timeh_1 . " " . $jumuah3_time_2;
                if ($timeformat_24 == "yes") {
                    $jumuah3 = date("H:i", strtotime($jumuah3));
                }

                $iqamahjummah2 = substr($iqamah->jummah1, -2);
                $iqamahjummah1 = str_replace(
                    $iqamahjummah2,
                    "",
                    $iqamah->jummah1
                );
                $iqamah_jummah1 = $iqamahjummah1 . " " . $iqamahjummah2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah1 = date("H:i", strtotime($iqamah_jummah1));
                }
                $iqamahjummah_2 = substr($iqamah->jummah2, -2);
                $iqamahjummah_1 = str_replace(
                    $iqamahjummah_2,
                    "",
                    $iqamah->jummah2
                );
                $iqamah_jummah2 = $iqamahjummah_1 . " " . $iqamahjummah_2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah2 = date("H:i", strtotime($iqamah_jummah2));
                }

                if (empty($iqamah->jummah1) || $iqamah->jummah1 == "-") {
                } else {
                    $html .=
                        "<span>" .
                        $iqamah_jummah1 .
                        "</span> <span>" .
                        $jumuah1_lable .
                        "</span>";
                }
                if (empty($iqamah->jummah2) || $iqamah->jummah2 == "-") {
                } else {
                    $html .=
                        " | <span>" .
                        $iqamah_jummah2 .
                        "</span> <span>" .
                        $jumuah2_lable .
                        "</span>";
                }
                if (empty($iqamah->jummah3) || $iqamah->jummah3 == "-") {
                //
                    
                } else {
                    $html .=
                        " | <span>" .
                        $jumuah3 .
                        "</span> <span>" .
                        $jumuah3_lable .
                        "</span>";
                }
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_iqamah_jummah", "mptsi_masjidal_iqamah_jummah");

/*sunrise 1*/
if (!function_exists("mptsi_masjidal_iqamah_sunrise")) {
    function mptsi_masjidal_iqamah_sunrise()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="sunrise_sunsset">';
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $salah = $newArray[$i]->salah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $salahsunrise = substr($salah->sunrise, -2);
                $salahsunrise2 = str_replace(
                    $salahsunrise,
                    "",
                    $salah->sunrise
                );
                $salah_sunrise = $salahsunrise2 . " " . $salahsunrise;
                if ($timeformat_24 == "yes") {
                    $salah_sunrise = date("H:i", strtotime($salah_sunrise));
                }

                $html .= "<div><span>" . $salah_sunrise . " </span></div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_salah_sunrise", "mptsi_masjidal_iqamah_sunrise");

/*sunset 1*/
if (!function_exists("mptsi_masjidal_iqamah_sunset")) {
    function mptsi_masjidal_iqamah_sunset()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="sunrise_sunsset">';
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $salah = $newArray[$i]->salah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $salahmaghrib = substr($salah->maghrib, -2);
                $salahmaghrib2 = str_replace(
                    $salahmaghrib,
                    "",
                    $salah->maghrib
                );
                $salah_maghrib = $salahmaghrib2 . " " . $salahmaghrib;
                if ($timeformat_24 == "yes") {
                    $salah_maghrib = date("H:i", strtotime($salah_maghrib));
                }

                $html .= "<div><span>" . $salah_maghrib . " </h1></span>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_salah_sunset", "mptsi_masjidal_iqamah_sunset");

/*jummah 1*/

if (!function_exists("mptsi_masjidal_jummah1")) {
    function mptsi_masjidal_jummah1()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="jamuah">';
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $iqamahjummah2 = substr($iqamah->jummah1, -2);
                $iqamahjummah1 = str_replace(
                    $iqamahjummah2,
                    "",
                    $iqamah->jummah1
                );
                $iqamah_jummah1 = $iqamahjummah1 . " " . $iqamahjummah2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah1 = date("H:i", strtotime($iqamah_jummah1));
                }

                if (!empty($iqamah_jummah1)) {
                    $html .= "<div><span>" . $iqamah_jummah1 . "</span></div>";
                }
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_jummah1", "mptsi_masjidal_jummah1");

/*jummah 2*/
if (!function_exists("mptsi_masjidal_jummah2")) {
    function mptsi_masjidal_jummah2()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="jamuah">';
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $iqamah = $newArray[$i]->iqamah;
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $iqamahjummah_2 = substr($iqamah->jummah2, -2);
                $iqamahjummah_1 = str_replace(
                    $iqamahjummah_2,
                    "",
                    $iqamah->jummah2
                );
                $iqamah_jummah2 = $iqamahjummah_1 . " " . $iqamahjummah_2;
                if ($timeformat_24 == "yes") {
                    $iqamah_jummah2 = date("H:i", strtotime($iqamah_jummah2));
                }

                if (!empty($iqamah_jummah2)) {
                    $html .= " <div><span>" . $iqamah_jummah2 . "</span></div>";
                }
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_jummah2", "mptsi_masjidal_jummah2");

/*jummah 3*/
if (!function_exists("mptsi_masjidal_jummah3")) {
    function mptsi_masjidal_jummah3()
    {
        $timeformat_24 = get_option("timeformat_24");
        $jumuah3_time = get_option("jumuah3_time");
        $html = '<div class="slideshow-container jamuah">';
        if (!empty($jumuah3_time)) {
            $jumuah3_time_2 = substr($jumuah3_time, -2);
            $jumuah3_timeh_1 = str_replace($jumuah3_time_2, "", $jumuah3_time);
            $jumuah3 = $jumuah3_timeh_1 . " " . $jumuah3_time_2;
            if ($timeformat_24 == "yes") {
                $jumuah3 = date("H:i", strtotime($jumuah3));
            }

            if (!empty($jumuah3_time) && $jumuah3_time != "12:undefined AM") {
                $html .= " <div><span>" . $jumuah3 . "</span></div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_jummah3", "mptsi_masjidal_jummah3");

/*hijri date*/
if (!function_exists("mptsi_masjidal_hijri_date")) {
    function mptsi_masjidal_hijri_date()
    {
        global $api_data_value, $date;
        $results = $api_data_value;
        $newArray = [];
        $newArray = $results->times;
        $timeformat_24 = get_option("timeformat_24");
        $html = '<div class="jamuah">';
        for ($i = 0, $size = count($newArray); $i < $size; $i++) {
            $date_api_today = strtotime($newArray[$i]->date);
            $hijri_date = $newArray[$i]->hijri_date; //$salah->hijri_date;
            $new_date = explode(", ", $hijri_date);

            $date_api_today = strtotime($salah->date);
            //$today_date=date("Y-m-d");
            $today_date = $date->format("Y-m-d");
            $today_date = strtotime($today_date);
            if ($date_api_today == $today_date) {
                $html .=
                    "<div><span>" .
                    $new_date[0] .
                    "  " .
                    $newArray[$i]->hijri_month .
                    ", " .
                    $new_date[1] .
                    "</span></div>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}
add_shortcode("masjidal_hijri_date", "mptsi_masjidal_hijri_date");

/*date shortcode*/
if (!function_exists("mptsi_masjidal_date")) {
    function mptsi_masjidal_date()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        $ipInfo = file_get_contents("http://ip-api.com/json/" . $ip);
        $ipInfo = json_decode($ipInfo);
        $timezone = $ipInfo->timezone;
        //date_default_timezone_set($timezone);
        //$today_view_date = date("l, M d Y");
        $date = new DateTime("now", new DateTimeZone($timezone));
        $today_date = $date->format("l, M d Y");
        $html .= '<div id="hijri-date_new">' . $today_view_date . "</div>";
        return $html;
    }
}
add_shortcode("masjidal_today_date", "mptsi_masjidal_date");

function echo_log($what)
{
    echo "<pre>" . print_r($what, true) . "</pre>";
}