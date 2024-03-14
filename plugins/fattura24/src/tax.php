<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * Descrizione: gestisce la tab "Configurazione Tassa" della schermata di impostazioni
 */
namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'methods/met_tax.php';

if (is_admin())
{
    //@session_start();
}


// visualizza la schermata "Configurazione Tassa"
function fatt_24_show_tax()
{
    global $wpdb;
    $prefix = is_multisite()? $wpdb->base_prefix : $wpdb->prefix;
    $blog_id = is_multisite()? get_current_blog_id() : 1;
    $table_name = $prefix . "fattura_tax";
    $tax_id = ''; // definisco $tax_id
    $tax_rate = fatt_24_getZeroRates();
    $fattEl = fatt_24_get_invoice_doctype() == FATT_24_DT_FATTURA_ELETTRONICA;
    $msg = '';
    $type = '';
    $natura_records = fatt_24_get_natura_records();
    $id_used_for_shipping = fatt_24_get_used_for_shipping_id($natura_records);
 

    if (isset($_POST['insert'])) {
        $tax_id = sanitize_text_field($_POST["tax_id"]);
        $tax_code = sanitize_text_field($_POST["tax_code"]);
        $id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : ''; //mi serve per controllare le modifiche al post
        $used_for_shipping = isset($_POST["used_for_shipping"]) ? 1 : 0;
                               
        if (empty($tax_id)) {
            $type = 'error';
            $msg=__('Please select Tax Name', 'fattura24');
        } elseif ($tax_code == "Scegli") {
            $type = 'error';
            $msg=__('Please enter Natura', 'fattura24');
        } elseif ($used_for_shipping && (!empty($id_used_for_shipping) && $tax_id !== $id_used_for_shipping)) {
            $type = 'error';
            $msg = __('You can set up only one natura code for shipping', 'fattura24');
        } else {
			 /**
             * Se faccio click su 'Modifica' la variabile id NON è vuota, perciò posso aggiornare il record
             * passando per la condizione else; altrimenti mostro il messaggio di avvertimento
             * fix del 15.09.2020 - Davide Iandoli
             */
            $row = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where tax_id=%s and blog_id=%s", $tax_id, $blog_id));
            //fatt_24_trace('riga :', $row);
            if (count($row) > 0) {
                if (empty($id)) {
                    $type = 'warning';
                    $msg=__('Duplicate entry found with the same Tax', 'fattura24');
                } else {
                    $wpdb->update(
                        $table_name, //table
                        array('tax_id' => $tax_id, 
                              'tax_code' => $tax_code, 
                              'used_for_shipping' => $used_for_shipping, 
                              'blog_id' => $blog_id), //data
                        array('id' => $id), //where
                        array('%s'), //data format
                        array('%s','%s') //where format
                    );
                   
                    wp_redirect('admin.php?page=fatt-24-tax');
                }
            } elseif (count($row) == 0) {
                $wpdb->insert(
                    $table_name, //table
                       array('tax_id' => $tax_id, 
                             'tax_code' => $tax_code, 
                             'used_for_shipping' => $used_for_shipping,
                             'blog_id' => $blog_id), //data
                       array('%d', '%s') //data format
                );
                
                wp_redirect('admin.php?page=fatt-24-tax');
                exit;
            }
        }
    } elseif (isset($_GET['del'])) {
        $id = sanitize_text_field($_GET['del']);
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s AND blog_id = %s", $id, $blog_id));
        $type = 'success';
        $msg =__('Record has been deleted successfully', 'fattura24');
        wp_redirect('admin.php?page=fatt-24-tax');
        exit;
    } elseif (isset($_GET['id'])) {
        $id = sanitize_text_field($_GET['id']);
        $row = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name  where id=%s AND blog_id = %s", $id, $blog_id));

        if (count($row) > 0) {
            $row = $row[0];
            $id = $row->id;
            $tax_code = $row->tax_code;
            $tax_id = $row->tax_id;
            $used_for_shipping = $row->used_for_shipping;
        }
    } ?>

	<div class='wrap'>
    <h2></h2>
    <?php fatt_24_get_link_and_logo(__('', 'fatt-24-tax')); 
        echo fatt_24_build_nav_bar();
    ?>

   
   
    <?php
        // controllo se ci sono altri messaggi già visualizzati
        $naturaMessages = fatt_24_getNaturaMessages();
        $id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : '0';
        $used_for_shipping_checked = isset($_GET['used_for_shipping']) 
                    && $_GET['used_for_shipping'] == '1' ? 'checked' : '';
        if (empty($naturaMessages) && !empty($msg)) {
            echo fatt_24_getMessageHtml($msg, $type, true);
        }
        ?>
            <form method='post' >
    		<table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width" width="15%"><?php echo __('ID', 'fattura24'); ?></th>
                    <td><?php echo $id; ?></td>
                </tr>    
                <tr>
                   <th class="ss-th-width" width="15%"><?php echo __('Tax', 'fattura24'); ?></th>
                    	<td>
						<?php
                         
                          $select = "<select name='tax_id' class='postform'>";
                          $select.= "<option value=''>".__('Select Tax', 'fattura24')."</option>";
                            if (!empty($tax_rate)) {
                                foreach ($tax_rate as $category) {
                                    if ($category->tax_rate_id==$tax_id) {
                                        $select.= "<option value='".$category->tax_rate_id."' selected='selected'>".$category->tax_rate_name."</option>";
                                    } else {
                                        $select.= "<option value='".$category->tax_rate_id."'>".$category->tax_rate_name."</option>";
                                    }
                                }
                            }
                                                
                            $select.= "</select>";
                            echo $select;
                        ?>
						</td>
                </tr>
                <tr>
                    <th class="ss-th-width"><?php _e('Natura', 'fattura24'); ?></th>
     				<td><?php echo fatt_24_getNaturaOptions(); ?></td>
                </tr>
                <tr>
                    <th class="ss-th-width"><?php _e('Used for shipping', 'fattura24'); ?></th>
     				<td><input type='checkbox' name='used_for_shipping' <?php printf($used_for_shipping_checked); ?> ></td>
                </tr>
				<tr>
                    <th class="ss-th-width"></th>
                    <td> <input type='submit' name="insert" value=<?php _e('Save', 'fattura24')?> class='button'></td>
                </tr>
            </table>
		</form>
	</div>

    <div class='wrap'>

        <?php
            //$prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
            // WooCommerce aggiunge tante tabelle per quanti sono i siti nel network
            $prefix = $wpdb->prefix;
            $sql="SELECT m.id,m.tax_id,m.tax_code,m.blog_id, m.used_for_shipping, t.tax_rate_name from $table_name as m LEFT JOIN ".$prefix."woocommerce_tax_rates as t ON (m.tax_id=t.tax_rate_id) order by m.id desc";
            $rows = $wpdb->get_results($sql); 
        
        ?>

        <table class='wp-list-table widefat fixed striped pages'>
			<thead>
            <tr>
                <th class="manage-column ss-list-width" width="80"><?php echo __('ID', 'fattura24'); ?></th>
                <th class="manage-column ss-list-width"><?php _e('Name', 'fattura24'); ?></th>
                <th class="manage-column ss-list-width"><?php _e('Natura', 'fattura24'); ?></th>
                <th class="manage-column ss-list-width"><?php _e('Used for shipping', 'fattura24'); ?></th>
                <th colspan="2" width="150"><?php _e('Action', 'fattura24'); ?></th>
            </tr>
            </thead>
			<tbody>
				<?php foreach ($rows as $row) { 
                      // con questa booleana gestisco la tabella in modalità multisito
                      $showRow = $row->blog_id == get_current_blog_id(); ?>
           			<tr>
                        <?php if ($showRow) { ?>
               			<td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
               			<td class="manage-column ss-list-width"><?php echo $row->tax_rate_name; ?></td>
               			<td class="manage-column ss-list-width"><?php echo $row->tax_code; ?></td>
                        <td class="manage-column ss-list-width"><?php echo fatt_24_used_for_shipping_icon($row->used_for_shipping); ?></td>
               			<td><a href="<?php echo esc_url(admin_url('admin.php?page=fatt-24-tax&id=' . $row->id . '&used_for_shipping='. $row->used_for_shipping)); ?>"><?php echo __('Edit', 'fattura24'); ?></a></td>
		    			<td><a href="<?php echo esc_url(admin_url('admin.php?page=fatt-24-tax&del=' . $row->id)); ?>"><?php echo __('Delete', 'fattura24'); ?></a></td>
                        <?php } ?>
           			</tr>
       			<?php } ?>
   			</tbody>
        </table>
    </div>
	<div class="wrap">
       <div> 
        <table width="100%">         
           <tr>
                <td style="height: 440px; vertical-align: top;">
                    <p style="font-size:150%; font-weight:bold;"><?php _e('User instructions', 'fattura24') ?></p>
                  
                        <ol>
                            <li style ="font-size:120%;"><?php _e('Configure in Woocommerce->Settings->Tax one ore more zero rated taxes', 'fattura24')?></li>
                            <li style ="font-size:120%;"><?php _e('Warning: tax name should match the legal reference / natura.', 'fattura24')?></li>
                            <li style ="font-size:120%;"><?php _e('Save the changes: first dropdown will list all rates configured.', 'fattura24')?></li>
                            <li style ="font-size:120%;"><?php _e('Choose natura and save the changes.', 'fattura24')?></li>
                        </ol>
                      
                 
                    <p style = "padding:10px; font-size:120%;"><?php _e('Here below an explanatory picture.', 'fattura24')?></p>
                </td>
                <td style="width:250px; vertical-align: top;">
                    <?php echo fatt_24_infobox(); ?>
                </td> 
            </tr>
        </table>
        </div>        
    <div style="margin-top:-200px;"><?php echo fatt_24_img(fatt_24_attr('src', fatt_24_png('../assets/fattura24tax')), array())?></div>
           
	</div>
    <?php
}
