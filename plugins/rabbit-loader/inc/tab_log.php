<?php

if(class_exists('RabbitLoader_21_Tab_Log')){
    #it seems we have a conflict
    return;
}

class RabbitLoader_21_Tab_Log {

    public static function init(){
        add_settings_section(
            'rabbitloader_section_log',
            ' ',
            '',
            'rabbitloader-log'
        );
    }

    private static function getLogsV2(){
        $activity_log = get_transient( 'rabbitloader_trans_activity_log' );
        if(!is_array($activity_log)){
            $http = RabbitLoader_21_Core::callGETAPIV2("activitylog/domain/{domain_id}", $apiError, $apiMessage);
            if(!empty($http['body']['logs'])){
                $activity_log = $http['body']['logs'];
            }
            set_transient('rabbitloader_trans_activity_log', $activity_log, 300);
        }
        return $activity_log;
    }
    
    public static function echoMainContent(){
        do_settings_sections( 'rabbitloader-log' );
        $activity_log = self::getLogsV2();

        ?>
        <div class="" style="max-width: 1160px; margin:40px auto;">
            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded p-4">
                        <div class="row">
                            <div class="col-12 text-secondary">
                                <h5 class="mt-0">Recent Log Messages</h5>
                                <span class="mb-4 d-block">These are system generated log messages to get more insights on things running under the hood.</span>
                            </div>
                            <div class="col-12">
                                <?php
                                if(empty($activity_log)){
                                    _e('Everything looks good. No messages to show here.');
                                }else{
                                    $alert_class = ['I'=>'info', 'W'=>'warning', 'E'=>'danger'];
                                    $alert_icon = ['I'=>'info', 'W'=>'warning', 'E'=>'bell'];
                                    foreach($activity_log as $log){
                                        printf('<div class="alert alert-%s" role="alert"><span class="d-block "><span class="dashicons dashicons-%s align-middle"></span> %s UTC</span><hr><p class="fs-6 mb-0">%s</p></div>', $alert_class[$log['level']], $alert_icon[$log['level']], date('F j, Y h:i A', strtotime($log['time'])), $log['desc']);
                                    }
                                }
                                ?>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

?>