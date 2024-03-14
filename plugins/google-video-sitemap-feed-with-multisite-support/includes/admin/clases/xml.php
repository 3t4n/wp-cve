<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

/*
Clase que controla todo lo relacionado con el  XML
*/
class APGSitemapVideo {
	public function __construct() {		
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'do_feed_sitemap-video', [ $this, 'carga_plantilla' ], 10, 1 );
        add_filter( 'generate_rewrite_rules', [ $this, 'rewrite' ] );
        add_action( 'enviar_ping', [ $this, 'envia_ping' ], 10, 1 ); 
        //Actúa cuando se publica una página, una entrada o se borra una entrada
        add_action( 'publish_post', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'publish_page', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'delete_post', [ $this, 'programa_ping' ], 999, 1 );
        add_action( 'pre_post_update', [ $this, 'programa_ping' ], 999, 1 );
	}

    //Funciones iniciales
	public function init() {
		if ( defined( 'QT_LANGUAGE' ) ) {
			add_filter( 'xml_sitemap_url', [ $this, 'qtranslate' ], 99 );
		}
        
        //Inicializa la información del 100% de los vídeos
        if ( get_transient( 'xml_video_sitemap_procesado' ) === false && is_admin() ) {
            set_transient( 'xml_video_sitemap_procesado', 1, YEAR_IN_SECONDS );           
            APGSitemapVideo::procesamiento();
        }
	}
    
	//Carga la plantilla del XML
	public function carga_plantilla() {
		load_template( plugin_dir_path( __FILE__ ) . 'contenido-xml.php' );
	}

	//Añade el sitemap a los enlaces permanentes
	public function rewrite( $wp_rewrite ) {
        global $maximo_videos;
        
        $feed_rules           = [ 
            'sitemap-video.xml$'    => $wp_rewrite->index . '?feed=sitemap-video' 
        ];
        $videos               = get_transient( 'xml_video_sitemap_consulta' );
        if ( $videos !== false && ! empty( $videos ) && ceil( count( $videos ) / $maximo_videos ) > 1 ) {
            for ( $i = 1; $i <= ceil( count( $videos ) / $maximo_videos ); $i++ ) {
                $feed_rules[ "sitemap-video-$i.xml$" ]   = $wp_rewrite->index . "?feed=sitemap-video";
            }
        }
		$wp_rewrite->rules    = $feed_rules + $wp_rewrite->rules;
	}

    //qTranslate
	public function qtranslate( $input ) {
		global $q_config;

		if ( is_array( $input ) ) { // got an array? return one!
			foreach ( $input as $url ) {
				foreach( $q_config[ 'enabled_languages' ] as $language ) {
					$return[] = qtrans_convertURL( $url, $language );
				}
			}
		} else {
			$return = qtrans_convertURL( $input ); // not an array? just convert the string.
		}

		return $return;
	}

	//Envía el ping a Google y Bing
	public function envia_ping() {
        $url      = urlencode( home_url( '/' ) . "sitemap-video.xml" );
		$ping     = [ 
			"https://www.google.com/webmasters/sitemaps/ping?sitemap=$url", 
			"https://www.bing.com/webmaster/ping.aspx?siteMap=$url" 
		];
		$opciones = [
            'timeout'   => 10,
        ];
		foreach( $ping as $url ) {
			wp_remote_get( $url, $opciones );
		}
	}

	//Programa el ping a los buscadores web
	public function programa_ping() {
		delete_transient( 'xml_video_sitemap_consulta' );
		wp_schedule_single_event( time(), 'enviar_ping' );
	}

	//Desactiva el plugin
	public static function desactivar() {
		global $wp_rewrite;

		remove_filter( 'generate_rewrite_rules', [ __CLASS__, 'rewrite' ] );
		$wp_rewrite->flush_rules();
	}
    
    //Devuelve la búqueda que añade todos los tipos de entradas
    static public function dame_busqueda() {
        $argumentos         = [
           'public'   => true,
        ];
        $tipos_de_entradas  = get_post_types( $argumentos, 'names' );
        $busqueda           = '';
        foreach ( $tipos_de_entradas as $tipo_de_entrada ) {
            $busqueda  .= "post_type = '$tipo_de_entrada' OR ";
        }
        $busqueda           = substr_replace( $busqueda, '', -4, -1 );
        if ( strlen( $busqueda ) ) {
            $busqueda  = "AND ($busqueda)";
        }

        return $busqueda;
    }

    //Genera y devuelve la consulta a la base de datos
    static public function consulta() {
        $videos = get_transient( 'xml_video_sitemap_consulta' );
        if ( $videos === false ) {
            global $wpdb;
            
            $busqueda   = APGSitemapVideo::dame_busqueda();
            $videos     = $wpdb->get_results( "(SELECT id, post_title, post_content, post_excerpt, post_date
                                            FROM $wpdb->posts
                                            WHERE post_status = 'publish'
                                                $busqueda
                                                AND (post_content LIKE '%youtube.com%'
                                                    OR post_content LIKE '%youtube-nocookie.com%'
                                                    OR post_content LIKE '%youtu.be%'                              
                                                    OR post_content LIKE '%dailymotion.com%'
                                                    OR post_content LIKE '%vimeo.com%')
                                                OR (post_excerpt LIKE '%youtube.com%'
                                                    OR post_excerpt LIKE '%youtube-nocookie.com%'
                                                    OR post_excerpt LIKE '%youtu.be%'                              
                                                    OR post_excerpt LIKE '%dailymotion.com%'
                                                    OR post_excerpt LIKE '%vimeo.com%'))											
                                        UNION ALL
                                            (SELECT id, post_title, meta_value as 'post_content', post_excerpt, post_date
                                                FROM $wpdb->posts
                                                JOIN $wpdb->postmeta
                                                    ON id = post_id
                                                        AND meta_key = 'wpex_post_oembed'
                                                        AND (meta_value LIKE '%youtube.com%'
                                                            OR meta_value LIKE '%youtube-nocookie.com%'
                                                            OR meta_value LIKE '%youtu.be%'
                                                            OR meta_value LIKE '%dailymotion.com%'
                                                            OR meta_value LIKE '%vimeo.com%')
                                                WHERE post_status = 'publish'
                                                    $busqueda)
                                        UNION ALL
                                            (SELECT id, post_title, post_excerpt, post_parent, post_date
                                                FROM $wpdb->posts
                                                WHERE post_type = 'attachment'
                                                        AND post_mime_type like 'video%'
                                                        AND post_parent > 0)
                                        ORDER BY post_date DESC" ); //Consulta mejorada con ayuda de Ludo Bonnet [https://github.com/ludobonnet]
            set_transient( 'xml_video_sitemap_consulta', $videos, 24 * HOUR_IN_SECONDS );
            APGSitemapVideo::desactivar();
        }
        
        return $videos;
    }

    //Envía un correo informando de que el vídeo ya no existe
    static public function envia_correo( $video ) {
        global $wpdb;

        $busqueda   = APGSitemapVideo::dame_busqueda();
        $entrada    = $wpdb->get_results( "SELECT id, post_title FROM $wpdb->posts WHERE post_status = 'publish' $busqueda AND (post_content LIKE '%$video%')" );

        wp_mail( get_option( 'admin_email' ), __( 'Video not found!', 'google-video-sitemap-feed-with-multisite-support' ), sprintf( __( 'Please check the page <a href="%s">%s</a> from your website %s and edit the deleted video with id %s.<br /><br />email sended by <a href="https://artprojectgroup.es/plugins-para-wordpress/apg-google-video-sitemap-feed">APG Google Video Sitemap Feed</a>.', 'google-video-sitemap-feed-with-multisite-support' ), get_permalink( $entrada[ 0 ]->id ), $entrada[ 0 ]->post_title, get_bloginfo( 'name' ), $video ), "Content-type: text/html" );
    }

    //Obtiene información del vídeo ( función mejorada con ayuda de Ludo Bonnet [https://github.com/ludobonnet] )
    static public function procesa_url( $url, $video ) {
        $configuracion  = get_option( 'xml_video_sitemap' );
        $respuesta      = get_transient( $url );
        if ( $respuesta === false ) { //No hay información en la base de datos
            $respuesta    = wp_remote_get( $url );
            set_transient( $url, $respuesta, 365 * DAY_IN_SECONDS );
            $configuracion[ $video ]    = $url;
            if ( get_option( 'xml_video_sitemap' ) || get_option( 'xml_video_sitemap' ) == NULL ) {
                update_option( 'xml_video_sitemap', $configuracion );
            } else {
                add_option( 'xml_video_sitemap', $configuracion );
            }
        }

        //Comprueba si hay error en la respuesta y si hay que enviar el correo de aviso
        $envia  = false;
        if ( ! is_wp_error( $respuesta ) ) {
            $dailymotion  = json_decode( $respuesta[ 'body' ] );
            if ( $respuesta[ 'response' ][ 'code' ] == 404 || $respuesta[ 'body' ] == 'Video not found' || $respuesta[ 'body' ] == 'Invalid id' || $respuesta[ 'body' ] == 'Private video' || isset( $dailymotion->error ) ) {
                $envia  = true;
            }
        } else {
            $envia  = true;
        }
        if ( $envia ) {
            if ( ! empty( $configuracion ) && ! array_key_exists( $video, $configuracion ) && $configuracion[ 'correo' ] == "1" ) { //No se ha enviado nunca
                $configuracion[ $video ]    = 1;
                update_option( 'xml_video_sitemap', $configuracion );
                APGSitemapVideo::envia_correo( $video );
            }
            delete_transient( $url );
            
            return NULL;
        }

        return $respuesta[ 'body' ];
    }

    //Procesa los datos externos
    static public function obtiene_informacion( $identificador, $proveedor ) {
        $api   = [ 
            'youtube'		=> 'https://noembed.com/embed?url=https://www.youtube.com/watch?v=' . $identificador, 
            'dailymotion'	=> 'https://api.dailymotion.com/video/' . $identificador, 
            'vimeo'			=> 'https://vimeo.com/api/v2/video/' . $identificador . ".json"
        ];

        if ( $proveedor == 'vimeo' ) {
            $vimeo   = json_decode( APGSitemapVideo::procesa_url( $api[ $proveedor ] , $identificador ) );
            if ( isset ( $vimeo[ 0 ] ) ) {
                return $vimeo[ 0 ];
            }
        } else {
            return json_decode( APGSitemapVideo::procesa_url( $api[ $proveedor ], $identificador ) );
        }

        return false;
    }

    //Busca el vídeo en el contenido
    static public function busca_videos( $contenido, $videos ) { //Mejorado con ayuda de Ludo Bonnet [https://github.com/ludobonnet]
        if ( preg_match_all( '/youtube\.com\/(v\/|watch\?v=|embed\/)([^\$][a-zA-Z0-9\-_]*)/', $contenido, $busquedas, PREG_SET_ORDER ) || preg_match_all( '/youtube-nocookie\.com\/(v\/|watch\?v=|embed\/)([^\$][a-zA-Z0-9\-_]*)/', $contenido, $busquedas, PREG_SET_ORDER ) ) { //Youtube
            foreach ( $busquedas as $busqueda ) {
                $identificador               = $busqueda[ 2 ];
                $videos[ $identificador ]    = [ 
                    'proveedor'		=> 'youtube', 
                    'identificador'	=> $identificador, 
                    'reproductor'	=> "https://www.youtube.com/embed/$identificador", 
                    'imagen'		=> "https://i.ytimg.com/vi/$identificador/hqdefault.jpg" 
                ];
            }
        }
        if ( preg_match_all( '/youtu\.be\/([^\$][a-zA-Z0-9\-_]*)/', $contenido, $busquedas, PREG_SET_ORDER ) ) { //Acortador de Youtube
            foreach ( $busquedas as $busqueda ) {
                $identificador               = $busqueda[ 1 ];
                $videos[ $identificador ]    = [ 
                    'proveedor'		=> 'youtube', 
                    'identificador'	=> $identificador, 
                    'reproductor'	=> "https://www.youtube.com/embed/$identificador", 
                    'imagen'		=> "https://i.ytimg.com/vi/$identificador/hqdefault.jpg" 
                ];
            }
        }
        if ( preg_match_all( '/dailymotion\.com\/video\/([^\$][a-zA-Z0-9]*)/', $contenido, $busquedas, PREG_SET_ORDER ) ) { //Dailymotion. Añadido por Ludo Bonnet [https://github.com/ludobonnet]	
            foreach ( $busquedas as $busqueda ) {
                $identificador               = $busqueda[ 1 ];
                $videos[ $identificador ]    = [ 
                    'proveedor'		=> 'dailymotion', 
                    'identificador'	=> $identificador, 
                    'reproductor'	=> "https://www.dailymotion.com/embed/video/$identificador", 
                    'imagen'        => "https://www.dailymotion.com/thumbnail/video/$identificador" 
                ];
            }
        }
        if ( preg_match_all( '/vimeo\.com\/moogaloop.swf\?clip_id=([^\$][0-9]*)/', $contenido, $busquedas, PREG_SET_ORDER ) || preg_match_all( '/vimeo\.com\/video\/([^\$][0-9]*)/', $contenido, $busquedas, PREG_SET_ORDER ) || preg_match_all( '/vimeo\.com\/([^\$][0-9]*)/', $contenido, $busquedas, PREG_SET_ORDER ) ) { //Vimeo. Mejorado a partir del código aportado por Ludo Bonnet [https://github.com/ludobonnet]
            foreach ( $busquedas as $busqueda ) {
                $identificador               = $busqueda[ 1 ];
                if ( is_numeric( $identificador ) ) {
                    $videos[ $identificador ]    = [ 
                        'proveedor'		=> 'vimeo', 
                        'identificador'	=> $identificador, 
                        'reproductor'	=> "https://player.vimeo.com/video/$identificador" 
                    ];
                }
            }
        }

        return $videos;
    }
    
    //Genera el procesamiento de los vídeos
    static public function procesamiento() {
        $videos = APGSitemapVideo::consulta();
        if ( ! empty( $videos ) ) {
            $videos_buscados    = [];
            $video_procesado    = [];
            foreach ( $videos as $video ) {
                //Procesamos el contenido
                $contenido        = $video->post_content;
                $videos_buscados  = APGSitemapVideo::busca_videos( $contenido, $videos_buscados );
                //Procesamos el extracto
                $contenido        = $video->post_excerpt;
                $videos_buscados  = APGSitemapVideo::busca_videos( $contenido, $videos_buscados );
                if ( ! empty( $videos_buscados ) ) {
                    foreach ( $videos_buscados as $video_buscado ) {
                        $argumentos     = [
                            'identificador' => $video_buscado[ 'identificador' ], 
                            'proveedor'     => $video_buscado[ 'proveedor' ]
                        ];
                        if ( false === as_next_scheduled_action( 'apg_video_sitemap_procesamiento' ) ) {
                            as_schedule_recurring_action( strtotime( '+1 minute' ), YEAR_IN_SECONDS, 'apg_video_sitemap_procesamiento', $argumentos, 'apg_video_sitemap' );
                        }
                    }
                }
            }
        }
    }
}
new APGSitemapVideo();
