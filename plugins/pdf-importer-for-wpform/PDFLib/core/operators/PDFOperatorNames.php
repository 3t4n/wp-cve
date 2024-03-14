<?php


namespace rnpdfimporter\PDFLib\core\operators;


class PDFOperatorNames
{
    public static $NonStrokingColor = 'sc';
    public static $NonStrokingColorN = 'scn';
    public static $NonStrokingColorRgb = 'rg';
    public static $NonStrokingColorGray = 'g';
    public static $NonStrokingColorCmyk = 'k';
    public static $NonStrokingColorspace = 'cs';

    // Stroking Color Operators
    public static $StrokingColor = 'SC';
    public static $StrokingColorN = 'SCN';
    public static $StrokingColorRgb = 'RG';
    public static $StrokingColorGray = 'G';
    public static $StrokingColorCmyk = 'K';
    public static $StrokingColorspace = 'CS';

    // Marked Content Operators
    public static $BeginMarkedContentSequence = 'BDC';
    public static $BeginMarkedContent = 'BMC';
    public static $EndMarkedContent = 'EMC';
    public static $MarkedContentPointWithProps = 'DP';
    public static $MarkedContentPoint = 'MP';
    public static $DrawObject = 'Do';

    // Graphics State Operators
    public static $ConcatTransformationMatrix = 'cm';
    public static $PopGraphicsState = 'Q';
    public static $PushGraphicsState = 'q';
    public static $SetFlatness = 'i';
    public static $SetGraphicsStateParams = 'gs';
    public static $SetLineCapStyle = 'J';
    public static $SetLineDashPattern = 'd';
    public static $SetLineJoinStyle = 'j';
    public static $SetLineMiterLimit = 'M';
    public static $SetLineWidth = 'w';
    public static $SetTextMatrix = 'Tm';
    public static $SetRenderingIntent = 'ri';

    // Graphics Operators
    public static $AppendRectangle = 're';
    public static $BeginInlineImage = 'BI';
    public static $BeginInlineImageData = 'ID';
    public static $EndInlineImage = 'EI';
    public static $ClipEvenOdd = 'W*';
    public static $ClipNonZero = 'W';
    public static $CloseAndStroke = 's';
    public static $CloseFillEvenOddAndStroke = 'b*';
    public static $CloseFillNonZeroAndStroke = 'b';
    public static $ClosePath = 'h';
    public static $AppendBezierCurve = 'c';
    public static $CurveToReplicateFinalPoint = 'y';
    public static $CurveToReplicateInitialPoint = 'v';
    public static $EndPath = 'n';
    public static $FillEvenOddAndStroke = 'B*';
    public static $FillEvenOdd = 'f*';
    public static $FillNonZeroAndStroke = 'B';
    public static $FillNonZero = 'f';
    public static $LegacyFillNonZero = 'F';
    public static $LineTo = 'l';
    public static $MoveTo = 'm';
    public static $ShadingFill = 'sh';
    public static $StrokePath = 'S';

    // Text Operators
    public static $BeginText = 'BT';
    public static $EndText = 'ET';
    public static $MoveText = 'Td';
    public static $MoveTextSetLeading = 'TD';
    public static $NextLine = 'T*';
    public static $SetCharacterSpacing = 'Tc';
    public static $SetFontAndSize = 'Tf';
    public static $SetTextHorizontalScaling = 'Tz';
    public static $SetTextLineHeight = 'TL';
    public static $SetTextRenderingMode = 'Tr';
    public static $SetTextRise = 'Ts';
    public static $SetWordSpacing = 'Tw';
    public static $ShowText = 'Tj';
    public static $ShowTextAdjusted = 'TJ';
    public static $ShowTextLine = "'"; // tslint:disable-line quotemark
    public static $ShowTextLineAndSpace = '"';

    // Type3 Font Operators
    public static $Type3D0 = 'd0';
    public static $Type3D1 = 'd1';

    // Compatibility Section Operators
    public static $BeginCompatibilitySection = 'BX';
    public static $EndCompatibilitySection = 'EX';
}