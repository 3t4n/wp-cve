<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Utils;

use InvisibleReCaptcha\MchLib\Exceptions\MchLibException;

class MchImageUtils
{
	public static function isValidImageFile($filePath)
	{

	}

	public static function getSize($filePath)
	{
		$arrImageInfo = @getimagesize($filePath);
		$arrImageSize = array();
		!isset($arrImageInfo[0]) ?: $arrImageSize['width']  = $arrImageInfo[0];
		!isset($arrImageInfo[0]) || !isset($arrImageInfo[1]) ?: $arrImageSize['height'] = $arrImageInfo[1];

		return $arrImageSize;
	}

	/**
	 * @return string|null
	 * @throws \InvisibleReCaptcha\MchLib\Exceptions\MchLibException
	 */
	public static function resize($filePath, $destinationFilePath, $width, $height, $crop = true, $quality = 90)
	{
		$imageEditor = wp_get_image_editor($filePath);

		if(MchWpUtils::isWPError($imageEditor)){
			throw new MchLibException('Failed to load WP Image Editor');
		}

		($quality === 90 || !is_numeric($quality)) ?: $imageEditor->set_quality($quality);

		if(MchWpUtils::isWPError($saveResult = $imageEditor->resize($width, $height, $crop))){
			throw new MchLibException($saveResult->get_error_message());
		}

		if(MchWpUtils::isWPError($saveResult = $imageEditor->save($destinationFilePath))){
			throw new MchLibException($saveResult->get_error_message());
		}

		return $saveResult;

		return !empty($saveResult['path']) ? $saveResult['path'] : null;

	}

	/**
	 * @return string|null
	 */
	public static function crop($filePath, $destinationFilePath, $xCoordinate, $yCoordinate, $width, $height)
	{
		$imageEditor = wp_get_image_editor($filePath);

		if(MchWpUtils::isWPError($imageEditor)){
			throw new MchLibException('Failed to load WP Image Editor');
		}

		if(MchWpUtils::isWPError($saveResult = $imageEditor->crop($xCoordinate, $yCoordinate, $width, $height))){
			throw new MchLibException($saveResult->get_error_message());
		}

		if(MchWpUtils::isWPError($saveResult = $imageEditor->save($destinationFilePath))){
			throw new MchLibException($saveResult->get_error_message());
		}

		return $saveResult;

		return !empty($saveResult['path']) ? $saveResult['path'] : null;

//		return array(
//			'path'      => $filename,
//			'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', $filename ) ),
//			'width'     => $this->size['width'],
//			'height'    => $this->size['height'],
//			'mime-type' => $mime_type,
//		);


	}


}