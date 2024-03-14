<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2024-01-17
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
global $wpdb;
echo '<div class="wrap-recaptcha">' . "\n";
echo '<h2 class="title">'.esc_attr__("Analytics Lasts 7 days", "recaptcha-for-all").'</h2>' . "\n";
$recaptcha_for_all_is_empty = false;

$table_name = $wpdb->prefix . "recaptcha_for_all_stats";
if(!recaptcha_for_all_tablexist($table_name))
    recaptcha_for_all_create_db_stats();


?>

    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            text-align:center;
        }

        .column {
            width: 50%;
            box-sizing: border-box;
            padding: 10px;
            border: 0px solid #ccc;
            text-align:center;
        }

        @media (max-width: 800px) {
            .column {
                width: 100%;
            }
        }
    </style>

<div class="container">
    <div class="column">


        <center>
        <?php 
          require_once "table.php"; 
          if($recaptcha_for_all_is_empty){
   
            /*
            echo '<br>';
            echo esc_attr__('No data is currently available. Please try again later.');
            echo '<br>';
            return;
            */

          }
           
           
        ?>
    </center>

    </div>

    <div class="column">
        <center>
        <?php 
        if(!$recaptcha_for_all_is_empty)
            require_once "circle_chart.php"; 
        ?>
    </center>
    </div>
</div>

<?php
//echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<center>';
require_once "line_chart.php";


echo '<br>';
echo '<br>';


// echo esc_attr("Started collecting data with the latest plugin update (Jan 18, 2024)","recaptcha-for-all");

/*
$today = get_the_date();

$limit_date = strtotime("+7 days", strtotime("2024-01-19"));

if ($today < $limit_date) {
    echo esc_attr("Started collecting data with the latest plugin update (Jan 19, 2024)","recaptcha-for-all");
}
*/



echo '<br>';
echo '<br>';
echo '</center>';

echo '</div>';

