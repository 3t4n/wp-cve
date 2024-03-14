<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/5/2018
 * Time: 11:55 AM
 */

namespace rnpdfimporter\Lib\TextProcessor;

use rnpdfimporter\core\PluginBase;
use rnpdfimporter\Lib\TextProcessor\Font\Cache;
use rnpdfimporter\Lib\TextProcessor\Font\FontCache;
use rnpdfimporter\Lib\TextProcessor\Font\FontFileFinder;
use rnpdfimporter\Lib\TextProcessor\Font\MetricsGenerator;
use rnpdfimporter\Lib\TextProcessor\Font\Otl;

class TextProcessor
{
    public $listitem=null;
    public $fontdata=[
        "dejavusanscondensed" => [
            'R' => "DejaVuSansCondensed.ttf",
            'B' => "DejaVuSansCondensed-Bold.ttf",
            'I' => "DejaVuSansCondensed-Oblique.ttf",
            'BI' => "DejaVuSansCondensed-BoldOblique.ttf",
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        "dejavusans" => [
            'R' => "DejaVuSans.ttf",
            'B' => "DejaVuSans-Bold.ttf",
            'I' => "DejaVuSans-Oblique.ttf",
            'BI' => "DejaVuSans-BoldOblique.ttf",
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        "dejavuserif" => [
            'R' => "DejaVuSerif.ttf",
            'B' => "DejaVuSerif-Bold.ttf",
            'I' => "DejaVuSerif-Italic.ttf",
            'BI' => "DejaVuSerif-BoldItalic.ttf",
        ],
        "dejavuserifcondensed" => [
            'R' => "DejaVuSerifCondensed.ttf",
            'B' => "DejaVuSerifCondensed-Bold.ttf",
            'I' => "DejaVuSerifCondensed-Italic.ttf",
            'BI' => "DejaVuSerifCondensed-BoldItalic.ttf",
        ],
        "dejavusansmono" => [
            'R' => "DejaVuSansMono.ttf",
            'B' => "DejaVuSansMono-Bold.ttf",
            'I' => "DejaVuSansMono-Oblique.ttf",
            'BI' => "DejaVuSansMono-BoldOblique.ttf",
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        "freesans" => [
            'R' => "FreeSans.ttf",
            'B' => "FreeSansBold.ttf",
            'I' => "FreeSansOblique.ttf",
            'BI' => "FreeSansBoldOblique.ttf",
            'useOTL' => 0xFF,
        ],
        "freeserif" => [
            'R' => "FreeSerif.ttf",
            'B' => "FreeSerifBold.ttf",
            'I' => "FreeSerifItalic.ttf",
            'BI' => "FreeSerifBoldItalic.ttf",
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        "freemono" => [
            'R' => "FreeMono.ttf",
            'B' => "FreeMonoBold.ttf",
            'I' => "FreeMonoOblique.ttf",
            'BI' => "FreeMonoBoldOblique.ttf",
        ],
        /* OCR-B font for Barcodes */
        "ocrb" => [
            'R' => "ocrb10.ttf",
        ],
        /* Miscellaneous language font(s) */
        "estrangeloedessa" => [/* Syriac */
            'R' => "SyrCOMEdessa.otf",
            'useOTL' => 0xFF,
        ],
        "kaputaunicode" => [/* Sinhala  */
            'R' => "kaputaunicode.ttf",
            'useOTL' => 0xFF,
        ],
        "abyssinicasil" => [/* Ethiopic */
            'R' => "Abyssinica_SIL.ttf",
            'useOTL' => 0xFF,
        ],
        "aboriginalsans" => [/* Cherokee and Canadian */
            'R' => "AboriginalSansREGULAR.ttf",
        ],
        "jomolhari" => [/* Tibetan */
            'R' => "Jomolhari.ttf",
            'useOTL' => 0xFF,
        ],
        "sundaneseunicode" => [/* Sundanese */
            'R' => "SundaneseUnicode-1.0.5.ttf",
            'useOTL' => 0xFF,
        ],
        "taiheritagepro" => [/* Tai Viet */
            'R' => "TaiHeritagePro.ttf",
        ],
        "aegean" => [
            'R' => "Aegean.otf",
            'useOTL' => 0xFF,
        ],
        "aegyptus" => [
            'R' => "Aegyptus.otf",
            'useOTL' => 0xFF,
        ],
        "akkadian" => [/* Cuneiform */
            'R' => "Akkadian.otf",
            'useOTL' => 0xFF,
        ],
        "quivira" => [
            'R' => "Quivira.otf",
            'useOTL' => 0xFF,
        ],
        "eeyekunicode" => [/* Meetei Mayek */
            'R' => "Eeyek.ttf",
        ],
        "lannaalif" => [/* Tai Tham */
            'R' => "lannaalif-v1-03.ttf",
            'useOTL' => 0xFF,
        ],
        "daibannasilbook" => [/* New Tai Lue */
            'R' => "DBSILBR.ttf",
        ],
        "garuda" => [/* Thai */
            'R' => "Garuda.ttf",
            'B' => "Garuda-Bold.ttf",
            'I' => "Garuda-Oblique.ttf",
            'BI' => "Garuda-BoldOblique.ttf",
            'useOTL' => 0xFF,
        ],
        "khmeros" => [/* Khmer */
            'R' => "KhmerOS.ttf",
            'useOTL' => 0xFF,
        ],
        "dhyana" => [/* Lao fonts */
            'R' => "Dhyana-Regular.ttf",
            'B' => "Dhyana-Bold.ttf",
            'useOTL' => 0xFF,
        ],
        "tharlon" => [/* Myanmar / Burmese */
            'R' => "Tharlon-Regular.ttf",
            'useOTL' => 0xFF,
        ],
        "padaukbook" => [/* Myanmar / Burmese */
            'R' => "Padauk-book.ttf",
            'useOTL' => 0xFF,
        ],
        "zawgyi-one" => [/* Myanmar / Burmese */
            'R' => "ZawgyiOne.ttf",
            'useOTL' => 0xFF,
        ],
        "ayar" => [/* Myanmar / Burmese */
            'R' => "ayar.ttf",
            'useOTL' => 0xFF,
        ],
        "taameydavidclm" => [/* Hebrew with full Niqud and Cantillation */
            'R' => "TaameyDavidCLM-Medium.ttf",
            'useOTL' => 0xFF,
        ],
        /* SMP */
        "mph2bdamase" => [
            'R' => "damase_v.2.ttf",
        ],
        /* Indic */
        "lohitkannada" => [
            'R' => "Lohit-Kannada.ttf",
            'useOTL' => 0xFF,
        ],
        "pothana2000" => [
            'R' => "Pothana2000.ttf",
            'useOTL' => 0xFF,
        ],
        /* Arabic fonts */
        "xbriyaz" => [
            'R' => "XB Riyaz.ttf",
            'B' => "XB RiyazBd.ttf",
            'I' => "XB RiyazIt.ttf",
            'BI' => "XB RiyazBdIt.ttf",
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        "lateef" => [/* Sindhi, Pashto and Urdu */
            'R' => "LateefRegOT.ttf",
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        "kfgqpcuthmantahanaskh" => [/* KFGQPC Uthman Taha Naskh - Koranic */
            'R' => "Uthman.otf",
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        /* CJK fonts */
        "sun-exta" => [
            'R' => "Sun-ExtA.ttf",
            'sip-ext' => 'sun-extb', /* SIP=Plane2 Unicode (extension B) */
        ],
        "sun-extb" => [
            'R' => "Sun-ExtB.ttf",
        ],
        "unbatang" => [/* Korean */
            'R' => "UnBatang_0613.ttf",
        ],
    ];
    public $fontFileFinder;
    public $fontDescriptor;
    public $fonts=[];
    public $extraFontSubsets=0;
    public $otl;
    public $CurrentFont;
    public $fontLanguageOverride=null;
    public $currentLang=null;
    public $useDictionaryLBR=true;
    public $useTibetanLBR=true;
    public $textvar=0;
    public $OTLdata;
    public $mb_enc='UTF-8';
    public $textbuffer=[];
    public $onlyCoreFonts=false;
    const SCALE = 2.83464566929;
    public $page=0;
    public $FontSizePt=9;
    public $FontFamily='';
    public $available_unifonts;
    public $default_available_fonts;
    public static $_instance=null;

    /**
     * @return TextProcessor
     */
    public static function GetInstance(){
        if(self::$_instance==null)
            self::$_instance=new TextProcessor();
        return self::$_instance;
    }

    private function __construct()
    {
        //\vendor\dompdf\dompdf\lib\fonts
        $this->fontCache = new FontCache(new Cache(PluginBase::$PluginDir . '/Font'));
        $this->fontFileFinder = new FontFileFinder(PluginBase::$PluginDir . '/Font');

        $this->AddFont('dejavusans');
        $this->available_unifonts = [];
        foreach ($this->fontdata as $f => $fs) {
            if (isset($fs['R']) && $fs['R']) {
                $this->available_unifonts[] = $f;
            }
            if (isset($fs['B']) && $fs['B']) {
                $this->available_unifonts[] = $f . 'B';
            }
            if (isset($fs['I']) && $fs['I']) {
                $this->available_unifonts[] = $f . 'I';
            }
            if (isset($fs['BI']) && $fs['BI']) {
                $this->available_unifonts[] = $f . 'BI';
            }
        }

        $this->default_available_fonts = $this->available_unifonts;




        $this->CurrentFont=$this->fonts['dejavusans'];
        $this->otl = new Otl($this, $this->fontCache);
        $this->SetFont('dejavusans');

    }

    public function Process($text)
    {

/*        if($fontFamily!='')
        {
            if(!isset($this->fontdata[$fontFamily]))
            {
                $lookup=$this->dompdf->getFontMetrics()->getFontFamilies();
                if(is_array($lookup)&&isset($lookup[$fontFamily]))
                {
                    $this->fontdata[$fontFamily]=[
                        'R' => basename($lookup[$fontFamily]['normal']).".ttf",
                        'B' =>basename($lookup[$fontFamily]['bold']).".ttf",
                        'I' => basename($lookup[$fontFamily]['italic']).".ttf",
                        'BI' => basename($lookup[$fontFamily]['bold_italic']).".ttf",
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ];
                    $this->AddFont($fontFamily);
                }

            }
            $this->CurrentFont=$this->fonts[$fontFamily];
            $this->SetFont($fontFamily);
        }*/

        $this->textbuffer=[];
        $txt=$this->otl->applyOTL($text,$this->CurrentFont['useOTL']);
        $this->OTLdata = $this->otl->OTLdata;
        $this->otl->removeChar($e, $this->OTLdata, "\xef\xbb\xbf");
        $this->_saveTextBuffer($txt);
        $chuckorder=[0];

        global $wooUseRTL;
        $this->otl->bidiPrepare($this->textbuffer,$wooUseRTL==true?'rtl':'ltr');
        $data=array($this->textbuffer[0][18]);
        $content=array($txt);
        $this->otl->bidiReorder($chuckorder, $content,$data , $wooUseRTL==true?'rtl':'ltf');
        return $content[0];

    }

    function setMBencoding($enc)
    {
        if ($this->mb_enc != $enc) {
            $this->mb_enc = $enc;
            mb_internal_encoding($this->mb_enc);
        }
    }

    function _getCharWidth(&$cw, $u, $isdef = true)
    {
        $w = 0;

        if ($u == 0) {
            $w = false;
        } elseif (isset($cw[$u * 2 + 1])) {
            $w = (ord($cw[$u * 2]) << 8) + ord($cw[$u * 2 + 1]);
        }

        if ($w == 65535) {
            return 0;
        } elseif ($w) {
            return $w;
        } elseif ($isdef) {
            return false;
        } else {
            return 0;
        }
    }

    function SetFont($family, $style = '', $size = 0, $write = true, $forcewrite = false)
    {
        $family = strtolower($family);

        if (!$this->onlyCoreFonts) {
            if ($family == 'sans' || $family == 'sans-serif') {
                $family = $this->sans_fonts[0];
            }
            if ($family == 'serif') {
                $family = $this->serif_fonts[0];
            }
            if ($family == 'mono' || $family == 'monospace') {
                $family = $this->mono_fonts[0];
            }
        }

        if (isset($this->fonttrans[$family]) && $this->fonttrans[$family]) {
            $family = $this->fonttrans[$family];
        }

        if ($family == '') {
            if ($this->FontFamily) {
                $family = $this->FontFamily;
            } elseif ($this->default_font) {
                $family = $this->default_font;
            } else {
                throw new \Mpdf\MpdfException("No font or default font set!");
            }
        }

        $this->ReqFontStyle = $style; // required or requested style - used later for artificial bold/italic

        if (($family == 'csymbol') || ($family == 'czapfdingbats') || ($family == 'ctimes') || ($family == 'ccourier') || ($family == 'chelvetica')) {
            if ($this->PDFA || $this->PDFX) {
                if ($family == 'csymbol' || $family == 'czapfdingbats') {
                    throw new \Mpdf\MpdfException("Symbol and Zapfdingbats cannot be embedded in mPDF (required for PDFA1-b or PDFX/1-a).");
                }
                if ($family == 'ctimes' || $family == 'ccourier' || $family == 'chelvetica') {
                    if (($this->PDFA && !$this->PDFAauto) || ($this->PDFX && !$this->PDFXauto)) {
                        $this->PDFAXwarnings[] = "Core Adobe font " . ucfirst($family) . " cannot be embedded in mPDF, which is required for PDFA1-b or PDFX/1-a. (Embedded font will be substituted.)";
                    }
                    if ($family == 'chelvetica') {
                        $family = 'sans';
                    }
                    if ($family == 'ctimes') {
                        $family = 'serif';
                    }
                    if ($family == 'ccourier') {
                        $family = 'mono';
                    }
                }
                $this->usingCoreFont = false;
            } else {
                $this->usingCoreFont = true;
            }
            if ($family == 'csymbol' || $family == 'czapfdingbats') {
                $style = '';
            }
        } else {
            $this->usingCoreFont = false;
        }

        // mPDF 5.7.1
        if ($style) {
            $style = strtoupper($style);
            if ($style == 'IB') {
                $style = 'BI';
            }
        }
        if ($size == 0) {
            $size = $this->FontSizePt;
        }

        $fontkey = $family . $style;

        $stylekey = $style;
        if (!$stylekey) {
            $stylekey = "R";
        }

        if (!$this->onlyCoreFonts && !$this->usingCoreFont) {
            if (!isset($this->fonts[$fontkey]) || count($this->default_available_fonts) != count($this->available_unifonts)) { // not already added

                /* -- CJK-FONTS -- */
                if (in_array($fontkey, $this->available_CJK_fonts)) {
                    if (!isset($this->fonts[$fontkey])) { // already added
                        if (empty($this->Big5_widths)) {
                            require __DIR__ . '/../data/CJKdata.php';
                        }
                        $this->AddCJKFont($family); // don't need to add style
                    }
                } else { // Test to see if requested font/style is available - or substitute /* -- END CJK-FONTS -- */
                    if (!in_array($fontkey, $this->available_unifonts)) {
                        // If font[nostyle] exists - set it
                        if (in_array($family, $this->available_unifonts)) {
                            $style = '';
                        } // elseif only one font available - set it (assumes if only one font available it will not have a style)
                        elseif (count($this->available_unifonts) == 1) {
                            $family = $this->available_unifonts[0];
                            $style = '';
                        } else {
                            $found = 0;
                            // else substitute font of similar type
                            if (in_array($family, $this->sans_fonts)) {
                                $i = array_intersect($this->sans_fonts, $this->available_unifonts);
                                if (count($i)) {
                                    $i = array_values($i);
                                    // with requested style if possible
                                    if (!in_array(($i[0] . $style), $this->available_unifonts)) {
                                        $style = '';
                                    }
                                    $family = $i[0];
                                    $found = 1;
                                }
                            } elseif (in_array($family, $this->serif_fonts)) {
                                $i = array_intersect($this->serif_fonts, $this->available_unifonts);
                                if (count($i)) {
                                    $i = array_values($i);
                                    // with requested style if possible
                                    if (!in_array(($i[0] . $style), $this->available_unifonts)) {
                                        $style = '';
                                    }
                                    $family = $i[0];
                                    $found = 1;
                                }
                            } elseif (in_array($family, $this->mono_fonts)) {
                                $i = array_intersect($this->mono_fonts, $this->available_unifonts);
                                if (count($i)) {
                                    $i = array_values($i);
                                    // with requested style if possible
                                    if (!in_array(($i[0] . $style), $this->available_unifonts)) {
                                        $style = '';
                                    }
                                    $family = $i[0];
                                    $found = 1;
                                }
                            }

                            if (!$found) {
                                // set first available font
                                $fs = $this->available_unifonts[0];
                                preg_match('/^([a-z_0-9\-]+)([BI]{0,2})$/', $fs, $fas); // Allow "-"
                                // with requested style if possible
                                $ws = $fas[1] . $style;
                                if (in_array($ws, $this->available_unifonts)) {
                                    $family = $fas[1]; // leave $style as is
                                } elseif (in_array($fas[1], $this->available_unifonts)) {
                                    // or without style
                                    $family = $fas[1];
                                    $style = '';
                                } else {
                                    // or with the style specified
                                    $family = $fas[1];
                                    $style = $fas[2];
                                }
                            }
                        }
                        $fontkey = $family . $style;
                    }
                }
            }

            // try to add font (if not already added)
            $this->AddFont($family, $style);

            // Test if font is already selected
            if ($this->FontFamily == $family && $this->FontFamily == $this->currentfontfamily && $this->FontStyle == $style && $this->FontStyle == $this->currentfontstyle && $this->FontSizePt == $size && $this->FontSizePt == $this->currentfontsize && !$forcewrite) {
                return $family;
            }

            $fontkey = $family . $style;

            // Select it
            $this->FontFamily = $family;
            $this->FontStyle = $style;
            $this->FontSizePt = $size;
            $this->FontSize = $size / self::SCALE;
            $this->CurrentFont = &$this->fonts[$fontkey];
            if ($write) {
                $fontout = (sprintf('BT /F%d %.3F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
                if ($this->page > 0 && ((isset($this->pageoutput[$this->page]['Font']) && $this->pageoutput[$this->page]['Font'] != $fontout) || !isset($this->pageoutput[$this->page]['Font']))) {
                    $this->_out($fontout);
                }
                $this->pageoutput[$this->page]['Font'] = $fontout;
            }

            // Added - currentfont (lowercase) used in HTML2PDF
            $this->currentfontfamily = $family;
            $this->currentfontsize = $size;
            $this->currentfontstyle = $style;
            $this->setMBencoding('UTF-8');
        } else {  // if using core fonts
            if ($this->PDFA || $this->PDFX) {
                throw new \Mpdf\MpdfException('Core Adobe fonts cannot be embedded in mPDF (required for PDFA1-b or PDFX/1-a) - cannot use option to use core fonts.');
            }
            $this->setMBencoding('windows-1252');

            // Test if font is already selected
            if (($this->FontFamily == $family) and ( $this->FontStyle == $style) and ( $this->FontSizePt == $size) && !$forcewrite) {
                return $family;
            }

            if (!isset($this->CoreFonts[$fontkey])) {
                if (in_array($family, $this->serif_fonts)) {
                    $family = 'ctimes';
                } elseif (in_array($family, $this->mono_fonts)) {
                    $family = 'ccourier';
                } else {
                    $family = 'chelvetica';
                }
                $this->usingCoreFont = true;
                $fontkey = $family . $style;
            }

            if (!isset($this->fonts[$fontkey])) {
                // STANDARD CORE FONTS
                if (isset($this->CoreFonts[$fontkey])) {
                    // Load metric file
                    $file = $family;
                    if ($family == 'ctimes' || $family == 'chelvetica' || $family == 'ccourier') {
                        $file .= strtolower($style);
                    }
                    require __DIR__ . '/../data/font/' . $file . '.php';
                    if (!isset($cw)) {
                        throw new \Mpdf\MpdfException(sprintf('Could not include font metric file "%s"', $file));
                    }
                    $i = count($this->fonts) + $this->extraFontSubsets + 1;
                    $this->fonts[$fontkey] = ['i' => $i, 'type' => 'core', 'name' => $this->CoreFonts[$fontkey], 'desc' => $desc, 'up' => $up, 'ut' => $ut, 'cw' => $cw];
                    if ($this->useKerning && isset($kerninfo)) {
                        $this->fonts[$fontkey]['kerninfo'] = $kerninfo;
                    }
                } else {
                    throw new \Mpdf\MpdfException(sprintf('Font %s not defined', $fontkey));
                }
            }

            // Test if font is already selected
            if (($this->FontFamily == $family) and ( $this->FontStyle == $style) and ( $this->FontSizePt == $size) && !$forcewrite) {
                return $family;
            }
            // Select it
            $this->FontFamily = $family;
            $this->FontStyle = $style;
            $this->FontSizePt = $size;
            $this->FontSize = $size / Mpdf::SCALE;
            $this->CurrentFont = &$this->fonts[$fontkey];
            if ($write) {
                $fontout = (sprintf('BT /F%d %.3F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
                if ($this->page > 0 && ((isset($this->pageoutput[$this->page]['Font']) && $this->pageoutput[$this->page]['Font'] != $fontout) || !isset($this->pageoutput[$this->page]['Font']))) {
                    $this->_out($fontout);
                }
                $this->pageoutput[$this->page]['Font'] = $fontout;
            }
            // Added - currentfont (lowercase) used in HTML2PDF
            $this->currentfontfamily = $family;
            $this->currentfontsize = $size;
            $this->currentfontstyle = $style;
        }

        return $family;
    }

    function _saveTextBuffer($t, $link = '', $intlink = '', $return = false)
    {
        // mPDF 6  Lists
        $arr = [];
        $arr[0] = $t;
        if (isset($link) && $link) {
            $arr[1] = $link;
        }
        $arr[2] = $this->currentfontstyle;
        if (isset($this->colorarray) && $this->colorarray) {
            $arr[3] = $this->colorarray;
        }
        $arr[4] = $this->currentfontfamily;
        $arr[5] = $this->currentLang; // mPDF 6
        if (isset($intlink) && $intlink) {
            $arr[7] = $intlink;
        }
        // mPDF 6
        // If Kerning set for OTL, and useOTL has positive value, but has not set for this particular script,
        // set for kerning via kern table
        // e.g. Latin script when useOTL set as 0x80
        if (isset($this->OTLtags['Plus']) && strpos($this->OTLtags['Plus'], 'kern') !== false && empty($this->OTLdata['GPOSinfo'])) {
            $this->textvar = ($this->textvar | TextVars::FC_KERNING);
        }
        $arr[8] = $this->textvar; // mPDF 5.7.1
        if (isset($this->textparam) && $this->textparam) {
            $arr[9] = $this->textparam;
        }
        if (isset($this->spanbgcolorarray) && $this->spanbgcolorarray) {
            $arr[10] = $this->spanbgcolorarray;
        }
        $arr[11] = $this->currentfontsize;
        if (isset($this->ReqFontStyle) && $this->ReqFontStyle) {
            $arr[12] = $this->ReqFontStyle;
        }
        if (isset($this->lSpacingCSS) && $this->lSpacingCSS) {
            $arr[14] = $this->lSpacingCSS;
        }
        if (isset($this->wSpacingCSS) && $this->wSpacingCSS) {
            $arr[15] = $this->wSpacingCSS;
        }
        if (isset($this->spanborddet) && $this->spanborddet) {
            $arr[16] = $this->spanborddet;
        }
        if (isset($this->textshadow) && $this->textshadow) {
            $arr[17] = $this->textshadow;
        }
        if (isset($this->OTLdata) && $this->OTLdata) {
            $arr[18] = $this->OTLdata;
            $this->OTLdata = [];
        } // mPDF 5.7.1
        else {
            $arr[18] = null;
        }
        // mPDF 6  Lists
        if ($return) {
            return ($arr);
        }
        if ($this->listitem) {
            $this->textbuffer[] = $this->listitem;
            $this->listitem = [];
        }
        $this->textbuffer[] = $arr;
    }

    function UTF8StringToArray($str, $addSubset = true)
    {
        $out = [];
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $uni = -1;
            $h = ord($str[$i]);
            if ($h <= 0x7F) {
                $uni = $h;
            } elseif ($h >= 0xC2) {
                if (($h <= 0xDF) && ($i < $len - 1)) {
                    $uni = ($h & 0x1F) << 6 | (ord($str[++$i]) & 0x3F);
                } elseif (($h <= 0xEF) && ($i < $len - 2)) {
                    $uni = ($h & 0x0F) << 12 | (ord($str[++$i]) & 0x3F) << 6 | (ord($str[++$i]) & 0x3F);
                } elseif (($h <= 0xF4) && ($i < $len - 3)) {
                    $uni = ($h & 0x0F) << 18 | (ord($str[++$i]) & 0x3F) << 12 | (ord($str[++$i]) & 0x3F) << 6 | (ord($str[++$i]) & 0x3F);
                }
            }
            if ($uni >= 0) {
                $out[] = $uni;
                if ($addSubset && isset($this->CurrentFont['subset'])) {
                    $this->CurrentFont['subset'][$uni] = $uni;
                }
            }
        }
        return $out;
    }



    function AddFont($family, $style = '')
    {
        if (empty($family)) {
            return;
        }

        $family = strtolower($family);
        $style = strtoupper($style);
        $style = str_replace('U', '', $style);

        if ($style == 'IB') {
            $style = 'BI';
        }

        $fontkey = $family . $style;

        // check if the font has been already added
        if (isset($this->fonts[$fontkey])) {
            return;
        }

      /*
        if (in_array($family, $this->available_CJK_fonts)) {
            if (empty($this->Big5_widths)) {
                require __DIR__ . '/../data/CJKdata.php';
            }
            $this->AddCJKFont($family); // don't need to add style
            return;
        }*/
        /* -- END CJK-FONTS -- */

        $stylekey = $style;
        if (!$style) {
            $stylekey = 'R';
        }

        if (!isset($this->fontdata[$family][$stylekey]) || !$this->fontdata[$family][$stylekey]) {
            throw new \Mpdf\MpdfException(sprintf('Font "%s%s%s" is not supported', $family, $style ? ' - ' : '', $style));
        }

        $name = '';
        $cw = '';
        $glyphIDtoUni = '';
        $originalsize = 0;
        $sip = false;
        $smp = false;
        $useOTL = 0; // mPDF 5.7.1
        $fontmetrics = ''; // mPDF 6
        $haskerninfo = false;
        $haskernGPOS = false;
        $hassmallcapsGSUB = false;
        $BMPselected = false;
        $GSUBScriptLang = [];
        $GSUBFeatures = [];
        $GSUBLookups = [];
        $GPOSScriptLang = [];
        $GPOSFeatures = [];
        $GPOSLookups = [];

        if ($this->fontCache->has($fontkey . '.mtx.php')) {
            require $this->fontCache->tempFilename($fontkey . '.mtx.php');
        }

        $ttffile = $this->fontFileFinder->findFontFile($this->fontdata[$family][$stylekey]);
        $ttfstat = stat($ttffile);

        if (isset($this->fontdata[$family]['TTCfontID'][$stylekey])) {
            $TTCfontID = $this->fontdata[$family]['TTCfontID'][$stylekey];
        } else {
            $TTCfontID = 0;
        }

        $fontUseOTL = isset($this->fontdata[$family]['useOTL']) ? $this->fontdata[$family]['useOTL'] : false;

        $BMPonly = false;


        $regenerate = false;
        if ($BMPonly && !$BMPselected) {
            $regenerate = true;
        } elseif (!$BMPonly && $BMPselected) {
            $regenerate = true;
        }

        // mPDF 5.7.1
        if ($fontUseOTL && $useOTL != $fontUseOTL) {
            $regenerate = true;
            $useOTL = $fontUseOTL;
        } elseif (!$fontUseOTL && $useOTL) {
            $regenerate = true;
            $useOTL = 0;
        }

        if ($this->fontDescriptor != $fontmetrics) {
            $regenerate = true;
        } // mPDF 6

        if (empty($name) || $originalsize != $ttfstat['size'] || $regenerate) {
            $generator = new MetricsGenerator($this->fontCache, $this->fontDescriptor);

            $generator->generateMetrics(
                $ttffile,
                $ttfstat,
                $fontkey,
                $TTCfontID,
                false,
                $BMPonly,
                $useOTL,
                $fontUseOTL
            );

            require $this->fontCache->tempFilename($fontkey . '.mtx.php');
            $cw = $this->fontCache->load($fontkey . '.cw.dat');
            $glyphIDtoUni = $this->fontCache->load($fontkey . '.gid.dat');
        } else {
            if ($this->fontCache->has($fontkey . '.cw.dat')) {
                $cw = $this->fontCache->load($fontkey . '.cw.dat');
            }

            if ($this->fontCache->has($fontkey . '.gid.dat')) {
                $glyphIDtoUni = $this->fontCache->load($fontkey . '.gid.dat');
            }
        }

        if (isset($this->fontdata[$family]['sip-ext']) && $this->fontdata[$family]['sip-ext']) {
            $sipext = $this->fontdata[$family]['sip-ext'];
        } else {
            $sipext = '';
        }

        // Override with values from config_font.php
        if (isset($this->fontdata[$family]['Ascent']) && $this->fontdata[$family]['Ascent']) {
            $desc['Ascent'] = $this->fontdata[$family]['Ascent'];
        }
        if (isset($this->fontdata[$family]['Descent']) && $this->fontdata[$family]['Descent']) {
            $desc['Descent'] = $this->fontdata[$family]['Descent'];
        }
        if (isset($this->fontdata[$family]['Leading']) && $this->fontdata[$family]['Leading']) {
            $desc['Leading'] = $this->fontdata[$family]['Leading'];
        }

        $i = count($this->fonts) + $this->extraFontSubsets + 1;
        if ($sip || $smp) {
            $this->fonts[$fontkey] = [
                'i' => $i,
                'type' => $type,
                'name' => $name,
                'desc' => $desc,
                'panose' => $panose,
                'unitsPerEm' => $unitsPerEm,
                'up' => $up,
                'ut' => $ut,
                'strs' => $strs,
                'strp' => $strp,
                'cw' => $cw,
                'ttffile' => $ttffile,
                'fontkey' => $fontkey,
                'subsets' => [0 => range(0, 127)],
                'subsetfontids' => [$i],
                'used' => false,
                'sip' => $sip,
                'sipext' => $sipext,
                'smp' => $smp,
                'TTCfontID' => $TTCfontID,
                'useOTL' => $fontUseOTL,
                'useKashida' => (isset($this->fontdata[$family]['useKashida']) ? $this->fontdata[$family]['useKashida'] : false),
                'GSUBScriptLang' => $GSUBScriptLang,
                'GSUBFeatures' => $GSUBFeatures,
                'GSUBLookups' => $GSUBLookups,
                'GPOSScriptLang' => $GPOSScriptLang,
                'GPOSFeatures' => $GPOSFeatures,
                'GPOSLookups' => $GPOSLookups,
                'rtlPUAstr' => $rtlPUAstr,
                'glyphIDtoUni' => $glyphIDtoUni,
                'haskerninfo' => $haskerninfo,
                'haskernGPOS' => $haskernGPOS,
                'hassmallcapsGSUB' => $hassmallcapsGSUB]; // mPDF 5.7.1	// mPDF 6
        } else {
            $ss = [];
            for ($s = 32; $s < 128; $s++) {
                $ss[$s] = $s;
            }
            $this->fonts[$fontkey] = [
                'i' => $i,
                'type' => $type,
                'name' => $name,
                'desc' => $desc,
                'panose' => $panose,
                'unitsPerEm' => $unitsPerEm,
                'up' => $up,
                'ut' => $ut,
                'strs' => $strs,
                'strp' => $strp,
                'cw' => $cw,
                'ttffile' => $ttffile,
                'fontkey' => $fontkey,
                'subset' => $ss,
                'used' => false,
                'sip' => $sip,
                'sipext' => $sipext,
                'smp' => $smp,
                'TTCfontID' => $TTCfontID,
                'useOTL' => $fontUseOTL,
                'useKashida' => (isset($this->fontdata[$family]['useKashida']) ? $this->fontdata[$family]['useKashida'] : false),
                'GSUBScriptLang' => $GSUBScriptLang,
                'GSUBFeatures' => $GSUBFeatures,
                'GSUBLookups' => $GSUBLookups,
                'GPOSScriptLang' => $GPOSScriptLang,
                'GPOSFeatures' => $GPOSFeatures,
                'GPOSLookups' => $GPOSLookups,
                'rtlPUAstr' => $rtlPUAstr,
                'glyphIDtoUni' => $glyphIDtoUni,
                'haskerninfo' => $haskerninfo,
                'haskernGPOS' => $haskernGPOS,
                'hassmallcapsGSUB' => $hassmallcapsGSUB
            ];
        }

        if ($haskerninfo) {
            $this->fonts[$fontkey]['kerninfo'] = $kerninfo;
        }

        $this->FontFiles[$fontkey] = [
            'length1' => $originalsize,
            'type' => 'TTF',
            'ttffile' => $ttffile,
            'sip' => $sip,
            'smp' => $smp
        ];

        unset($cw);
    }
}