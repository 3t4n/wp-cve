<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2017
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
$site = WPTOOLSADMURL . "admin.php?page=wp-tools";
$site = WPTOOLSADMURL . "admin.php?page=wp-tools&tab=memory_usage";
$site = WPTOOLSADMURL . "admin.php?page=settings-wptools&tab=";
global $wpdb;


// https://wptoolsplugin.com/wp-admin/admin.php?page=settings-wptools&tab=uso_della_memoria
?>



<div id="wptools-steps3">
    <div class="wptools-block-title">
        <?php esc_attr_e("WP Tools Server Performance","wptools");?>
    </div>
  

        <!-- "Column 2">  -->


        <div class="wptools-help-container1">
 

        
        <div style="max-width: 45% !important; text-align:center; margin-right: 30px;"
            class="wptools-help-column wptools-help-column-2">



            <div class="bill-dashboard-titles2"><?php esc_attr_e("Server Benchmark","wptools");?></div>
            
            <?php

            
            

                require_once "gauge.php"; 

                echo '<br />';
                
                
                esc_attr_e("For details","wptools");
                echo '&nbsp;';
                $site = WPTOOLSADMURL . "admin.php?page=wptools_options30"; 
                echo '<a href="'.esc_url($site).'">';
                echo esc_attr__("click here.","wptools");
                echo '</a>';
                
         ?>   
            

         

  

        </div>




 

        <!-- "Column 3">  -->
        <div style="max-width: 55% !important;" class="wptools-help-column wptools-help-column-2">
            <div class="bill-dashboard-titles2"><?php esc_attr_e("Processor Usage","wptools");?></div>
      
            <?php
            $get_numbercores = false;
            if (function_exists('sys_getloadavg')) {
				$loadavg = sys_getloadavg();
				if(gettype($loadavg) === 'array' and count($loadavg) > 2)
                   $get_numbercores = true;     
            }


           if($get_numbercores == false){
                 esc_attr_e("Your hosting is blocking the PHP function sys_getloadavg(). Look the tab Server Check and Requirements","wptools");?> 

                <div style="display:none;">
                    <span id="load_1"></span> &nbsp;<span id="load_5"></span>&nbsp; <span id="load_15"></span><br>
                    <span id="cores_label"></span><span id="cores"></span><br><br>

                    <canvas id="wpt_chart" style="width:100%; height:200px;"></canvas>
                </div>
                
                <?php
           }
           else {     

               

                esc_attr_e("Load average:","wptools");?> 

                <span id="load_1"></span> &nbsp;<span id="load_5"></span>&nbsp; <span id="load_15"></span><br>
                <span id="cores_label"><?php esc_attr_e("Number Cores:","wptools");echo ' ';?></span><span id="cores"></span><br><br>
                <div style="padding-right:20px;">
                  <canvas id="wpt_chart" style="width:100%; height:200px;"></canvas>
                </div>


          <?php
          }
         ?>         



        </div> <!-- "Column 3 end " -->
    </div> <!-- "Container 1 " -->


</div> <!-- "wptools-steps3"> -->




<div id="wptools-steps3">


    <div class="wptools-block-title">
        <?php esc_attr_e("WP Tools Dashboard","wptools");?>
    </div>


    <div class="wptools-help-container1">


        <div style="max-width: 50% !important;" class="wptools-help-column wptools-help-column-1">
            <div class="bill-dashboard-titles2"><?php esc_attr_e("Memory Usage","wptools");?></div>
            <br /> <br />
            <?php
            /*
            $ds = 256;
            $du = 60;
            */
            $wptools_memory = wptools_check_memory();
            if ($wptools_memory['msg_type'] == 'notok') {
                esc_attr_e("Unable to get your Memory Info","wptools"); 
            } else {
                $ds = $wptools_memory['wp_limit'];
                $du = $wptools_memory['usage'];
                if ($ds > 0)
                    $perc = number_format(100 * $du / $ds, 0);
                else
                    $perc = 0;
                if ($perc > 100)
                    $perc = 100;
                //die($perc);
                $color = '#e87d7d';
                $color = '#029E26';
                if ($perc > 50)
                    $color = '#e8cf7d';
                if ($perc > 70)
                    $color = '#ace97c';
                if ($perc > 50)
                    $color = '#F7D301';
                if ($perc > 70)
                    $color = '#ff0000';
                $initValue = $perc;
                require_once "circle_memory.php";

                $wptools_tab = trim(str_replace(' ','_',remove_accents(__('Memory Usage','wptools'))));

            ?>
            <br /> <br />
            <center>
            <?php 
            esc_attr_e("For details,","wptools");
            $site2 = WPTOOLSADMURL . "admin.php?page=settings-wptools&tab="; 
            
            ?>
            <a href="<?php echo strtolower(esc_url($site2.$wptools_tab)); ?>">
                <?php esc_attr_e("click","wptools");?>
            </a>
           
            <?php esc_attr_e("the Settings => Memory Usage Tab.","wptools");?>
            <?php } ?>
            </center>
        </div>


        <!-- "Column 3">  -->
        <div style="max-width: 50% !important;" class="wptools-help-column wptools-help-column-2">

            <div class="bill-dashboard-titles2"><?php esc_attr_e("Site Issues (7 days)","wptools");?></div>

            <br /> 
            <?php 
                require_once "errorsgraph.php";
            ?>
            <br /> 
            <center>

            <?php esc_attr_e("For details,","wptools");?>
            <?php $site = WPTOOLSADMURL . "admin.php?page=wptools_options21"; ?>
            <a href="<?php echo esc_url($site); ?>">
                <?php esc_attr_e("click","wptools");?>
            </a>
            wpTools => Show Errors

           </center>


        </div> <!-- "Column 3 end " -->


         <!-- "Column 3">  -->
        <div style="max-width: 50% !important;" class="wptools-help-column wptools-help-column-2">
                    <div class="bill-dashboard-titles2"><?php esc_attr_e("Page Load Seconds (7 days)","wptools");?></div>
                    <br /> 
                    <?php 
                        require_once "loadsgraph.php";
                    ?>
                    <br /> 
                    <center>
                    <?php esc_attr_e("To learn more,","wptools");?>
                    <?php $site = "https://wptoolsplugin.com/page-load-info-queries-and-load-time/"; ?>
                    <a href="<?php echo esc_url($site); ?>">
                        <?php esc_attr_e("click","wptools");?>
                    </a>
                    </center>
        </div> <!-- "Column 3 end " -->


    </div> <!-- "Container 1 " -->
</div> <!-- "wptools-steps3"> -->


<div id="wptools-steps3" style="padding: 20px;">
                    <center>
                    <div class="bill-dashboard-titles2"><?php esc_attr_e("Top 10 Front End Pages with the Highest Average Load Time Over the Last 7 Days","wptools");?></div>
                    </center>
                    <?php 
                            global $wpdb;
                            $table_name = $wpdb->prefix . 'wptools_page_load_times';
                           
                            $sql = "SELECT page_url, AVG(load_time) AS average_load_time
                            FROM $table_name
                            WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                            AND NOT page_url LIKE 'wp-admin%'
                            GROUP BY page_url
                            ORDER BY average_load_time DESC
                            LIMIT 10";


                            $results = $wpdb->get_results($sql);
                            if ($results) {
                                echo '<div style="display: flex; justify-content: center;">';
                                echo '<table class="wp-list-table widefat fixed striped" style="width: 100%;">';
                                echo '<thead><tr><th scope="col" style="width: 70%;">';
                                esc_attr_e("Page","wptools");
                                echo '</th><th scope="col" style="width: 30%;">';
                                esc_attr_e("Load Time (seconds)","wptools");
                                echo '</th></tr></thead>';
                                echo '<tbody>';
                                foreach ($results as $result) {
                                    echo '<tr>';
                                    echo '<td>' . esc_html($result->page_url) . '</td>';
                                    // echo '<td>' . number_format($result->average_load_time, 1) . '</td>';
                                    echo '<td>' . number_format($result->average_load_time > 0.05 ? ceil($result->average_load_time) : $result->average_load_time, 1) . '</td>';

                                    echo '</tr>';
                                }
                                echo '</tbody>';
                                echo '</table>';
                                echo '</div>';
                                
                            } else {
                                esc_attr_e("No data found","wptools");
                            }
                    ?>
                    <br /> 
                    <center>
                    <?php esc_attr_e("To learn more,","wptools");?>
                    <?php $site = "https://wptoolsplugin.com/page-load-info-queries-and-load-time/"; ?>
                    <a href="<?php echo esc_url($site); ?>">
                        <?php esc_attr_e("click","wptools");?>
                    </a>
                    </center>

</div> <!-- "wptools-steps3"> -->


<!-- raw 2 -->

<div id="wptools-services3">


    <div class="wptools-help-container1">
        <div class="wptools-help-column wptools-help-column-1">
            <div class="wptools-dash-server">

                <?php
                $os = wptools_OSName();
                if ($os) {
                    echo '<b>'. esc_attr__("OS type & version","wptools"). '</b>';
                    echo '<br /><br />';
                    echo trim(esc_attr($os));
                } else {
                    echo '<b>'. esc_attr__("PHP Version","wptools"). '</b>';
                    echo '<br /><br />';
                    echo PHP_VERSION;
                }
            ?>
            </div>
        </div> <!-- "Column1">  -->
        <div class="wptools-help-column wptools-help-column-2">
            <div class="wptools-dash-server">

                <?php
            try{
                if (function_exists("shell_exec"))
                    $result = shell_exec('uptime -p');
                else
                    $result = false;
            }
            catch (Exception $e) {
                $result = false;
            }
            if ($result) {
                echo '<b>'. esc_attr__("Server Uptime (since reboot)","wptools"). '</b>';
                echo '<br /><br />';
                echo esc_attr($result);
            } else {
                echo '<b>'. esc_attr__("Database Software and Version","wptools"). '</b>';
                echo '<br /><br />';
                // echo PHP_VERSION;
                echo esc_attr(wptools_database_software()); 
                echo ' - '.wptools_database_version();
            }
            ?>
            </div>
        </div> <!-- "columns 2">  -->



        <div class="wptools-help-column wptools-help-column-3">
            <div class="wptools-dash-server">


            <?php
                
            try{

                if (@is_readable('/proc/cpuinfo') and gettype( @file_get_contents('/proc/cpuinfo') !== 'boolean')) {
                    
                        $cpuinfo = @file_get_contents('/proc/cpuinfo');
                   
                        echo '<b>';
                        esc_attr_e("Server CPU cores and architecture","wptools");
                        echo '</b>'; 
                        echo '<br />';
                        echo '<br />'; 

                        preg_match_all('/^processor/m', $cpuinfo, $matches);
                        $file = file('/proc/cpuinfo');
                        $proc_details = $file[4];
                        if(count($matches[0]) < 1)
                          echo esc_attr(substr($proc_details, 13));
                        else
                          echo esc_attr(count($matches[0])) . ' x ' . esc_attr(substr($proc_details, 13));


                } else {

                    echo '<b>';
                    esc_attr_e("MySQL database uptime","wptools");
                    echo '</b>'; 
                    echo '<br />';
                    echo '<br />'; 
                    $results = $wpdb->get_results("SHOW GLOBAL STATUS LIKE 'Uptime'");
                    if (isset($results[0]->Value)) {
                        $mysql_uptime = $results[0]->Value;
                    }
                        
                   if (isset($mysql_uptime)) {
                        echo esc_attr(wptools_secondsToTime($mysql_uptime));
                   } else {
                        echo "-";
                   }
                }
            }
            catch (Exception $e) {
                echo '<b>';
                esc_attr_e("MySQL database uptime","wptools");
                echo '</b>'; 
                echo '<br />';
                echo '<br />'; 
               if (isset($mysql_uptime)) {
                    echo esc_attr(wptools_secondsToTime($mysql_uptime));
               } else {
                    echo "-";
               }
            }
            ?>
            </div>
        </div> <!-- "Column 3">  -->
    </div> <!-- "Container1 ">  -->



                    <!-- part 2 -->




    <div class="wptools-help-container1">
        <div class="wptools-help-column wptools-help-column-1">
            <div class="wptools-dash-server">
                <b><?php esc_attr_e("Hostname","wptools");?></b>
                <br />
                <br />
                <?php
 
                    try{
                      $hostname = trim(gethostname());
                    }
                    catch (Exception $e) {
                           $hostname = '-';
                    }
                    //vmi391326.contaboserver.net          
                    if(strlen($hostname) > 30)
                        echo '<small><small>'. esc_attr($hostname) .'</small></small>';
                    else
                       echo esc_attr($hostname);
                ?>
                <br />
                <br />
            </div>
        </div> <!-- "Column1">  -->
        <div class="wptools-help-column wptools-help-column-2">
            <div class="wptools-dash-server">

                <b><?php esc_attr_e("Server IP","wptools");?></b>
                
                <br />
                <br />
                <?php


            try{
                    $ip_server = sanitize_text_field($_SERVER['SERVER_ADDR']);
                    if (filter_var($ip_server, FILTER_VALIDATE_IP)) {
                        if($ip_server == '127.0.0.1' )
                        echo esc_attr__('Unable to get your server Ip. Probably blocked by your hosting company.','wptools');
                        else {

                        //$ip_server = '2002:818:db89:c100:7190:7be2:f897:c307';

                        if(strlen($ip_server) > 15)
                            echo '<small><small>'.esc_attr($ip_server).'</small></small>';
                        else
                            echo esc_attr($ip_server);
                        }

                    } else {
                        echo esc_attr__('Unable to get your server Ip. Probably blocked by your hosting company.','wptools');
                    }
            }
            catch (Exception $e) {
                echo esc_attr__('Unable to get your server Ip. Probably blocked by your hosting company.','wptools');
            }
            ?>
            </div>
        </div> <!-- "columns 2">  -->
        <div class="wptools-help-column wptools-help-column-3">
            <div class="wptools-dash-server">
                <b><?php esc_attr_e("Web Server Soft","wptools");?></b>
                <br />
                <br />
                <?php
            
            try{
               echo esc_attr($_SERVER['SERVER_SOFTWARE']);
            }
            catch (Exception $e) {
                echo esc_attr__('Unable to get your server software. Probably blocked by your hosting company.','wptools');
            }
            ?>
            </div>
        </div> <!-- "Column 3">  -->
    </div> <!-- "Container1 ">  -->



                    <!--  Part 3   -->




    <div class="wptools-help-container1">
        <div class="wptools-help-column wptools-help-column-1">
            <div class="wptools-dash-server">


                <?php
                    echo '<b>'. esc_attr__("Root Path","wptools"). '</b>';
                    echo '<br /><br />';

    // /home/vertical/public_html/ 

                    if(strlen(ABSPATH) > 40 )
                       echo '<small><small><small>'.esc_attr(ABSPATH).'</small></small></small>';
                    elseif(strlen(ABSPATH) > 35 and strlen(ABSPATH) < 41 )
                      echo '<small>'.esc_attr(ABSPATH).'</small>';
                    else
                      echo esc_attr(ABSPATH);

                ?>

            </div>
        </div> <!-- "Column1">  -->


        <div class="wptools-help-column wptools-help-column-2">
            <div class="wptools-dash-server">


                <?php
                echo '<b>'. esc_attr__("Site Health","wptools"). '</b>';
                echo '<br /><br />';

                $allowed_atts = array(
                    'align'      => array(),
                    'class'      => array(),
                    'type'       => array(),
                    'id'         => array(),
                    'dir'        => array(),
                    'lang'       => array(),
                    'style'      => array(),
                    'xml:lang'   => array(),
                    'src'        => array(),
                    'alt'        => array(),
                    'href'       => array(),
                    'rel'        => array(),
                    'rev'        => array(),
                    'target'     => array(),
                    'novalidate' => array(),
                    'type'       => array(),
                    'value'      => array(),
                    'name'       => array(),
                    'tabindex'   => array(),
                    'action'     => array(),
                    'method'     => array(),
                    'for'        => array(),
                    'width'      => array(),
                    'height'     => array(),
                    'data'       => array(),
                    'title'      => array(),
        
                    'checked' => array(),
                    'selected' => array(),
                    'div' => array(),
        
        
                );
        
        
        
        
                $my_allowed['form'] = $allowed_atts;
                $my_allowed['select'] = $allowed_atts;
                // select options
                $my_allowed['option'] = $allowed_atts;
                $my_allowed['style'] = $allowed_atts;
                $my_allowed['label'] = $allowed_atts;
                $my_allowed['input'] = $allowed_atts;
                $my_allowed['div'] = $allowed_atts;
                $my_allowed['textarea'] = $allowed_atts;
        
                //more...future...
                $allowedposttags['form']     = $allowed_atts;
                $allowedposttags['label']    = $allowed_atts;
                $allowedposttags['input']    = $allowed_atts;
                $allowedposttags['textarea'] = $allowed_atts;
                $allowedposttags['iframe']   = $allowed_atts;
                $allowedposttags['script']   = $allowed_atts;
                $allowedposttags['style']    = $allowed_atts;
                $allowedposttags['strong']   = $allowed_atts;
                $allowedposttags['small']    = $allowed_atts;
                $allowedposttags['table']    = $allowed_atts;
                $allowedposttags['span']     = $allowed_atts;
                $allowedposttags['abbr']     = $allowed_atts;
                $allowedposttags['code']     = $allowed_atts;
                $allowedposttags['pre']      = $allowed_atts;
                $allowedposttags['div']      = $allowed_atts;
                $allowedposttags['img']      = $allowed_atts;
                $allowedposttags['h1']       = $allowed_atts;
                $allowedposttags['h2']       = $allowed_atts;
                $allowedposttags['h3']       = $allowed_atts;
                $allowedposttags['h4']       = $allowed_atts;
                $allowedposttags['h5']       = $allowed_atts;
                $allowedposttags['h6']       = $allowed_atts;
                $allowedposttags['ol']       = $allowed_atts;
                $allowedposttags['ul']       = $allowed_atts;
                $allowedposttags['li']       = $allowed_atts;
                $allowedposttags['em']       = $allowed_atts;
                $allowedposttags['hr']       = $allowed_atts;
                $allowedposttags['br']       = $allowed_atts;
                $allowedposttags['tr']       = $allowed_atts;
                $allowedposttags['td']       = $allowed_atts;
                $allowedposttags['p']        = $allowed_atts;
                $allowedposttags['a']        = $allowed_atts;
                $allowedposttags['b']        = $allowed_atts;
                $allowedposttags['i']        = $allowed_atts;
                
                // echo wp_kses(wptools_site_health(), $my_allowed);
                echo wp_kses_post(wptools_site_health());
                //    echo  wptools_site_health();

            ?>

            </div>
        </div> <!-- "columns 2">  -->



        <div class="wptools-help-column wptools-help-column-3">
            <div class="wptools-dash-server">


            <?php
                

                    echo '<b>';
                    esc_attr_e("Search Engine Visibility ","wptools");
                    echo '</b>'; 
                    echo '<br />';
                    echo '<br />'; 
                    echo ( ( 0 == get_option( 'blog_public' ) ) ? esc_attr__('Discouraged', 'wptools') : esc_attr__('Encouraged', 'wptools'));

            ?>
            </div>
        </div> <!-- "Column 3">  -->
    </div> <!-- "Container1 ">  -->


</div> <!-- "services"> -->




<!-- raw 4 -->
<div id="wptools-services3">
    <div class="wptools-help-container1">
        <div class="wptools-help-column wptools-help-column-1">
            <img alt="aux" src="<?php echo esc_url(WPTOOLSURL) ?>images/service_configuration.png" />
            <div class="bill-dashboard-titles">
            <br>
            <?php esc_attr_e("Start Up Guide and Settings","wptools");?></div>
            <br>
            <?php esc_attr_e("Just click Settings in the left menu (WP Tools).","wptools");?>
            <br />
            <?php esc_attr_e("Dashboard => WP Tools => Settings","wptools");?>
            <br />
            <?php $site = esc_url(WPTOOLSADMURL) . "admin.php?page=settings-wptools";?>
            <a href="<?php echo esc_url($site); ?>" class="button button-primary"><?php esc_attr_e("Go","wptools");?></a>
            <br /><br />
        </div> <!-- "Column1">  -->
        <div class="wptools-help-column wptools-help-column-2">
            <img alt="aux" src="<?php echo esc_url(WPTOOLSURL); ?>images/support.png" />
            <div class="bill-dashboard-titles"> 
            <br>
            <?php esc_attr_e("Blog, Support...","wptools");?></div>
            <br />
            <?php esc_attr_e("You will find our Blog with tips about this tool, link to support and more in our site.","wptools");?>
            <br />
            <?php $site = 'http://wptoolsplugin.com'; ?>
            <a href="<?php echo esc_url($site); ?>" class="button button-primary"><?php esc_attr_e("Go","wptools");?></a>
        </div> <!-- "columns 2">  -->
        <div class="wptools-help-column wptools-help-column-3">
            <img alt="aux" src="<?php echo esc_url(WPTOOLSURL); ?>images/system_health.png" />
            <div class="bill-dashboard-titles">
            <br>
            <?php esc_attr_e("Troubleshooting Guide","wptools");?></div>
            <br>
            <?php esc_attr_e("Use old WP version, Low memory, some plugin with Javascript error are some possible problems.","wptools");?>
            <br />
            <a href="http://siterightaway.net/troubleshooting/" class="button button-primary"><?php esc_attr_e("Troubleshooting Page","wptools");?></a>
        </div> <!-- "Column 3">  -->
    </div> <!-- "Container1 ">  -->
</div> <!-- "services"> -->
