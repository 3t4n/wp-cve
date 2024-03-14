<?php





if (!function_exists('ai_post_generator_return_json_2')){





    /**





     * return_json





     *





     * @param array $response





     * @return json





     */





    function ai_post_generator_return_json_2($response = array()) {





        header('Content-Type: application/json');





        exit(json_encode($response));





    }





}





if (!function_exists('ai_post_generator_stripAccents2')){





    function ai_post_generator_stripAccents2($str) {





        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');





    }





}











if (!function_exists('get_ai_post_generator_toc')){





function get_ai_post_generator_toc($content) {


  // get headlines


      $headings = get_ai_post_generator_headings($content, 1, 6);


      $lang = get_bloginfo("language");


	  if (str_starts_with($lang, 'es')){


        $toc="Tabla de contenido";


      }elseif(str_starts_with($lang, 'en')){


        $toc = "Table Of Contents";


      }elseif(str_starts_with($lang, 'fr')){


        $toc = "Table des matières";


      } elseif(str_starts_with($lang, 'de')){


        $toc = "Inhaltsverzeichnis";


      } elseif(str_starts_with($lang, 'ru')){


        $toc = "Оглавление";


      } elseif(str_starts_with($lang, 'it')){


        $toc = "Indice";


      } elseif(str_starts_with($lang, 'pt')){


        $toc = "Índice";


      } elseif(str_starts_with($lang, 'pl')){


        $toc = "Spis treści";


      } elseif(str_starts_with($lang, 'sv')){


        $toc = "Innehållsförteckning";


      } elseif(str_starts_with($lang, 'nl')){


        $toc = "Inhoudsopgave";


      } elseif(str_starts_with($lang, 'da')){


        $toc = "Indholdsfortegnelse";


      } elseif(str_starts_with($lang, 'fi')){


        $toc = "Sisällysluettelo";


      } elseif(str_starts_with($lang, 'tr')){


        $toc = "İçindekiler";


      } elseif(str_starts_with($lang, 'el')){


        $toc = "Πίνακας περιεχομένων";


      } elseif(str_starts_with($lang, 'ar')){


        $toc = "فهرس المحتويات";


      } elseif(str_starts_with($lang, 'he')){


        $toc = "תוכן העניינים";


      } elseif(str_starts_with($lang, 'fa')){


        $toc = "فهرست مطالب";


      } elseif(str_starts_with($lang, 'no')){


        $toc = "Innholdsfortegnelse";


      } elseif(str_starts_with($lang, 'cs')){


        $toc = "Obsah";


      } elseif(str_starts_with($lang, 'th')){


        $toc = "สารบัญ";


      } elseif(str_starts_with($lang, 'hu')){


        $toc = "Tartalomjegyzék";


      } elseif(str_starts_with($lang, 'ja')){


        $toc = "目次";


      } elseif(str_starts_with($lang, 'ko')){


        $toc = "목차";


      } elseif(str_starts_with($lang, 'hi')){


        $toc = "अनुक्रमणिका";


      } elseif(str_starts_with($lang, 'bn')){


        $toc = "সূচিপত্র";


      } elseif(str_starts_with($lang, 'uk')){


        $toc = "Зміст";


      } elseif(str_starts_with($lang, 'vi')){


        $toc = "Mục lục";


      } elseif(str_starts_with($lang, 'id')){


        $toc = "Daftar Isi";


      } elseif(str_starts_with($lang, 'ro')){


        $toc = "Cuprins";


      } elseif(str_starts_with($lang, 'ms')){


        $toc = "Kandungan";


      } elseif(str_starts_with($lang, 'ca')){


        $toc = "Índex";


      } elseif(str_starts_with($lang, 'lt')){


        $toc = "Turinys";


      } elseif(str_starts_with($lang, 'bg')){


        $toc = "Съдържание";


      } elseif(str_starts_with($lang, 'hr')){


        $toc = "Sadržaj";


      } elseif(str_starts_with($lang, 'sr')){


        $toc = "Садржај";


      } elseif(str_starts_with($lang, 'zh')){


        $toc = "目录";


      } elseif(str_starts_with($lang, 'is')){


        $toc = "Efnisyfirlit";


      } elseif(str_starts_with($lang, 'sl')){


        $toc = "Kazalo vsebine";


      } elseif(str_starts_with($lang, 'et')){


        $toc = "Sisukord";


      } elseif(str_starts_with($lang, 'sk')){


        $toc = "Obsah";


      } elseif(str_starts_with($lang, 'mk')){


        $toc = "Содржина";


      } elseif(str_starts_with($lang, 'az')){


        $toc = "Mündəricat";


      } elseif(str_starts_with($lang, 'lv')){


        $toc = "Saturs";


      } elseif(str_starts_with($lang, 'hy')){


        $toc = "Տեքստի բաղադրիչ";


      } elseif(str_starts_with($lang, 'eu')){


        $toc = "Edukien taula";


      } elseif(str_starts_with($lang, 'gl')){


        $toc = "Índice";


      } elseif(str_starts_with($lang, 'af')){


        $toc = "Inhoudsopgawe";


      } else{


		$toc = "Table of contents";


	  }


      // parse toc


      ob_start();


      echo "<div class='ai-table-of-contents'>";


      echo "<span class='toc-headline'>" . esc_html($toc) . "</span>";


      echo "<span class='toggle-toc custom-setting' title='collapse'>−</span>";


       echo esc_html(ai_post_generator_parse_toc($headings, 0, 0));


      echo "</div>";


      return ob_get_clean();


    }


}





if (!function_exists('ai_post_generator_parse_toc')){
    function ai_post_generator_parse_toc($headings, $index, $recursive_counter) {
      if($recursive_counter > 60 || !count($headings)) return;

      $last_element = $index > 0 ? $headings[$index - 1] : NULL;

      $current_element = $headings[$index];

      $next_element = NULL;

      if($index < count($headings) && isset($headings[$index + 1])) {

        $next_element = $headings[$index + 1];

      }

      if($current_element == NULL) return;

      $tag = intval($headings[$index]["tag"]);

      $id = esc_attr($headings[$index]["id"]);

      $classes = isset($headings[$index]["classes"]) ? $headings[$index]["classes"] : array();

      $name = esc_html($headings[$index]["name"]);

      if(isset($current_element["classes"]) && $current_element["classes"] && in_array("nitoc", $current_element["classes"])) {

        ai_post_generator_parse_toc($headings, $index + 1, $recursive_counter + 1);

        return;

      }

      if($last_element == NULL) echo "<ul>";

      if($last_element != NULL && $last_element["tag"] < $tag) {

        for($i = 0; $i < $tag - $last_element["tag"]; $i++) {

          echo "<ul>";

        }

      }

      $li_classes = "";

    if(isset($current_element["classes"]) && $current_element["classes"] && in_array("toc-bold", $current_element["classes"])) $li_classes = " class='bold'";

    echo "<li" . esc_html($li_classes) .">";

      if(isset($current_element["classes"]) && $current_element["classes"] && in_array("toc-bold", $current_element["classes"])) {

        echo esc_html($name);

      } else {

        echo "<a href='#" . esc_attr($id) . "'>" . esc_html($name) . "</a>";

      }

      if($next_element && intval($next_element["tag"]) > $tag) {

        ai_post_generator_parse_toc($headings, $index + 1, $recursive_counter + 1);

      }
      echo "</li>";

      if($next_element && intval($next_element["tag"]) == $tag) {

        ai_post_generator_parse_toc($headings, $index + 1, $recursive_counter + 1);

      }

      if ($next_element == NULL || ($next_element && $next_element["tag"] < $tag)) {


            echo "</ul>";

    if ($next_element && $tag - intval($next_element["tag"]) >= 2) {

      echo "</li>";

      for($i = 1; $i < $tag - intval($next_element["tag"]); $i++) {

        echo "</ul>";

      }

    }

  }

  if($next_element != NULL && $next_element["tag"] < $tag) {

    ai_post_generator_parse_toc($headings, $index + 1, $recursive_counter + 1);

  }

}





}





if (!function_exists('get_ai_post_generator_headings')){





    function get_ai_post_generator_headings($content, $from_tag = 1, $to_tag = 6) {


      $headings = array();


      preg_match_all("/<h([" . $from_tag . "-" . $to_tag . "])([^<]*)>(.*)<\/h[" . $from_tag . "-" . $to_tag . "]>/", $content, $matches);





      for($i = 0; $i < count($matches[1]); $i++) {


        $headings[$i]["tag"] = $matches[1][$i];


        // get id


        $att_string = $matches[2][$i];


        preg_match("/id=\"([^\"]*)\"/", $att_string , $id_matches);


        $headings[$i]["id"] = $id_matches[1];


        // get classes


        $att_string = $matches[2][$i];


        preg_match_all("/class=\"([^\"]*)\"/", $att_string , $class_matches);


        for($j = 0; $j < count($class_matches[1]); $j++) {


          $headings[$i]["classes"] = explode(" ", $class_matches[1][$j]);


        }


        $headings[$i]["name"] = strip_tags($matches[3][$i]);


      }


      return $headings;


    }





}





if (!function_exists('ai_post_generator_auto_id_headings')){


    /**


 * Automatically add IDs to headings such as <h2></h2>


 */


function ai_post_generator_auto_id_headings( $content ) {


  $content = preg_replace_callback('/(\<h[1-6](.*?))\>(.*)(<\/h[1-6]>)/i', function( $matches ) {


    if(!stripos($matches[0], 'id=')) {


      $matches[0] = $matches[1] . $matches[2] . ' id="' . sanitize_title( $matches[3] ) . '">' . $matches[3] . $matches[4];


    }


    return $matches[0];


  }, $content);


    return $content;


}





}





if (!function_exists('ai_post_generator_download_img')){





  function ai_post_generator_download_img($image_url, $name, $sanitazed_name) {





      // it allows us to use download_url() and wp_handle_sideload() functions


      require_once( ABSPATH . 'wp-admin/includes/file.php' );





      // download to temp dir


      $temp_file = download_url( $image_url );





      if( is_wp_error( $temp_file ) ) {


          return false;


      }


      $info = getimagesize($temp_file);


      $extension = image_type_to_extension($info[2]);


      if ($extension!=".jpeg" && $extension!=".gif" && $extension!=".png" && $extension!=".jpg" && $extension!=".webp"){


          return false;


      }





      // move the temp file into the uploads directory


      $file = array(


          'name'     => $sanitazed_name . $extension,


          'type'     => mime_content_type( $temp_file ),


          'tmp_name' => $temp_file,


          'size'     => filesize( $temp_file ),


      );


      $sideload = wp_handle_sideload(


          $file,


          array(


              'test_form'   => false // no needs to check 'action' parameter


          )


      );





      if( ! empty( $sideload[ 'error' ] ) ) {


          // you may return error message if you want


          return false;


      }





      // it is time to add our uploaded image into WordPress media library


      $attachment_id = wp_insert_attachment(


          array(


              'guid'           => $sideload[ 'url' ],


              'post_mime_type' => $sideload[ 'type' ],


              'post_title'     => $name,


              'post_content'   => '',


              'post_status'    => 'inherit',


          ),


          $sideload[ 'file' ]


      );





      if( is_wp_error( $attachment_id ) || ! $attachment_id ) {


          return false;


      }





      // update medatata, regenerate image sizes


      require_once( ABSPATH . 'wp-admin/includes/image.php' );





      wp_update_attachment_metadata(


          $attachment_id,


          wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] )


      );





      return $attachment_id;





  }





}

if (!function_exists('autowriter_callAPI')){
function autowriter_callAPI($method, $url, $data){
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
    );

    switch ($method){
       case "POST":
          $args['method'] = 'POST';
          if ($data)
             $args['body'] = $data;
          break;
       case "PUT":
          $args['method'] = 'PUT';
          if ($data)
             $args['body'] = $data;
          break;
       default:
          if ($data)
             $url = add_query_arg($data, $url);
    }

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        die('Connection Failure');
    }

    $body = wp_remote_retrieve_body($response);

    return $body;
}



}


?>