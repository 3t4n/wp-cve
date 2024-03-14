<?php

/**
 * 文章配图生成类
 */
class Article_With_Pictures_Api
{
    /**
     * @var int 图片宽度
     */
    private $width;

    /**
     * @var int 图片高度
     */
    private $height;

    /**
     * @var int 文字行高间距
     */
    private $lineHeight = 8;

    /**
     * @var array 图片背景颜色RGB
     */
    private $backgroundRGB = array(255, 255, 255);

    /**
     * @var array 文字颜色RGB
     */
    private $textRGB = array(0, 0, 0);

    /**
     * @var string 文字
     */
    private $text;

    /**
     * @var float 字体大小
     */
    private $fontSize = 20;

    /**
     * @var string 字体文件路径
     */
    private $fontFile;

    /**
     * @var bool 是否开启多行文字。如果文字一行显示不了，则多行显示
     */
    private $isMultiLine = false;

    /**
     * @var string 错误信息
     */
    private $error = '';

    /**
     * @var int 图片最多有多少行，针对于多行文字
     */
    private $maxLineNum = 0;

    /**
     * @var int 图片每行最多多少个文字
     */
    private $maxLineTextNum = 0;

    /**
     * @var int 默认每行文字减少文字个数
     */
    private $reduceLineTextNum = 0;

    /**
     * 获取图片宽度
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * 设置图片宽度
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * 获取图片高度
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * 设置图片高度
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * 获取背景颜色的RGB
     * @return array
     */
    public function getBackgroundRGB()
    {
        return $this->backgroundRGB;
    }

    /**
     * 设置背景颜色的RGB
     * @param array $backgroundRGB
     */
    public function setBackgroundRGB($backgroundRGB)
    {
        $this->backgroundRGB = $backgroundRGB;
    }

    /**
     * 获取文字颜色的RGB
     * @return array
     */
    public function getTextRGB()
    {
        return $this->textRGB;
    }

    /**
     * 设置文字颜色的RGB
     * @param array $textRGB
     */
    public function setTextRGB($textRGB)
    {
        $this->textRGB = $textRGB;
    }

    /**
     * 获取文字
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * 设置文字
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = trim($text);
    }

    /**
     * 获取文字大小
     * @return float
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * 设置文字大小
     * @param float $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }

    /**
     * 获取字体文件
     * @return string
     */
    public function getFontFile()
    {
        return $this->fontFile;
    }

    /**
     * 设置字体文件
     * @param string $fontFile
     */
    public function setFontFile($fontFile)
    {
        $this->fontFile = $fontFile;
    }

    /**
     * 判断是否为多行文字
     * @return bool
     */
    public function isMultiLine()
    {
        return $this->isMultiLine;
    }

    /**
     * 设置为多行文字
     * @param bool $isMultiLine
     */
    public function setIsMultiLine($isMultiLine)
    {
        $this->isMultiLine = $isMultiLine;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置错误信息
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * 获取最多行数
     * @return int
     */
    public function getMaxLineNum()
    {
        return $this->maxLineNum;
    }

    /**
     * 设置最多行数
     * @param int $maxLineNum
     */
    public function setMaxLineNum($maxLineNum)
    {
        $this->maxLineNum = $maxLineNum;
    }

    /**
     * 获取每行最多文字个数
     * @return int
     */
    public function getMaxLineTextNum()
    {
        return $this->maxLineTextNum;
    }

    /**
     * 设置每行最多文字个数
     * @param int $maxLineTextNum
     */
    public function setMaxLineTextNum($maxLineTextNum)
    {
        $this->maxLineTextNum = $maxLineTextNum;
    }

    /**
     * 获取每行文字减少文字个数
     * @return int
     */
    public function getReduceLineTextNum()
    {
        return $this->reduceLineTextNum;
    }

    /**
     * 设置每行文字减少文字个数
     * @param int $reduceLineTextNum
     */
    public function setReduceLineTextNum($reduceLineTextNum)
    {
        $this->reduceLineTextNum = $reduceLineTextNum;
    }

    /**
     * 设置文字行间距
     * @return int
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }

    /**
     * 获取文字行间距
     * @param int $lineHeight
     */
    public function setLineHeight($lineHeight)
    {
        $this->lineHeight = $lineHeight;
    }

    /**
     * 构造方法
     * @param int $width 图片宽度
     * @param int $height 图片高度
     */
    public function __construct($width, $height)
    {
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * 获取颜色的RGB
     * @param string $hexColor 颜色
     * @return bool|array
     */
    public function getRGB($hexColor)
    {
        if (!preg_match('/^#[0-9a-f]{3,6}$/i', $hexColor)) {
            $this->setError('颜色值不符合规则');
            return false;
        }
        $hexColor = substr($hexColor, 1);
        $len = strlen($hexColor);
        if (3 === $len) {
            return array(
                hexdec($hexColor[0] . $hexColor[0]),
                hexdec($hexColor[1] . $hexColor[1]),
                hexdec($hexColor[2] . $hexColor[2])
            );
        } else if (6 === $len) {
            return array(
                hexdec($hexColor[0] . $hexColor[1]),
                hexdec($hexColor[2] . $hexColor[3]),
                hexdec($hexColor[4] . $hexColor[5])
            );
        } else {
            $this->setError('颜色值长度不符合规则');
            return false;
        }
    }

    /**
     * 获取图片Im资源
     * @return false|GdImage|resource
     */
    public function getIm()
    {
        $im = imagecreatetruecolor($this->width, $this->height);
        if (false === $im) {
            $this->setError('创建真彩色图像失败');
            return false;
        }
        imagealphablending($im, true);
        imageantialias($im, true);
        // 使用背景颜色
        imagefilledrectangle($im, 0, 0, imagesx($im) - 1, imagesy($im) - 1, imagecolorallocate($im, $this->backgroundRGB[0], $this->backgroundRGB[1], $this->backgroundRGB[2]));
        if (!empty($this->text) && !empty($this->fontFile) && file_exists($this->fontFile)) {
            // 写文字
            // 获取当前字体下的每个文字宽度和高度
            $wordBbox = imagettfbbox($this->fontSize, 0, $this->fontFile, '果');
            $wordWidth = abs($wordBbox[0] - $wordBbox[4]) + abs($wordBbox[0] * 2);
            $wordHeight = abs($wordBbox[1] - $wordBbox[5]) + abs($wordBbox[1] * 2);
            $wordHeight += $this->lineHeight;
            // 图片上每行最多文字
            $lineTextNum = floor($this->width / $wordWidth);
            if (!empty($this->maxLineTextNum) && $lineTextNum > $this->maxLineTextNum) {
                $lineTextNum = $this->maxLineTextNum;
            }
            // 图片上减少文字个数
            if (!empty($this->reduceLineTextNum) && $lineTextNum > $this->reduceLineTextNum) {
                $lineTextNum -= $this->reduceLineTextNum;
            }
            if ($lineTextNum <= 0) {
                $this->setError('图片上无法写入文字');
                return false;
            }
            $text = $this->text;
            $textColor = imagecolorallocate($im, $this->textRGB[0], $this->textRGB[1], $this->textRGB[2]);
            if ($this->isMultiLine()) {
                // 图片上最多文字行数
                $lineNum = floor($this->height / $wordHeight);
                if (!empty($this->maxLineNum) && $lineNum > $this->maxLineNum) {
                    $lineNum = $this->maxLineNum;
                }
                // 多行文字
                $line = ceil(mb_strlen($text, 'utf-8') / $lineTextNum);
                // 图片文字行数超了，就截取
                if ($line > $lineNum) {
                    $line = $lineNum;
                    $textLen = $lineTextNum * $line;
                    $text = mb_substr($text, 0, $textLen, 'utf-8');
                }
                for ($i = 1; $i <= $line; $i++) {
                    $t = mb_substr($text, ($i - 1) * $lineTextNum, $lineTextNum, 'utf-8');
                    $bbox = imagettfbbox($this->fontSize, 0, $this->fontFile, $t);
                    $x = ($this->width - $bbox[4]) / 2;
                    $y = ($this->height - $line * $wordHeight - $bbox[1] - $this->lineHeight) / 2;
                    imagettftext($im, $this->fontSize, 0, (int)$x, (int)($y + $i * $wordHeight), $textColor, $this->fontFile, $t);
                }
            } else {
                // 单行文字
                if (mb_strlen($text, 'utf-8') > $lineTextNum) {
                    $text = mb_substr($text, 0, $lineTextNum, 'utf-8');
                }
                $bbox = imagettfbbox($this->fontSize, 0, $this->fontFile, $text);
                $x = abs($this->width - $bbox[4]) / 2;
                $y = abs($this->height - $bbox[5]) / 2;
                imagettftext($im, $this->fontSize, 0, (int)$x, (int)$y, $textColor, $this->fontFile, $text);
            }
        }
        return $im;
    }

    /**
     * 保存图片到指定文件路径
     * @param string $filename 图片完整路径，包括文件名，文件目录
     * @return bool
     */
    public function saveImage($filename)
    {
        if (empty($filename)) {
            $this->setError('图片文件路径不能为空');
            return false;
        }
        $im = $this->getIm();
        if (empty($im)) {
            return false;
        }
        $pathinfo = pathinfo($filename);
        if (empty($pathinfo)) {
            $this->setError('图片文件路径解析失败');
            return false;
        }
        if (!is_dir($pathinfo['dirname'])) {
            $result = @mkdir($pathinfo['dirname'], 0777, true);
            if (!$result) {
                $this->setError('图片文件目录创建失败');
                return false;
            }
        }
        if (!empty($pathinfo['extension'])) {
            switch (strtolower($pathinfo['extension'])) {
                case 'png':
                    $result = imagepng($im, $filename);
                    imagedestroy($im);
                    return $result;
                case 'bmp':
                    if (function_exists('imagebmp')) {
                        $result = imagebmp($im, $filename);
                        imagedestroy($im);
                        return $result;
                    }
                    break;
                case 'jpeg':
                case 'jpg':
                    $result = imagejpeg($im, $filename, 100);
                    imagedestroy($im);
                    return $result;
                case 'webp':
                    if (function_exists('imagewebp')) {
                        $result = imagewebp($im, $filename, 100);
                        imagedestroy($im);
                        return $result;
                    }
                    break;
            }
        }
        $this->setError('图片格式不支持');
        return false;
    }
}