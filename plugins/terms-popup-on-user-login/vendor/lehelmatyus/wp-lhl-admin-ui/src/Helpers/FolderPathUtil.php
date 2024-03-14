<?php

namespace WpLHLAdminUi\Helpers;

/**
 * Given a folder name
 * return th path to the folder in the uploads directory
 * provides a url path for it
 */
class FolderPathUtil {

    private $directory = '';
    private $url = '';
    private $options = [
		'folder' => "uploads",
	];

    public function __construct($folder_name, $option = false) {

        // Folder name
        $folder_name = sanitize_title($folder_name);

        // Options
		if(!empty($options)){
			$this->options = array_merge($this->options, $options);
		}

        // fodler path
        switch ($this->options['folder']) {

            case 'value':
                # code...
                break;

            case 'uploads':
            default:
                /**
                 * Defaults to uploads directory
                 */
                $uploads_dir  = wp_upload_dir( null, true );
                $file_dir = $uploads_dir['basedir'] . '/' . $folder_name;
                $this->directory = $file_dir;
                
                $url = set_url_scheme($uploads_dir["baseurl"], 'https') . "/" . $folder_name;
                $this->url = $url;

                break;
        }

    }

    public function getDirectory() {
        return $this->directory;
    }
    
    public function getUrl() {
        return $this->url;
    }
    
}