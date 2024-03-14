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
 * VikAppointments user note model.
 *
 * @since 1.7
 */
class VikAppointmentsModelUsernote extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		// check if we are updating an existing 
		if (isset($data['attachments']) && !empty($data['id']))
		{
			// get previous attachments
			$prevAttachments = $this->getItem($data['id'])->attachments;

			if (!is_array($data['attachments']))
			{
				// decode JSON
				$data['attachments'] = json_decode($data['attachments']);
			}

			// fetch items that differ between the previous attachments
			// and the one we are updating
			$diff = array_diff($prevAttachments, $data['attachments']);

			if ($diff)
			{
				// delete all missing files
				JModelVAP::getInstance('media')->delete($diff);
			}

			// NOTE: in case the user uploads and delete a file during the
			// creation of a new element, the deleted file won't be unlinked
			// from the file system, because the process will never enter
			// here, as we are creating a new record.
		}

		if (isset($data['tags']))
		{
			// commit tags
			$data['tags'] = JModelVAP::getInstance('tag')->writeTags($data['tags'], 'usernotes');
		}

		// attempt to save the record
		$id = parent::save($data);

		if (!$id)
		{
			return false;
		}

		if (!empty($data['notifycust']))
		{
			// send e-mail notification to customer
			$this->sendEmailNotification($id);
		}

		return $id;
	}

	/**
	 * Saves a DRAFT.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function saveDraft($data)
	{
		// split paragraphs
		$chunks = preg_split("/\R{2,2}/", @$data['content']);

		if (count($chunks) > 1)
		{
			// extract title from chunks (first paragraph)
			$data['title'] = array_shift($chunks);

			// title has a maximum length of 128 chars, make
			// sure the specified paragraph doesn't exceed it
			if (strlen((string) $data['title']) > 128)
			{
				// re-push the title within the list of paragraphs
				array_unshift($chunks, $data['title']);
				// unset title
				$data['title'] = '';
			}

			// merge remaining contents
			$data['content'] = implode("\n", array_map(function($p)
			{
				return '<p>' . $p . '</p>';
			}, $chunks));
		}

		return $this->save($data);
	}

	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                         If not set the instance property value is used.
	 * @param   boolean  $new  True to return an empty object if missing.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($pk, $new = false)
	{
		// load item through parent
		$item = parent::getItem($pk, $new);

		if ($item)
		{
			// decode encoded parameters
			$item->attachments = $item->attachments ? (array) json_decode($item->attachments, true) : array();

			// add base path to attachments list
			$item->attachments = array_map(function($file)
			{
				return VAPCUSTOMERS_DOCUMENTS . DIRECTORY_SEPARATOR . $file;
			}, $item->attachments);
		}

		return $item;
	}

	/**
	 * Sends an e-mail notification to target of the user note.
	 *
	 * @param 	integer  $id  The user note ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function sendEmailNotification($id)
	{
		// load note details
		$item = $this->getItem((int) $id);

		// make sure the note is public
		if (!$item->status)
		{
			$this->setError('Cannot notify private notes');
			return false;
		}

		if ($item->group == 'appointments')
		{
			// send e-mail to the owner of the appointment
			return JModelVAP::getInstance('reservation')->sendEmailNotification($item->id_parent);
		}
		else if ($item->id_user)
		{
			// load customer details
			$customer = VikAppointments::getCustomer($item->id_user);

			// make sure the customer exists
			if (!$customer)
			{
				$this->setError('Customer not found');
				return false;
			}

			// make sure the user owns an e-mail address
			if (!$customer->billing_mail)
			{
				// billing e-mail not found, try to use the account e-mail
				$customer->billing_mail = $customer->user->email;

				if (!$customer->billing_mail)
				{
					$this->setError('Customer did not specify an e-mail address');
					return false;	
				}
			}

			$sendername = VAPFactory::getConfig()->get('agencyname');

			// fetch e-mail subject and content
			$subject = JText::sprintf('VAPUSERNOTEMAILSUBJECT', $sendername, $item->title);
			// append user note title and subject
			$content = $item->content;

			// import mail factory to implement subject/content extendability
			VAPLoader::import('libraries.mail.factory');

			// trigger hook to allow subject manipulation: onBeforeSendMailSubjectUsernote
			if (!VAPMailFactory::letPluginsManipulateMail('usernote', 'subject', $subject, $item))
			{
				// e-mail sending prevented
				return false;
			}

			// trigger hook to allow content manipulation: onBeforeSendMailContentUsernote
			if (!VAPMailFactory::letPluginsManipulateMail('usernote', 'content', $content, $item))
			{
				// e-mail sending prevented
				return false;
			}

			// send e-mail
			return VAPApplication::getInstance()->sendMail(
				VikAppointments::getSenderMail(),
				$sendername,
				$customer->billing_mail,
				$reply = null,
				$subject,
				$content,
				$item->attachments,
				$is_html = true
			);
		}

		// invalid user note
		return false;
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		if (!$ids)
		{
			return false;
		}

		$files = array();

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		$dbo = JFactory::getDbo();

		// load all attached files
		$q = $dbo->getQuery(true)
			->select($dbo->qn('attachments'))
			->from($dbo->qn('#__vikappointments_user_notes'))
			->where($dbo->qn('id') . ' IN (' . implode(',', $ids) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadColumn() as $json)
		{
			$files = array_merge($files, $json ? (array) json_decode($json, true) : array());
		}

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$media = JModelVAP::getInstance('media');

		// delete all files
		foreach ($files as $file)
		{
			$media->delete(VAPCUSTOMERS_DOCUMENTS . DIRECTORY_SEPARATOR . $file);
		}

		// resursively scan the parent folders of the deleted files
		// and remove all the directories without children
		$this->deleteEmptyFolders($files);

		return true;
	}

	/**
	 * Helper method used to clean those directories that do not
	 * own any children.
	 *
	 * @param 	array  $paths  A list of deleted files.
	 *
	 * @return 	void
	 */
	protected function deleteEmptyFolders($paths)
	{
		// get all parent directories
		$paths = array_unique(array_map(function($elem)
		{
			return dirname($elem);
		}, $paths));

		jimport('joomla.filesystem.folder');

		$deleted = false;

		// iterate all paths and checks whether they have any files
		foreach ($paths as $path)
		{
			if ($path == '.' || $path == DIRECTORY_SEPARATOR || !$path)
			{
				// we reached the root, go ahead
				continue;
			}

			// prepend base path
			$path = VAPCUSTOMERS_DOCUMENTS . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);

			// make sure the directory exists
			if (is_dir($path))
			{
				/**
				 * Check whether the directory is empty.
				 * As pointed by PHP doc, the valid() method seeks at the
				 * beginning of the list and, in case there are no files,
				 * it'll immediately return false.
				 *
				 * @link https://www.php.net/manual/en/directoryiterator.valid.php
				 */
				$iterator = new FilesystemIterator($path);
				if (!$iterator->valid())
				{
					// directory is empty, delete folder
					JFolder::delete($path);

					$deleted = true;
				}
			}
		}

		if ($deleted)
		{
			// recursively call this method only in case a folder has been deleted
			$this->deleteEmptyFolders($paths);
		}
	}

	/**
	 * Returns the attachment path assigned to the user note.
	 * In case the folder doesn't exist, a new one will be created.
	 *
	 * @param 	mixed   $note     Either a user note ID or an object.
	 * @param 	array   $options  An array of options.
	 * 
	 * @return 	string  The resulting upload path.
	 *
	 * @throws  Exception
	 */
	public function getUploadsPath($note = null, array $options = array())
	{
		if (!is_object($note))
		{
			// get note details
			$note = $this->getItem($note, $blank = true);
		}

		if (!empty($options['id_user']))
		{
			// use the specified user ID
			$note->id_user = $options['id_user'];
		}

		if (!empty($options['id_parent']))
		{
			// use the specified parent ID
			$note->id_parent = $options['id_parent'];
		}

		if (!empty($options['group']))
		{
			// use the specified parent group
			$note->group = $options['group'];
		}

		$parts = array();

		if ($note->group)
		{
			// append group to path
			$parts[] = $note->group;
		}

		if ($note->group == 'appointments')
		{
			// load appointment details
			$appointment = JModelVAP::getInstance('reservation')->getItem($note->id_parent);

			if (!$appointment)
			{
				throw new Exception('Appointment not found.', 404);
			}

			// build path as [ORDNUM]-[ORDKEY]
			$parts[] = substr($appointment->id . '-' . $appointment->sid, 0, 12);
		}
		else
		{
			// load user note details
			$customer = JModelVAP::getInstance('customer')->getItem($note->id_user);

			if (!$customer)
			{
				throw new Exception('Customer not found.', 404);
			}

			VAPLoader::import('libraries.sef.helper');
			// create safe string of the customer name
			$name = VAPSefHelper::stringToAlias($customer->billing_name);

			if (!$name)
			{
				// invalid name, use the e-mail
				$name = $customer->billing_mail;
			}
			else
			{
				// append user ID to avoid conflicts between homonyms
				$name .= '-' . $customer->id;
			}

			if (!$name)
			{
				throw new Exception('Invalid customer details.', 404);
			}

			if (!$note->secret)
			{
				// generate secret key for the user note
				$note->secret = VikAppointments::generateSerialCode(12, 'usernote-secret');
			}

			// build path as [NOMINATIVE]/[TOKEN] 
			$parts = array_merge($parts, array($name, $note->secret));
		}

		if (!$parts)
		{
			// invalid path
			throw new Exception('Invalid path', 500);
		}

		// create folder path
		$path = VAPCUSTOMERS_DOCUMENTS . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, $parts));

		// in case the path is not a folder, create it
		if (!is_dir($path))
		{
			jimport('joomla.filesystem.folder');

			if (!JFolder::create($path))
			{
				// unable to create the folder
				throw new Exception(sprintf('Unable to create [%s] folder', $path), 500);
			}
		}

		return $path;
	}
}
