<?php
/**
* Questo file è parte del plugin WooCommerce v3.x di Fattura24
* Autore: Fattura24.com <info@fattura24.com> 
*
* Descrizione: gestisce la tab "App" della schermata di impostazioni
* 
*/
namespace fattura24;

if (!defined('ABSPATH'))
    exit;

/*if (is_admin()) Davide 11.02.2020 commentato per futura cancellazione
{
//@session_start();
}*/

function fatt_24_show_app() {
    
    ?>

    <div class='wrap'>
    <h2></h2>
    <?php fatt_24_get_link_and_logo(__('', 'fatt-24-app')); 
            echo fatt_24_build_nav_bar();
    ?>
       <div>
          <table width="100%">
                <tr>
                    <td>  
                        <p style="font-size:120%; margin-top:10px; padding: 10px;">
                
                        <?php _e('Download Fattura24 App and enter all the documents saved by your eCommerce nel tuo account di Fattura24.<br /> Moreover in the App you will find 15 report to check in fast and pratic way your business\' trend.', 'fattura24') ?>
                
                        </p>
                
                        <p style="font-size:120%; padding: 10px;"><?php _e('Click on one of the badges to download', 'fattura24')?></p>
                        <a style="padding: 10px;" href="https://itunes.apple.com/it/app/fattura24/id691821270" target="_blank"><?php echo fatt_24_img(fatt_24_attr('src', fatt_24_png('../assets/ios-app')), array())?></a>&nbsp; &nbsp;
                        <a style="padding: 10px;" href="https://play.google.com/store/apps/details?id=com.fattura24.smartphone" target="_blank"><?php echo fatt_24_img(fatt_24_attr('src', fatt_24_png('../assets/android-app')), array())?></a>
                        <p style="padding: 10px;"><?php echo fatt_24_img(fatt_24_attr('src', fatt_24_png('../assets/img_iphone-app'), "width='100%'"), array()); ?>
                        </p>
                    </td>
                    <td style="width:250px; vertical-align: top;"><?php echo fatt_24_infobox(); ?></td>
                </tr>
           </table>         
       </div>
<?php  }