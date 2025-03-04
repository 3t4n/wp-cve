<?php
/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      https://softdiscover.com/zigaform/wordpress-cost-estimator
 */
if ( ! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
ob_start();
?>
<?php
$id_field = ( ! empty($id_field) ) ? $id_field : '';
?>
    <div id="<?php echo $id_field; ?>" data-typefield="2" data-iscontainer="1" class="uiform-gridsytem-table uiform-gridsystem-two uiform-field">
        <div class="uiform-field-wrap">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td  data-maxpercent="50" data-blocks="6" width="50%">
                            <div class="uiform-items-container uiform-grid-inner-col rkfm-bend-fcontainer-wrap">
                                
                            </div>
                        </td>
                        <td  data-maxpercent="100" data-blocks="6" width="50%">
                            <div class="uiform-items-container uiform-grid-inner-col">
                                
                            </div>
                        </td>    
                    </tr>
            </table>
            
       </div>
    </div>
<?php
$cntACmp = ob_get_contents();
$cntACmp = Uiform_Form_Helper::sanitize_output($cntACmp);
ob_end_clean();
echo $cntACmp;
?>
