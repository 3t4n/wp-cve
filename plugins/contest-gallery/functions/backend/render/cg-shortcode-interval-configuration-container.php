<?php

if(!function_exists('cg_shortcode_interval_configuration_container')){
    function cg_shortcode_interval_configuration_container($GalleryID,$cgProFalse){

        if(!function_exists('cg_cg_set_default_editor')){
            function cg_cg_set_default_editor() {
                $r = 'html';
                return $r;
            }
        }

        add_filter( 'wp_default_editor', 'cg_cg_set_default_editor' );

        $wp_upload_dir = wp_upload_dir();

        $optionsJsonPath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
        $optionsJson = json_decode(file_get_contents($optionsJsonPath),true);
        $optionsJsonSource = $optionsJson;

        ?>
        <script>

            cgJsClassAdmin.index.vars.cgOptionsJson = <?php echo json_encode($optionsJson);?>;

            if(!cgJsClassAdmin.index.vars.cgOptionsJson.interval){
                cgJsClassAdmin.index.vars.cgOptionsJson.interval = {};// so no error in future processing
            }

        </script>
            <?php

        $shortcodesToCheck = ['cg_gallery','cg_gallery_user','cg_gallery_no_voting','cg_gallery_winner','cg_users_contact','cg_users_reg','cg_users_login','cg_google_sign_in'];

        foreach ($shortcodesToCheck as $shortcodeType){
            if(isset($optionsJson['interval']) && isset($optionsJson['interval'][$shortcodeType])){
                $intervalConf = cg_shortcode_interval_check($GalleryID,$optionsJson,$shortcodeType);
                $isShortcodeIsActive = true;
                if(!$intervalConf['shortcodeIsActive']){
                    $isShortcodeIsActive = false;
                }
                ?>
                <script data-cg-processing="true">
                    var shortcodeType = <?php echo json_encode($shortcodeType);?>;
                    var isShortcodeIsActive = <?php echo json_encode($isShortcodeIsActive);?>;
                    var shortcodeCheckIsActivated = <?php echo json_encode($intervalConf['shortcodeCheckIsActivated']);?>;
                    if(shortcodeCheckIsActivated){
                        cgJsClassAdmin.index.vars.isShortcodeIntervalConfActive[shortcodeType] = isShortcodeIsActive;
                    }
                </script>
                <?php
            }
        }
        ?>
        <script>
            cgJsClassAdmin.intervalConf.functions.isShortcodeIntervalConfActiveCheck();
        </script>

        <?php

        echo "<div id='cgShortcodeIntervalConfigurationContainer' class='cg_backend_action_container cg_hide'>
<span class='cg_message_close'></span>";

?>

        <div class="cg-lds-dual-ring-gallery-hide cg_hide"></div>

        <form enctype="multipart/form-data" class="cg_hide" id="cgShortcodeIntervalConfigurationForm" action='<?php echo '?page="'.cg_get_version().'"/index.php'; ?>' method='POST'>
        <input type='hidden' name='cgGalleryHash' value='<?php echo md5(wp_salt( 'auth').'---cngl1---'.$GalleryID);?>'>
        <input type='hidden' name='GalleryID' value='<?php echo $GalleryID;?>'>
        <input type='hidden' name='action' value='post_cg_shortcode_interval_conf'>
        <input type='hidden' class="shortcodeType" name='shortcodeType' value=''>
<?php

echo "<div class='cg_shortcode_conf_title_container'  style='margin-top: 25px;margin-bottom: 15px;'>";
    echo "<div class='cg_shortcode_conf_title_main' >";

    echo "</div>";
    echo "<div class='cg_shortcode_conf_title_sub' >";

    echo "</div>";
echo "</div>";


echo "<div id='cgShortcodeIntervalConfigurationCgProFalseDiv' class='$cgProFalse'>";
echo "<div class='cg_main_options' >";
        echo <<<HEREDOC
    <div class='cg_view_options_row' style='margin-top:0;'>
        <div  class='cg_view_option cg_entry_page_description cg_view_option_100_percent cg_border_bottom_none $cgProFalse '>
            <div class='cg_view_option_title  cg_view_option_title_full_width'>
                <p>
                    Activate interval for <span class="cg_shortcode_conf_activate_type"></span><br>
                    <span class="cg_view_option_title_note"><b>NOTE:</b> shortcode content will get displayed only in the selected time intervals</span>
                </p>
            </div>
            <div class="cg_view_option_checkbox">
                  <input id="cgShortcodeIntervalConfigurationActivate"  type="checkbox"  name="" >
            </div>
        </div>
    </div>
HEREDOC;
echo "</div>";


echo "<div class='cg_shortcode_conf_tab'  >";

$timestampBasedOnWpConf = strtotime(cg_get_time_based_on_wp_timezone_conf(time(),'Y-m-d H:i:s'));

$currentYear = date("Y",$timestampBasedOnWpConf);

$nextYear = date("Y", strtotime('+1 year',$timestampBasedOnWpConf));

    echo '<div class="cg_shortcode_conf_tab_left active" data-cg-year="'.$currentYear.'">'.$currentYear.'</div>';
    echo '<div class="cg_shortcode_conf_tab_right" data-cg-year="'.$nextYear.'">'.$nextYear.'</div>';

echo "</div>";

        echo "<div  id='cgShortcodeIntervalConfiguration$currentYear' data-cg-year='$currentYear' class='cg_main_options cgShortcodeIntervalConfiguration' style='margin-top:0;'>";
            cg_shortcode_interval_configuration_container_render_main_options_interval_type($currentYear);
            cg_shortcode_interval_configuration_container_render_year_with_daily($currentYear);
            cg_shortcode_interval_configuration_container_render_year_with_weekly($currentYear);
            cg_shortcode_interval_configuration_container_render_year_with_months($currentYear);
        echo "</div>";

        echo "<div id='cgShortcodeIntervalConfiguration$nextYear' data-cg-year='$nextYear' class='cg_main_options cgShortcodeIntervalConfiguration cg_hide' style='margin-top:0;'>";
            cg_shortcode_interval_configuration_container_render_main_options_interval_type($nextYear);
            cg_shortcode_interval_configuration_container_render_year_with_daily($nextYear);
            cg_shortcode_interval_configuration_container_render_year_with_weekly($nextYear);
            cg_shortcode_interval_configuration_container_render_year_with_months($nextYear,true);
        echo "</div>";


echo <<<HEREDOC
<div class='cg_main_options cg_main_options_shortcode_text' data-cg-year="$nextYear"  >
HEREDOC;

echo <<<HEREDOC
    <div class='cg_view_options_row'>
        <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="wp-cgTextWhenShortcodeIntervalIsOn-wrap-Container">
            <div class='cg_view_option_title'>
                <p>Text if shortcode is active in interval<br><span class="cg_view_option_title_note">Text will be displayed above the shortcode content</span></p>
            </div>
            <div class='cg_view_option_html cg_view_option_input_full_width' >
                 <div class='cg-wp-editor-container' data-wp-editor-id="cgTextWhenShortcodeIntervalIsOn"  >
                    <textarea class='cg-wp-editor-template cg_view_option_textarea cg_view_option_textarea_interval_on' id='cgTextWhenShortcodeIntervalIsOn' name></textarea>
                </div>
            </div>
        </div>
    </div>
HEREDOC;

echo <<<HEREDOC
    <div class='cg_view_options_row'>
        <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="wp-cgTextWhenShortcodeIntervalIsOff-wrap-Container">
            <div class='cg_view_option_title'>
                <p>Text if interval has ended</p>
            </div>
            <div class='cg_view_option_html cg_view_option_input_full_width' >
                 <div class='cg-wp-editor-container' data-wp-editor-id="cgTextWhenShortcodeIntervalIsOff"  >
                    <textarea class='cg-wp-editor-template cg_view_option_textarea cg_view_option_textarea_interval_off'   id='cgTextWhenShortcodeIntervalIsOff' name></textarea>
                </div>
            </div>
        </div>
    </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;

        ?>





        <div id="cgShortcodeIntervalConfigurationSubmitContainer" >
            <input type="submit" id="cgShortcodeIntervalConfigurationSubmit" class="cg_backend_button_gallery_action" value="Save changes" >
        </div>

        </div>

    </form>

        <?php
    echo "</div>";

    }
}

if(!function_exists('cg_shortcode_interval_configuration_container_render_main_options_interval_type')){
    function cg_shortcode_interval_configuration_container_render_main_options_interval_type($year){
        echo "<div class='cg_main_options cg_main_options_interval_type cg_main_options_interval_type_$year ' data-cg-year='$year'>";
        echo <<<HEREDOC
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_bottom_none'>
                    <div class='cg_view_option_title'>
                        <p>Select interval type</p>
                    </div>
                    <div class='cg_view_option_radio_multiple'>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Monthly
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="$year" class="cg_view_option_radio_multiple_input_field cg_view_option_radio_multiple_input_field_interval_type_monthly" value="monthly"   />
                            </div>
                        </div>
                       <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Weekly
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="$year" class="cg_view_option_radio_multiple_input_field cg_view_option_radio_multiple_input_field_interval_type_weekly" value="weekly"   />
                            </div>
                        </div>
                          <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Daily
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="$year" class="cg_view_option_radio_multiple_input_field cg_view_option_radio_multiple_input_field_interval_type_daily" value="daily"   />
                            </div>
                        </div>
                </div>
            </div>
        </div>
HEREDOC;
        echo "</div>";
    }
}

if(!function_exists('cg_shortcode_interval_configuration_container_render_year_with_daily')){
    function cg_shortcode_interval_configuration_container_render_year_with_daily($year){

        $hourpickerFrom = '<select class="cg-hourspicker-from-left" >';
        for ($iTime=0;$iTime<=23;$iTime++){
            $selected = '';
            if($iTime==0){
                $selected = 'selected';
            }
            if($iTime<10){
                $hourpickerFrom .= "<option value='0$iTime' $selected>0$iTime</option>";
            }else{
                $hourpickerFrom .= "<option value='$iTime'>$iTime</option>";
            }
        }
        $hourpickerFrom .= '</select>';

        $minutespickerFrom = '<select class="cg-minutespicker-from-left"  >';
        for ($iTime=0;$iTime<=59;$iTime++){
            $selected = '';
            if($iTime==0){
                $selected = 'selected';
            }
            if($iTime<10){
                $minutespickerFrom .= "<option value='0$iTime' $selected >0$iTime</option>";
            }else{
                $minutespickerFrom .= "<option value='$iTime' >$iTime</option>";
            }
        }
        $minutespickerFrom .= '</select>';

        $hourpickerTo = '<select class="cg-hourspicker-from-right"  >';
        for ($iTime=0;$iTime<=23;$iTime++){
            $selected = '';
            if($iTime==23){
                $selected = 'selected';
            }
            if($iTime<10){
                $hourpickerTo .= "<option value='0$iTime' >0$iTime</option>";
            }else{
                $hourpickerTo .= "<option value='$iTime' $selected>$iTime</option>";
            }
        }
        $hourpickerTo .= '</select>';

        $minutespickerTo = '<select class="cg-minutespicker-from-right"  >';
        for ($iTime=0;$iTime<=59;$iTime++){
            $selected = '';
            if($iTime==59){
                $selected = 'selected';
            }
            if($iTime<10){
                $minutespickerTo .= "<option value='$iTime' >0$iTime</option>";
            }else{
                $minutespickerTo .= "<option value='$iTime' $selected>$iTime</option>";
            }
        }
        $minutespickerTo .= '</select>';

        echo "<div class='cg_main_options cg_main_options_shortcode_interval cg_main_options_daily cg_hide' >";
        echo <<<HEREDOC
        <div class='cg_view_options_row cg_shortcode_interval_datepicker_row' data-cg-year="$year"    data-cg-interval-type="daily">
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title cg_view_option_title_datepicker'>
                        <p class="cg_hide">
                         </p>
                    </div>
                    <div class='cg_view_option_select'>
                            <div class="cg_shortcode_interval_time cg_shortcode_interval_time_start" >
                                <div>
                                    Start Time<br>$hourpickerFrom $minutespickerFrom
                                </div>
                            </div>
                             <div  class="cg_shortcode_interval_time cg_shortcode_interval_time_end" >
                                <div>
                                    End Time<br>$hourpickerTo $minutespickerTo
                                </div>
                            </div>
                    </div>
                </div>
    </div>
HEREDOC;

        echo "</div>";
    }
}

if(!function_exists('cg_shortcode_interval_configuration_container_render_year_with_weekly')){
    function cg_shortcode_interval_configuration_container_render_year_with_weekly($year){

        $daysSelectStart = '<select class="cg_days_select_start"  >';
        $daysSelectStart .= "<option value='' >Select start day</option>";
        $daysSelectStart .= "<option value='monday' >Monday</option>";
        $daysSelectStart .= "<option value='tuesday' >Tuesday</option>";
        $daysSelectStart .= "<option value='wednesday' >Wednesday</option>";
        $daysSelectStart .= "<option value='thursday' >Thursday</option>";
        $daysSelectStart .= "<option value='friday' >Friday</option>";
        $daysSelectStart .= "<option value='saturday' >Saturday</option>";
        $daysSelectStart .= "<option value='sunday' >Sunday</option>";
        $daysSelectStart .= '</select>';

        $daysSelectEnd = '<select class="cg_days_select_end"  >';
        $daysSelectEnd .= "<option value='' >Select end day</option>";
        $daysSelectEnd .= "<option value='monday' >Monday</option>";
        $daysSelectEnd .= "<option value='tuesday' >Tuesday</option>";
        $daysSelectEnd .= "<option value='wednesday' >Wednesday</option>";
        $daysSelectEnd .= "<option value='thursday' >Thursday</option>";
        $daysSelectEnd .= "<option value='friday' >Friday</option>";
        $daysSelectEnd .= "<option value='saturday' >Saturday</option>";
        $daysSelectEnd .= "<option value='sunday' >Sunday</option>";
        $daysSelectEnd .= '</select>';

        $hourpickerFrom = '<select class="cg-hourspicker-from-left" >';
        for ($iTime=0;$iTime<=23;$iTime++){
            $selected = '';
            if($iTime==0){
                $selected = 'selected';
            }
            if($iTime<10){
                $hourpickerFrom .= "<option value='0$iTime' $selected>0$iTime</option>";
            }else{
                $hourpickerFrom .= "<option value='$iTime'>$iTime</option>";
            }
        }
        $hourpickerFrom .= '</select>';

        $minutespickerFrom = '<select class="cg-minutespicker-from-left"  >';
        for ($iTime=0;$iTime<=59;$iTime++){
            $selected = '';
            if($iTime==0){
                $selected = 'selected';
            }
            if($iTime<10){
                $minutespickerFrom .= "<option value='0$iTime' $selected >0$iTime</option>";
            }else{
                $minutespickerFrom .= "<option value='$iTime' >$iTime</option>";
            }
        }
        $minutespickerFrom .= '</select>';

        $hourpickerTo = '<select class="cg-hourspicker-from-right"  >';
        for ($iTime=0;$iTime<=23;$iTime++){
            $selected = '';
            if($iTime==23){
                $selected = 'selected';
            }
            if($iTime<10){
                $hourpickerTo .= "<option value='0$iTime' >0$iTime</option>";
            }else{
                $hourpickerTo .= "<option value='$iTime' $selected>$iTime</option>";
            }
        }
        $hourpickerTo .= '</select>';

        $minutespickerTo = '<select class="cg-minutespicker-from-right"  >';
        for ($iTime=0;$iTime<=59;$iTime++){
            $selected = '';
            if($iTime==59){
                $selected = 'selected';
            }
            if($iTime<10){
                $minutespickerTo .= "<option value='$iTime' >0$iTime</option>";
            }else{
                $minutespickerTo .= "<option value='$iTime' $selected>$iTime</option>";
            }
        }
        $minutespickerTo .= '</select>';


        echo "<div class='cg_main_options cg_main_options_shortcode_interval cg_main_options_weekly cg_hide' >";
        echo <<<HEREDOC
        <div class='cg_view_options_row cg_shortcode_interval_datepicker_row' data-cg-year="$year"   data-cg-interval-type="weekly">
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title cg_view_option_title_datepicker'>
                        <p class="cg_hide">
                         </p>
                    </div>
                    <div class='cg_view_option_select'>
                            <div class="cg_shortcode_interval_time cg_shortcode_interval_time_start" >
                                <div>
                                    Start<br>$daysSelectStart
                                </div>
                                <div>
                                    Start Time<br>$hourpickerFrom $minutespickerFrom
                                </div>
                            </div>
                             <div  class="cg_shortcode_interval_time cg_shortcode_interval_time_end" >
                                <div>
                                    End<br>$daysSelectEnd
                                </div>
                                <div>
                                    End Time<br>$hourpickerTo $minutespickerTo
                                </div>
                            </div>
                    </div>
                </div>
    </div>
HEREDOC;

        echo "</div>";
    }
}

if(!function_exists('cg_shortcode_interval_configuration_container_render_year_with_months')){
    function cg_shortcode_interval_configuration_container_render_year_with_months($year, $isNextYear = false){

        $timestampBasedOnWpConf = strtotime(cg_get_time_based_on_wp_timezone_conf(time(),'Y-m-d H:i:s'));

echo "<div class='cg_main_options cg_main_options_shortcode_interval cg_main_options_monthly' >";

        $currentMonthName = date('F',$timestampBasedOnWpConf);

        if(!$isNextYear){

            $hourpickerFrom = '<select class="cg-hourspicker-from-left" >';
            for ($iTime=0;$iTime<=23;$iTime++){
                $selected = '';
                if($iTime==0){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $hourpickerFrom .= "<option value='0$iTime' $selected>0$iTime</option>";
                }else{
                    $hourpickerFrom .= "<option value='$iTime'>$iTime</option>";
                }
            }
            $hourpickerFrom .= '</select>';

            $minutespickerFrom = '<select class="cg-minutespicker-from-left"  >';
            for ($iTime=0;$iTime<=59;$iTime++){
                $selected = '';
                if($iTime==0){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $minutespickerFrom .= "<option value='0$iTime' $selected >0$iTime</option>";
                }else{
                    $minutespickerFrom .= "<option value='$iTime' >$iTime</option>";
                }
            }
            $minutespickerFrom .= '</select>';

            $hourpickerTo = '<select class="cg-hourspicker-from-right"  >';
            for ($iTime=0;$iTime<=23;$iTime++){
                $selected = '';
                if($iTime==23){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $hourpickerTo .= "<option value='0$iTime' >0$iTime</option>";
                }else{
                    $hourpickerTo .= "<option value='$iTime' $selected>$iTime</option>";
                }
            }
            $hourpickerTo .= '</select>';

            $minutespickerTo = '<select class="cg-minutespicker-from-right"  >';
            for ($iTime=0;$iTime<=59;$iTime++){
                $selected = '';
                if($iTime==59){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $minutespickerTo .= "<option value='$iTime' >0$iTime</option>";
                }else{
                    $minutespickerTo .= "<option value='$iTime' $selected>$iTime</option>";
                }
            }
            $minutespickerTo .= '</select>';

            $currentMonthNameLowercase = strtolower($currentMonthName);

            $monthNumber = date('m',$timestampBasedOnWpConf);

            $dateForLastDay = "$year-$monthNumber-01";
            $lastDay = date('t',strtotime($dateForLastDay,$timestampBasedOnWpConf));

            echo <<<HEREDOC
        <div class='cg_view_options_row cg_shortcode_interval_datepicker_row'  data-cg-year="$year"  data-cg-month="$currentMonthNameLowercase"  data-cg-interval-type="monthly"     >
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title cg_view_option_title_datepicker'>
                        <p>
                                <span title="Set/Unset range" >$currentMonthName</span><span title="Set/Unset range" class="cg_shortcode_interval_datepicker"   data-cg-month="$currentMonthNameLowercase" data-cg-year="$year" data-cg-month-number="$monthNumber"  data-cg-month-last-day="$lastDay"   />
                                <input type="hidden" class="cg_shortcode_interval_datepicker_input_start" value="" name="" />
                                <input type="hidden" class="cg_shortcode_interval_datepicker_input_end" value="" name="" />
                         </p>
                    </div>
                    <div class='cg_view_option_select cg_hide'>
                            <div class="cg_shortcode_interval_time cg_shortcode_interval_time_start" >
                                <div>
                                 <span class="cg_shortcode_interval_time_set_date_start"></span>
                                </div>
                                <div>
                                    Start Time<br>$hourpickerFrom $minutespickerFrom
                                </div>
                            </div>
                             <div  class="cg_shortcode_interval_time cg_shortcode_interval_time_end" >
                                <div>
                                    <span class="cg_shortcode_interval_time_set_date_end"></span>
                                </div>
                                <div>
                                    End Time<br>$hourpickerTo $minutespickerTo
                                </div>
                            </div>
                    </div>
                </div>
    </div>
HEREDOC;
        }

        for ($i=1;(
            (date('F', strtotime("+$i month",$timestampBasedOnWpConf))!=$currentMonthName && date('F', strtotime("+$i month",$timestampBasedOnWpConf))!='January' && !$isNextYear) ||
            ($i <=12 && $isNextYear)
        );$i++){

            if(!$isNextYear){

                $nextMonthNumber = date('n',$timestampBasedOnWpConf)+$i;
                $yearNow = date("Y");

                if($nextMonthNumber<10){
                    $nextMonthNumber = '0'+$nextMonthNumber;
                }

                $monthName = date('F', strtotime("$yearNow-$nextMonthNumber-01",$timestampBasedOnWpConf));
                $monthNumber = date('m', strtotime("$yearNow-$nextMonthNumber-01",$timestampBasedOnWpConf));

            }else{
              if($i==1){$monthName='January';$monthNumber = 1;}
                if($i==2){$monthName='February';$monthNumber = 2;}
              if($i==3){$monthName='March';$monthNumber = 3;}
              if($i==4){$monthName='April';$monthNumber = 4;}
              if($i==5){$monthName='May';$monthNumber = 5;}
              if($i==6){$monthName='June';$monthNumber = 6;}
              if($i==7){$monthName='July';$monthNumber = 7;}
              if($i==8){$monthName='August';$monthNumber = 8;}
              if($i==9){$monthName='September';$monthNumber = 9;}
              if($i==10){$monthName='October';$monthNumber = 10;}
              if($i==11){$monthName='November';$monthNumber = 11;}
              if($i==12){$monthName='December';$monthNumber = 12;}
            }


            $hourpickerFrom = '<select  class="cg-hourspicker-from-left"   >';
            for ($iTime=0;$iTime<=23;$iTime++){
                $selected = '';
                if($iTime==0){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $hourpickerFrom .= "<option value='0$iTime' $selected>0$iTime</option>";
                }else{
                    $hourpickerFrom .= "<option value='$iTime'>$iTime</option>";
                }
            }
            $hourpickerFrom .= '</select>';

            $minutespickerFrom = '<select  class="cg-minutespicker-from-left"   >';
            for ($iTime=0;$iTime<=59;$iTime++){
                $selected = '';
                if($iTime==0){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $minutespickerFrom .= "<option value='0$iTime' $selected>0$iTime</option>";
                }else{
                    $minutespickerFrom .= "<option value='$iTime' >$iTime</option>";
                }
            }
            $minutespickerFrom .= '</select>';

            $hourpickerTo = '<select  class="cg-hourspicker-from-right"  >';
            for ($iTime=0;$iTime<=23;$iTime++){
                $selected = '';
                if($iTime==23){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $hourpickerTo .= "<option value='0$iTime' >0$iTime</option>";
                }else{
                    $hourpickerTo .= "<option value='$iTime' $selected>$iTime</option>";
                }
            }
            $hourpickerTo .= '</select>';

            $minutespickerTo = '<select  class="cg-minutespicker-from-right"   >';
            for ($iTime=0;$iTime<=59;$iTime++){
                $selected = '';
                if($iTime==59){
                    $selected = 'selected';
                }
                if($iTime<10){
                    $minutespickerTo .= "<option value='$iTime' >0$iTime</option>";
                }else{
                    $minutespickerTo .= "<option value='$iTime' $selected>$iTime</option>";
                }
            }
            $minutespickerTo .= '</select>';

            $monthNameLowercase = strtolower($monthName);

            $dateForLastDay = "$year-$monthNumber-01";
            $lastDay = date('t',strtotime($dateForLastDay,$timestampBasedOnWpConf));

            echo <<<HEREDOC
        <div class='cg_view_options_row cg_shortcode_interval_datepicker_row'  data-cg-year="$year"  data-cg-month="$monthNameLowercase" data-cg-interval-type="monthly"   >
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title cg_view_option_title_datepicker'>
                        <p>
                                <span title="Set/Unset range" >$monthName</span><span type="text" title="Set/Unset range" class="cg_shortcode_interval_datepicker"   data-cg-month="$monthNameLowercase" data-cg-year="$year" data-cg-month-number="$monthNumber"  data-cg-month-last-day="$lastDay"    />
                                <input type="hidden" class="cg_shortcode_interval_datepicker_input_start" value="" name="" />
                                <input type="hidden" class="cg_shortcode_interval_datepicker_input_end" value="" name="" />
                         </p>
                    </div>
                    <div class='cg_view_option_select cg_hide'>
                            <div class="cg_shortcode_interval_time cg_shortcode_interval_time_start" >
                                <div>
                                 <span class="cg_shortcode_interval_time_set_date_start"></span>
                                </div>
                                <div>
                                    Start Time<br>$hourpickerFrom $minutespickerFrom
                                </div>
                            </div>
                             <div  class="cg_shortcode_interval_time cg_shortcode_interval_time_end" >
                                <div>
                                    <span class="cg_shortcode_interval_time_set_date_end"></span>
                                </div>
                                <div>
                                    End Time<br>$hourpickerTo $minutespickerTo
                                </div>
                            </div>
                    </div>
                </div>
    </div>
HEREDOC;
        }
        echo "</div>";

    }
}

?>