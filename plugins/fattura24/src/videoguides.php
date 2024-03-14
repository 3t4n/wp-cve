<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * Descrizione: Tab "VideoGuides" della schermata di impostazioni
 *
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

function fatt_24_hide_notice_text()
{
    return get_locale() == 'it_IT';
}

function fatt_24_show_videos()
{
    ?>
     <div class='wrap'>
    <h2></h2>
    <?php fatt_24_get_link_and_logo(__('', 'fatt-24-videos'));

    echo fatt_24_build_nav_bar();

    $notice = '';
    if (!fatt_24_hide_notice_text()) {
        $notice = fatt_24_h1(__('Notice : these video guides are spoken in Italian', 'fattura24'));
    }

    echo $notice;

    $woo_lessons = fatt_24_get_woo_lessons(); 
    ?>
   
   <table width="100%">
       <?php
            foreach ($woo_lessons as $lesson) {
                ?>
                <tr style="vertical-align: top;">
                    <td>
                        <?php echo '<a href="' .$lesson['link'] . '" target="_blank">';
                              echo '<img src="' . $lesson['img'] . '" width="350px" style="border-radius: 10px;" alt="lezioni WooCommerce" />'; ?>
                    </td>    
                    <td><h2 style="padding-left: 10px;"><?php echo $lesson['title']; ?></h2>
                        <p style="padding-left: 10px; font-size: 120%; text-align: justify;">
                            <?php echo $lesson['description']; ?>
                        </p>
                    </td>    
                </tr>
                <?php
            } 
        ?>
    </table>  
      
<?php
}