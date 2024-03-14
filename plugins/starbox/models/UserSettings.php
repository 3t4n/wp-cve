<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

class ABH_Models_UserSettings {

    /**
     * Add the image for gravatar
     *
     * @param string $file
     * @return array [name (the name of the file), image (the path of the image), message (the returned message)]
     *
     */
    public function addImage($file) {
        $out = array();

        add_filter('upload_dir', function ($upload_dir) {
            $upload_dir['path'] = rtrim(_ABH_GRAVATAR_DIR_, '/');
            $upload_dir['url'] = rtrim(_ABH_GRAVATAR_URL_, '/');
            $upload_dir['subdir'] = '';
            $upload_dir['basedir'] = $upload_dir['path'];
            $upload_dir['baseurl'] = $upload_dir['url'];
            return $upload_dir;
        });

        $movefile = wp_handle_upload($file, array('action' => 'update'));

        if ($movefile && !isset($movefile['error'])) {
            //print_R($movefile);
            $out['name'] = strtolower(basename($movefile['file']));
            $out['gravatar'] = $movefile['file'];

            $img = new Model_ABH_Image();
            /* Transform the image into icon */
            $img->openImage($out['gravatar']);
            $img->resizeImage(ABH_IMAGESIZE, ABH_IMAGESIZE);
            $img->saveImage();

            if(!$img->isError()) {
                copy($img->image, strtolower($out['gravatar']));
            }

            $out['message'] = __("The gravatar has been updated.", _ABH_PLUGIN_NAME_);

        } else {
            ABH_Classes_Error::setError($movefile['error']);
            $out['message'] = $movefile['error'];
        }

        return $out;
    }

}

/**
 * Upload the image to the server
 */
class Model_ABH_Image {

    var $imageType;
    var $imgH;
    var $image;
    var $quality = 100;
    var $error = false;

    public function isError(){
        return $this->error;
    }

    public function openImage($image) {
        $this->image = $image;

        if (!file_exists($image)) {
            $this->error = true;
            return;
        }

        $imageData = @getimagesize($image);

        if (!$imageData) {
            $this->error = true;
        } else {
            $this->imageType = @image_type_to_mime_type($imageData[2]);

            switch ($this->imageType) {
                case 'image/gif':
                    if (function_exists('imagecreatefromgif')) {
                        $this->imgH = imagecreatefromgif($image);
                        imagealphablending($this->imgH, true);
                    }
                    break;
                case 'image/png':
                    if (function_exists('imagecreatefrompng')) {
                        $this->imgH = imagecreatefrompng($image);
                        imagealphablending($this->imgH, true);
                    }
                    break;
                case 'image/jpg':
                case 'image/jpeg':
                    if (function_exists('imagecreatefromjpeg')) {
                        $this->imgH = imagecreatefromjpeg($image);
                    }
                    break;

                // CHANGED EXCEPTION TO RETURN FALSE
                default:
                    $this->error = true;
            }
        }
    }

    public function saveImage() {
        switch ($this->imageType) {
            case 'image/jpg':
            case 'image/jpeg':
                if (function_exists('imagejpeg')) {
                    return @imagejpeg($this->imgH, $this->image, $this->quality);
                }
                break;
            case 'image/gif':
                if (function_exists('imagegif')) {
                    return @imagegif($this->imgH, $this->image);
                }
                break;
            case 'image/png':
                if (function_exists('imagepng')) {
                    return @imagepng($this->imgH, $this->image);
                }
                break;
            default:
                if (function_exists('imagejpeg')) {
                    return @imagejpeg($this->imgH, $this->image);
                }
        }
        if (function_exists('imagedestroy')) {
            @imagedestroy($this->imgH);
        }
    }

    public function resizeImage($maxwidth, $maxheight, $preserveAspect = true) {
        if (!function_exists('imagesx')) {
            $this->error = true;
            return;
        }

        $width = @imagesx($this->imgH);
        $height = @imagesy($this->imgH);

        if ($width > $maxwidth && $height > $maxheight) {
            $oldprop = round($width / $height, 2);
            $newprop = round($maxwidth / $maxheight, 2);
            $preserveAspectx = round($width / $maxwidth, 2);
            $preserveAspecty = round($height / $maxheight, 2);

            if ($preserveAspect) {
                if ($preserveAspectx < $preserveAspecty) {
                    $newwidth = $width / ($height / $maxheight);
                    $newheight = $maxheight;
                } else {
                    $newwidth = $maxwidth;
                    $newheight = $height / ($width / $maxwidth);
                }

                $dest = @imagecreatetruecolor($newwidth, $newheight);
                $this->applyTransparency($dest);
                // CHANGED EXCEPTION TO RETURN FALSE
                if (@imagecopyresampled($dest, $this->imgH, 0, 0, 0, 0, $newwidth, $newheight, $width, $height) == false)
                    $this->error = true;
            } else {
                $dest = @imagecreatetruecolor($maxwidth, $maxheight);
                $this->applyTransparency($dest);
                // CHANGED EXCEPTION TO RETURN FALSE
                if (@imagecopyresampled($dest, $this->imgH, 0, 0, 0, 0, $maxwidth, $maxheight, $width, $height) == false)
                    $this->error = true;
            }
            $this->imgH = $dest;
        }
    }

    public function applyTransparency($imgH) {
        if ($this->imageType == 'image/png' || $this->imageType == 'image/gif') {
            imagealphablending($imgH, false);
            $col = imagecolorallocatealpha($imgH, 255, 255, 255, 127);
            imagefilledrectangle($imgH, 0, 0, 485, 500, $col);
            imagealphablending($imgH, true);
        }
    }

    public function checkFunctions() {
        return function_exists('gd_info');
    }

}