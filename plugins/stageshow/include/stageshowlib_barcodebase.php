<?php

if (!class_exists('BarcodeBase'))
{
	class BarcodeBase // Define class
	{
		function createImage($text='', $showText=true)
		{
		}
		
		public function outputBarcode($text='', $showText=true)
		{
			$bin = $this->createImage($text, $showText);
			
			header("Content-type:  image/{$this->fileType}");
			StageShowLibEscapingClass::Safe_EchoHTML($bin);		
		}
			
		public function CreateBarcodeImage($text)
		{
			$bin = $this->createImage($text);			
			return $bin;
		}		
			
		public function toFile($text, $fileName, $showText=true)
		{
			$bin = $this->createImage($text, $showText, $fileName);
			file_put_contents($fileName, $bin);
		}
		
	}
}
	
