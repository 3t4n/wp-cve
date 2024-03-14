<?php

class Qrcode_from_Goaskle_Com
{
    public $cacheDir;
    
    private $timeStart;    // used for creation time measure
    
    const CREATIONLOG    = 'creation.log';   // name of the creation timing file
    const CACHEERRORFILE = 'cacheerror.gif'; // name of the cache error file (write permissions missing)
    
    /**
     * Generate the QR Code object and initializes the Cache directory
     */
    public function __construct()
    {
        // initialize cache directory
        $this->cacheDir = dirname(__FILE__).'/../../data/';
    }    
    
    //compatiblity
    public function Qrcode_from_Goaskle_Com(){
    self::__construct();
    }        
    
    
    /**
     * Check if file extension is as expected
     * 
     * @param  string    $filename    the filename including extension
     * @param  string    $extension   expected extension
     * @return bool
     */
    public function isExtension($filename, $extension)
    { 
        // convert to lower string and extract file extension
        return (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) == strtolower($extension));
    }
    
    /**
     * Save image type based on file extension
     * 
     * @param  resource      $img    GD image
     * @param  string        $file   complete file path
     */
    public function saveImage($img, $file) 
    {
        if ($this->isExtension($file,'png')) {
            imagepng($img, $file);
        } elseif ($this->isExtension($file,'gif')) {
            imagegif($img, $file);
        } else {
            imagejpeg($img, $file);
        }
    }
    
    /**
     * Stub for QR Code creation
     * 
     * @param  mixed    $content    QR Code content
     * @param  string   $file       image file name
     * @param  integer  $size       size of the image
     * @param  string   $enc        encoding of the content
     * @param  string   $ecc        error correction code type
     * @param  integer  $margin     QR Code image
     * @param  integer  $version    QR Code version
     */
    public function create($content, $file, $size, $enc, $ecc, $margin, $version) 
    {
        // not declared as abstract because of cache usage, well this could be static.
    }

    /**
     * Start execution timer
     */
    public function startTimer()
    {
        $this->timeStart = microtime(true);
    }
    
    
    /**
     * Stop execution Timer and store execution time
     */
    public function stopTimer()
    {
        // time = end - start
        $time = microtime(true) - $this->timeStart;
        
        // if cache directory is writeable store execution time
        if (is_writable($this->cacheDir)) {
            $file = $this->cacheDir.self::CREATIONLOG;
            $fh = fopen($file, 'a') or die("can't open file");
            fwrite($fh, '<?php $creationTimes[]='.$time."; ?>\n");
            fclose($fh);
        }
    }
    
    
    /**
     * Remove whitespace from image and resize with specified margin
     *  
     * @param  resource   $image       GD image
     * @param  integer    $size        size of the resulting image
     * @param  integer    $margin      including this whitespace margin
     * @return resource    
     */
    public function cropImage($image, $size, $margin) 
    {
        // get image dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        $refColor = imagecolorat($image, 0,0); // get top left pixel as reference
        
        // find top line
        $topY = -1;
        $sameColor = TRUE;
        while (($sameColor) && ($topY < $height-1)) {
            $topY++;
            $x = 0;
            while (($sameColor) && ($x < $width)) {
                $sameColor = (imagecolorat($image, $x, $topY) == $refColor);
                $x++;
            }
        }
        if ($sameColor) {
            $topY = 0;
        }

        // find bottom line
        $bottomY = $height;
        $sameColor = TRUE;
        while (($sameColor) && ($bottomY >= 0)) {
            $x = 0;
            $bottomY--;
            while (($sameColor) && ($x<$width)) {
                $sameColor = (imagecolorat($image, $x, $bottomY) == $refColor);
                $x++;
            }
        }
        if ($sameColor) {
            $bottomY = $height-1;
        }
        
        // find left border
        $leftX = -1;
        $sameColor = TRUE;
        while (($sameColor) && ($leftX < $width-1)) {
            $leftX++;
            $y = $topY;
            while (($sameColor) && ($y <= $bottomY)) {
                $sameColor = (imagecolorat($image, $leftX, $y) == $refColor);
                $y++;
            }
        }
        if ($sameColor) {
            $leftX = 0;
        }
        
        // find right border
        $rightX = $width;
        $sameColor = TRUE;
        while (($sameColor) && ($rightX >= 0)) {
            $rightX--;
            $y = $topY;
            while (($sameColor) && ($y <= $bottomY)) {
                $sameColor = (imagecolorat($image, $rightX, $y) == $refColor);
                $y++;
            }
        }
        if ($sameColor) {
            $rightX = $width-1;
        }

        // calculate dimensions
        $cropWidth = $rightX-$leftX+1;
        $cropHeight = $bottomY-$topY+1;
        $croppedWidth = $cropWidth + $margin*2;
        $croppedHeight = $cropHeight + $margin*2;
  
//  echo '<pre>'; print_r($cropImage); echo '</pre>';    
        
        // create cropped image and fill it with white
        $cropImage = imagecreate($croppedWidth, $croppedHeight);
                //$cropImage = imagecolorallocate($cropImageSize, 255, 0, 0);

//          echo '<pre>'; print_r($bg); echo '</pre>';            

if (isset(get_option('qrct_from_goaskle_com_options')['shortcode']['dots_color'])){
$qr_dots_color = get_option('qrct_from_goaskle_com_options')['shortcode']['dots_color'];	
} else {
	$qr_dots_color = '';
}

if ( !function_exists('hex2rgba')) {

function hex2rgba( $color, $default = array(0,0,0) ) {
	//$default = array(0,0,0);

	if( empty( $color ) ) {
        return $default; 
	}

    if ( $color[0] == '#' ) {
    	$color = substr( $color, 1 );
    }

    if ( strlen($color) == 6 ) {
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
        return $default;
    }

  return array_map( 'hexdec', $hex );
}
}

$dots_color_res = (hex2rgba( $qr_dots_color, array(255,255,255) )) ? hex2rgba( $qr_dots_color, array(255,255,255) ) : array(255,255,255);
        
        imagefill($cropImage, 0, 0, imagecolorallocate($cropImage, $dots_color_res[0], $dots_color_res[1], $dots_color_res[2]));

 
   
        // crop it with margin
        imagecopy($cropImage, $image, $margin, $margin, $leftX, $topY, $cropWidth, $cropHeight);

        // if zoom mode is specified, change size
        if ($size<10) {
            $size = $croppedWidth*($size+1); 
        }
        
        // resize
        $outputImage = imagecreate($size, $size);
        imagecopyresized($outputImage, $cropImage, 0, 0, 0, 0, $size, $size, $croppedWidth, $croppedHeight);



        // destroy temporary cropImage
        imagedestroy($cropImage);

          
        return $outputImage;
    }

    /**
     * Get a QR Code file name (either create one or load from cache)
     * 
     * @param  mixed    $content    QR Code content
     * @param  string   $fileExt    image file extension    
     * @param  integer  $size       (optional) size of the image (defaults to 125)
     * @param  string   $enc        (optional) encoding of the content (defaults to UTF-8)
     * @param  string   $ecc        (optional) error correction code type (defaults to L)
     * @param  integer  $margin     (optional) QR Code image (defaults to 4)
     * @param  integer  $version    (optional) QR Code version (defaults to 0=auto)
     * @return string
     */
    public function get($content, $fileExt, $size = '125', $enc = 'UTF-8', $ecc = 'L', $margin = '4', $version = 0) 
    {
        //print_r($content);exit;
        // create hash parameters        
        $params = array('d'=>$content, 's'=>$size, 'e'=>$enc, 'c'=>$ecc, 'm'=>$margin, 'v'=>$version);
        
        // create cacheId file name based on hash parameters
        $cacheId = 'qrct-'.md5(serialize($params)).'.'.$fileExt;

//06 03 23 goaskle qr code radius around image
if ( !function_exists('imageCreateCorners')) {
  function imageCreateCorners($sourceImageFile, $radius) {
    # test source image
    if (file_exists($sourceImageFile)) {
      $res = is_array($info = getimagesize($sourceImageFile));
      } 
    else $res = false;


 
    # open image
    if ($res) {
      $w = $info[0];
      $h = $info[1];
      switch ($info['mime']) {
        case 'image/jpeg': $src = imagecreatefromjpeg($sourceImageFile);
          break;
        case 'image/gif': $src = imagecreatefromgif($sourceImageFile);
          break;
        case 'image/png': $src = imagecreatefrompng($sourceImageFile);
          break;
        default: 
          $res = false;
        }
      }


    # create corners
    if ($res) {

      $q = 10; # change this if you want
      $radius *= $q;

      # find unique color
      do {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        }
      while (imagecolorexact($src, $r, $g, $b) < 0);

      $nw = $w*$q;
      $nh = $h*$q;

       //echo '<pre>'; print_r('tut1'); echo '</pre>';                  
       
      $img = imagecreatetruecolor($nw, $nh);
      $alphacolor = imagecolorallocatealpha($img, $r, $g, $b, 127);
      imagealphablending($img, false);

      imagesavealpha($img, true);

      imagefilledrectangle($img, 0, 0, $nw, $nh, $alphacolor);

      imagefill($img, 0, 0, $alphacolor);
      imagecopyresampled($img, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

      imagearc($img, $radius-1, $radius-1, $radius*2, $radius*2, 180, 270, $alphacolor);
      imagefilltoborder($img, 0, 0, $alphacolor, $alphacolor);
      imagearc($img, $nw-$radius, $radius-1, $radius*2, $radius*2, 270, 0, $alphacolor);
      imagefilltoborder($img, $nw-1, 0, $alphacolor, $alphacolor);
      imagearc($img, $radius-1, $nh-$radius, $radius*2, $radius*2, 90, 180, $alphacolor);
      imagefilltoborder($img, 0, $nh-1, $alphacolor, $alphacolor);
      imagearc($img, $nw-$radius, $nh-$radius, $radius*2, $radius*2, 0, 90, $alphacolor);
      imagefilltoborder($img, $nw-1, $nh-1, $alphacolor, $alphacolor);
      imagealphablending($img, true);
      imagecolortransparent($img, $alphacolor);

      # resize image down
      $dest = imagecreatetruecolor($w, $h);
      imagealphablending($dest, false);
      imagesavealpha($dest, true);
      imagefilledrectangle($dest, 0, 0, $w, $h, $alphacolor);
      imagecopyresampled($dest, $img, 0, 0, 0, 0, $w, $h, $nw, $nh);

      # output image
      $res = $dest;
      imagedestroy($src);
      imagedestroy($img);
      }

    return $res;
    }
}

//        

//$cacheId = imageCreateCorners($cacheId,40);

 
        // prepare cache directory
        $cacheFilePath = $this->cacheDir.$cacheId;
        
        //$cacheFilePath = imageCreateCorners($cacheFilePath,40);
// echo '<pre>'; print_r($cacheFilePath); echo '</pre>';                  
        $cacheWriteable = is_writable($this->cacheDir);
        
        if (file_exists($cacheFilePath)) { // if file alreas exists, return filename
            return $cacheId;
        } elseif (!is_writable($this->cacheDir)) { // if cache directory isn't writeable return error file
            return self::CACHEERRORFILE;
        } else { // create new code and return filename
            $this->startTimer();
            $this->create($content, $cacheFilePath, $size, $enc, $ecc, $margin, $version);
            $this->stopTimer();
            return $cacheId;
        }
    }
    
    /**
     * Clear QR Code Cache
     */
    public function clearCache()
    {
        // if cache is writeable then scan directory for files
        if (is_writable($this->cacheDir)) {
            $files = scandir($this->cacheDir);
            foreach($files as $file) {
                if ( (($this->isExtension($file,'gif')) ||
                      ($this->isExtension($file,'png')) ||
                      ($this->isExtension($file,'jpg'))) && ($file != self::CACHEERRORFILE)) {
                          // delete if it's an image but not the cache Error File
                         unlink($this->cacheDir.$file);
                }
            }
            // if creation log exists, delete this also
            $creationLog = $this->cacheDir.self::CREATIONLOG;
            if (file_exists($creationLog)) {
                unlink ($creationLog);
            }
        }
    }
    
    /**
     * Return current QR Code Cache state
     * 
     * @param integer    &$cacheFiles        number of files in the cache
     * @param integer    &$cacheSize         cacheSize in Bytes 
     * @param float      &$avgCreationTime   average QR Code creation time
     */
    public function cacheState(&$cacheFiles, &$cacheSize, &$avgCreationTime)
    {
        // reset values
        $cacheFiles = 0;
        $cacheSize = 0;
        $avgCreationTime = 0;
        
        // scan cache directory and count files and size
        $files = scandir($this->cacheDir);
        foreach($files as $file) {
                if ( (($this->isExtension($file,'gif')) ||
                      ($this->isExtension($file,'png')) ||
                      ($this->isExtension($file,'jpg'))) && ($file != self::CACHEERRORFILE)) {
                         $cacheFiles++;
                         $cacheSize += filesize($this->cacheDir.$file);
                }
        }
        
        // read creation log
        $creationLog = $this->cacheDir.self::CREATIONLOG;
        
        // only if exists and calculate average creation time
        if (file_exists($creationLog)) {
            $creationTimes = array();
            include($creationLog);
            foreach($creationTimes as $creationTime) {
                $avgCreationTime += $creationTime;
            }
            if (count($creationTimes)>0) {            
                $avgCreationTime = $avgCreationTime / count($creationTimes);
            }
        }
    }
    
}