<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
MJTC_message::MJTC_getMessage();
wp_enqueue_script('jquery-ui-tabs');
?>
<?php
$majesticsupport_js ="
jQuery(document).ready(function ($) {
    jQuery('.tabs').tabs();
});

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('cronjob'); ?>
        <div id="msadmin-data-wrp" class="">
            <!-- ticket via email cron -->
            <div id="cp_wraper">
                <?php $array = array('even', 'odd');
                $k = 0; ?>
                <div id="tabs" class="tabs">
                    <ul>
                        <li><a title="<?php echo esc_attr(__('Web Cron Job','majestic-support')); ?>" class="selected" data-css="controlpanel" href="#webcrown"><?php echo esc_html(__('Web Cron Job','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('Wget','majestic-support')); ?>"  data-css="controlpanel" href="#wget"><?php echo esc_html(__('Wget','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('Curl','majestic-support')); ?>"  data-css="controlpanel" href="#curl"><?php echo esc_html(__('Curl','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('PHP Script','majestic-support')); ?>"  data-css="controlpanel" href="#phpscript"><?php echo esc_html(__('PHP Script','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('URL','majestic-support')); ?>"  data-css="controlpanel" href="#url"><?php echo esc_html(__('URL','majestic-support')); ?></a></li>
                    </ul>
                    <div class="tabInner">
                    <div id="webcrown">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('Configuration of a backup job with webcron.org','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo esc_html(__('Name of cron job','majestic-support')); ?>
                                </span>
                                <span class="crown_text_right"><?php echo esc_html(__('Ticket via email','majestic-support')); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo esc_html(__('Timeout','majestic-support')); ?>
                                </span>
                                <span class="crown_text_right"><?php echo esc_html(__('180 secs If the setting is not completely increased, most sites will work with a setting of between 180 and 600','majestic-support')); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('URL you want to execute','majestic-support')); ?></span>
                                <span class="crown_text_right">
                                    <?php echo esc_html(majesticsupport::makeUrl(array('mscron'=>'ticketviaemail','mspageid'=>majesticsupport::getPageid()))); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('Login','majestic-support')); ?></span>
                                <span class="crown_text_right">
                                    <?php echo esc_html(__('Leave this blank','majestic-support')); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('Password','majestic-support')); ?></span>
                                <span class="crown_text_right"><?php echo esc_html(__('Leave this blank','majestic-support')); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo esc_html(__('Execution time','majestic-support')); ?>
                                </span>
                                <span class="crown_text_right">
                                    <?php echo esc_html(__('That the grid below the other options select when and how','majestic-support')); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('Alerts','majestic-support')); ?></span>
                                <span class="crown_text_right">
                                <?php echo esc_html(__('If you have already set up alert methods in the webcron.org interface, we recommend choosing an alert type','majestic-support')); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="wget">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('Cron scheduling using wget','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo esc_html('wget --max-redirect=10000 "') . esc_html(majesticsupport::makeUrl(array('mscron'=>'ticketviaemail','mspageid'=>majesticsupport::getPageid()))) .esc_html('" -O - 1>/dev/null 2>/dev/null '); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="curl">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('Cron scheduling using Curl','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo esc_html('curl "') . esc_html(majesticsupport::makeUrl(array('mscron'=>'ticketviaemail','mspageid'=>majesticsupport::getPageid()))).'"<br>' . esc_html(__('OR','majestic-support')) . '<br>'; ?>
                                <?php echo esc_html('curl -L --max-redirs 1000 -v "') . esc_html(majesticsupport::makeUrl(array('mscron'=>'ticketviaemail','mspageid'=>majesticsupport::getPageid()))).esc_html('" 1>/dev/null 2>/dev/null '); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="phpscript">
                        <div id="cron_job">
                            <span class="crown_text">
                                    <?php echo esc_html(__('Custom PHP script to run the cron job','majestic-support')); ?>
                            </span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                    <?php
                                    echo wp_kses('  $curl_handle=curl_init();<br>
                                                curl_setopt($curl_handle, CURLOPT_URL, \'' . esc_url(majesticsupport::makeUrl(array('mscron'=>'ticketviaemail','mspageid'=>majesticsupport::getPageid()))).'\');<br>
                                                curl_setopt($curl_handle,CURLOPT_FOLLOWLOCATION, TRUE);<br>
                                                curl_setopt($curl_handle,CURLOPT_MAXREDIRS, 10000);<br>
                                                curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);<br>
                                                $buffer = curl_exec($curl_handle);<br>
                                                curl_close($curl_handle);<br>
                                                if (empty($buffer))<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo "' . esc_html(__('Sorry the cron job didnot work','majestic-support')) . '";<br>
                                                else<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo esc_attr($buffer);<br>
                                                ', MJTC_ALLOWED_TAGS);
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="url">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('URL for use with your own scripts and third-party scripts','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth"><?php echo esc_html(majesticsupport::makeUrl(array('mscron'=>'ticketviaemail','mspageid'=>majesticsupport::getPageid()))); ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="cron_job">
                        <span class="cron_job_help_txt"><?php echo esc_html(__('It is recommended to run the script every hour.','majestic-support')); ?></span>
                    </div>
                    </div>
                </div>
            </div>
            <!-- update ticket status cron -->
            <div id="cp_wraper">
                <?php $array = array('even', 'odd');
                $k = 0; ?>
                <div id="tabs" class="tabs">
                    <ul>
                        <li><a title="<?php echo esc_attr(__('Web Cron Job','majestic-support')); ?>" class="selected" data-css="controlpanel" href="#webcrown"><?php echo esc_html(__('Web Cron Job','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('Wget','majestic-support')); ?>"  data-css="controlpanel" href="#wget"><?php echo esc_html(__('Wget','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('Curl','majestic-support')); ?>"  data-css="controlpanel" href="#curl"><?php echo esc_html(__('Curl','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('PHP Script','majestic-support')); ?>"  data-css="controlpanel" href="#phpscript"><?php echo esc_html(__('PHP Script','majestic-support')); ?></a></li>
                        <li><a title="<?php echo esc_attr(__('URL','majestic-support')); ?>"  data-css="controlpanel" href="#url"><?php echo esc_html(__('URL','majestic-support')); ?></a></li>
                    </ul>
                    <div class="tabInner">
                    <div id="webcrown">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('Configuration of a backup job with webcron.org','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo esc_html(__('Name of cron job','majestic-support')); ?>
                                </span>
                                <span class="crown_text_right"><?php echo esc_html(__('Update ticket status','majestic-support')); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo esc_html(__('Timeout','majestic-support')); ?>
                                </span>
                                <span class="crown_text_right"><?php echo esc_html(__('180 secs If the setting is not completely increased, most sites will work with a setting of between 180 and 600','majestic-support')); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('URL you want to execute','majestic-support')); ?></span>
                                <span class="crown_text_right">
                                    <?php echo esc_html(majesticsupport::makeUrl(array('mscron'=>'updateticketstatus','mspageid'=>majesticsupport::getPageid()))); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('Login','majestic-support')); ?></span>
                                <span class="crown_text_right">
                                    <?php echo esc_html(__('Leave this blank','majestic-support')); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('Password','majestic-support')); ?></span>
                                <span class="crown_text_right"><?php echo esc_html(__('Leave this blank','majestic-support')); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo esc_html(__('Execution time','majestic-support')); ?>
                                </span>
                                <span class="crown_text_right">
                                    <?php echo esc_html(__('That the grid below the other options select when and how','majestic-support')); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo esc_attr($array[$k]);$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo esc_html(__('Alerts','majestic-support')); ?></span>
                                <span class="crown_text_right">
                                <?php echo esc_html(__('If you have already set up alert methods in the webcron.org interface, we recommend choosing an alert type','majestic-support')); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="wget">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('Cron scheduling using wget','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo esc_html('wget --max-redirect=10000 "') . esc_html(majesticsupport::makeUrl(array('mscron'=>'updateticketstatus','mspageid'=>majesticsupport::getPageid()))) . esc_html('" -O - 1>/dev/null 2>/dev/null '); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="curl">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('Cron scheduling using Curl','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo esc_html('curl "') . esc_html(majesticsupport::makeUrl(array('mscron'=>'updateticketstatus','mspageid'=>majesticsupport::getPageid()))).'"<br>' . esc_html(__('OR','majestic-support')) . '<br>'; ?>
                                <?php echo esc_html('curl -L --max-redirs 1000 -v "') . esc_html(majesticsupport::makeUrl(array('mscron'=>'updateticketstatus','mspageid'=>majesticsupport::getPageid()))). esc_html('" 1>/dev/null 2>/dev/null '); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="phpscript">
                        <div id="cron_job">
                            <span class="crown_text">
                                    <?php echo esc_html(__('Custom PHP script to run the cron job','majestic-support')); ?>
                            </span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                    <?php
                                    echo wp_kses('  $curl_handle=curl_init();<br>
                                                curl_setopt($curl_handle, CURLOPT_URL, \'' . esc_url(majesticsupport::makeUrl(array('mscron'=>'updateticketstatus','mspageid'=>majesticsupport::getPageid()))).'\');<br>
                                                curl_setopt($curl_handle,CURLOPT_FOLLOWLOCATION, TRUE);<br>
                                                curl_setopt($curl_handle,CURLOPT_MAXREDIRS, 10000);<br>
                                                curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);<br>
                                                $buffer = curl_exec($curl_handle);<br>
                                                curl_close($curl_handle);<br>
                                                if (empty($buffer))<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo "' . esc_html(__('Sorry the cron job didnot work','majestic-support')) . '";<br>
                                                else<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo esc_attr($buffer);<br>
                                                ', MJTC_ALLOWED_TAGS);
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="url">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo esc_html(__('URL for use with your own scripts and third-party scripts','majestic-support')); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth"><?php echo esc_html(majesticsupport::makeUrl(array('mscron'=>'updateticketstatus','mspageid'=>majesticsupport::getPageid()))); ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="cron_job">
                        <span class="cron_job_help_txt"><?php echo esc_html(__('It is recommended to run the script every hour.','majestic-support')); ?></span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
