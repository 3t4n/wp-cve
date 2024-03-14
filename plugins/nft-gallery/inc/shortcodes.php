<?php
/**
* NFT Gallery - shortcodes.php
*
* In this file,
* you will find all functions related to the shortcodes that are available on the plugin.
*
* @author   Hendra Setiawan
* @version  1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function nftgallery_function( $atts ){ 
        wp_enqueue_style( 'nftgallery' );
        wp_enqueue_style( 'justifiedGallery' );
        wp_enqueue_script( 'nftgallery' );
        wp_enqueue_script( 'justifiedGallery' );

        $args = array(
            'headers'     => array(
                'X-API-KEY' => get_option('nftgallery-api'),
            ),
        );

        $type = get_option('nftgallery-type');
        $limit = get_option('nftgallery-limit');
        $id = get_option('nftgallery-id');
        $style = get_option('nftgallery-style');

        $request = wp_remote_get( 'https://api.opensea.io/api/v1/assets?format=json&limit='.$limit.'&offset=0&order_direction=desc&'.$type.'='.$id,$args );

        ob_start();
        $nfts = '';

        if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            $nfts .= '<pre>No NFTs detected! Please check the wallet address or the collection slug.</pre>';
        } else {
            $body = wp_remote_retrieve_body( $request );

            $data = json_decode( $body );

            if( ! empty( $data ) ) {
                if($style == 'grid') {
                    wp_enqueue_style( 'flexbox' );
                    $nfts .= '<div class="row nftgallery">';
                    foreach( $data->assets as $asset ) {
                        if($asset->name) { $title = $asset->name; } else { $title = '#'.$asset->token_id; }

                        $imageURL = $asset->image_preview_url;
                        $parsedUrl = parse_url($imageURL);
                        $scheme = $parsedUrl['scheme'];
                        $host = $parsedUrl['host'];
                        $path = $parsedUrl['path'];

                        $clenedImageURL = $scheme . '://' . $host . $path;
                        
                        $nfts .= '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4 nftgallery-wrapper">';
                            $nfts .= '<div class="nft" data-url="'.$asset->permalink.'">';
                            $nfts .= '<div class="image" style="background-image: url('.$clenedImageURL.');"></div>';
                            $nfts .= '<div class="desc">
                                        <div class="collection">'.$asset->collection->name.'</div>
                                        <h2>'.$title.'</h2>
                                      </div>';
                            $nfts .= '</div>';
                        $nfts .= '</div>';
                    
                    }
                    if($type == 'collection'):

                        $nfts .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 os-button-wrapper"><a href="https://opensea.io/collection/'.$id.'" class="view-opensea" target="_blank">View '.$asset->collection->name.' on OpenSea</a></div>';
                    
                    endif;
                    $nfts .= '</div>';
                } else if($style == 'photography') {
                    wp_enqueue_style( 'lightgallery' );        
                    wp_enqueue_style( 'lightgalleryzoom' );
                    wp_enqueue_style( 'lightgallerythumbnail' );
                    wp_enqueue_style( 'lightgallerytransition' );

                    wp_enqueue_script( 'lightgallery' );
                    wp_enqueue_script( 'lightgallerythumbnail' );
                    wp_enqueue_script( 'lightgalleryzoom' );      
                                           
                    $nfts .= '<div class="gallery-container nftgallery" id="lightgallery">';
                    $no = 1;
                    foreach( $data->assets as $asset ) {
                        $basename = basename($asset->image_url);
                        if($asset->name) { $title = $asset->name; } else { $title = $asset->token_id; }
                        $title = strip_tags($title);
                        $title = preg_replace('#[^\w()/.%\-&]#'," ",$title);
                        
                        $nfts .= '<a data-src="'.$asset->image_url.'" data-download-url="false" class="gallery-item" data-sub-html=".caption'.$no.'">';
                        $nfts .= '<img class="img-fluid" src="'.$asset->image_preview_url.'" />';
                        $nfts .= '<div class="caption caption'.$no.'"><p class="nft-title">'.$title.'</p><p>Minted by <strong>'.$asset->creator->user->username.'</strong> in <strong>'.$asset->collection->name.'</strong></p><button class="openseaBtn" data-url="'.$asset->permalink.'">View on OpenSea</button></div>';
                        $nfts .= '</a>';
                        $no++;
                    }
                    $nfts .= '</div>';
                }
            }
        }
        
        echo wp_kses_post($nfts);
        return ob_get_clean(); 
}
add_shortcode('nftgallery', 'nftgallery_function');