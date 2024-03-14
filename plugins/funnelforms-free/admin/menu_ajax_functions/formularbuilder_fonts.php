<?php 
class Fnsf_Af2AjaxFormularbuilderFonts {

    function __construct() {}

    public function af2_delete_font() {
        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $deleted = $this->af2DeleteFontFile($_POST['deletefile']);
        
        if ($deleted) {
            echo 'Datei erfolgreich gelöscht.';
        } else {
            echo 'Fehler beim Löschen der Datei oder Datei nicht gefunden.';
        }

        wp_die();
    }

    public function af2_add_font() {
        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $upload_dir = wp_upload_dir();
        $af2_fonts_dir = $upload_dir['basedir'] . '/af2_fonts';
    
        if (!file_exists($af2_fonts_dir)) {
            wp_mkdir_p($af2_fonts_dir);
        }
    
        if ($_FILES['af2FontFile']['error'] === UPLOAD_ERR_OK) {
            $tempFile = $_FILES['af2FontFile']['tmp_name'];
            $originalFileName = $_FILES['af2FontFile']['name'];
            $targetFile = $af2_fonts_dir . '/' . $originalFileName;
    
            if (move_uploaded_file($tempFile, $targetFile)) {
                echo 'Datei erfolgreich hochgeladen und gespeichert.';
            } else {
                echo 'Fehler beim Speichern der Datei.';
            }
        } else {
            echo 'Fehler beim Hochladen der Datei.';
        }
    
        wp_die();
    }

    private function af2DeleteFontFile($filename) {
        $upload_dir = wp_upload_dir();
        
        $af2_fonts_dir = $upload_dir['basedir'] . '/af2_fonts';
        
        $file_path = $af2_fonts_dir . '/' . $filename;
        if (file_exists($file_path) && is_file($file_path)) {
            unlink($file_path);
            return true;
        } else {
            return false;
        }
    } 
}