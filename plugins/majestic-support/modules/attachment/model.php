<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_attachmentModel {

    function getAttachmentForForm($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT filename,filesize,id
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_attachments`
                    WHERE ticketid = " . esc_sql($id) . " and replyattachmentid = 0";
        majesticsupport::$_data[5] = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function getAttachmentForReply($id, $replyattachmentid) {
        if (!is_numeric($id))
            return false;
        if (!is_numeric($replyattachmentid))
            return false;
        $query = "SELECT filename,filesize,id
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_attachments`
                    WHERE ticketid = " . esc_sql($id) . " AND replyattachmentid = " . esc_sql($replyattachmentid);
        $result = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $result;
    }

    function storeAttachments($data) {
        MJTC_includer::MJTC_getObjectClass('uploads')->MJTC_storeTicketAttachment($data, $this);
        return;
    }

    function MJTC_storeTicketAttachment($ticketid, $replyattachmentid, $filesize, $filename) {
        if (!is_numeric($ticketid))
            return false;
        $created = date_i18n('Y-m-d H:i:s');
        $data = array('ticketid' => $ticketid,
            'replyattachmentid' => $replyattachmentid,
            'filesize' => $filesize,
            'filename' => $filename,
            'status' => 1,
            'created' => $created
        );

        $row = MJTC_includer::MJTC_getTable('attachments');

        $data = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 1) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            return false;
        }
        return true;
    }

    function removeAttachment($id) {
        if (!is_numeric($id))
            return false;
        $query = $query = "SELECT ticket.attachmentdir AS foldername,ticket.id AS ticketid,attach.filename  "
                . " FROM `".majesticsupport::$_db->prefix."mjtc_support_attachments` AS attach "
                . " JOIN `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket ON ticket.id = attach.ticketid "
                . " WHERE attach.id = ".esc_sql($id);
        $obj = majesticsupport::$_db->get_row($query);
        $filename = $obj->filename;
        $foldername = $obj->foldername;

        $row = MJTC_includer::MJTC_getTable('attachments');
        if ($row->delete($id)) {
            $datadirectory = majesticsupport::$_config['data_directory'];

            $maindir = wp_upload_dir();
            $path = $maindir['basedir'];
            $path = $path .'/'.$datadirectory;
            $path = $path . '/attachmentdata';

            $path = $path . '/ticket/'.$foldername.'/' . $filename;
            unlink($path);
            MJTC_message::MJTC_setMessage(esc_html(__('The attachment has been removed', 'majestic-support')), 'updated');
        } else {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            MJTC_message::MJTC_setMessage(esc_html(__('The attachment has not been removed', 'majestic-support')), 'error');
        }
    }

    function getAttachmentImage($id){
        $query = "SELECT ticket.attachmentdir AS foldername,ticket.id AS ticketid,attach.filename  "
                . " FROM `".majesticsupport::$_db->prefix."mjtc_support_attachments` AS attach "
                . " JOIN `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket ON ticket.id = attach.ticketid "
                . " WHERE attach.id = " . esc_sql($id);
        $object = majesticsupport::$_db->get_row($query);
        $datadirectory = majesticsupport::$_config['data_directory'];
        $foldername = $object->foldername;
        $filename = $object->filename;

        $maindir = wp_upload_dir();
        $path = $maindir['baseurl'];
        $path = $path .'/'.$datadirectory;
        $path = $path . '/attachmentdata';
        $path = $path . '/ticket/' . $foldername;
        $file = $path . '/'.$filename;
        return $file;
    }


    function getDownloadAttachmentById($id){
        if(!is_numeric($id)) return false;
        $query = "SELECT ticket.attachmentdir AS foldername,ticket.id AS ticketid,attach.filename  "
                . " FROM `".majesticsupport::$_db->prefix."mjtc_support_attachments` AS attach "
                . " JOIN `".majesticsupport::$_db->prefix."mjtc_support_tickets` AS ticket ON ticket.id = attach.ticketid "
                . " WHERE attach.id = " .esc_sql($id);
        $object = majesticsupport::$_db->get_row($query);
        $foldername = $object->foldername;
        $ticketid = $object->ticketid;
        $filename = $object->filename;
        $download = false;
        if(!MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()){
            if(current_user_can('manage_options') || current_user_can('ms_support_ticket_tickets') ){
                $download = true;
            }else{
                if( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff()){
                    $download = true;
                }else{
                    if(MJTC_includer::MJTC_getModel('ticket')->validateTicketDetailForUser($ticketid)){
                        $download = true;
                    }
                }
            }
        }else{ // user is visitor
            $download = MJTC_includer::MJTC_getModel('ticket')->validateTicketDetailForVisitor($ticketid);
        }
        if($download == true){
            $datadirectory = majesticsupport::$_config['data_directory'];
            $maindir = wp_upload_dir();
            $path = $maindir['basedir'];
            $path = $path .'/'.$datadirectory;
            $path = $path . '/attachmentdata';
            $path = $path . '/ticket/' . $foldername;
            $file = $path . '/'.$filename;

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . MJTC_majesticsupportphplib::MJTC_basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            flush();
            readfile($file);
            exit();
        }else{
            include( get_query_template( '404' ) );
            exit;
        }
    }

    function getDownloadAttachmentByName($file_name,$id){
        if(empty($file_name)) return false;
        if(!is_numeric($id)) return false;
        $filename = MJTC_majesticsupportphplib::MJTC_str_replace(' ', '_',$file_name);
        $query = "SELECT attachmentdir FROM `".majesticsupport::$_db->prefix."mjtc_support_tickets` WHERE id = ".esc_sql($id);
        $foldername = majesticsupport::$_db->get_var($query);

        $datadirectory = majesticsupport::$_config['data_directory'];
        $maindir = wp_upload_dir();
        $path = $maindir['basedir'];
        $path = $path .'/'.$datadirectory;

        $path = $path . '/attachmentdata';
        $path = $path . '/ticket/' . $foldername;
        $file = $path . '/'.$filename;

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . MJTC_majesticsupportphplib::MJTC_basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush();
        readfile($file);
        exit();
        exit;

    }

    function getAllDownloads() {
        $downloadid = MJTC_request::MJTC_getVar('downloadid');
        $ticketattachment = MJTC_includer::MJTC_getModel('ticket')->getAttachmentByTicketId($downloadid);
        if(!class_exists('PclZip')){
            do_action('majesticsupport_load_wp_pcl_zip');
        }
        $path = MJTC_PLUGIN_PATH;
        $path .= 'zipdownloads';
        MJTC_includer::MJTC_getModel('majesticsupport')->makeDir($path);
        $randomfolder = $this->getRandomFolderName($path);
        $path .= '/' . $randomfolder;

        MJTC_includer::MJTC_getModel('majesticsupport')->makeDir($path);
        $archive = new PclZip($path . '/alldownloads.zip');
        $datadirectory = majesticsupport::$_config['data_directory'];
        $maindir = wp_upload_dir();
        $jpath = $maindir['basedir'];
        $jpath = $jpath .'/'.$datadirectory;
        $scanned_directory = [];
        foreach ($ticketattachment AS $ticketattachments) {
            $directory = $jpath . '/attachmentdata/ticket/' . $ticketattachments->attachmentdir . '/';
        array_push($scanned_directory,$ticketattachments->filename);
        }

        $filelist = '';
        foreach ($scanned_directory AS $file) {
            $filelist .= $directory . '/' . $file . ',';
        }
        $filelist = MJTC_majesticsupportphplib::MJTC_substr($filelist, 0, MJTC_majesticsupportphplib::MJTC_strlen($filelist) - 1);
        $v_list = $archive->create($filelist, PCLZIP_OPT_REMOVE_PATH, $directory);
        if ($v_list == 0) {
            die("Error : '" . $archive->errorInfo() . "'");
        }
        $file = $path . '/alldownloads.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . MJTC_majesticsupportphplib::MJTC_basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush();
        readfile($file);
        @unlink($file);
        $path = MJTC_PLUGIN_PATH;
        $path .= 'zipdownloads';
        $path .= '/' . $randomfolder;
        @unlink($path . '/index.html');
        if (file_exists($path)) {
            rmdir($path);
        }
        exit();
    }

    function getAllReplyDownloads() {
        $downloadid = MJTC_request::MJTC_getVar('downloadid');
        $replyattachment = MJTC_includer::MJTC_getModel('reply')->getAttachmentByReplyId($downloadid);
        if(!class_exists('PclZip')){
            do_action('majesticsupport_load_wp_pcl_zip');
        }
        $path = MJTC_PLUGIN_PATH;
        $path .= 'zipdownloads';
        MJTC_includer::MJTC_getModel('majesticsupport')->makeDir($path);
        $randomfolder = $this->getRandomFolderName($path);
        $path .= '/' . $randomfolder;

        MJTC_includer::MJTC_getModel('majesticsupport')->makeDir($path);
        $archive = new PclZip($path . '/alldownloads.zip');
        $datadirectory = majesticsupport::$_config['data_directory'];
        $maindir = wp_upload_dir();
        $jpath = $maindir['basedir'];
        $jpath = $jpath .'/'.$datadirectory;
        $scanned_directory = [];
        foreach ($replyattachment AS $replyattachments) {
            $directory = $jpath . '/attachmentdata/ticket/' . $replyattachments->attachmentdir . '/';
        array_push($scanned_directory,$replyattachments->filename);
        }

        $filelist = '';
        foreach ($scanned_directory AS $file) {
            $filelist .= $directory . '/' . $file . ',';
        }
        $filelist = MJTC_majesticsupportphplib::MJTC_substr($filelist, 0, MJTC_majesticsupportphplib::MJTC_strlen($filelist) - 1);
        $v_list = $archive->create($filelist, PCLZIP_OPT_REMOVE_PATH, $directory);
        if ($v_list == 0) {
            die("Error : '" . $archive->errorInfo() . "'");
        }
        $file = $path . '/alldownloads.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . MJTC_majesticsupportphplib::MJTC_basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush();
        readfile($file);
        @unlink($file);
        $path = MJTC_PLUGIN_PATH;
        $path .= 'zipdownloads';
        $path .= '/' . $randomfolder;
        @unlink($path . '/index.html');
        if (file_exists($path)) {
            @rmdir($path);
        }
        exit();
    }

    function getRandomFolderName($path) {
        $match = '';
        do {
            $rndfoldername = "";
            $length = 5;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
            $maxlength = MJTC_majesticsupportphplib::MJTC_strlen($possible);
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            $i = 0;
            while ($i < $length) {
                $char = MJTC_majesticsupportphplib::MJTC_substr($possible, mt_rand(0, $maxlength - 1), 1);
                if (!MJTC_majesticsupportphplib::MJTC_strstr($rndfoldername, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $rndfoldername .= $char;
                            $i++;
                        }
                    } else {
                        $rndfoldername .= $char;
                        $i++;
                    }
                }
            }
            $folderexist = $path . '/' . $rndfoldername;
            if (file_exists($folderexist))
                $match = 'Y';
            else
                $match = 'N';
        }while ($match == 'Y');

        return $rndfoldername;
    }
}

?>
