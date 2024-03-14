<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments media model.
 *
 * @since 1.7
 */
class VikAppointmentsModelMedia extends JModelVAP
{
	/**
	 * An array containing all the loaded images.
	 * 
	 * @var   array
	 * @since 1.7.2
	 */
	protected static $imagesCache = [];

	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed  $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                       If not set the instance property value is used.
	 *
	 * @return  mixed  The record object on success, null otherwise.
	 * 
	 * @since 	1.7.2
	 */
	public function getItem($pk, $new = false)
	{
		$id = $pk;

		if (isset(static::$imagesCache[$id]))
		{
			// return cached data
			return static::$imagesCache[$id];
		}

		if (is_string($pk))
		{
			// use reverse lookup
			$pk = ['image' => $pk];
		}

		// fetch image details
		static::$imagesCache[$id] = parent::getItem($pk, $new);

		return static::$imagesCache[$id];
	}

	/**
	 * Entirely rewrite save method because the media files
	 * do not use database tables.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		try
		{
			/**
			 * Trigger event to allow the plugins to bind the object that
			 * is going to be saved.
			 *
			 * @param 	mixed 	   &$data  The array/object to bind.
			 * @param 	JModelVAP  $model  The model instance.
			 *
			 * @return 	boolean    False to abort saving.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort
			 *                     the saving process and return a readable message.
			 *
			 * @since 	1.7.2
			 */
			if ($dispatcher->false('onBeforeSaveMediaFile', array(&$data, $this)))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			return false;
		}

		// attempt to bind the source to the instance
		if (!$this->bind($data))
		{
			return false;
		}

		// run any sanity checks on the instance and verify that it is ready for storage
		if (!$this->check())
		{
			return false;
		}

		// attempt to store the media file
		if (!$this->store())
		{
			return false;
		}

		/**
		 * Trigger event to allow the plugins to make something after
		 * saving a media file.
		 *
		 * @param 	array 	   $args   The saved record.
		 * @param 	JModelVAP  $model  The model instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.2
		 */
		$dispatcher->trigger('onAfterSaveMediaFile', array($this->getData(), $this));

		return $this->id;
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src  An associative array or object to bind.
	 *
	 * @return  boolean  True on success.
	 */
	protected function bind($src)
	{
		$src = (array) $src;

		// register media file name
		if (!empty($src['id']))
		{
			$this->id = $src['id'];
		}
		else
		{
			$this->id = null;
		}

		$this->path = null;

		// register uploaded file, if any
		if (!empty($src['file']))
		{
			$this->file = $src['file'];

			// look for a custom path, if any
			if (!empty($src['path']))
			{
				$this->path = $src['path'];

				// make sure the path is an existing dir
				if (!is_dir($this->path))
				{
					// nope, maybe we have a base64 string
					$this->path = base64_decode($this->path);

					// try again with decoded string
					$this->path = is_dir($this->path) ? $this->path : null;
				}
			}
		}
		else
		{
			$this->file = null;
		}

		// register new name
		if (!empty($src['name']))
		{
			// use specified name
			$this->name = $src['name'];

			// in case the name doesn't contain the extension type, retrieve it if possible
			if ($this->id && !preg_match("/\.(png|jpe?g|gif|bmp)$/", $this->name))
			{
				// get file properties
				$prop = AppointmentsHelper::getFileProperties(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $this->id);
				// append extension
				$this->name .= $prop['file_ext'];
			}
		}
		else if ($this->file && !empty($this->file['name']))
		{
			// use name of uploaded file
			$this->name = $this->file['name'];
		}
		else if ($this->id)
		{
			// use same file name
			$this->name = $this->id;
		}
		else
		{
			// no specified name
			$this->name = '';
		}

		// register action
		$this->action = isset($src['action']) ? (int) $src['action'] : 0;

		// register file properties
		$this->properties = array(
			'oriwres'   => @$src['oriwres'],
			'orihres'   => @$src['orihres'],
			'smallwres' => @$src['smallwres'],
			'smallhres' => @$src['smallhres'],
			'isresize'  => @$src['isresize'],
		);

		// register media attributes
		$this->media = [];

		if (isset($src['alt']))
		{
			$this->media['alt'] = $src['alt'];
		}

		if (isset($src['title']))
		{
			$this->media['title'] = $src['title'];
		}

		if (isset($src['caption']))
		{
			$this->media['caption'] = $src['caption'];
		}

		return true;
	}

	/**
	 * Method to perform sanity checks to ensure they are safe to store.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored.
	 */
	public function check()
	{
		// make sure a file was specified when replacing
		if ((int) $this->action > 0 && !$this->file)
		{
			// register error message
			$this->setError(JText::sprintf('VAP_INVALID_REQ_FIELD', JText::translate('VAPCONFIGFILETYPEERROR')));

			// file not selected
			return false;
		}

		return true;
	}

	/**
	 * Method to upload/update a media in the server filesystem.
	 *
	 * @return  boolean  True on success.
	 */
	public function store()
	{
		// save media properties first of all
		VikAppointments::storeMediaProperties($this->properties);

		// DO NOT INVOKE PARENT

		// update existing
		if ($this->id)
		{
			// overwrite only in case the name of the uploaded file
			// is the same of the existing one
			$overwrite = $this->id == $this->name;

			// replace original image
			if ($this->action == 1)
			{
				// upload original image
				$resp = VikAppointments::uploadFile($this->file, VAPMEDIA . DIRECTORY_SEPARATOR, 'jpeg,jpg,png,gif,bmp', $overwrite);

				if (!$resp->status)
				{
					// unable to upload the image, abort
					if ($resp->errno == 1)
					{
						$this->setError(JText::sprintf('VAPCONFIGFILETYPEERRORWHO', $resp->mimeType));
					}
					else
					{
						$this->setError(JText::translate('VAPCONFIGUPLOADERROR'));
					}

					return false;
				}

				// rename media file to original one
				rename(
					VAPMEDIA . DIRECTORY_SEPARATOR . $resp->name,
					VAPMEDIA . DIRECTORY_SEPARATOR . $this->id
				);
			}
			// replace thumbnail image
			else if ($this->action == 2)
			{
				// upload thumbnail image
				$resp = VikAppointments::uploadFile($this->file, VAPMEDIA_SMALL . DIRECTORY_SEPARATOR, 'jpeg,jpg,png,gif,bmp', $overwrite);

				if (!$resp->status)
				{
					// unable to upload the image, abort
					if ($resp->errno == 1)
					{
						$this->setError(JText::sprintf('VAPCONFIGFILETYPEERRORWHO', $resp->mimeType));
					}
					else
					{
						$this->setError(JText::translate('VAPCONFIGUPLOADERROR'));
					}

					return false;
				}

				// rename media file to original one
				rename(
					VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $resp->name,
					VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $this->id
				);
			}
			// replace both original and thumbnail images
			else if ($this->action == 3)
			{
				$resp = VikAppointments::uploadMedia($this->file, $settings = null, $overwrite);

				if (!$resp->status)
				{
					// unable to upload the image, abort
					if ($resp->errno == 1)
					{
						$this->setError(JText::sprintf('VAPCONFIGFILETYPEERRORWHO', $resp->mimeType));
					}
					else
					{
						$this->setError(JText::translate('VAPCONFIGUPLOADERROR'));
					}

					return false;
				}

				// rename original media file to previous name
				rename(
					VAPMEDIA . DIRECTORY_SEPARATOR . $resp->name,
					VAPMEDIA . DIRECTORY_SEPARATOR . $this->id
				);

				// rename thumbnail media file to previous name
				rename(
					VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $resp->name,
					VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $this->id
				);
			}

			// finally, rename the file to the specified name if different
			if ($this->id != $this->name)
			{
				// make sure the new destination is empty
				if (is_file(VAPMEDIA . DIRECTORY_SEPARATOR . $this->name))
				{
					// specified path is already occupied by a different file, raise error
					$this->setError(JText::sprintf('VAPMEDIARENERR', $this->name));

					return false;
				}

				// rename media file and update all the records that were using it
				if ($this->rename($this->id, $this->name))
				{
					// update primary key
					$this->id = $this->name;
				}
			}
		}
		// upload new
		else if ($this->file)
		{
			if ($this->path)
			{
				if (!$this->isPathAllowed($this->path))
				{
					// path not allowed for uploads
					$this->setError(sprintf('Path [%s] does not support uploads', $this->path));
					return false;
				}

				// get accepted files filter
				$filter = $this->getFileAllowedRegex();

				// upload the image into the given path
				$resp = VikAppointments::uploadFile($this->file, $this->path, $filter);
			}
			else
			{
				// upload original image and create thumbnail (do not overwrite existing)
				$resp = VikAppointments::uploadMedia($this->file);
			}

			if (!$resp->status)
			{
				// unable to upload the image, abort
				if ($resp->errno == 1)
				{
					$this->setError(JText::sprintf('VAPCONFIGFILETYPEERRORWHO', $resp->mimeType));
				}
				else
				{
					$this->setError(JText::translate('VAPCONFIGUPLOADERROR'));
				}

				return false;
			}

			// inject name of uploaded file within the table as primary key
			$this->id = $resp->name;
			// register file path
			$this->file = $resp->path;
		}

		// check if we should proceed with the update of the media attributes
		if (isset($this->media))
		{
			// save attributes only in case there is at least a non-empty value or if
			// we need to update an existing media (just to unset the previous data)
			if (array_filter($this->media) || $this->getItem($this->id))
			{
				// inject media name for a reverse lookup
				$this->media['image'] = $this->id;

				// save media attributes
				$this->getTable('media')->save($this->media);
			}
		}

		return true;
	}

	/**
	 * Returns the table properties, useful to retrieve the information
	 * that have been registered while saving a record.
	 *
	 * @return 	array
	 */
	public function getData()
	{
		// return all public class properties
		return $this->getProperties();
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   mixed    $ids   Either the record ID or a list of records.
	 * @param 	mixed 	 $path  An optional path from which the file should be
	 * 							deleted. If not specified, the default media
	 * 							folders will be used.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($ids = null, $path = null)
	{
		if (!$ids)
		{
			return false;
		}

		$ids = (array) $ids;

		$dispatcher = VAPFactory::getEventDispatcher();

		try
		{
			/**
			 * Trigger event to allow the plugins to make something before
			 * deleting one or more media files.
			 *
			 * @param 	mixed 	   $ids    Either the record ID or a list of records.
			 * @param 	mixed 	   $path   An optional path from which the file should be deleted.
			 * @param 	JModelVAP  $model  The model instance.
			 *
			 * @return 	boolean    False to abort delete.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort
			 *                     the delete process and return a readable message.
			 *
			 * @since 	1.7
			 */
			if ($dispatcher->false('onBeforeDeleteMediaFile', array($ids, $path, $this)))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			return false;
		}

		$res = false;

		// check if the given path is a directory
		if ($path && !is_dir($path))
		{
			// try to decode from base 64
			$path = base64_decode($path);

			if (!is_dir($path))
			{
				// invalid path
				$path = null;
			}
		}

		$items_to_delete = [];

		foreach ($ids as $id)
		{
			// make sure we are deleting an image and it is not protected
			if ($this->isFileAllowed($id))
			{
				// check if we should delete the specified file inclusive of path
				if (is_file($id))
				{
					// make sure the path of the file is safe
					if ($this->isPathAllowed($id))
					{
						$res = unlink($id) || $res;
					}
				}
				// check if we should delete the file from the given path
				else if ($path || is_file($id))
				{
					// make sure the file exists and the path in which we are working is safe
					if ($this->isPathAllowed($path) && is_file($path . DIRECTORY_SEPARATOR . $id))
					{
						$res = unlink($path . DIRECTORY_SEPARATOR . $id) || $res;
					}
				}
				else
				{
					// try to delete original file, if exists
					if (is_file(VAPMEDIA . DIRECTORY_SEPARATOR . $id))
					{
						$res = unlink(VAPMEDIA . DIRECTORY_SEPARATOR . $id) || $res;
					}

					// try to delete thumbnail file, if exists
					if (is_file(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $id))
					{
						$res = unlink(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $id) || $res;
					}

					// check whether the deleted media owns some attributes
					if ($item = $this->getItem($id))
					{
						$items_to_delete[] = $item->id;
					}
				}
			}
		}

		if ($res)
		{
			// trigger a separated event for each ID in the list
			foreach ($ids as $id)
			{
				/**
				 * Trigger event to allow the plugins to make something after
				 * deleting one or more media files.
				 *
				 * @param 	string 	   $id     The path of the deleted media file.
				 * @param 	mixed 	   $path   An optional path from which the file has been deleted.
				 * @param 	JModelVAP  $model  The model instance.
				 *
				 * @return 	void
				 *
				 * @since 	1.7.2
				 */
				$dispatcher->trigger('onAfterDeleteMediaFile', array($id, $path, $this));
			}

			// try to delete the media record
			$this->getTable()->delete($items_to_delete);

			$dbo = JFactory::getDbo();

			// load any assigned translation
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id'))
				->from($dbo->qn('#__vikappointments_lang_media'))
				->where($dbo->qn('image') . ' IN (' . implode(',', array_map([$dbo, 'q'], $ids)) . ')' );

			$dbo->setQuery($q);

			if ($lang_ids = $dbo->loadColumn())
			{
				// get translation model
				$model = JModelVAP::getInstance('langmedia');
				// delete assigned translations
				$model->delete($lang_ids);
			}
		}

		return $res;
	}

	/**
	 * Renames the specified media file with the new one.
	 *
	 * @param 	string 	 $prev  The previous file name.
	 * @param 	string 	 $new   The new file name.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected function rename($prev, $new)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		try
		{
			/**
			 * Trigger event to allow the plugins to make something before
			 * renaming one or more media files.
			 *
			 * @param 	string 	   &$new   The new file name.
			 * @param 	string 	   $prev   The previous file name.
			 * @param 	JModelVAP  $model  The model instance.
			 *
			 * @return 	boolean    False to abort rename.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort
			 *                     the rename process and return a readable message.
			 *
			 * @since 	1.7.2
			 */
			if ($dispatcher->false('onBeforeRenameMediaFile', array(&$new, $prev, $this)))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			return false;
		}

		// rename original media file and its thumbnail
		$renamed = rename(VAPMEDIA . DIRECTORY_SEPARATOR . $prev, VAPMEDIA . DIRECTORY_SEPARATOR . $new)
			&& rename(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $prev, VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $new);

		if (!$renamed)
		{
			// something went wrong while renaming the files
			return false;
		}

		$dbo = JFactory::getDbo();

		// check whether the renamed file owns a media record
		$item = $this->getItem($prev);

		if ($item)
		{
			// rename image stored within the media record too
			$this->getTable()->save([
				'id'    => $item->id,
				'image' => $new,
			]);

			// update all the related translations too
			$q = $dbo->getQuery(true)
				->update($dbo->qn('#__vikappointments_lang_media'))
				->set($dbo->qn('image') . ' = ' . $dbo->q($new))
				->where($dbo->qn('image') . ' = ' . $dbo->q($prev));

			$dbo->setQuery($q);
			$dbo->execute();
		}

		// relationship between database tables and columns
		// containing media names
		$lookup = array(
			'service'  => 'image',
			'employee' => 'image',
			'option'   => 'image',
		);

		// mass update all the specified database tables
		foreach ($lookup as $table => $column)
		{
			$q = $dbo->getQuery(true)
				->update($dbo->qn('#__vikappointments_' . $table))
				->set($dbo->qn($column) . ' = ' . $dbo->q($new))
				->where($dbo->qn($column) . ' = ' . $dbo->q($prev));

			$dbo->setQuery($q);
			$dbo->execute();
		}

		/**
		 * @todo fetch all database tables that might store encoded images
		 */

		// try to rename the company logo stored within the configuration
		$config = VAPFactory::getConfig();
		$logo = $config->get('companylogo');

		if ($logo == $prev)
		{
			$config->set('companylogo', $new);
		}

		/**
		 * Trigger event to allow the plugins to make something after
		 * renaming one or more media files.
		 *
		 * @param 	string 	   $new    The new file name.
		 * @param 	string 	   $prev   The previous file name.
		 * @param 	JModelVAP  $model  The model instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.2
		 */
		$dispatcher->trigger('onAfterRenameMediaFile', array($new, $prev, $this));

		return true;
	}

	/**
	 * Checks whether the specified file is allowed.
	 * This only checks the extension file type.
	 *
	 * @param 	string 	 $file 	The file name/path.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function isFileAllowed($file)
	{
		/**
		 * Trigger event to allow the plugins to extend the validation of
		 * a specific file, in order to support the upload of file types
		 * that are not supported by default.
		 *
		 * @param 	string   $file   The file path/name to check.
		 * @param 	JModel   $model  The current media model instance.
		 *
		 * @return 	boolean  True to always allow the file upload.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->true('onCheckFileAllowed', array($file, $this)))
		{
			// validated by a plugin
			return true;
		}

		// validate file type
		return preg_match($this->getFileAllowedRegex(), $file);
	}

	/**
	 * Checks whether the specified path is allowed.
	 * Ensures that we are handling a safe directory.
	 *
	 * @param 	string 	 $file 	The file path.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public function isPathAllowed($file)
	{
		/**
		 * Trigger event to allow the plugins to extend the validation of
		 * a specific path, in order to support the upload on folders that
		 * are not supported by default.
		 *
		 * @param 	string   $file   The file path to check.
		 * @param 	JModel   $model  The current media model instance.
		 *
		 * @return 	boolean  True to always allow the file upload.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->true('onCheckPathAllowed', array($file, $this)))
		{
			// validated by a plugin
			return true;
		}

		// validate path against the list of default folders
		foreach ($this->allowedPaths as $path)
		{
			// check whether the given file contains the current path
			if ($path && strpos($file, $path) !== false)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the regex used to check whether a file type if accepted or not.
	 *
	 * @param   mixed   $path  An optional group to take only the files that
	 *                         belong to the specified category (eg. image or video).
	 *                         Leave empty to take all the groups.
	 *
	 * @return 	string
	 */
	public function getFileAllowedRegex($sub = null)
	{
		$sub = (array) $sub;

		$regex = array();

		foreach ($this->allowedFiles as $group => $types)
		{
			// check whether we should include this group
			if (!$sub || in_array($group, $sub))
			{
				// merge types into regex list
				$regex = array_merge($regex, $types);
			}
		}

		// create regex to check whether the specified file is allowed
		$regex = implode('|', $regex);
		// escape reserved chars that may be used by mime-types
		$regex = preg_replace("/[\/]/", '\\\\$0', $regex);

		if ($regex)
		{
			// returned given regex
			return "/(^|[.\/])($regex)$/i";
		}
		
		// accept everything
		return "/./";
	}

	/**
	 * Returns an identifier of the specified media type.
	 * In example, in case of a .zip file, it will belong
	 * to the "archive" category.
	 *
	 * @param 	string 	$file  The file path/name.
	 *
	 * @return 	mixed   The media type on success, false otherwise.
	 */
	public function detectMediaType($file)
	{
		// iterate all supported groups
		foreach ($this->allowedFiles as $type => $list)
		{
			// check whether the file is supported by this group
			if (preg_match($this->getFileAllowedRegex($type), $file))
			{
				// found, return matching type
				return $type;
			}
		}

		/**
		 * Trigger event to allow external plugins to register their own media types
		 * or to extend the existing ones.
		 *
		 * @param 	string  $file   The file path/name to check.
		 * @param 	JModel  $model  The current media model instance.
		 *
		 * @return  string  A custom detected type.
		 *
		 * @since 	1.7
		 */
		$type = VAPFactory::getEventDispatcher()->triggerOnce('onDetectMediaType', array($file, $this));

		if ($type)
		{
			// use type fetched by a plugin
			return $type;
		}

		// unable to detect media type
		return false;
	}

	/**
	 * Renders the media file according to its type.
	 *
	 * @param 	mixed 	$file  Either a file path or an array of file properties.
	 *
	 * @return 	string  The resulting HTML.
	 */
	public function renderMedia($file)
	{
		// check if we have a file path
		if (is_string($file))
		{
			// get file properties
			$file = AppointmentsHelper::getFileProperties($file);

			if (!$file)
			{
				// file not found
				return '';
			}
		}

		// detect media type from file name
		$mediaType = $this->detectMediaType($file['name']);

		if (!$mediaType)
		{
			// media type not found, use "binary" by default
			$mediaType = 'binary';
		}

		// attempt to render the field
		$html = JLayoutHelper::render('mediamanager.types.' . $mediaType, $file);

		if (!$html)
		{
			// not a standard media file, which might be supported by
			// a third party plugin, so dispatch an event

			/**
			 * Trigger event to allow external plugins to implement a layout for
			 * their own media types.
			 *
			 * @param 	array   $file   An array of file properties.
			 * @param 	JModel  $model  The current media model instance.
			 *
			 * @return  string  The resulting HTML.
			 *
			 * @since 	1.7
			 */
			$html = VAPFactory::getEventDispatcher()->triggerOnce('onRenderMedia', array($file, $this));
		}

		return $html;
	}

	/**
	 * Method to get the model name
	 *
	 * The model name. By default parsed using the classname or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @return  string  The name of the model
	 *
	 * @since   1.7.2
	 */
	public function getName()
	{
		/**
		 * Override this method in order to prevent Joomla from using
		 * the name property to fetch the model name, which might be 
		 * already occupied by the image name.
		 */

		return 'media';
	}

	/**
	 * Defines a list of allowed folders.
	 *
	 * @var array
	 */
	public $allowedPaths = array(
		VAPMEDIA,
		VAPMEDIA_SMALL,
		VAPCUSTOMERS_UPLOADS,
		VAPCUSTOMERS_AVATAR,
		VAPCUSTOMERS_DOCUMENTS,
		VAPINVOICE,
		VAPMAIL_ATTACHMENTS,
	);

	/**
	 * Defines a list of accepted file types.
	 * The files extensions are regex compliant.
	 *
	 * @var array
	 */
	public $allowedFiles = array(
		'image' => array(
			'a?png',
			'bmp',
			'gif',
			'ico',
			'jpe?g',
			'svg',
		),
		'video' => array(
			'mp4',
			'mov',
			'ogm',
			'webm',
			'3gp',
			'asf',
			'avi',
			'divx',
			'flv',
			'mkv',
			'mpe?g',
			'wmv',
			'xvid',
		),
		'audio' => array(
			'aac',
			'm4a',
			'mp3',
			'opus',
			'(x-)?wave?',
			'ac3',
			'aiff',
			'flac',
			'midi?',
			'wma',
			'ogg',
		),
		'archive' => array(
			'zip',
			'tar',
			'rar',
			'gz',
			'bzip2',
		),
		'document' => array(
			'pdf',
			'docx?',
			'rtf',
			'odt',
			'pages',
		),
		'spreadsheet' => array(
			'xlsx?',
			'csv',
			'ods',
			'numbers',
		),
		'presentation' => array(
			'ppsx?',
			'odp',
			'key',
		),
		'text' => array(
			'txt',
			'md',
			'markdown',
		),
	);
}
