<?php
if(!function_exists('cg_shortcode_interval_check')){
    function cg_shortcode_interval_check($GalleryID,$options,$cg_gallery_shortcode_type){
        $isActive = true;
        $shortcodeCheckIsActivated = false;
        $intervalStartDate = null;
        $intervalEndDate = null;
        $TextWhenShortcodeIntervalIsOn = '';
        $TextWhenShortcodeIntervalIsOff = '';
        if(isset($options['interval'][$cg_gallery_shortcode_type])
            && isset($options['interval'][$cg_gallery_shortcode_type]['active'])
            && $options['interval'][$cg_gallery_shortcode_type]['active']=='on'
        ){
            $shortcodeCheckIsActivated = true;
            $TextWhenShortcodeIntervalIsOn = $options['interval'][$cg_gallery_shortcode_type]['TextWhenShortcodeIntervalIsOn'];
            $TextWhenShortcodeIntervalIsOff = $options['interval'][$cg_gallery_shortcode_type]['TextWhenShortcodeIntervalIsOff'];
            $isActive = false;
            $currentYear = date("Y");
            // selectedIntervalType
            if(isset($options['interval'][$cg_gallery_shortcode_type][$currentYear])){
                $interval = $options['interval'][$cg_gallery_shortcode_type][$currentYear]['selectedIntervalType'];
                $currentMonthName = strtolower(date('F'));
                if(isset($options['interval'][$cg_gallery_shortcode_type][$currentYear])){
                    if($interval=='monthly'){
                        $fromDate = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['fromDate'];
                        $toDate = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['toDate'];
                        if($fromDate && $toDate){
                            $fromDate = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['fromDate'];
                            $toDate = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['toDate'];
                            $fromHours = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['fromHours'];
                            $fromMinutes = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['fromMinutes'];
                            $toHours = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['toHours'];
                            $toMinutes = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval][$currentMonthName]['toMinutes'];
                            $dateNow = cg_get_date_time_object_based_on_wp_timezone_conf((new DateTime('now'))->getTimestamp());

                            $intervalStartDate = new DateTime($fromDate.' '.$fromHours.':'.$fromMinutes.':00');
                            $intervalEndDate = new DateTime($toDate.' '.$toHours.':'.$toMinutes.':59');

                            if($intervalStartDate->getTimestamp()<=$dateNow->getTimestamp()
                                && $intervalEndDate->getTimestamp()>=$dateNow->getTimestamp()){
                                $isActive = true;
                            }
                        }
                    }
                    if($interval=='weekly'){

                        $dayStart = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['dayStart'];
                        $dayEnd = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['dayEnd'];
                        if($dayStart && $dayEnd){

                            $fromHours = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['fromHours'];
                            $fromMinutes = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['fromMinutes'];
                            $toHours = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['toHours'];
                            $toMinutes = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['toMinutes'];
                            $dateNow = cg_get_date_time_object_based_on_wp_timezone_conf((new DateTime('now'))->getTimestamp());
                            $intervalStartDate = new DateTime(date('Y-m-d', strtotime("$dayStart this week")).' '.$fromHours.':'.$fromMinutes.':00');
                            $intervalEndDate = new DateTime(date('Y-m-d', strtotime("$dayEnd this week")).' '.$toHours.':'.$toMinutes.':59');

                            if($intervalStartDate->getTimestamp()<=$dateNow->getTimestamp()
                                && $intervalEndDate->getTimestamp()>=$dateNow->getTimestamp()){
                                $isActive = true;
                            }
                        }
                    }
                    if($interval=='daily'){
                        $fromHours = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['fromHours'];
                        $fromMinutes = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['fromMinutes'];
                        $toHours = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['toHours'];
                        $toMinutes = $options['interval'][$cg_gallery_shortcode_type][$currentYear][$interval]['toMinutes'];
                        if($fromHours && $toHours && $fromMinutes && $toMinutes){
                            $dateNow = cg_get_date_time_object_based_on_wp_timezone_conf((new DateTime('now'))->getTimestamp());
                            $intervalStartDate = new DateTime(date('Y-m-d', strtotime("today")).' '.$fromHours.':'.$fromMinutes.':00');
                            $intervalEndDate = new DateTime(date('Y-m-d', strtotime("today")).' '.$toHours.':'.$toMinutes.':59');
                            if($intervalStartDate->getTimestamp()<=$dateNow->getTimestamp()
                                && $intervalEndDate->getTimestamp()>=$dateNow->getTimestamp()){
                                $isActive = true;
                            }
                        }
                    }
                }
            }
        }

        return [
            'shortcodeIsActive' => $isActive,
            //'shortcodeIsActive' => true,
            'shortcodeCheckIsActivated' => $shortcodeCheckIsActivated,
            'intervalStartDate' => $intervalStartDate,
            'intervalEndDate' => $intervalEndDate,
            'TextWhenShortcodeIntervalIsOn' => $TextWhenShortcodeIntervalIsOn,
            'TextWhenShortcodeIntervalIsOff' => $TextWhenShortcodeIntervalIsOff,
        ];

    }
}

if(!function_exists('cg_shortcode_interval_check_show_ajax_message')){
    function cg_shortcode_interval_check_show_ajax_message($intervalConf,$GalleryID = 0){

        if(!$intervalConf['shortcodeIsActive']){
            ?>
            <script data-cg-processing="true">
                var gid = <?php echo json_encode($GalleryID); ?>;
                var TextWhenShortcodeIntervalIsOff = <?php echo json_encode($intervalConf['TextWhenShortcodeIntervalIsOff']);?>;
                cgJsClass.gallery.function.message.showPro(gid,TextWhenShortcodeIntervalIsOff);
            </script>
            <?php
        }

    }
}