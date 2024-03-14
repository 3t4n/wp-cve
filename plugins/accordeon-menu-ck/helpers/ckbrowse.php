<?php
/**
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Plugins CK - Cédric KEIFLIN - https://www.ceikay.com
 */
namespace Accordeonmenuck;

defined('ABSPATH') or die;

// load the additional files
CKFof::loadHelper('folder');
CKFof::loadHelper('file');
CKFof::loadHelper('path');
CKFof::loadHelper('input');

class CKBrowse {

	static $input;

	private static function init() {
		self::$input = new CKInput();
	}

	/**
	 * Show the list of elements
	 */
	public static function showBrowser($type = 'image') {
		self::init();
		// check first user rights
//		if (! current_user_can('edit_plugins')) die('You don\'t have sufficient permissions');
		$input = self::$input;
		$items = self::getItemsList($type);
		
		$type = $input->get('type', 'image', 'string');
		$imagespath = ACCORDEONMENUCK_MEDIA_URL .'/images/';
		$returnFunc = $input->get('func', 'ckSelectFile', 'cmd');
		$returnField = $input->get('field', '', 'string');

		switch ($type) {
			case 'video' :
				$fileicon = 'file_video.png';
				break;
			case 'audio' :
				$fileicon = 'file_audio.png';
				break;
			case 'image' :
			default :
				$fileicon = 'file_image.png';
				break;
		}

		ob_start();
		?>
		<link href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/ckbrowse.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/ckbrowse.js"></script>
		<div id="ckbrowse" class="clearfix">
		<?php if ($type != 'folder') { ?>
		<div id="ckfolderupload">
			<div class="inner">
				<div class="upload">
					<h2 class="uploadinstructions"><?php echo __('Drop files here to upload', 'slideshow-ck'); ?></h2>
					<p><?php echo __( 'or Select Files' ); ?></p><input id="tck_file_upload" type="file" class="" />
				</div>
				<?php
				// $max_upload_size = wp_max_upload_size();
				// if ( ! $max_upload_size ) {
					// $max_upload_size = 0;
				// }
				?>

				<p class="max-upload-size"><?php
					// printf( __( 'Maximum upload file size: %s.' ), esc_html( size_format( $max_upload_size ) ) );
				?></p>
			</div>
		</div>
		<?php } ?>
		<div id="ckfoldertreelist">
		<?php if ($type != 'folder') { ?>
		<?php } ?>
		<?php
		if ($type == 'folder') {
			$onclick = 'ckGetFolder(\'' . $input->get('fieldid', '', 'string') . '\',$ck(this).attr(\'data-path\'))';
		} else {
			$onclick = 'ckShowFiles(this)';
		}
		$lastitem = 0;
		foreach ($items as $i => $folder) {
			$submenustyle = '';
			$folderclass = '';
			if ($folder->level == 1) {
				$submenustyle = 'display: block;';
				$folderclass = 'ckcurrent';
			}
			?>
			<div class="ckfoldertree <?php echo $folderclass ?> <?php echo ($folder->deeper ? 'parent' : '') ?> <?php echo (count($folder->files) ? 'hasfiles' : '') ?>" data-level="<?php echo $folder->level ?>" data-path="<?php echo utf8_encode($folder->basepath) ?>">
				<?php if ($folder->level > 1) { ?><div class="ckfoldertreetoggler" onclick="ckToggleTreeSub(this)"></div><?php } ?>
				<div class="ckfoldertreename" data-path="<?php echo utf8_encode($folder->basepath) ?>" onclick="<?php echo $onclick  ?>"><img src="<?php echo $imagespath ?>folder.png" /><?php echo utf8_encode($folder->name); ?></div>
				<div class="ckfoldertreecount"><?php echo count($folder->files); ?></div>
				<?php if ($type != 'folder') { ?>
				<div class="ckfoldertreefiles">
				<?php foreach ($folder->files as $j => $file) { 
				?>
					<div class="ckfoldertreefile ckwait" data-type="<?php echo $type ?>" onclick="ckSelectFile(this)" data-path="<?php echo utf8_encode($folder->basepath) ?>" data-filename="<?php echo utf8_encode($file) ?>"><div class="ckfakeimage" data-src="<?php echo site_url() . '/' . utf8_encode($folder->basepath) . '/' . utf8_encode($file) ?>" title="<?php echo utf8_encode($file); ?>" ></div></div>
				<?php } ?>
				</div>
				<?php } ?>

			<?php
				if ($folder->deeper)
				{
					echo '<div class="cksubfolder" style="' . $submenustyle . '">';
				}
				elseif ($folder->shallower)
				{
					// The next item is shallower.
					echo '</div>'; // close ckfoldertree
					echo str_repeat('</div></div>', $folder->level_diff); // close cksubfolder + ckfoldertree
				} 
				else
				{
					// The next item is on the same level.
					echo '</div>'; // close ckfoldertree
				}
		}

		?>
		</div>
		<div id="ckfoldertreepreview">
			<div class="inner">
				<?php if ($type == 'image') { ?>
				<div id="ckfoldertreepreviewimage">
				</div>
				<?php } ?>
			</div>
		</div>

		</div>
		<script>
		var $ck = window.$ck || jQuery.noConflict();
		var URIROOT = window.URIROOT || '<?php echo site_url() ?>';
//		var cktoken = '<?php //echo JSession::getFormToken() ?>';
		function ckSelectFile(btn) {
			try {
				if (typeof(window.parent.<?php echo $returnFunc ?>) != 'undefined') {
					window.parent.<?php echo $returnFunc ?>($ck(btn).attr('data-path') + '/' + $ck(btn).attr('data-filename'), '<?php echo $returnField ?>');
					if (typeof(window.parent.CKBox) != 'undefined') window.parent.CKBox.close();
				} else {
					alert('ERROR : The function <?php echo $returnFunc ?> is missing in the parent window. Please contact the developer');
				}
			}
			catch(err) {
				alert('ERROR : ' + err.message + '. Please contact the developper.');
			}
		}
		</script>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/*
	 * Get a list of folders and files 
	 */
	private static function getItemsList($type = 'image') {
		$input = self::$input;

		$type = $input->get('type', $type, 'string');

		switch ($type) {
			case 'video' :
				$filetypes = array('.mp4', '.ogv', '.webm', '.MP4', '.OGV', '.WEBM');
				break;
			case 'audio' :
				$filetypes = array('.mp3', '.ogg', '.MP3', '.OGG');
				break;
			case 'image' :
			default :
				$filetypes = array('.jpg', '.jpeg', '.png', '.gif', '.tiff', '.JPG', '.JPEG', '.PNG', '.GIF', '.TIFF', '.ico');
				break;
		}

		$folder = $input->get('folder', WP_CONTENT_DIR. '/uploads', 'string');
		$tree = new \stdClass();

		// list the files in the root folder
		self::getImagesInFolder( $folder, $tree, implode('|', $filetypes), 1);

		// look for all folder and files
		self::getSubfolder($folder, $tree, implode('|', $filetypes), 2);
		$tree = self::prepareList($tree);

		return $tree;
	}

	/* 
	 * List the subfolders and files according to the filter
	 */
	private static function getSubfolder($folder, &$tree, $filter, $level) {
		$folders = CKFolder::folders($folder, '.', $recurse = false, $fullpath = true);
		natcasesort($folders);

		if (! count($folders)) return;

		foreach ($folders as $f) {
			// list all authorized files from the folder
			self::getImagesInFolder($f, $tree, $filter, $level);

			// recursive loop
			self::getSubfolder($f, $tree, $filter, $level+1);
		}
		return;
	}
	
	/* 
	 * List the subfolders and files according to the filter
	 */
	private static function getImagesInFolder($f, &$tree, $filter, $level) {

			// list all authorized files from the folder
			$files = CKFolder::files($f, $filter, $recurse = false, $fullpath = false);
			natcasesort($files);
			$fName = CKFile::makeSafe(str_replace(ABSPATH, '', $f));
			$tree->$fName = new \stdClass();
			$name = explode('/', $f);
			$name = end($name);
			$tree->$fName->name = $name;
			$tree->$fName->path = $f;
			$tree->$fName->files = $files;
			$tree->$fName->level = $level;
		}

	/* 
	 * Set level diff and check for depth
	 */
	private static function prepareList($items) {
		if (! $items) return $items;

		$lastitem = 0;
		foreach ($items as $i => $item)
		{
			self::prepareItem($item);

			if ($item->level != 0) {
				if (isset($items->$lastitem))
				{
					$items->$lastitem->deeper     = ($item->level > $items->$lastitem->level);
					$items->$lastitem->shallower  = ($item->level < $items->$lastitem->level);
					$items->$lastitem->level_diff = ($items->$lastitem->level - $item->level);
				}
			}
			$lastitem = $i;

			
		}

		// for the last item
		if (isset($items->$lastitem))
		{
			$items->$lastitem->deeper     = (1 > $items->$lastitem->level);
			$items->$lastitem->shallower  = (1 < $items->$lastitem->level);
			$items->$lastitem->level_diff = ($item->level - 1);
		}

		return $items;
	}

	/* 
	 * Set the default values
	 */
	private static function prepareItem(&$item) {
		$item->deeper     = false;
		$item->shallower  = false;
		$item->level_diff = 0;
		$item->basepath = str_replace('\\', '/', $item->path);
		$abspath = str_replace('\\', '/', ABSPATH);
		$item->basepath = str_replace($abspath, '', $item->basepath);
		$item->basepath = trim($item->basepath, '/');
	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public static function ajaxAddPicture() {
		// check why it does not work
//		if (! JSession::checkToken('get')) {
//			$msg = __('JINVALID_TOKEN');
//			echo '{"error" : "' . $msg . '"}';
//			exit;
//		}

		$input = self::$input;
		$file = $input->files->get('file', '', 'array');
		$imgpath = '/' . trim($input->get('path', '', 'string'), '/') . '/';

		if (!is_array($file)) {
			$msg = __('No file received', 'slideshow-ck');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}

		$filename = CKFile::makeSafe($file['name']);

		//Set up the source and destination of the file
		$src = $file['tmp_name'];

		// check if the file exists
		if (!$src || !CKFile::exists($src)) {
			$msg = __('File does not exists', 'slideshow-ck');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}

		// check if folder exists, if not then create it
		if (!CKFolder::exists(ABSPATH . $imgpath)) {
			if (!CKFolder::create(ABSPATH . $imgpath)) {
				$msg = __('Unable to create the folder', 'slideshow-ck') . ' : ' . $imgpath;
				echo '{"error" : "' . $msg . '"}';
				exit;
			}
		}

		// write the file
		if (! CKFile::copy($src, ABSPATH . $imgpath . $filename)) {
			$msg = __('Unable to write the file', 'slideshow-ck');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}
		echo '{"img" : "' . $imgpath . $filename . '", "filename" : "' . $filename . '"}';
		exit;
	}
}
