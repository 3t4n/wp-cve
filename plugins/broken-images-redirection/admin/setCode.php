<?php



if( ! class_exists( 'BIR_setCode' ) ) {
    class BIR_setCode
    {

        public function __construct()
        {

        }

        function modify_htaccess($old_file,$new_image,$id)
        {

            if($old_file == ''){
                return false;
            }


            if (isset($new_image) && $new_image != '') {

                $image_id = isset($new_image) ? absint($new_image) : '';
                if ($image_id != '') {
                    $image = wp_get_original_image_path($image_id);
                    $ruls[] = <<<EOT
RewriteOptions inherit
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^$old_file$ $image [PT,NC,L]
</IfModule>
EOT;
                    $upload_dir = wp_upload_dir();
                    $htaccess_file = isset($upload_dir['basedir'])?$upload_dir['basedir']:'';
                    $htaccess_file = $htaccess_file . '/.htaccess';

                    return $this->add_htaccess($ruls,$id,$htaccess_file);
                }
            }
        }
        function modify_htaccess_def_image($new_image)
        {

            if (isset($new_image) && $new_image != '') {

                $image_id = isset($new_image) ? absint($new_image) : '';
                if ($image_id != '') {
                    $image = wp_get_original_image_path($image_id);

                    $ruls[] = <<<EOT
RewriteOptions inherit
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} \.(gif|jpg|jpeg|png)$
RewriteRule (.*) $image [PT,NC,L]
</IfModule>
EOT;
                    $htaccess_file = WP_CONTENT_DIR . '/.htaccess';
                    return $this->add_htaccess($ruls,broken_image_val_update_htaccess,$htaccess_file);
                }
            }
        }


        function clear_htaccess($id)
        {
            $htaccess_file = WP_CONTENT_DIR.'/.htaccess';

            $this->insert_with_markers_htaccess($htaccess_file, 'All_404_marker_comment_link_'.$id, "");
        }
        function clear_htaccess_upload($id)
        {
            $upload_dir = wp_upload_dir();
            $htaccess_file = isset($upload_dir['basedir'])?$upload_dir['basedir']:'';
            $htaccess_file = $htaccess_file . '/.htaccess';

            $this->insert_with_markers_htaccess($htaccess_file, 'All_404_marker_comment_link_'.$id, "");
        }
        function add_htaccess($insertion,$id,$htaccess_file)
        {
            //Clear the old htaccess file located inside the main website directory

            $filename = $htaccess_file;
            if (!file_exists($filename)) {
                touch($filename);
            }
            if (is_writable($filename)) {
                return array('status' => true, 'massage' => $this->insert_with_markers_htaccess($htaccess_file, 'All_404_marker_comment_link_'.absint($id), (array)$insertion));
            } else {
                return array('status' => false, 'massage' => $insertion);
            }
        }


        function insert_with_markers_htaccess($filename, $marker, $insertion)
        {
            if (!file_exists($filename) || is_writeable($filename)) {
                if (!file_exists($filename)) {
                    $markerdata = '';
                } else {
                    $markerdata = explode("\n", implode('', file($filename)));
                }

                if (!$f = @fopen($filename, 'w'))
                    return false;

                $foundit = false;
                if ($markerdata) {
                    $state = true;
                    foreach ($markerdata as $n => $markerline) {
                        if (strpos($markerline, '# BEGIN ' . $marker) !== false)
                            $state = false;
                        if ($state) {
                            if ($n + 1 < count($markerdata))
                                fwrite($f, "{$markerline}\n");
                            else
                                fwrite($f, "{$markerline}");
                        }
                        if (strpos($markerline, '# END ' . $marker) !== false) {
                            fwrite($f, "# BEGIN {$marker}\n");
                            if (is_array($insertion))
                                foreach ($insertion as $insertline)
                                    fwrite($f, "{$insertline}\n");
                            fwrite($f, "# END {$marker}\n");
                            $state = true;
                            $foundit = true;
                        }
                    }
                }
                if (!$foundit) {
                    fwrite($f, "\n# BEGIN {$marker}\n");
                    if (is_array($insertion))
                        foreach ($insertion as $insertline)
                            fwrite($f, "{$insertline}\n");
                    fwrite($f, "# END {$marker}\n");
                }
                fclose($f);
                return true;
            } else {
                return false;
            }
        }
    }
}
