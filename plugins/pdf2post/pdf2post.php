<?php
/**
 * Plugin Name: PDF 2 Post
 * Plugin URI: https://wordpress.org/plugins/pdf2post/
 * Description: Bulk convert PDF documents to posts (imports all text and images - and attach images automatically to newly created posts).
 * Version: 2.4.0
 * Author: termel
 * Author URI: https://www.termel.fr
 */

// to port to windows : use https://codex.wordpress.org/Function_Reference/wp_normalize_path
if (! defined('ABSPATH')) {
    die();
}

function pdf2post_log($message)
{
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}

if (! class_exists('PDF2Post')) {

    class PDF2Post
    {

        function __construct()
        {
            add_action('admin_menu', array(
                $this,
                'pdf2post_setup_menu'
            ));
            add_shortcode('pdf2post_demo', array(
                $this,
                'pdf2post_front_end_create_post_form'
            ));
            add_action('wp_enqueue_scripts', array(
                $this,
                'pdf2post_frontend_stylesheet'
            ));
        }

        function pdf2post_frontend_stylesheet()
        {
            wp_enqueue_style('pdf2post-css', plugins_url('/css/pdf2post.css', __FILE__));
        }

        function pdf2post_front_end_create_post_form()
        {
            ob_start();
            echo '<div style="background: #f5f5f5; border-radius: 4px; padding: 1em; border: 1px solid #a3a3a3; font-size: 0.8rem;">';
            echo '<h3>Document processing results</h3>';
            $this->handle_pdf();
            echo '</div>';
            $this->output_file_upload_form();
            return ob_get_clean();
        }

        function output_file_upload_form()
        {
            ?>
<h2>
	Upload a File (single <em>.pdf</em> or a <em>.zip</em> containing
	multiple <em>.pdf</em> files)
</h2>
<!-- Form to handle the upload - The enctype value here is very important -->
<form method="post" enctype="multipart/form-data">   		
        		<?php wp_nonce_field( 'pdf2post_pdf_upload', 'pdf2post_upload_nonce' ); ?>
	<fieldset>
		<label for="pdf_file_to_upload">Select zip file:</label> <input
			type='file' id='pdf_file_to_upload' name='pdf_file_to_upload'></input>
	</fieldset>
	<fieldset>
		<label for="selected_post_type">Create post of type:</label> <select
			name="selected_post_type" id="selected_post_type_id">
<?php
            $args = array();
            $operator = 'and';
            $output = 'objects';
            pdf2post_log("get post types...");
            $post_types = get_post_types($args, $output);
            foreach ($post_types as $post_type) {
                // pdf2post_log($post_type );
                if (! is_admin() && $post_type->name != 'post') {
                    continue;
                }
                $shownVal = $post_type->label;
                $key = $post_type->name;
                printf('<option value="%s" style="margin-bottom:3px;">%s</option>', $key, $shownVal);
            }
            ?>
	        </select>
	</fieldset>
	<fieldset>
		<label for="status">Create post in status:</label> <select
			name="status" id="status_id">
    			<?php
            pdf2post_log("get post statuses...");
            $statuses = get_post_statuses();
            foreach ($statuses as $key => $val) {
                if (! is_admin() && $key != 'publish') {
                    continue;
                }
                printf('<option value="%s" style="margin-bottom:3px;">%s</option>', $key, $val);
            }
            ?>
		</select>
	</fieldset>
	<fieldset>
		<label for="type">Processing method:</label> <select name="type"
			id="types_id">
    			<?php
            pdf2post_log("get post processing methods...");
            $processingMethods = array(
                'xml_clean' => 'Smart XML (recommended)',
                'xml' => 'XML',
                'text' => 'Text',
                'html' => 'HTML',
                'tag' => 'Tag'
            );
            foreach ($processingMethods as $key => $val) {
                printf('<option value="%s" style="margin-bottom:3px;">%s</option>', $key, $val);
            }
            ?>
		</select>
	</fieldset>
	<fieldset style="margin-top: 1rem;">
	<?php if (is_user_logged_in()) {?>
		<input style="color: lime;" type="submit" name="pdf2post_submit"
			value="<?php _e('Convert to post now!', 'pdf2post'); ?>" />
		<?php } else {?>
		
		<a
			style="border-radius: 4px; color: lightcoral; padding: 1rem; background-color: black;"
			href="<?php
                
global $wp;
                $redirect = add_query_arg($wp->query_vars, home_url($wp->request));
                echo wp_login_url($redirect);
                ?>"><?php _e('Please log in before converting :)', 'pdf2post'); ?></a>
	
		<?php } ?>
	</fieldset>
</form>
<?php
            pdf2post_log("form ready!");
        }

        function pdf2post_setup_menu()
        {
            $menuTitle = __('New Post from PDF', 'pdf2post');
            
            add_submenu_page('edit.php', $menuTitle, $menuTitle, 'delete_others_posts', 'new-post-from-pdf', array(
                $this,
                'pdf2post_upload_page'
            ));
            
            add_submenu_page('edit.php?post_type=page', $menuTitle, $menuTitle, 'delete_others_posts', 'new-post-from-pdf', array(
                $this,
                'pdf2post_upload_page'
            ));
            
            add_submenu_page('edit.php?post_type=article', $menuTitle, $menuTitle, 'delete_others_posts', 'new-post-from-pdf', array(
                $this,
                'pdf2post_upload_page'
            ));
        }

        function output_file_upload_form_old()
        {
            ?>
<h2>
	Upload a File (single <em>.pdf</em> or a <em>.zip</em> containing
	multiple <em>.pdf</em> files)
</h2>
<!-- Form to handle the upload - The enctype value here is very important -->
<form method="post" enctype="multipart/form-data">
	<input type='file' id='pdf_file_to_upload' name='pdf_file_to_upload'></input>
        		<?php wp_nonce_field( 'pdf2post_pdf_upload', 'pdf2post_upload_nonce' ); ?>
	
	
        <select name="selected_post_type" id="selected_post_type_id">
	                        <?php
            $args = array();
            $operator = 'and';
            $output = 'objects'; // names or objects
            pdf2post_log("get post types...");
            $post_types = get_post_types($args, $output);
            foreach ($post_types as $post_type) {
                $shownVal = $post_type->label;
                $key = $post_type->name;
                printf('<option value="%s" style="margin-bottom:3px;">%s</option>', $key, $shownVal);
            }
            ?>
	                                </select> <select name="status"
		id="status_id">
    			<?php
            $statuses = get_post_statuses();
            foreach ($statuses as $key => $val) {
                printf('<option value="%s" style="margin-bottom:3px;">%s</option>', $key, $val);
            }
            ?>
				</select> <select name="type" id="types_id">
    			<?php
            $processingMethods = array(
                'xml_clean' => 'Smart XML (recommended)',
                'xml' => 'XML',
                'text' => 'Text',
                'html' => 'HTML',
                'tag' => 'Tag'
            );
            foreach ($processingMethods as $key => $val) {
                printf('<option value="%s" style="margin-bottom:3px;">%s</option>', $key, $val);
            }
            ?>
				</select>	

    		<?php submit_button('Create post from PDF file')?>
    	</form>
<?php
        }

        function pdf2post_upload_page()
        {
            $this->handle_pdf();
            
            $cmd = "python --version";
            $cmd = "python -c 'import sys; print sys.version'";
            pdf2post_log("Using command: " . $cmd);
            $outputArray = array();
            $pythonVersion = exec($cmd, $outputArray);
            // $pythonVersion = exec($cmd);
            pdf2post_log("Py version: " . $pythonVersion);
            pdf2post_log($outputArray);
            if (count($outputArray) > 0) {
                $pythonText = '<span style="color:green;">Installed: ' . $outputArray[0] . '</span>';
            } else {
                $pythonText = '<span style="color:red;">Not installed!</span>';
            }
            
            $installedColor = 'orange';
            $installedText = 'N/A';
            $additionnalText = '';
            if (extension_loaded('zip')) {
                $installedColor = 'green';
                $installedText = 'Installed';
            } else {
                $installedColor = 'red';
                $installedText = 'Not installed!';
                $additionnalText = 'Use something like : <pre>apt-get install php7.0-zip</pre>';
            }
            $zipText = '<span style="color:' . $installedColor . ';">' . $installedText . '</span>';
            if ($additionnalText) {
                $zipText .= $additionnalText;
            }
            
            $cmdWhich = "which pdfimages";
            pdf2post_log("Using command: " . $cmdWhich);
            $outputArray = array();
            // $passthru = passthru($cmd);
            
            $appLocation = exec($cmdWhich, $outputArray);
            
            pdf2post_log("Pdfimages location: " . $appLocation);
            
            $cmd = $appLocation . " -v";
            pdf2post_log("Using command: " . $cmd);
            $appVersion = exec($cmd, $outputArray);
            
            pdf2post_log($outputArray);
            if (count($outputArray) > 0) {
                $pdfimagesText = '<span style="color:green;">Installed: ' . $outputArray[0] . '</span>';
            } else {
                $pdfimagesText = '<span style="color:red;">Not installed! please see <a href="https://en.wikipedia.org/wiki/Pdfimages" target="_blank">https://en.wikipedia.org/wiki/Pdfimages</a></span>';
                $ubuntuCmd = "sudo apt-get install poppler-utils";
                $pdfimagesText .= '<br/><span style="color:red;">Use something like : ' . $ubuntuCmd . '</span>';
            }
            
            $pdfminerNotFound1 = $pdfminerNotFound2 = $pdfminerNotFound = $pdfminerSixNotFound = false;
            
            $outputArray = array();
            
            $cmd = "which pdf2txt.py";
            $lastLine = exec($cmd, $outputArray);
            pdf2post_log( $cmd." => ".$lastLine);
            pdf2post_log( $outputArray);
            
            $possible_pdfminer_exe = ["pdf2txt.py -V" => "pdf2txt.py","pdf2txt.py --version" => "pdf2txt.py", "pdf2txt -V"=>"pdf2txt"];
            
            foreach ($possible_pdfminer_exe as $v_cmd => $exe) {
                pdf2post_log("Looking for PDFMiner, Using command: " . $v_cmd);
                $retVal = '';
                $outputArray = [];
                $exec_version = exec($v_cmd, $outputArray);
                pdf2post_log($outputArray);
                pdf2post_log($retVal);
                pdf2post_log($exec_version);
                if (count($outputArray) > 0) {                  
                    
                    $pdf2txtText = '<span style="color:green;">Installed: ' . $exec_version. '</span>';
                    $basePdf2txtCmd = $exe;
                    break;
                } else {
                    $ubuntuCmd = "pip install pdfminer.six";
                    $explanationMessage = __('Not installed! please use <a href="https://pdfminersix.readthedocs.io/en/latest/tutorial/install.html" target="_blank">'.$ubuntuCmd.'</a>');
                    $pdf2txtText = '<span style="color:red;">'.$explanationMessage.'</span>';                    
                    $pdf2txtText .= '<br/><span style="color:red;">Use something like : ' . $ubuntuCmd . '</span>';
                }
            }
       
            pdf2post_log("pdf2txt exe : " . $basePdf2txtCmd);
            update_option("pdf2txt_executable", $basePdf2txtCmd);
            
            ?>
<div style="text-align: center; padding: 5px;">
	<h1>PDF 2 Post!</h1>
	<a class="button-primary"
		href="https://wordpress.org/plugins/pdf2post/" target="_blank"><?php echo __( 'Visit Plugin Site', 'pdf2post' ); ?>  </a>
	<a class="button-primary" style="color: #FFF600;"
		href="https://wordpress.org/support/plugin/pdf2post/reviews/#new-post"
		target="_blank"><?php echo __ ( 'Please Rate!', 'pdf2post' ); ?>  </a>
</div>
<div
	style="background: #e0e0e0; border-radius: 4px; padding: 1em; border: 1px solid #a3a3a3; font-size: 1.2em;">
	The following libraries <em>NEED</em> to be installed on your server :
	<?php
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                echo '<span style="color:red;">Unfortunately, this plugin has not been tested on Windows OS.</span>';
            } else {
                echo '<pre>' . php_uname() . '</pre>';
            }
            
            ?>
	<ul style="list-style: inside;">
		<li><a href="http://php.net/manual/fr/class.ziparchive.php"
			target="_blank">ZipArchive</a> <?php echo $zipText;?></li>
		<li><a href="http://www.unixuser.org/~euske/python/pdfminer/"
			target="_blank">PDFMiner</a></li><?php echo '<pre>'.$pdf2txtText.'</pre>';?>
		<li><a href="https://www.python.org/" target="_blank">Python</a> <?php echo '<pre>'.$pythonText.'</pre>';?>
			</li>
		<li><a href="https://en.wikipedia.org/wiki/Pdfimages" target="_blank">pdfimages</a><?php echo '<pre>'.$pdfimagesText.'</pre>';?>
		</li>
	</ul>
</div><?php
            $this->output_file_upload_form();
        }

        function attach_image_to_post($filename, $post_id, $images_legends, $featured)
        {
            
            // $filename should be the path to a file in the upload directory.
            // $filename = '/path/to/uploads/2013/03/filename.jpg';
            
            // The ID of the post this attachment is for.
            $parent_post_id = $post_id;
            
            // Check the type of file. We'll use this as the 'post_mime_type'.
            $filetype = wp_check_filetype(basename($filename), null);
            
            // Get the path to the upload directory.
            $wp_upload_dir = wp_upload_dir();
            
            // Prepare an array of post data for the attachment.
            $attachment = array(
                'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                'post_mime_type' => $filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            
            // Insert the attachment.
            $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);
            
            // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
            require_once (ABSPATH . 'wp-admin/includes/image.php');
            
            // Generate the metadata for the attachment, and update the database record.
            $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
            wp_update_attachment_metadata($attach_id, $attach_data);
            
            // set_post_thumbnail( $parent_post_id, $attach_id );
            if ($featured) {
                pdf2post_log("Featured image");
                set_post_thumbnail($parent_post_id, $attach_id);
            }
            return $attach_id;
        }

        function pdf_upload_filter($file)
        {
            pdf2post_log("|||||| Filtering File name " . $file['name']);
            $file['name'] = preg_replace("/ /", "-", $file['name']);
            
            return $file;
        }

        function normpath($path)
        {
            if (empty($path))
                return '.';
            
            if (strpos($path, '/') === 0)
                $initial_slashes = true;
            else
                $initial_slashes = false;
            if (($initial_slashes) && (strpos($path, '//') === 0) && (strpos($path, '///') === false))
                $initial_slashes = 2;
            $initial_slashes = (int) $initial_slashes;
            
            $comps = explode('/', $path);
            $new_comps = array();
            foreach ($comps as $comp) {
                if (in_array($comp, array(
                    '',
                    '.'
                )))
                    continue;
                if (($comp != '..') || (! $initial_slashes && ! $new_comps) || ($new_comps && (end($new_comps) == '..')))
                    array_push($new_comps, $comp);
                elseif ($new_comps)
                    array_pop($new_comps);
            }
            $comps = $new_comps;
            $path = implode('/', $comps);
            if ($initial_slashes)
                $path = str_repeat('/', $initial_slashes) . $path;
            if ($path)
                return $path;
            else
                return '.';
        }

        function clean($string)
        {
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
            
            return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        }

        function dir_tree($dir)
        {
            // http://www.php.net/manual/de/function.scandir.php#102505
            $paths = array();
            $stack[] = $dir;
            while ($stack) {
                $thisdir = array_pop($stack);
                if ($dircont = scandir($thisdir)) {
                    $i = 0;
                    while (isset($dircont[$i])) {
                        if ($dircont[$i] !== '.' && $dircont[$i] !== '..') {
                            $current_file = "{$thisdir}/{$dircont[$i]}";
                            if (is_file($current_file)) {
                                $paths[] = "{$thisdir}/{$dircont[$i]}";
                            } elseif (is_dir($current_file)) {
                                $paths[] = "{$thisdir}/{$dircont[$i]}";
                                $stack[] = $current_file;
                            }
                        }
                        $i ++;
                    }
                }
            }
            return $paths;
        }

        function handle_pdf()
        {
            // 'pdf2post_pdf_upload', 'pdf2post_upload_nonce' );
            // First check if the file appears on the _FILES array
            if (isset($_FILES['pdf_file_to_upload'])) {
                if (wp_verify_nonce($_POST['pdf2post_upload_nonce'], 'pdf2post_pdf_upload')) {
                    pdf2post_log("wp_verify_nonce : OK");
                    $this->displayHTMLStatusOfCmd("wp_verify_nonce", "OK", 0);
                    $file = $_FILES['pdf_file_to_upload'];
                    if (isset($file['error']) && $file['error'] > 0){
                        $this->displayHTMLStatusOfCmd("upload of file", "KO", 1);
                        pdf2post_log("upload : KO");
                        pdf2post_log($file);
                        $phpFileUploadErrors = array(
                            0 => 'There is no error, the file uploaded with success',
                            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                            3 => 'The uploaded file was only partially uploaded',
                            4 => 'No file was uploaded',
                            6 => 'Missing a temporary folder',
                            7 => 'Failed to write file to disk.',
                            8 => 'A PHP extension stopped the file upload.',
                        );
                        $error_msg = $phpFileUploadErrors[$file['error']];
                        pdf2post_log($error_msg);
                        echo $error_msg;
                        return;
                        
                    }
                    $filename = sanitize_file_name($file['name']);
                    $status = $_POST['status'];
                    $post_type_to_create = sanitize_text_field($_POST['selected_post_type']);
                    $type = $_POST['type'];
                    pdf2post_log($file);
                    pdf2post_log($status);
                    pdf2post_log($type);
                    if (stripos($filename, '.zip')) {
                        // multiple files to process
                        pdf2post_log("<---");
                        pdf2post_log("<--- Multiple Files received (" . $status . ") : ");
                        pdf2post_log("<---");
                        
                        $post_id = 0;
                        $unzipped = array();
                        $uploadDir = wp_upload_dir();
                        $writeToPath = trailingslashit($uploadDir['path']);
                        foreach ($_FILES as $fid => $array) {
                            if ($array['error'] !== UPLOAD_ERR_OK) {
                                return "upload error : " . $array['error'];
                            }
                            WP_Filesystem();
                            
                            $folder = $writeToPath . "unzipped_" . basename($array['tmp_name']);
                            pdf2post_log($folder);
                            if (! is_dir($folder)) {
                                mkdir($folder);
                            }
                            
                            if (unzip_file($array['tmp_name'], $folder) === True) {
                                $filepaths = $this->dir_tree($folder);
                                foreach ($filepaths as $k => $filepath) {
                                    if (is_file($filepath)) {
                                        $file = array();
                                        $file['name'] = basename($filepath);
                                        $file['size'] = filesize($filepath);
                                        $file['tmp_name'] = $filepath;
                                        $unzipped["unzipped_$k"] = $file;
                                    }
                                }
                            }
                            
                            $uploaded = media_handle_upload($fid, $post_id);
                            if (is_wp_error($uploaded)) {
                                $msg = "Error uploading file: " . $uploaded->get_error_message();
                                pdf2post_log($msg);
                                echo $msg;
                            } else {
                                $msg = "File uploaded successfully!";
                                pdf2post_log($msg);
                                echo $msg;
                            }
                            // update_post_meta($post_id, '_thumbnail_id', $attach_id);
                            // $post_id = wp_update_post();
                        }
                        foreach ($unzipped as $file) {
                            $uploaded = media_handle_sideload($file, $post_id);
                            if (is_wp_error($uploaded)) {
                                $msg = "Error uploading file: " . $uploaded->get_error_message();
                                pdf2post_log($msg);
                                echo $msg;
                            } else {
                                $msg = "File uploaded successfully!";
                                pdf2post_log($msg);
                                echo $msg;
                            }
                            // update_post_meta($post_id, '_thumbnail_id', $attach_id);
                            // $post_id = wp_update_post();
                            $singleFilename = $file['tmp_name']; // $zip->getNameIndex($i);
                            pdf2post_log("###############################################################");
                            pdf2post_log("###### File in ZIP: " . $singleFilename . " ######");
                            pdf2post_log("###############################################################");
                            
                            $fileinfo = pathinfo($singleFilename);
                            pdf2post_log($fileinfo);
                            
                            $this->pdf2postFromFile($fileinfo['basename'], $status, $post_type_to_create, false, $type);
                        }
                    } else {
                        // single file to process
                        pdf2post_log("<--- Single File received (" . $status . ") : ");
                        pdf2post_log($file);
                        if (empty($file['tmp_name'])) {
                            $this->displayHTMLStatusOfCmd('File upload:', array(
                                "No file selected"
                            ), 1);
                            return;
                        }
                        // Use the wordpress function to uploa
                        // test_upload_pdf corresponds to the position in the $_FILES array
                        // 0 means the content is not associated with any other posts
                        // wp_upload_bits( string $name, null|string $deprecated, mixed $bits, string $time = null )
                        
                        $uploaded = wp_upload_bits($filename, null, file_get_contents($file['tmp_name']));
                        
                        // $uploaded = media_handle_upload('pdf_file_to_upload', 0);
                        // $uploaded = media_handle_sideload('pdf_file_to_upload', 0);
                        
                        if (is_wp_error($uploaded)) {
                            $msg = "Error uploading file: " . $uploaded->get_error_message();
                            pdf2post_log($msg);
                            echo $msg . '<br/>';
                        } else {
                            $msg = "File uploaded successfully! " . $uploaded['file'];
                            pdf2post_log($msg);
                            echo $msg . '<br/>';
                        }
                        $this->pdf2postFromAbsFile($uploaded['file'], $status, $post_type_to_create, false, $type);
                    }
                } else {
                    $msg = "The security check failed";
                    // The security check failed, maybe show the user an error.
                    pdf2post_log($msg);
                    return null;
                    // echo $msg . '<br/>';
                }
            } else {
                $msg = $this->displayHTMLStatusOfCmd("", "Need to upload a file", 1);
                echo $msg;
                // The security check failed, maybe show the user an error.
                pdf2post_log($msg);
                return null;
            }
        }

        function pdf2postFromFile($filename, $status, $post_type_to_create, $multiple = false, $type = 'text')
        {
            $inputFileName = str_replace($multiple ? '.zip' : '.pdf', '', $filename);
            $inputFileNameWithoutExt = sanitize_file_name($inputFileName); // $this->clean($inputFileName);
            $inputFileNameWithExt = $inputFileNameWithoutExt . '.pdf';
            
            // $inputFileNameWithoutExt = str_replace('.pdf', '', $inputFileName);
            pdf2post_log("File RAW: " . $filename);
            pdf2post_log("File : " . $inputFileNameWithExt);
            pdf2post_log("File without : " . $inputFileNameWithoutExt);
            $uploadDir = wp_upload_dir();
            pdf2post_log("Upload Dir: ");
            pdf2post_log($uploadDir);
            
            $writeToPath = trailingslashit($uploadDir['path']);
            pdf2post_log("will write here: " . $writeToPath);
            
            return $this->pdf2postFromAbsFile($writeToPath . $inputFileNameWithExt, $status, $post_type_to_create, $type);
        }

        function displayHTMLStatusOfCmd($cmd, $output, $ret_val, $additionnalMesg = '')
        {
            $show_status = '<span style="color:grey;font-size:0.7em;">' . $cmd . '</span>';
            $show_status .= '<span ';
            if ($ret_val == 0) {
                $color = 'green';
            } else {
                $color = 'red';
            }
            $show_status .= 'style="color:' . $color . '">';
            if (empty($output)) {
                $show_status .= 'Ok';
            } else {
                $show_status .= '<ul><li>';
                if (is_array($output)) {
                    $show_status .= implode('</li><li>', $output);
                } else {
                    $show_status .= $output;
                }
                $show_status .= '</li></ul>';
            }
            
            $show_status .= $additionnalMesg;
            $show_status .= '</span>';
            if (is_array($output)) {
                pdf2post_log(implode(' | ', $output));
            }
            return $show_status;
        }

        function pdf2postFromAbsFile($abs_filename, $status, $post_type_to_create, $multiple = false, $type = 'text')
        {
            $info = pathinfo($abs_filename);
            $inputFileNameWithoutExt = basename($abs_filename, '.' . $info['extension']);
            $inputFileNameWithExt = basename($abs_filename);
            $uploadDir = wp_upload_dir();
            $writeToPath = trailingslashit($uploadDir['path']);
            $uploadedFilePath = $this->normpath($writeToPath . $inputFileNameWithoutExt);
            pdf2post_log("realpath " . $uploadedFilePath);
            
            $uploadedFilePathWithExt = realpath($this->normpath($writeToPath . $inputFileNameWithExt));
            pdf2post_log("realpath " . $uploadedFilePathWithExt);
            
            pdf2post_log("Need to extract images from file in " . $uploadedFilePath);
            
            $imagesOutputFolder = $this->normpath($writeToPath . '/' . $inputFileNameWithoutExt . '_out/');
            if (! is_dir($imagesOutputFolder)) {
                if (! mkdir($imagesOutputFolder, 0755)) {
                    pdf2post_log("ERROR::Cannot create directory " . $imagesOutputFolder);
                }
            } else {
                $files = glob($imagesOutputFolder . '/*'); // get all file names
                $deleted = 0;
                foreach ($files as $file) { // iterate files
                    if (is_file($file)) {
                        
                        $deleted ++;
                        unlink($file); // delete file
                    }
                }
                pdf2post_log($deleted . " files deleted from " . $imagesOutputFolder);
            }
            $imagesOutputFolder = realpath($imagesOutputFolder);
            pdf2post_log($imagesOutputFolder);
            $prefix = '_images'; // $inputFileNameWithoutExt;
            $outImagesPrefix = 'extracted_';
            $outputImagesFolder = $this->normpath($imagesOutputFolder . '/' . $prefix);
            // $outImagesWithPrefix = $this->normpath($outputImagesFolder . '/' . $outImagesPrefix);
            
            if (! is_dir($outputImagesFolder)) {
                pdf2post_log('PDFIMAGES *** not a dir : ' . $outputImagesFolder);
                if (! mkdir($outputImagesFolder, 0755)) {
                    pdf2post_log("ERROR::Cannot create directory " . $outputImagesFolder);
                } else {
                    pdf2post_log("Create directory " . $outputImagesFolder);
                }
            }
            
            if (is_dir($outputImagesFolder)) {
                
                $cmd = "pdfimages -png " . $uploadedFilePathWithExt . " " . $outputImagesFolder;
                
                pdf2post_log("Using command: " . $cmd);
                // exec ( string $command [, array &$output [, int &$return_var ]] ) : string
                $ret_var = '';
                $lastLine = exec($cmd, $output, $ret_var);
                
                if ($ret_var == 99) {
                    $cmd = "pdfimages " . $uploadedFilePathWithExt . " " . $outputImagesFolder;
                    pdf2post_log("Using command: " . $cmd);
                    $lastLine = exec($cmd, $output, $ret_var);
                }
                
                $fi = new FilesystemIterator($imagesOutputFolder, FilesystemIterator::SKIP_DOTS);
                $additionnalMesg = $lastLine;
                $additionnalMesg .= $ret_var;
                $additionnalMesg .= 'PDFIMAGES : images extracted : ' . iterator_count($fi);
                
                pdf2post_log($additionnalMesg);
                pdf2post_log($ret_var);
                switch ($ret_var) {
                    case 0:
                        $msg = "No error.";
                        $additionnalMesg .= $msg;
                        pdf2post_log($msg);
                        break;
                    case 1:
                        $msg = "Error opening a PDF file.";
                        $additionnalMesg .= $msg;
                        pdf2post_log($msg);
                        break;
                    case 2:
                        $msg = "Error opening an output file.";
                        $additionnalMesg .= $msg;
                        pdf2post_log($msg);
                        break;
                    case 3:
                        $msg = "Error related to PDF permissions.";
                        $additionnalMesg .= $msg;
                        pdf2post_log($msg);
                        break;
                    case 99:
                        $msg = "Other error.";
                        $additionnalMesg .= $msg;
                        pdf2post_log($msg);
                        break;
                }
                
                $this->displayHTMLStatusOfCmd($cmd, $output, $ret_var, $additionnalMesg);
            } else {
                $msg = 'ERROR *** cannot create dir : ' . $outputImagesFolder;
                pdf2post_log($msg);
                echo $msg;
            }
            
            $cmdTypeOption = $type;
            if ($type == 'xml_clean') {
                $cmdTypeOption = 'xml';
            }
            $here = trailingslashit(plugin_dir_path(__FILE__));
            $pdfminerVersion = '20140328';
            // $pdf2txtPath = $here . 'libs/pdfminer-'.$pdfminerVersion.'/tools/pdf2txt.py';
            $pdf2txtPath = get_option("pdf2txt_executable"); // self::$pdf2txtExe;
            $pdf2txtOutputFile = $this->normpath($imagesOutputFolder . '/' . $inputFileNameWithoutExt . '.' . $cmdTypeOption);
            $pdf2txtXMLCleanedOutputFile = $this->normpath($imagesOutputFolder . '/' . $inputFileNameWithoutExt . '_cleaned.' . $cmdTypeOption);
            if (! is_file($uploadedFilePathWithExt)) {
                $msg = 'PDF2TXT *** not a file : ' . $uploadedFilePathWithExt;
                pdf2post_log($msg);
                echo $msg;
                return false;
            } else {
                
                $command = $pdf2txtPath . " -t " . $cmdTypeOption . " -o " . $pdf2txtOutputFile . " " . realpath($uploadedFilePathWithExt);
                // $command = $pdf2txtPath . " -t html -Y loose -o " . $pdf2txtOutputFile . " " . $uploadedFilePathWithExt;
                $output = '';
                pdf2post_log("Using command: " . $command);
                $ret_val = '';
                $pdfToHTMLConversionOutputLastLine = exec($command, $output, $ret_val);
                pdf2post_log($output);
                
                $this->displayHTMLStatusOfCmd($command, $output, $ret_val);
                
                if ($type == 'xml_clean') {
                    // extract paragraphs so as they are usable as post text
                    $ptExtractScriptFilename = $here . 'py/parseXMLOutput.py';
                    
                    if (file_exists($ptExtractScriptFilename)) {
                        $xml_extract_command = 'python ' . $ptExtractScriptFilename . ' ' . $pdf2txtOutputFile . ' > ' . $pdf2txtXMLCleanedOutputFile;
                        
                        pdf2post_log("Using command: " . $xml_extract_command);
                        
                        $xmlExtractionOutputLastLine = exec($xml_extract_command, $output, $ret_val);
                        pdf2post_log($output);
                        $this->displayHTMLStatusOfCmd($xml_extract_command, $xmlExtractionOutputLastLine, $ret_val);
                        // set new output as
                        $pdf2txtOutputFile = $pdf2txtXMLCleanedOutputFile;
                    } else {
                        pdf2post_log("ERROR::No python script: " . $ptExtractScriptFilename);
                    }
                }
                
                // get bounding boxes
                /*
                $ptExtractScriptFilename = $here . 'py/getBoundingBoxes.py';
                
                if (file_exists($ptExtractScriptFilename)) {
                    $xml_extract_command = 'python3 ' . $ptExtractScriptFilename . ' ' . $pdf2txtOutputFile ;//. ' > ' . $pdf2txtXMLCleanedOutputFile;
                    
                    pdf2post_log("Using command: " . $xml_extract_command);
                    
                    $xmlExtractionOutputLastLine = exec($xml_extract_command, $output, $ret_val);
                    pdf2post_log($output);
                    $this->displayHTMLStatusOfCmd($xml_extract_command, $xmlExtractionOutputLastLine, $ret_val);
                    // set new output as
                    $pdf2txtOutputFile = $pdf2txtXMLCleanedOutputFile;
                } else {
                    pdf2post_log("ERROR::No python script: " . $ptExtractScriptFilename);
                }
                */
            }
            
            if (file_exists(realpath($pdf2txtOutputFile))) {
                $pdf2txtOutputFile = realpath($pdf2txtOutputFile);
            }
            pdf2post_log("realpath after file written " . $pdf2txtOutputFile);
            
            // if (file_exists($filename)) {
            if (! file_exists($pdf2txtOutputFile)) {
                $msg = "ERROR: no file created " . $pdf2txtOutputFile;
                pdf2post_log($msg);
                echo $msg;
                return;
            } else {
                $msg = "File successfully created " . $pdf2txtOutputFile;
                pdf2post_log($msg);
                echo $msg;
            }
            
            $contentOfHtml = file_get_contents($pdf2txtOutputFile);
            pdf2post_log("content of file:");
            pdf2post_log($contentOfHtml);
            
            $user_id = get_current_user_id();
            pdf2post_log("Current user id: " . $user_id);
            if (! is_admin()) {
                $category_ids = [];
                $slugs = [
                    'demo',
                    'pdf2post'
                ];
                foreach ($slugs as $slug) {
                    $idObj = get_category_by_slug($slug);
                    if ($idObj instanceof WP_Term) {
                        $category_ids[] = $idObj->term_id;
                    }
                }
            } else {
                $category_ids = [
                    1
                ];
            }
            
            // insert post of type= $post_type_to_create
            
            $postarr = array(
                'post_author' => $user_id,
                'post_content' => $contentOfHtml,
                'post_content_filtered' => '',
                'post_title' => $inputFileNameWithoutExt,
                'post_excerpt' => '',
                'post_status' => $status,
                'post_type' => $post_type_to_create,
                'post_category' => $category_ids,
                'comment_status' => '',
                'ping_status' => '',
                'post_password' => '',
                'to_ping' => '',
                'pinged' => '',
                'post_parent' => 0,
                'menu_order' => 0,
                'guid' => '',
                'import_id' => 0,
                'context' => ''
            );
            
            $postarr = sanitize_post($postarr, 'db');
            
            $inserted = wp_insert_post($postarr, true);
            if (is_wp_error($inserted)) {
                pdf2post_log("Error creating post: " . $inserted->get_error_message());
                echo "Error creating post: " . $inserted->get_error_message();
            } else {
                $attachedImagesArray = array();
                $sucessMsg = '<span style="color:green;">Post successfully inserted with ID ' . $inserted . " :)</span>";
                pdf2post_log($sucessMsg);
                echo $sucessMsg;
                echo '<br/>';
                $files = glob($imagesOutputFolder . '/*.png'); // get all file names
                pdf2post_log("Images extracted in " . $imagesOutputFolder . " : " . count($files));
                $featured_image = 0;
                $idx = 0;
                foreach ($files as $file) { // iterate files
                    if (is_file($file)) {
                        $attachMsg = $idx . " -> Attaching image to post: " . $file;
                        pdf2post_log($attachMsg);
                        echo $attachMsg . '<br/>';
                        $featured = $featured_image == $idx;
                        $attachedImagesArray[] = $this->attach_image_to_post($file, $inserted, null, $featured);
                        $idx ++;
                    }
                }
                
                // add image gallery [gallery ids="729,732,731,720"]
                // $postarr['post_content'] = $postarr['post_content'] . '[gallery ids="'.implode(',',$attachedImagesArray).'"]';
                
                // Update post 37
                $update_post = array(
                    'ID' => $inserted,
                    // 'post_title' => 'This is the post title.',
                    'post_content' => $postarr['post_content'] . '[gallery size="full" link="file" columns="4" ids="' . implode(',', $attachedImagesArray) . '"]'
                );
                
                // Update the post into the database
                echo 'Creating image gallery...<br/>';
                $updated_id = wp_update_post($update_post);
                if (is_wp_error($updated_id)) {
                    $errors = $updated_id->get_error_messages();
                    foreach ($errors as $error) {
                        pdf2post_log($error);
                        echo '<span style="color:red;">' . $error . '</span><br/>';
                    }
                } else {
                    $attachMsg = '<span style="color:green;">Gallery successfully created</span>';
                    pdf2post_log($attachMsg);
                    echo $attachMsg . '<br/>';
                }
                
                $newposturl = get_edit_post_link($inserted);
                $preview = get_preview_post_link($inserted);
                
                ?>
<div class="pdf2post_buttons_container">
	<span class="convertion_result_button pdf2post_preview_container"><?php echo get_the_post_thumbnail( $updated_id, 'thumbnail', array( 'class' => 'pdf2post_thumbnail_result' )  ); ?> <a
		target="_blank" href="<?php echo $preview ?>">Preview created post : <?php echo $inputFileNameWithoutExt ?></a></span>
	<?php if (is_admin()) { ?>
	<span class="convertion_result_button"> <a target="_blank"
		href="<?php echo $newposturl ?>">Edit created post : <?php echo $inputFileNameWithoutExt ?></a></span>
</div>
<?php
                
}
            }
        }
    }
}
$obj = new PDF2Post();