<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Label;

use WpifyWooDeps\Endroid\QrCode\Color\Color;
use WpifyWooDeps\Endroid\QrCode\Color\ColorInterface;
use WpifyWooDeps\Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use WpifyWooDeps\Endroid\QrCode\Label\Alignment\LabelAlignmentInterface;
use WpifyWooDeps\Endroid\QrCode\Label\Font\Font;
use WpifyWooDeps\Endroid\QrCode\Label\Font\FontInterface;
use WpifyWooDeps\Endroid\QrCode\Label\Margin\Margin;
use WpifyWooDeps\Endroid\QrCode\Label\Margin\MarginInterface;
final class Label implements LabelInterface
{
    private string $text;
    private FontInterface $font;
    private LabelAlignmentInterface $alignment;
    private MarginInterface $margin;
    private ColorInterface $textColor;
    public function __construct(string $text, FontInterface $font = null, LabelAlignmentInterface $alignment = null, MarginInterface $margin = null, ColorInterface $textColor = null)
    {
        $this->text = $text;
        $this->font = $font ?? new Font(__DIR__ . '/../../assets/noto_sans.otf', 16);
        $this->alignment = $alignment ?? new LabelAlignmentCenter();
        $this->margin = $margin ?? new Margin(0, 10, 10, 10);
        $this->textColor = $textColor ?? new Color(0, 0, 0);
    }
    public static function create(string $text) : self
    {
        return new self($text);
    }
    public function getText() : string
    {
        return $this->text;
    }
    public function setText(string $text) : self
    {
        $this->text = $text;
        return $this;
    }
    public function getFont() : FontInterface
    {
        return $this->font;
    }
    public function setFont(FontInterface $font) : self
    {
        $this->font = $font;
        return $this;
    }
    public function getAlignment() : LabelAlignmentInterface
    {
        return $this->alignment;
    }
    public function setAlignment(LabelAlignmentInterface $alignment) : self
    {
        $this->alignment = $alignment;
        return $this;
    }
    public function getMargin() : MarginInterface
    {
        return $this->margin;
    }
    public function setMargin(MarginInterface $margin) : self
    {
        $this->margin = $margin;
        return $this;
    }
    public function getTextColor() : ColorInterface
    {
        return $this->textColor;
    }
    public function setTextColor(ColorInterface $textColor) : self
    {
        $this->textColor = $textColor;
        return $this;
    }
}
