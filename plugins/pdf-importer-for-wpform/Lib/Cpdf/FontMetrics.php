<?php
/**
 * @package dompdf
 * @link    http://dompdf.github.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @author  Helmut Tischer <htischer@weihenstephan.org>
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace rnpdfimporter\Lib\Cpdf;

use FontLib\Font;
use rnpdfimporter\core\Integration\FileManager;
use rnpdfimporter\core\Integration\Processors\Loader\ProcessorLoaderBase;
use rnpdfimporter\core\Loader;
use rnpdfimporter\pr\Managers\FontManager;

/**
 * The font metrics class
 *
 * This class provides information about fonts and text.  It can resolve
 * font names into actual installed font files, as well as determine the
 * size of text in a particular font and size.
 *
 * @static
 * @package dompdf
 */
class FontMetrics
{
    /**
     * Name of the font cache file
     *
     * This file must be writable by the webserver process only to update it
     * with save_font_families() after adding the .afm file references of a new font family
     * with FontMetrics::saveFontFamilies().
     * This is typically done only from command line with load_font.php on converting
     * ttf fonts to ufm with php-font-lib.
     */
    const CACHE_FILE = "dompdf_font_family_cache.php";

    /**
     * @var Canvas
     * @deprecated
     */
    protected $pdf;

    /**
     * Underlying {@link Canvas} object to perform text size calculations
     *
     * @var Canvas
     */
    protected $canvas;

    /**
     * Array of font family names to font files
     *
     * Usually cached by the {@link load_font.php} script
     *
     * @var array
     */
    public $fontLookup = array();

    /**
     * @var Options
     */
    private $options;
    private $RootPath;
    /** @var Loader */
    public $Loader;
    /**
     * Class initialization
     */
    public function __construct($loader)
    {
        $this->Loader=$loader;
        $fileManager=new FileManager($loader);
        $this->RootPath=$fileManager->GetFontMetricsPath();
        $this->loadFontFamilies();
    }

    /**
     * @deprecated
     */
    public function save_font_families()
    {
        $this->saveFontFamilies();
    }

    /**
     * Saves the stored font family cache
     *
     * The name and location of the cache file are determined by {@link
     * FontMetrics::CACHE_FILE}.  This file should be writable by the
     * webserver process.
     *
     * @see Font_Metrics::load_font_families()
     */
    public function saveFontFamilies()
    {

        file_put_contents($this->getCacheFile(), json_encode($this->fontLookup));
    }

    /**
     * @deprecated
     */
    public function load_font_families()
    {
        $this->loadFontFamilies();
    }

    /**
     * Loads the stored font family cache
     *
     * @see save_font_families()
     */
    public function loadFontFamilies()
    {
        $fontDir = $this->RootPath;
        $rootDir = $this->RootPath;

        // FIXME: tempoarary define constants for cache files <= v0.6.2
        if (!defined("DOMPDF_DIR")) { define("DOMPDF_DIR", $rootDir); }
        if (!defined("DOMPDF_FONT_DIR")) { define("DOMPDF_FONT_DIR", $fontDir); }

        $file = $rootDir . "font_family_cache.php";
        if(!file_exists($file))
        {
            file_put_contents($file,'[]');
        }

        $this->fontLookup= json_decode(file_get_contents($file),true);
        if(!isset($this->fontLookup['Default']))
        {
            $this->registerFont('Default',$this->Loader->DIR.'./Font/DejaVuSans.ttf');
        }

        if($this->Loader->IsPR())
        {
            $fontManager = new FontManager($this->Loader);
            $fonts = $fontManager->GetAvailableFonts(false);


            if ($fonts != null)
            {
                foreach ($fonts as $currentFont)
                {
                    $fontPath = $fontManager->folderPath . $currentFont->Name . '.ttf';
                    if (file_exists($fontPath) && !isset($this->fontLookup[$currentFont->Name]))
                        $this->registerFont($currentFont->Name, $fontPath);
                }
            }
        }



    }

    /**
     * @param array $style
     * @param string $remote_file
     * @param resource $context
     * @return bool
     * @deprecated
     */
    public function register_font($style, $remote_file, $context = null)
    {
        return $this->registerFont($style, $remote_file);
    }

    /**
     * @param array $style
     * @param string $remoteFile
     * @param resource $context
     * @return bool
     */
    public function registerFont($fontName,$fontFile, $context = null)
    {

        $font = Font::load($fontFile);
        $destiny=$this->RootPath.sanitize_file_name($fontName);


        $parts=pathinfo($fontFile);
        $fontDestiny=$destiny;


        $font->parse();
        $font->saveAdobeFontMetrics("$destiny.ufm");
        $font->close();


        if ( !file_exists("$destiny.ufm") ) {
            return false;
        }

        file_put_contents($fontDestiny.'.'.$parts['extension'],file_get_contents($fontFile));

        if ( !file_exists($fontDestiny.'.'.$parts['extension']) ) {
            unlink("$destiny.ufm");
            return false;
        }


        $this->setFontFamily($fontName,$fontDestiny);
        $this->saveFontFamilies();

        return true;
    }

    /**
     * @param $text
     * @param $font
     * @param $size
     * @param float $word_spacing
     * @param float $char_spacing
     * @return float
     * @deprecated
     */
    public function get_text_width($text, $font, $size, $word_spacing = 0.0, $char_spacing = 0.0)
    {
        //return self::$_pdf->get_text_width($text, $font, $size, $word_spacing, $char_spacing);
        return $this->getTextWidth($text, $font, $size, $word_spacing, $char_spacing);
    }

    /**
     * Calculates text size, in points
     *
     * @param string $text the text to be sized
     * @param string $font the desired font
     * @param float $size  the desired font size
     * @param float $wordSpacing
     * @param float $charSpacing
     *
     * @internal param float $spacing word spacing, if any
     * @return float
     */
    public function getTextWidth($text, $font, $size, $wordSpacing = 0.0, $charSpacing = 0.0)
    {
        // @todo Make sure this cache is efficient before enabling it
        static $cache = array();

        if ($text === "") {
            return 0;
        }

        // Don't cache long strings
        $useCache = !isset($text[50]); // Faster than strlen

        $key = "$font/$size/$wordSpacing/$charSpacing";

        if ($useCache && isset($cache[$key][$text])) {
            return $cache[$key]["$text"];
        }

        $width = $this->getCanvas()->get_text_width($text, $font, $size, $wordSpacing, $charSpacing);

        if ($useCache) {
            $cache[$key][$text] = $width;
        }

        return $width;
    }

    /**
     * @param $font
     * @param $size
     * @return float
     * @deprecated
     */
    public function get_font_height($font, $size)
    {
        return $this->getFontHeight($font, $size);
    }

    /**
     * Calculates font height
     *
     * @param string $font
     * @param float $size
     *
     * @return float
     */
    public function getFontHeight($font, $size)
    {
        return $this->getCanvas()->get_font_height($font, $size);
    }

    /**
     * @param $family_raw
     * @param string $subtype_raw
     * @return string
     * @deprecated
     */
    public function get_font($family_raw, $subtype_raw = "normal")
    {
        return $this->getFont($family_raw, $subtype_raw);
    }

    /**
     * Resolves a font family & subtype into an actual font file
     * Subtype can be one of 'normal', 'bold', 'italic' or 'bold_italic'.  If
     * the particular font family has no suitable font file, the default font
     * ({@link Options::defaultFont}) is used.  The font file returned
     * is the absolute pathname to the font file on the system.
     *
     * @param string $familyRaw
     * @param string $subtypeRaw
     *
     * @return string
     */
    public function getFont($familyRaw, $subtypeRaw = "normal")
    {
        static $cache = array();

        if (isset($cache[$familyRaw][$subtypeRaw])) {
            return $cache[$familyRaw][$subtypeRaw];
        }

        /* Allow calling for various fonts in search path. Therefore not immediately
         * return replacement on non match.
         * Only when called with NULL try replacement.
         * When this is also missing there is really trouble.
         * If only the subtype fails, nevertheless return failure.
         * Only on checking the fallback font, check various subtypes on same font.
         */

        $subtype = strtolower($subtypeRaw);

        if ($familyRaw) {
            $family = str_replace(array("'", '"'), "", strtolower($familyRaw));

            if (isset($this->fontLookup[$family][$subtype])) {
                return $cache[$familyRaw][$subtypeRaw] = $this->fontLookup[$family][$subtype];
            }

            if (isset($this->fontLookup[$family]['normal'])) {
                return $cache[$familyRaw][$subtypeRaw] = $this->fontLookup[$family]['normal'];
            }

            return null;
        }

        $family = "serif";

        if (isset($this->fontLookup[$family][$subtype])) {
            return $cache[$familyRaw][$subtypeRaw] = $this->fontLookup[$family][$subtype];
        }

        if (!isset($this->fontLookup[$family])) {
            return null;
        }

        $family = $this->fontLookup[$family];

        foreach ($family as $sub => $font) {
            if (strpos($subtype, $sub) !== false) {
                return $cache[$familyRaw][$subtypeRaw] = $font;
            }
        }

        if ($subtype !== "normal") {
            foreach ($family as $sub => $font) {
                if ($sub !== "normal") {
                    return $cache[$familyRaw][$subtypeRaw] = $font;
                }
            }
        }

        $subtype = "normal";

        if (isset($family[$subtype])) {
            return $cache[$familyRaw][$subtypeRaw] = $family[$subtype];
        }

        return null;
    }

    /**
     * @param $family
     * @return null|string
     * @deprecated
     */
    public function get_family($family)
    {
        return $this->getFamily($family);
    }

    /**
     * @param string $family
     * @return null|string
     */
    public function getFamily($family)
    {
        $family = str_replace(array("'", '"'), "", mb_strtolower($family));

        if (isset($this->fontLookup[$family])) {
            return $this->fontLookup[$family];
        }

        return null;
    }

    /**
     * @param $type
     * @return string
     * @deprecated
     */
    public function get_type($type)
    {
        return $this->getType($type);
    }

    /**
     * @param string $type
     * @return string
     */
    public function getType($type)
    {
        if (preg_match("/bold/i", $type)) {
            if (preg_match("/italic|oblique/i", $type)) {
                $type = "bold_italic";
            } else {
                $type = "bold";
            }
        } elseif (preg_match("/italic|oblique/i", $type)) {
            $type = "italic";
        } else {
            $type = "normal";
        }

        return $type;
    }

    /**
     * @return array
     * @deprecated
     */
    public function get_font_families()
    {
        return $this->getFontFamilies();
    }

    /**
     * Returns the current font lookup table
     *
     * @return array
     */
    public function getFontFamilies()
    {
        return $this->fontLookup;
    }

    /**
     * @param string $fontname
     * @param mixed $entry
     * @deprecated
     */
    public function set_font_family($fontname, $entry)
    {
        $this->setFontFamily($fontname, $entry);
    }

    /**
     * @param string $fontname
     * @param mixed $entry
     */
    public function setFontFamily($fontname, $entry)
    {
        $this->fontLookup[$fontname] = $entry;
    }

    /**
     * @return string
     */
    public function getCacheFile()
    {
        return $this->RootPath.'font_family_cache.php';
    }

    /**
     * @param Options $options
     * @return $this
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Canvas $canvas
     * @return $this
     */
    public function setCanvas(Canvas $canvas)
    {
        $this->canvas = $canvas;
        // Still write deprecated pdf for now. It might be used by a parent class.
        $this->pdf = $canvas;
        return $this;
    }

    /**
     * @return Canvas
     */
    public function getCanvas()
    {
        return $this->canvas;
    }
}