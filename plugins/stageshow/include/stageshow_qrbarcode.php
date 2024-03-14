<?php

if (!class_exists('QRcode'))
	include STAGESHOW_FILE_PATH.'lib/phpqrcode/qrlib.php';

if (!class_exists('StageShowLibQRCodeClass'))
{
	class StageShowLibQRCodeClass extends QRcode // Define class
	{
		const BARCODE_TYPE_QRCODE = 'qrcode';
		
		function __construct($code_type = self::BARCODE_TYPE_CODE39, $orientation = self::BARCODE_HORIZONTAL, $size = 20, $mode = 'png')
		{
			$this->mode = $mode;
			
			$this->level = QR_ECLEVEL_L; 
			$this->size = 3; 
			$this->margin = 4;
		}
		
		public function CreateBarcodeImage($text)
		{
		    $enc = StageShowLibQRencodeClass::factory($this->level, $this->size, $this->margin);


			switch ($this->mode)
			{
				case 'png':
		            $bin = $enc->encodePNG($text);
		            break;
					
				case 'img':
		            $bin = $enc->encodeImg($text);
		            break;
				
				default:
					return null;
			}
			
			return $bin;
		}		
			
	}
	
 	class StageShowLibQRencodeClass extends QRencode // Define class
	{
        public static function factory($level = QR_ECLEVEL_L, $size = 3, $margin = 4)
        {
        	$enc = parent::factory($level, $size, $margin);
        	
        	$ourenc =  new StageShowLibQRencodeClass();
            $ourenc->size = $enc->size;
            $ourenc->margin = $enc->margin;
            $ourenc->level = $enc->level;
       	
        	return $ourenc;
        }
        
		public function encodePNG($intext, $outfile = false, $saveandprint=false) 
        {
            try {
            
                ob_start();
                $tab = $this->encode($intext);
                $err = ob_get_contents();
                ob_end_clean();
                
                if ($err != '')
                    QRtools::log($outfile, $err);
                
                $maxSize = (int)(QR_PNG_MAXIMUM_SIZE / (count($tab)+2*$this->margin));
                
 				ob_start();
				StageShowLibQRimageClass::png($tab, $outfile, min(max(1, $this->size), $maxSize), $this->margin,$saveandprint);
				$bin = ob_get_contents();
				ob_end_clean();
				
				return $bin;							
            } 
            catch (Exception $e) 
            {           
                QRtools::log($outfile, $e->getMessage());
                return null;           
            }
        }
        
		public function encodeImg($intext, $outfile = false, $saveandprint=false) 
        {
            try {
            
                ob_start();
                $tab = $this->encode($intext);
                $err = ob_get_contents();
                ob_end_clean();
                
                if ($err != '')
                    QRtools::log($outfile, $err);
                
                $maxSize = (int)(QR_PNG_MAXIMUM_SIZE / (count($tab)+2*$this->margin));
                
				$bin = StageShowLibQRimageClass::img($tab, $outfile, min(max(1, $this->size), $maxSize), $this->margin,$saveandprint);
				
				return $bin;							
            } 
            catch (Exception $e) 
            {           
                QRtools::log($outfile, $e->getMessage());
                return null;           
            }
        }
	}
	
	class StageShowLibQRimageClass extends QRimage // Define class
	{
        public static function img($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveandprint=FALSE) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            return $image;
        }
            
        public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveandprint=FALSE) 
        {
            $image = self::img($frame, $filename, $pixelPerPoint, $outerFrame, $saveandprint);
            
			ImagePng($image);
            ImageDestroy($image);           
        }
    
	}
}

?>