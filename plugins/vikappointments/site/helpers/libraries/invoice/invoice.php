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

/**
 * Abstract class used to implement the common functions
 * that will be invoked to generate and send invoices.
 *
 * @since 	1.6
 */
abstract class VAPInvoice
{
	/**
	 * The order details.
	 *
	 * @var object
	 */
	protected $order;

	/**
	 * The invoice arguments (e.g. increment number or legal info).
	 *
	 * @var object
	 */
	protected $params;

	/**
	 * The invoice properties (e.g. page margins or units).
	 *
	 * @var object
	 */
	protected $constraints;

	/**
	 * Class constructor.
	 *
	 * @param 	object 	The order details.
	 */
	public function __construct($order)
	{
		$this->order = $order;
	}
	
	/**
	 * Overwrites the invoice parameters.
	 *
	 * @param 	object  $data  The parameters to set.
	 *
	 * @return 	self    This object to support chaining.
	 *
	 * @since 	1.7
	 */
	public function setParams($data)
	{
		$this->params = $data->params;

		$this->constraints = $data->constraints;

		return $this;
	}

	/**
	 * Generates the invoices related to the specified order.
	 *
	 * @return 	mixed 	 The invoice array data on success, otherwise false.
	 *
	 * @uses 	getInvoicePath()
	 * @uses 	getPageTemplate()
	 * @uses 	parseTemplate()
	 * @uses 	isFontSupported()
	 */
	public function generate($increase = true)
	{
		if (!$this->order)
		{
			return false;
		}

		// prepare resulting data array
		$data = array();

		// get invoice path
		$data['path'] = $this->getInvoicePath();

		if (is_file($data['path']))
		{
			// unlink pdf if already exists
			@unlink($data['path']);
		}

		if (!empty($this->constraints->font))
		{
			// use specified font
			$font = $this->constraints->font;
		}
		else
		{
			// use DejavuSans font by default for UTF-8 compliance
			$font = 'dejavusans';
		}

		// check if the selected font is supported
		if (!$this->isFontSupported($font))
		{
			 // fallback to Courier default font
			 $font = 'courier';  
		}

		// load TCPDF only if missing, because it might have been already
		// loaded by a different plugin
		if (!class_exists('TCPDF'))
		{
			VAPLoader::import('pdf.tcpdf.tcpdf');
		}
		
		$pdf = new TCPDF($this->constraints->pageOrientation, $this->constraints->unit, $this->constraints->pageFormat, true, 'UTF-8', false);

		// get title from constraints
		$title = !empty($this->constraints->headerTitle) ? $this->constraints->headerTitle : null;

		if ($title)
		{
			// set page title
			$pdf->SetTitle($title);

			// show header
			$pdf->SetHeaderData('', 0, $title, '');

			// set header font
			$pdf->setHeaderFont(array($font, '', $this->constraints->fontSizes->header));

			// set header margin
			$pdf->SetHeaderMargin((int) $this->constraints->margins->header);
		}
		else
		{
			// nothing to display in header, hide it
			$pdf->SetPrintHeader(false);
		}	

		// default monospaced font
		// $pdf->SetDefaultMonospacedFont('courier');

		// margins
		$pdf->SetMargins($this->constraints->margins->left, $this->constraints->margins->top, $this->constraints->margins->right);

		$pdf->SetAutoPageBreak(true, $this->constraints->margins->bottom);
		$pdf->setImageScale($this->constraints->imageScaleRatio);
		$pdf->SetFont($font, '', $this->constraints->fontSizes->body);

		// check if we should display the footer
		if (!empty($this->constraints->showFooter))
		{
			// show footer
			$pdf->SetPrintFooter(true);

			// set footer font
			$pdf->setFooterFont(array($font, '', $this->constraints->fontSizes->footer));

			// set footer margin
			$pdf->SetFooterMargin($this->constraints->margins->footer);
		}
		else
		{
			// hide footer otherwise
			$pdf->SetPrintFooter(false);
		}

		// get invoice template
		$tmpl = $this->getPageTemplate($this->order);

		/**
		 * Parse the invoice template.
		 *
		 * @since 1.7  Pass the array data to let the invoice
		 *             handler fill it with the resulting info.
		 */
		$pages = $this->parseTemplate($tmpl, $data);

		if (!is_array($pages))
		{
			$pages = array($pages);
		}

		// add pages
		foreach ($pages as $page)
		{
			$pdf->addPage();
			$pdf->writeHTML($page, true, false, true, false, '');
		}
		
		// write file
		$pdf->Output($data['path'], 'F');

		// check if the file has been created
		if (!is_file($data['path']))
		{
			return false;
		}

		return $data;
	}

	/**
	 * Checks if the specified font is supported.
	 *
	 * @param 	string   $font  The font family name.
	 *
	 * @return 	boolean  True if the font is supported, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function isFontSupported($font)
	{
		$font = strtolower($font);

		// font system supported by default
		switch ($font)
		{
			case 'courier':
			case 'helvetica':
				return true;
		}

		// create font driver path under TCPDF "fonts" folder
		$path = implode(DIRECTORY_SEPARATOR, array(VAPHELPERS, 'pdf', 'tcpdf', 'fonts', $font . '.php'));

		// check if a the font is installed
		return is_file($path);
	}

	/**
	 * Parses the given template to replace the placeholders
	 * with the values contained in the order details.
	 *
	 * @param 	string 	$tmpl   The template to parse.
	 * @param 	array   &$data  An array data to fill.
	 *
	 * @return 	mixed 	The invoice page or an array of pages.
	 */
	protected function parseTemplate($tmpl, &$data)
	{
		$config = VAPFactory::getConfig();

		$logo_name = $config->get('companylogo');

		// company logo
		if ($logo_name)
		{ 
			$logo_str = '<img src="' . VAPMEDIA_URI . $logo_name . '" />';
		}
		else
		{
			$logo_str = '';
		}

		$tmpl = str_replace('{company_logo}', $logo_str, $tmpl);
		
		// company info
		$tmpl = str_replace('{company_info}', nl2br($this->params->legalinfo), $tmpl);
		
		// invoice details
		$suffix = '';

		if (!empty($this->params->suffix))
		{
			$suffix = '/' . $this->params->suffix;
		}

		$tmpl = str_replace('{invoice_number}', $this->params->number, $tmpl);
		$tmpl = str_replace('{invoice_suffix}', $suffix 			 , $tmpl);

		// register invoice number
		$data['inv_number'] = $this->params->number . $suffix;

		return $tmpl;
	}

	/**
	 * Returns the destination absolute path of the invoices folder.
	 * Inherit in children methods in case the path needs further
	 * subfolders.
	 *
	 * @return 	string 	The invoice folder path.
	 *
	 * @since 	1.7
	 */
	public function getInvoiceFolderPath()
	{
		// use default path
		return VAPINVOICE;
	}

	/**
	 * Returns the destination URI of the invoices folder.
	 * Inherit in children methods in case the path needs further
	 * subfolders.
	 *
	 * @return 	string 	The invoice folder URI.
	 *
	 * @since 	1.7
	 */
	public function getInvoiceFolderURI()
	{
		// use default path
		return VAPINVOICE_URI;
	}

	/**
	 * Returns the destination path of the invoice.
	 *
	 * @return 	string 	The invoice path.
	 */
	abstract protected function getInvoicePath();

	/**
	 * Returns the page template that will be used to 
	 * generate the invoice.
	 *
	 * @return 	string 	The base HTML.
	 */
	abstract protected function getPageTemplate();

	/**
	 * Returns the e-mail address of the user that should
	 * receive the invoice via mail.
	 *
	 * @return 	string 	The customer e-mail.
	 */
	abstract public function getRecipient();
}
