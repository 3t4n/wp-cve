<?php
/*
 Joomag WordPress Plugin
 ==============================================================================

 This plugin will allow you to embed Joomag magazines to your posts or pages on your Wordpress blog/website.

 Info for WordPress:
 ==============================================================================
 Plugin Name: WP Joomag
 Plugin URI: httsp://www.joomag.com/
 Description: Embed Joomag publications inside a post
 Author: Joomag.
 Version: 2.5.2
 Author URI: https://www.joomag.com

*/

function joomag_get_magazine( $attributes )
{

    extract( shortcode_atts( array(
            'allowfullscreen' => 1,
            'height' => 272,
            'width' => 420,
            'pagenumber' => 1,
            'magazineid' => '',
            'title' => '-',
            'backgroundcolor' => '',
            'backgroundimage' => '',
            'toolbar' => '',
            'autoflip' => '',
            'autofit'=> 'false',
            'theme' => ''
        ), $attributes )
    );

    $autofit = ( $autofit == 'true' ? true : false );

    $embedCodeStr =  '<iframe ${allowfullscreen} name="Joomag_embed_${UUID}"'.
        ' style="width:${width};height:${height}" width="${width}" height="${height}" hspace="0" vspace="0" frameborder="0" '.
        ' src="${magURL}?page=${startPage}&e=1${otherOptions}"></iframe>' ;

    $allowfullscreenReplace = '';

    if ($allowfullscreen == 1) {
        $allowfullscreenReplace = 'allowfullscreen="allowfullscreen"';
    }

    $embedCodeStr = str_replace('${allowfullscreen}', $allowfullscreenReplace, $embedCodeStr);

    $domain = 'www.joomag.com';

    $viewerURL = '//' . $domain . '/magazine/' . $title . '/' . $magazineid;

    $embedOpts = array();
    if($toolbar != '')
    {
        switch( $toolbar ) {
            case 'none':
                array_push($embedOpts, 'noToolbar');
                break;
            case 'transparent':
                array_push($embedOpts, 'none');
                break;
            default:
                array_push($embedOpts, "solid,{$toolbar}");
                break;
        }
    } elseif ( $theme != '') {
        array_push($embedOpts, "theme,{$theme}");
    } else {
        array_push($embedOpts, '');
    }

    if($backgroundcolor != '')
    {
        $bgColors = explode(',', $backgroundcolor);
        if( $backgroundcolor == 'transparent' ) {
            array_push($embedOpts, 'none');
        } elseif( is_array($bgColors) && count($bgColors) == 2 ) {
            array_push($embedOpts, "gradient,{$backgroundcolor}");
        } else {
            array_push($embedOpts, "solid,{$backgroundcolor}");
        }
    } else if( $backgroundimage != '' ) {
        array_push($embedOpts, "image,{$backgroundimage},fill");
    } else {
        array_push($embedOpts, '');
    }

    $embedOptsStr = '&embedInfo=' . implode(';', $embedOpts);
    if( is_numeric($autoflip) ) {
        $embedOptsStr .= "&autoFlipDelay={$autoflip}";
    }

    if( $autofit == true ) {
        $embedCodeStr = str_replace('${width}', '100%', $embedCodeStr);
        $embedCodeStr = str_replace('${height}', '100%', $embedCodeStr);}
    else {
        $embedCodeStr = str_replace('${width}', $width.'px', $embedCodeStr);
        $embedCodeStr = str_replace('${height}', $height.'px', $embedCodeStr);
    }

    $embedCodeStr = str_replace('${startPage}', $pagenumber, $embedCodeStr);

    $embedCodeStr = str_replace('${otherOptions}', $embedOptsStr, $embedCodeStr);

    $UUID = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $embedCodeStr = str_replace('${magURL}', $viewerURL, $embedCodeStr);
    $embedCodeStr = str_replace('${UUID}', $UUID, $embedCodeStr);

    return $embedCodeStr;
}

function joomag_get_bookshelf($attributes)
{
    extract(shortcode_atts(array(
            'allowfullscreen' => 1,
            'height' => 460,
            'width' => 450,
            'magazineid' => '',
            'title' => '',
            'cols' => 3,
            'rows' => 2,
            'version' => 1,
            'minheight' => 230,
            'publicationscount' => 6,
            'showtitles' => 0,
            'showeditions' => 0,
            'theme' => 'image',
        ), $attributes)
    );

    $domain = 'www.joomag.com';

    if ($version == 1) {
        // Old version with columns and rows
        $embedCodeStr = '<iframe ${allowfullscreen} name="Joomag_embed_${UUID}"' .
            ' style="width:${width};height:${height}" width="${width}" height="${height}" hspace="0" vspace="0" frameborder="0" ' .
            ' src="${bookshelfURL}&cols=${cols}&rows=${rows}"></iframe>';

        $bookshelfURL = '//' . $domain . '/Frontend/embed/bookshelf/index.php?UID=' . $magazineid;
    } else {
        $embedCodeStr = '<iframe ${allowfullscreen} name="Joomag_embed_${UUID}"' .
            ' style="width:${width};height:${height};min-height:${minheight};" width="${width}" height="${height}" hspace="0" vspace="0" frameborder="0" ' .
            ' src="${bookshelfURL}&publicationsCount=${publicationscount}&showTitles=${showtitles}&showEditions=${showeditions}&theme=${theme}"></iframe>';

        $bookshelfURL = '//' . $domain . '/Frontend/embed/bookshelf/v2.php?UID=' . $magazineid;
    }

    $allowfullscreenReplace = '';

    if ($allowfullscreen == 1) {
        $allowfullscreenReplace = 'allowfullscreen="allowfullscreen"';
    }

    $embedCodeStr = str_replace('${allowfullscreen}', $allowfullscreenReplace, $embedCodeStr);

    $UUID = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $embedCodeStr = str_replace(
        '${width}',
        strpos($width, '%') == false ? "{$width}px" : $width,
        $embedCodeStr
    );

    $embedCodeStr = str_replace(
        '${height}',
        strpos($height, '%') == false ? "{$height}px" : $height,
        $embedCodeStr
    );

    $embedCodeStr = str_replace('${bookshelfURL}', $bookshelfURL, $embedCodeStr);
    $embedCodeStr = str_replace('${minheight}', $minheight . 'px', $embedCodeStr);
    $embedCodeStr = str_replace('${cols}', $cols, $embedCodeStr);
    $embedCodeStr = str_replace('${rows}', $rows, $embedCodeStr);
    $embedCodeStr = str_replace('${publicationscount}', $publicationscount, $embedCodeStr);
    $embedCodeStr = str_replace('${showtitles}', $showtitles, $embedCodeStr);
    $embedCodeStr = str_replace('${showeditions}', $showeditions, $embedCodeStr);
    $embedCodeStr = str_replace('${theme}', $theme, $embedCodeStr);
    $embedCodeStr = str_replace('${UUID}', $UUID, $embedCodeStr);

    return $embedCodeStr;
}

add_shortcode( 'joomag', 'joomag_get_magazine' );
add_shortcode( 'joomag_bookshelf', 'joomag_get_bookshelf' );

?>
