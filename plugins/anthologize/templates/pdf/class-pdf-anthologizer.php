<?php

/*
 * class PdfAnthologizer
 * @package Anthologize
 * @subpackage PDF-Template
 *
 * Produces PDF from Anthologize TEI
 *
 */

class PdfAnthologizer extends Anthologizer {

	public $partH = '16';
	public $itemH = '12';
	public $headerLogo = 'logo-pdf.png'; //should be in /anthologize/images/
	public $headerLogoWidth = '10';
	public $tidy = false;

	public function init() {
		$page_size = $this->api->getProjectOutputParams('page-size');

		//keep track of how many pages in front so the TOC can be inserted in proper position in finish()
		$this->frontPages = 0;
	    $this->output = new AnthologizeTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $page_size, true, 'UTF-8', false);
	    // $this->output = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $page_size, true, 'UTF-8', false);
		$lg = array();
        // PAGE META DESCRIPTORS --------------------------------------

        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'ltr';
        $lg['a_meta_language'] = 'en';
        $lg['w_page'] = '';
		//set some language-dependent strings

		$this->output->setLanguageArray($lg);

		$this->output->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$this->output->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$this->output->setPrintHeader(false);
		$this->output->setPrintFooter(false);

		$this->output->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->output->setFooterFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


		// set default monospaced font
		$this->output->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set default font subsetting mode
		$this->output->setFontSubsetting(false);

		$font_family = $this->api->getProjectOutputParams('font-face');
		$this->baseH = $this->api->getProjectOutputParams('font-size');

		$this->anthFontsPath = WP_PLUGIN_DIR .
			DIRECTORY_SEPARATOR . 'anthologize' .
			DIRECTORY_SEPARATOR . 'templates' .
			DIRECTORY_SEPARATOR . 'pdf' .
			DIRECTORY_SEPARATOR . 'fonts' .
			DIRECTORY_SEPARATOR ;

		switch($font_family) {
			case 'arialunicid0-ko':
				//arialunicid0.php has a code to uncomment by language
				//since we need to switch on the fly, arialunicid0-XX will include that, then override the
				//uncommented code
				//see arialunicid0.php in TCPDF fonts directory (scroll to the bottom), and pdf/fonts/arialunicid0-ko
				$this->output->AddFont($font_family, '', $this->anthFontsPath . 'arialunicid0-ko.php');
				$this->output->AddFont($font_family, 'B', $this->anthFontsPath . 'arialunicid0-ko.php');
				$this->output->AddFont($font_family, 'I', $this->anthFontsPath . 'arialunicid0-ko.php');
				$this->output->AddFont($font_family, 'BI', $this->anthFontsPath . 'arialunicid0-ko.php');

			break;

			case 'arialunicid0-cj':
				$font_family = 'arialunicid0';
			break;

			default:
				//passthrough without changing font family
			break;

		}
		$this->font_family = $font_family;


		$this->output->SetFont($this->font_family, '', $this->baseH, '', true);

		$this->output->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->output->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->output->SetFooterMargin(PDF_MARGIN_FOOTER);
		$this->output->setHeaderTemplateAutoreset(true);

		$this->set_header(array('logo'=>$this->headerLogo, 'logo_width'=>$this->headerLogoWidth));

		$this->partH = $this->baseH + 4;
		$this->itemH = $this->baseH + 2;


	}

	public function appendFront() {
        //$this->output->startPageGroup();
		//add the front matter

		//title and author
		$creator = $this->api->getProjectCreator(false, false);
		$assertedAuthors = $this->api->getProjectAssertedAuthors(false);
		$book_title = $this->api->getProjectTitle(true);
		$this->output->SetCreator("Anthologize: A One Week | One Tool Production");
		$this->output->SetAuthor($creator);
		$this->output->SetTitle($book_title);

		//subjects

		//append cover
		$this->output->AddPage();

		$this->frontPages++;

		$this->output->SetY( 80 );

		$table = '<table><tr><td align="center">' . $book_title . '</td></tr></table>';
		$this->output->writeHTML( $table );

		$this->output->setFont($this->font_family, '', $this->baseH);

		switch($this->api->getProjectOutputParams('creatorOutputSettings')) {
		    case ANTHOLOGIZE_CREATORS_ALL:
		        $projectAuthorsString = $assertedAuthors;
		        break;

		    case ANTHOLOGIZE_CREATORS_ASSERTED:
		        $projectAuthorsString = $assertedAuthors;
		        break;
		    default:
		        $projectAuthorsString = $creator;
		        break;
		}

		$year = substr( $this->api->getProjectPublicationDate(), 0, 4 );

		$table  = '<table cellpadding="5">';
		$table .= '<tr><td align="center">' . $projectAuthorsString . '</td></tr>';
		$table .= '<tr><td align="center">' . $this->api->getProjectCopyright( false, false ) . ' &mdash; ' . $year . '</td></tr>';
		$table .= '</table>';

		$this->output->writeHTML( $table );

        $this->output->EndPage();

		//dedication
		$dedication = $this->api->getSectionPartItemContent('front', 0, 0);
		if ($dedication){
			$this->output->AddPage();
			$this->output->setFont('', 'B', $this->partH);
			$titleNode = $this->api->getSectionPartItemTitle('front', 0, 0, true);
			$title = $titleNode->nodeValue;
			$this->output->write('', $title, '', false, 'C', true);
			$this->output->writeHTML($dedication);
			$this->output->setFont($this->font_family, '', $this->baseH);
			$this->frontPages++;
		}

		//acknowledgements
		$acknowledgements = $this->api->getSectionPartItemContent('front', 0, 1);
		if ($acknowledgements){
			$this->output->AddPage();
			$this->output->setFont('', 'B', $this->partH);
			$titleNode = $this->api->getSectionPartItemTitle('front', 0, 1, true);
			$title = $titleNode->nodeValue;
			$this->output->write('', $title, '', false, 'C', true);
			$this->output->writeHTML($acknowledgements);
			$this->output->setFont($this->font_family, '', $this->baseH);
			$this->frontPages++;
		}

	}


	public function appendBody() {
		$this->output->startPageGroup();
		$this->output->setPrintHeader(true);

		//actually letting appendPart and append Item do the appending
		//this just fires up the loop through the body parts

		$partsCount = $this->api->getSectionPartCount('body');
		for($partNo = 0; $partNo <$partsCount; $partNo++) {
			$this->appendPart('body', $partNo);
		}
	}

	public function appendBack() {

	    //@TODO: looks like starting an empty page group resets page number on previous page?
		//$this->output->startPageGroup();

		$this->output->setPrintHeader(true);
		$partsCount = $this->api->getSectionPartItemCount('back');

		for($partNo = 0; $partNo < $partsCount; $partNo++) {
			$this->appendPart('back', $partNo);
		}
	}

	public function appendPart($section, $partNo) {

		$titleNode = $this->api->getSectionPartTitle($section, $partNo, true);
		$title = isset( $titleNode->textContent ) ? $titleNode->textContent : '';

		$firstItemNode = $this->api->getSectionPartItemTitle($section, $partNo, 0, true);
		$string = isset( $firstItemNode->textContent ) ? $firstItemNode->textContent : false;


		$this->set_header(array('title'=>$title, 'string'=>$string));

		if( ($partNo == 0) || ($this->api->getProjectOutputParams('break-parts') == 'on' ) ) {
			$this->output->AddPage();
		}

		if($section == 'body') {
			$this->output->Bookmark($title);
		}
		//TCPDF seems to add the footer to prev. page if AddPage hasn't been fired
		$this->output->setPrintFooter(true);


		//add the header info
		$this->appendPartHead($section, $partNo);

		//loop the items and append
		$itemsCount = $this->api->getSectionPartItemCount($section, $partNo);
		for($itemNo = 0; $itemNo < $itemsCount; $itemNo++) {
			$this->appendItem($section, $partNo, $itemNo);
		}
	}

	public function appendPartHead($section, $partNo) {
		//append the header stuff, avoiding HTML methods for optimization

		$titleNode = $this->api->getSectionPartTitle($section, $partNo, true);
		$title = $titleNode->textContent;
		$this->output->setFont($this->font_family, 'B', $this->partH);
		$this->output->Write('', $title, '', false, 'C', true );
		$this->output->setFont($this->font_family, '', $this->baseH);

	}

	public function appendItem($section, $partNo, $itemNo) {

		$titleNode = $this->api->getSectionPartItemTitle($section, $partNo, $itemNo, true);
		$title = isset( $titleNode->textContent ) ? $titleNode->textContent : '';

        $this->set_header(array('string'=>$title));
		if( ($this->api->getProjectOutputParams('break-items') == 'on') && $itemNo != 0   ) {
			$this->output->AddPage();
		}

		if($section == 'body') {
			$this->output->Bookmark($title, 1);
		}
		$this->appendItemHead($section, $partNo, $itemNo);

		$metadata_params_raw = $this->api->getProjectOutputParams( 'metadata' );
		if ( $metadata_params_raw ) {
			$metadata_params = json_decode( $metadata_params_raw );
			$this->appendItemMetadata( $section, $partNo, $itemNo, $metadata_params );
		}

		//append the item content
		$content = $this->writeItemContent($section, $partNo, $itemNo);
		$this->output->writeHTML($content, true, false, true);
	}

	protected function getItemStyles() {
		$style = '<style>
			.wp-caption {
				display: table-cell;
				text-align: center;
			}
			.wp-caption-text {
				color: #686868;
				font-size: .9em;
				font-style: italic;
				text-align: center;
			}
			img {
				border: 5px solid #fff;
			}
		</style>';

		return $style;
	}

	protected function writeItemContent($section, $partNo, $itemNo) {
		$content = parent::writeItemContent($section, $partNo, $itemNo);

		//when the TEI gets here, & has become &amp; in img@src, so this is a not-so-subtle-or-elegant fix
		if(strpos($content, "&amp;") !== false) {
			$content = htmlspecialchars_decode($content);
			$content = str_replace("&amp;", "&", $content);
		}

		$content = preg_replace( '/<figure([^>]*)(class="([^"]+)")?([^>]*)>/', '<div class="wp-caption $2">', $content );
		$content = preg_replace( '/<figcaption([^>]*)(class="([^"]+)")?([^>]*)>/', "\n" . '<div class="wp-caption-text $2">', $content );

		$content = str_replace(
			array(
				'</figure>',
				'</figcaption>',
			),
			array(
				'</div>',
				'</div>',
			),
			$content
		);

		$content = $this->getItemStyles() . $content;

		return $content;
	}

	public function appendItemHead($section, $partNo, $itemNo) {

		//write the head, avoiding HTML for optimization
		$titleNode = $this->api->getSectionPartItemTitle($section, $partNo, $itemNo, true);
		$title = isset( $titleNode->textContent ) ? $titleNode->textContent : '';

		$this->output->setFont($this->font_family, 'B', $this->itemH);
		$this->output->Write('', $title, '', false, 'C', true );
		$this->output->setFont($this->font_family, '', $this->baseH);

	}

	public function appendItemMetadata( $section, $partNo, $itemNo, $metadata_types ) {
		$mds = array();

		foreach ( $metadata_types as $metadata_type ) {
			switch ( $metadata_type ) {
				case 'author' :
					$author = $this->api->getSectionPartItemOriginalAuthor( $section, $partNo, $itemNo );
					if ( $author ) {
						/* translators: Item author name */
						$mds[] = sprintf( __( 'By %s', 'anthologize' ), $author );
					}

					break;

				case 'date' :
					$date = $this->api->getSectionPartItemPublicationDate( $section, $partNo, $itemNo );

					if ( $date ) {
						$mds[] = mysql2date( get_option( 'date_format' ), $date );
					}

					break;
			}
		}

		if ( ! $mds ) {
			return;
		}

		$text = implode( ' &middot; ', $mds );

		$this->output->writeHTML( $text, '', false, false, false, 'C' );
	}

	public function finish() {
        $this->output->endPage();
		//add TOC
		$this->output->setPrintHeader(false);
		$this->output->setPrintFooter(false);
		$this->output->addTOCPage();
		$this->output->Write(0, 'Table of Contents', '', false, 'C', true);

		$this->output->addTOC($this->frontPages + 1, '', '', 'Table of Contents');
		$this->output->endTOCPage();
	}


	private function _boldSetting() {
		//some font families fail on trying to make bold
		switch ($this->font_family) {
			case 'arialunicid0-ko':
				return '';
			break;

			default:
				return 'B';
			break;
		}
	}

	public function output() {
		$filename = $this->api->getFileName() . ".pdf";
		$this->output->Output($filename, 'D');
	}

	protected function set_header($array) {
		//get the current data. . .
		$newArray = $this->output->getHeaderData();
		//. . . and override with whatever is in the param . . .
		foreach($array as $prop=>$value) {
			$newArray[$prop] = $value;
		}
		//. . . and set it back in the TCPDF
		$this->output->setHeaderData($newArray['logo'], $newArray['logo_width'], $newArray['title'], $newArray['string']);
	}


}
