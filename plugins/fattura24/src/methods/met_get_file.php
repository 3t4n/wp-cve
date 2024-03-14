<?php
namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

function fatt_24_build_pdf_path($docType, $order_id)
{
    $file = fatt_24_PDF_filename($docType, $order_id);
    $folder = trailingslashit(FATT_24_DOCS_FOLDER); // la cartella è definita nelle costanti
    $wpdir = wp_upload_dir();
    $basedir = $wpdir['basedir'];
    $dir = $basedir.'/'.$folder;
    return array(
        'dir' => $dir,
        'file' => $file
    );
}

function fatt_24_store_PDF_file($status, $order_id, $PDF, $docType)
{
  
    $dir = fatt_24_build_pdf_path($docType, $order_id)['dir'];
    $file = fatt_24_build_pdf_path($docType, $order_id)['file'];
    wp_mkdir_p($dir);
    //$new_file = $status['pdfPath'];
    $new_file = $dir . $file;
   
    if (!file_exists($dir . 'index.php')) {
        file_put_contents($dir . 'index.php', '<?php');
    }
    $ifp = fopen($new_file, 'wb');
    if (!$ifp) {
        fatt_24_order_status_set_error($status, sprintf(__('Could not write file %s', 'fattura24'), $new_file));
    } else {
        fwrite($ifp, $PDF);
        fclose($ifp);
        fatt_24_set_file_permissions($new_file);
        fatt_24_order_status_set_file_data($status, $new_file, $docType);
    }
    return $status;
}

function fatt_24_get_pdf_link($order_status, $docType)
{
    $result = [];
   
    $history = isset($order_status['history']) ? $order_status['history'] : [];
    foreach ($history as $val) {
        if (isset($history['docType']) && $history['docType'] == $docType) {
            $result = ['docType' => $docType,
                       'pdfPath' => isset($history['pdfPath'])? $history['pdfPath'] : ''];
        }
    }

    if (empty($result) && fatt_24_is_iterable($order_status)) {
        foreach ($order_status as $val) {
            if (isset($order_status['docType']) && $order_status['docType'] == $docType) {
                $result = ['docType' => $docType,
                           'pdfPath' => isset($order_status['pdfPath']) ?
                                        $order_status['pdfPath'] : ''];
            }
        }
    }
    return $result;
}

function fatt_24_pdf_icon()
{
    return fatt_24_span(array('class' => 'dashicons dashicons-pdf', 'style' => 'font-size:35px;', 'title' => __('View file', 'fattura24')), array());
}

function fatt_24_update_icon()
{
    return fatt_24_span(array('class' => 'dashicons dashicons-update', 'style' => 'font-size:35px; margin-left:15px;', 'title' => __('Update file', 'fattura24')), array());
}

function fatt_24_download_icon($fontSize, $id = null) {
    $style = "font-size:$fontSize; color:#135e96; text-decoration:none; padding: 0 5px 10px;";
    return fatt_24_span(array(
                            'id' => $id, 
                            'class' => 'f24 dashicons dashicons-download', 
                            'style' => $style, 
                            'title' => __('Download PDF file', 'fattura24')), 
                            array()
                        );
}

