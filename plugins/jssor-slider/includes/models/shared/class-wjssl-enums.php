<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class WjsslEnumFillMode
{
    const Stretch = 0;
    const Contain = 1;
    const Cover = 2;
    const Actual = 4;
    const ContainAndActual = 5;
}

class WjsslEnumOrientation
{
    const None = 0;
    const Horizontal = 1;
    const Vertical = 2;
    const HorizontalVertical = 3;
}

class WjsslEnumLoop
{
    const Stop = 0;
    const Loop = 1;
    const Rewind = 2;
}

class WjsslEnumPauseOnHover
{
    const No = 0;
    const PauseOnDesktop = 1;
    const PauseOnTouchDevice = 2;
    const PauseOnDesktopAndTouchDevice = 3;
    const FreezeOnDesktop = 4;
    const FreezeOnTouchDevice = 8;
    const FreezeOnDesktopAndTouchDevice = 12;
}

class WjsslEnumResponsiveScaleMode
{
    const None = 0;

    const Width = 4;
    const Height = 8;
    const Size = 12; //self::Width + self::Height; // Constant expression support was added in PHP 5.6.0.

    //const Flex = 32;
    const Flex = 64;
    const Constrain = 128;

    const HtmlBodyCss = 256;
    const Wrapper = 512;

    const ParentWidth = self::Width;
    const ParentHeight = self::Height;

    const ParentSizeFlex = 76; //self::Size + self::Flex;         //76; // Constant expression support was added in PHP 5.6.0.
    const ParentSizeConstrain = 140; //self::Size + self::Constrain;     //140; // Constant expression support was added in PHP 5.6.0.

    const WindowSizeFlex = 844; //self::HtmlBodyCss + self::Wrapper + self::Size + self::Flex;      //844;// Constant expression support was added in PHP 5.6.0.
    //const WindowSizeCover = HtmlBodyCss + Wrapper + Size + Flex,      //844;// Constant expression support was added in PHP 5.6.0.
    const WindowSizeConstrain = 908; //self::HtmlBodyCss + self::Wrapper + self::Size + self::Constrain;   //908;// Constant expression support was added in PHP 5.6.0.

    //const WindowWidth = 6;
    //const WindowHeight = 10;
}

class WjsslEnumSlideshowPlay
{
    const Random = 0;
    const InOrder = 1;
}

class WjsslEnumActionMode
{
    const Click = 1;
    const MouseOver = 2;
    const Both = 3;
}

class WjsslEnumChanceToShow
{
    const Never = 0;
    const MouseOver = 1;
    const Always = 2;
}

class WjsslEnumLayerType
{
    const Content = 1;
    const Image = 2;
    const Panel = 3;
    const Folder = 4;
}

class WjsslEnumBreakPointType
{
    const Idle = 1;
    const MainIdle = 2;
}

class WjsslEnumFontProvider
{
    const None = 0;
    const System = 1;
    const Google = 2;
    const Custom = 3;
}

class WjsslEnumLayerControls
{
    const Invisible = 1;
    const Framed = 2;
    const Locked = 4;
}

//added since 8.9.0, 20180831
class EnumGradientType
{
    const None = 0;
    const Linear = 1;
    const Radial = 2;
    const RadialEllipse = 2;
    const Circle = 4;
    const RadialCircle = 6;
}

#region conditions

class WjsslEnumConditionPlay
{
    const None = 0;

    /// <summary>
    /// desktop
    /// </summary>
    const MouseEnter = 1;

    /// <summary>
    /// desktop
    /// </summary>
    const MouseClick = 2;

    /// <summary>
    /// mobile
    /// </summary>
    const Touch = 4;

    /// <summary>
    /// apply to this layer
    /// </summary>
    const ApplyThisLayer = 16;

    /// <summary>
    /// apply to child layers
    /// </summary>
    const ApplyChildLayers = 32;

    /// <summary>
    /// apply
    /// </summary>
    const Apply = 48;
}

class WjsslEnumConditionRollback
{
    /// <summary>
    /// desktop
    /// </summary>
    const Click = 2;

    /// <summary>
    /// Mobile
    /// </summary>
    const Touch = 4;

    /// <summary>
    /// desktop
    /// </summary>
    const ESC = 8;

    /// <summary>
    /// desktop
    /// </summary>
    const MouseLeave = 16;

    /// <summary>
    /// desktop and mobile
    /// </summary>
    const Blur = 32;
}

#endregion
