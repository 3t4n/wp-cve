<?php
/*
Genera la plantilla XML
*/
global $maximo_videos, $wp_query;

//Obtiene el listado de todos las vídeos
$videos = APGSitemapVideo::consulta();

//Añade la cabecera
status_header( '200' );
header( 'Content-Type: text/xml; charset=' . get_bloginfo( 'charset' ), true );

//Hay que dividir el sitemap en varios
$numero_feed    = preg_replace( '/[^0-9]/', '', $wp->request );
if ( ! empty ( $videos ) && count( $videos ) > $maximo_videos && ! $numero_feed ) {
    echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>
<!-- Created by APG Google Video Sitemap Feed by Art Project Group (https://artprojectgroup.es/plugins-para-wordpress/apg-google-video-sitemap-feed) -->
<!-- generated-on="' . date( 'Y-m-d\TH:i:s+00:00' ) . '" -->
<sitemapindex xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';
    for ( $i = 1; $i <= ceil( count( $videos ) / $maximo_videos ); $i++ ) {
        echo '<sitemap>
    <loc>' . home_url( '/' ) . "sitemap-video-$i.xml" . '</loc>
  </sitemap>';
    }
    echo '</sitemapindex>';

	return;
}

//Inicia la plantilla
echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>
<!-- Created by APG Google Video Sitemap Feed by Art Project Group (https://artprojectgroup.es/plugins-para-wordpress/apg-google-video-sitemap-feed) -->
<!-- Generated-on="' . date( 'Y-m-d\TH:i:s+00:00' ) . '" -->
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="https://www.google.com/schemas/sitemap-video/1.1">' . PHP_EOL;

$wp_query->is_404	= false;
$wp_query->is_feed	= true;

if ( ! empty( $videos ) ) {
	$videos_buscados    = [];
    $video_procesado    = [];
	
	if ( isset( $videos->query ) ) {
		$videos   = $videos->query;
	}
    
    if ( $numero_feed ) {
        $offset     = ( $numero_feed - 1 ) * $maximo_videos;
        $videos     = array_slice( $videos, $offset, $maximo_videos );
    }

	foreach ( $videos as $video ) {
		setup_postdata( $video );
		//Procesamos el contenido
		$contenido        = $video->post_content;
		$videos_buscados  = APGSitemapVideo::busca_videos( $contenido, $videos_buscados );
		//Procesamos el extracto
		$contenido        = $video->post_excerpt;
		$videos_buscados  = APGSitemapVideo::busca_videos( $contenido, $videos_buscados );
        
		if ( ! empty( $videos_buscados ) ) {
			$extracto    = ( ! empty( $video->post_excerpt ) ) ? $video->post_excerpt : get_the_excerpt( $video->id );
			
			$enlace      = htmlspecialchars( get_permalink( $video->id ) );
	
			foreach ( $videos_buscados as $video_buscado ) {
				if ( in_array( $video_buscado[ 'identificador' ], $video_procesado ) ) {
					continue; //Ya se ha procesado
				}
				
                //Guarda el vídeo procesado
				array_push( $video_procesado, $video_buscado[ 'identificador' ] );

				$informacion    = APGSitemapVideo::obtiene_informacion( $video_buscado[ 'identificador' ], $video_buscado[ 'proveedor' ] );
				
                if ( ! $informacion ) {
					continue;
				}

				if ( $video_buscado[ 'proveedor' ] == 'vimeo' ) {
					$video_buscado[ 'imagen' ] = $informacion->thumbnail_large;
				}
				
				echo "\t" . '<url>' . PHP_EOL;
				echo "\t\t" . '<loc>' . $enlace . '</loc>' . PHP_EOL;
				echo "\t\t" . '<video:video>' . PHP_EOL;
				echo "\t\t" . '<video:thumbnail_loc>'. $video_buscado[ 'imagen' ] .'</video:thumbnail_loc>' . PHP_EOL;
				echo "\t\t" . '<video:title>' . htmlspecialchars( $informacion->title, ENT_QUOTES ) . '</video:title>' . PHP_EOL;
				echo "\t\t" . '<video:description>' . htmlspecialchars( wp_strip_all_tags( $extracto ), ENT_QUOTES ) . '</video:description>' . PHP_EOL;
                echo "\t\t" . '<video:player_loc>' . $video_buscado[ 'reproductor' ] . '</video:player_loc>' . PHP_EOL;
   
				$etiquetas  = get_the_tags( $video->id ); 
				if ( $etiquetas ) { 
                	$numero_de_etiquetas   = 0;
                	foreach ( $etiquetas as $etiqueta ) {
                		if ( $numero_de_etiquetas++ > 32 ) {
							break;
						}
                		echo "\t\t" . '<video:tag>' . htmlspecialchars( $etiqueta->name, ENT_QUOTES ) . '</video:tag>' . PHP_EOL;
                	}
				}    

				echo "\t\t" . '</video:video>' . PHP_EOL;
				echo "\t" . '</url>' . PHP_EOL;
			}
		}
	}
}
echo "</urlset>";
