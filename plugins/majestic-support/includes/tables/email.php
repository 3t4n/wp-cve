<?php

if(!defined('ABSPATH'))
	die('Restricted Access');

class MJTC_emailTable extends MJTC_table {

	public $id = '';
	public $autoresponse = '';
	public $priorityid = '';
	public $email = '';
	public $name = '';
	public $uid = '';
	public $password = '';
	public $status = '';
	public $mailhost = '';
	public $mailprotocol = '';
	public $mailencryption = '';
	public $mailport = '';
	public $mailfetchfrequency = '';
	public $mailfetchmaximum = '';
	public $maildeleted = '';
	public $mailerrors = '';
	public $maillasterror = '';
	public $maillastfetch = '';
	public $smtpactive = '';
	public $smtphost = '';
	public $smtpport = '';
	public $smtpsecure = '';
	public $smtpauthencation = '';
	public $created = '';
	public $updated = '';
	public $smtpemailauth = '';
	public $smtphosttype = '';

	function __construct() {
		parent::__construct('email', 'id'); // tablename, primarykey
	}

}

?>