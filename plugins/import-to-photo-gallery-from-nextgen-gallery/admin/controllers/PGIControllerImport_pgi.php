<?php

class PGIControllerImport_pgi {
  function message($message, $type) {
    return '<div style="width:99%"><div class="' . $type . '"><p><strong>' . $message . '</strong></p></div></div>';
  }
  
  public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html(stripslashes($_POST['task'])) : '');
    if (method_exists($this, $task)) {
	  check_admin_referer('nonce_pgi', 'nonce_pgi');
      $this->$task();
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once PGI_IMPORT_DIR . "/admin/models/PGIModelImport_pgi.php";
    $model = new PGIModelImport_pgi();
    require_once PGI_IMPORT_DIR . "/admin/views/PGIViewImport_pgi.php";
    $view = new PGIViewImport_pgi($model);
    $view->display();
  }

  public function import() {
    global $wpdb;
    global $nggdb;
    require_once PGI_IMPORT_DIR . "/admin/models/PGIModelImport_pgi.php";
    $model = new PGIModelImport_pgi();
    require_once PGI_IMPORT_DIR . "/admin/views/PGIViewImport_pgi.php";
    $view = new PGIViewImport_pgi($model);
    require_once(BWG()->plugin_dir . '/framework/BWGOptions.php');
    $options = new WD_BWG_Options();
    $bwg_path = $options->upload_dir;

    if ( !file_exists($bwg_path . "/import") ) {
      mkdir($bwg_path . "/import");
    }
    if ( !file_exists($bwg_path . "/import/thumb") || !file_exists($bwg_path . "/import/.original") ) {
      mkdir($bwg_path . "/import/thumb");
      mkdir($bwg_path . "/import/.original");
    }
    $galleries_selected = isset($_POST["galleries_selected"]) ? $_POST["galleries_selected"] : array();
    $album_selected = isset($_POST["album_selected"]) ? $_POST["album_selected"] : array();
    if ( $album_selected ) {
      foreach ( $album_selected as $album_id ) {
        $albums = $nggdb->find_album($album_id);
        $album_gallery_ids = $albums->gallery_ids;
        $album_gallery_ids = array_intersect($galleries_selected, $album_gallery_ids);
        foreach ( $album_gallery_ids as $album_gallery_id ) {
          if ( !ctype_digit($album_gallery_id) ) {
            $galleries_selected[] = $model->album_id($album_gallery_id);
          }
          else {
            if ( !in_array($album_gallery_id, $galleries_selected) ) {
              $galleries_selected[] = $album_gallery_id;
            }
          }
        }
      }
    }
    $images_relation = array();
    $galleries_selected = array_unique($galleries_selected);
    $galleries_relation = array();
    $import_images_pid = array();
    $order =((int) $wpdb->get_var('SELECT MAX(`order`) FROM ' . $wpdb->prefix . 'bwg_gallery'));
    foreach ( $galleries_selected as $gallery_id ) {
	   $import_gallery = $model->get_results('ngg_gallery', 'gid', $gallery_id);
	   foreach ( $import_gallery as $gallery ) {
        $ng_upload_dir = strpos($gallery->path, ABSPATH) !== FALSE ? $gallery->path : ABSPATH . $gallery->path;
        $preview_pic = $wpdb->get_var("SELECT pictures.filename AS previewpic, pictures.pid  FROM " . $wpdb->prefix . "ngg_pictures  AS pictures JOIN " . $wpdb->prefix . "ngg_gallery AS gallery ON pictures.pid = gallery.previewpic WHERE gid='".$gallery->gid."'" );
        $preview_pic = $preview_pic != "" ? "/import/thumb/".$preview_pic : "";
        $save = $wpdb->insert($wpdb->prefix . 'bwg_gallery', array(
          'name' => isset($gallery->title) ? $model->pgi_get_unique_name('bwg_gallery', $gallery->title, $gallery->gid) : '',
          'slug' => $model->pgi_get_unique_slug('bwg_gallery', $gallery->slug, $gallery->gid),
          'description' => isset($gallery->galdesc) ? $gallery->galdesc : '',
          'page_link' => '',
          'preview_image' =>  $preview_pic,
          'random_preview_image' => '',
          'order' => ++$order,
          'author' => $gallery->author,
          'gallery_type' => '',
          'gallery_source' => '',
          'autogallery_image_number' => '',
          'update_flag' => '',
          'published' => 1,
          ), array(
           '%s',
           '%s',
           '%s',
           '%s',
           '%s',
           '%s',
           '%d',
           '%d',
           '%s',
           '%s',
           '%d',
           '%s',
           '%d',
        ));
        $id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'bwg_gallery');
        $galleries_relation[$gallery->gid] = $id;
        $import_images = $model->get_results('ngg_pictures','galleryid',$gallery->gid);
        foreach ($import_images as $import_image) {
          $import_images_pid[]= $import_image->pid;
          if ( file_exists($ng_upload_dir . '/' . $import_image->filename) ) {
            copy($ng_upload_dir . '/' . $import_image->filename, $bwg_path . '/import/' . $import_image->filename);
            copy($ng_upload_dir . '/' . $import_image->filename, $bwg_path . '/import/.original/' . $import_image->filename);
          }
          if ( file_exists($ng_upload_dir . '/thumbs/thumbs_' . $import_image->filename) ) {
            copy($ng_upload_dir . '/thumbs/thumbs_' . $import_image->filename, $bwg_path . '/import/thumb/' . $import_image->filename);
          }
          $image_url = '/import/' . $import_image->filename;
          $thumb_url = '/import/thumb/' . $import_image->filename;
          $resuolution = @getimagesize($bwg_path . '/import/.original/' . $import_image->filename);
          $path_parts = pathinfo($import_image->filename);
          $size = round(@filesize($bwg_path . '/import/.original/' . $import_image->filename) / 1024);
          $save = $wpdb->insert($wpdb->prefix . 'bwg_image', array(
            'gallery_id' => $id,
            'slug' => $import_image->image_slug."-bwg",
            'filename' => $import_image->filename,
            'image_url' => $image_url,
            'thumb_url' => $thumb_url,
            'description' => isset($import_image->description) ? $import_image->description : '',
            'alt' => isset($import_image->alttext) ? $import_image->alttext : '',
            'date' => $import_image->imagedate,
            'size' =>  $size . ' KB',
            'filetype' => strtoupper($path_parts['extension']),
            'resolution' => $resuolution[0] . ' x ' . $resuolution[1] . ' px' ,
            'author' => '',
            'order' => $import_image->sortorder,
            'published' => 1,
            'comment_count' => 0,
            'avg_rating' => 0,
            'rate_count' => 0,
            'hit_count' => 0,
            'redirect_url' => '',
          ), array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
            '%d',
            '%s',
          ));
          $max_image_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'bwg_image');
          $images_relation[$import_image->pid] = $max_image_id;
        }
      }
    }
    if (isset($_POST['import_tags']) && esc_html($_POST['import_tags']) == '1') {
      $import_images_pid = implode(',',$import_images_pid);
      $terms = $wpdb->get_results('SELECT t2.term_id, t2.description, t2.`parent`, t2.`count`, t3.name, t3.slug, t3.term_group, t1.object_ids FROM ( SELECT GROUP_CONCAT( object_id SEPARATOR ",") AS object_ids, term_taxonomy_id  FROM ' . $wpdb->prefix . 'term_relationships GROUP BY term_taxonomy_id) AS t1 JOIN ' . $wpdb->prefix . 'term_taxonomy AS t2  ON  t1.term_taxonomy_id = t2.term_taxonomy_id LEFT JOIN ' . $wpdb->prefix . 'terms AS t3 ON t3.term_id = t2.term_id WHERE t1.object_ids IN (' . $import_images_pid . ') AND t2.taxonomy="ngg_tag"');
      foreach ($terms as $term) {
        $save_terms = $wpdb->insert($wpdb->prefix . 'terms', array(
          'name' => $term->name,
          'slug' => $term->slug,
          'term_group' => $term->term_group,
        ), array(
          '%s',
          '%s',
          '%d',
        ));

        $max_term_id = $wpdb->get_var('SELECT MAX(term_id) FROM ' . $wpdb->prefix . 'terms');
        $object_ids = explode(",",$term->object_ids);
        foreach($object_ids as $pid){
          $save_image_tag = $wpdb->insert($wpdb->prefix . 'bwg_image_tag', array(
           'tag_id' => $max_term_id,
           'image_id' => $images_relation[$pid],
           'gallery_id' => $id,
           ), array(
            '%d',
            '%d',
            '%d',
          ));
        }
        $new_term_id = $wpdb->insert($wpdb->prefix . 'term_taxonomy', array(
          'term_id' => $max_term_id,
          'taxonomy' => 'bwg_tag',
          'description' => $term->description,
          'parent' => $term->parent,
          'count' => $term->count,
        ), array(
         '%d',
         '%s',
         '%s',
         '%d',
         '%d',
        ));
      }
    }
    $author = get_current_user_id();
    $album_selected = isset($_POST["album_selected"]) ? $_POST["album_selected"] : array();
    if ( $album_selected ) {
      foreach ( $album_selected as $albums_id ) {
        $albums = $nggdb->find_album($albums_id);
        $album_gallery_ids = $albums->gallery_ids;
        $album_gallery_ids = array_intersect($album_selected,$album_gallery_ids);
        foreach ( $album_gallery_ids as $album_gallery_id ) {
          if ( ctype_digit($album_gallery_id) ) {
            continue;
          }
          $album_selected[] = substr($album_gallery_id, 1);
        }
      }
      $album_relation = array();
      $album_selected = array_unique($album_selected);
      $order = ((int) $wpdb->get_var('SELECT MAX(`order`) FROM ' . $wpdb->prefix . 'bwg_album'));
      foreach ( $album_selected as $albums_id ) {
	      $album = $model->get_results('ngg_album', 'id', $albums_id);
        $album = $album[0];
        $preview_pic = $wpdb->get_var("SELECT pictures.filename AS previewpic, pictures.pid  FROM " . $wpdb->prefix . "ngg_pictures  AS pictures JOIN " . $wpdb->prefix . "ngg_album AS album ON pictures.pid = album.previewpic WHERE id='".$album->id."'" );
        if ( $preview_pic != "" && file_exists($bwg_path . "/import/thumb/" . $preview_pic) ) {
          $preview_pic = "/import/thumb/" . $preview_pic;
        }
        else {
          $preview_pic = '';
        }
        $save = $wpdb->insert($wpdb->prefix . 'bwg_album', array(
          'name' => $model->pgi_get_unique_name('bwg_album',$album->name,$album->id),
          'slug' => $model->pgi_get_unique_slug('bwg_album',$album->slug.'-bwg',$album->id),
          'description' => isset($album->albumdesc) ? $album->albumdesc : '',
          'preview_image' => $preview_pic,
          'random_preview_image' => '',
          'order' => ++$order,
          'author' => $author,
          'published' => 1,
          ), array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%d',
          '%d',
          '%d'
        ));	
		    $album_max_id = (int)$wpdb->get_var('SELECT MAX(`id`) FROM ' . $wpdb->prefix . 'bwg_album');
		    $album_relation[$album->id] = $album_max_id;
	    }
      if ($album_relation != array()) {
		  $order = ((int) $wpdb->get_var('SELECT MAX(`order`) FROM ' . $wpdb->prefix . 'bwg_album_gallery'));
	      foreach ($album_selected as $albums_id) {
		      $album = $model->get_results('ngg_album', 'id', $albums_id);
          $album = $album[0];
          $albums = $nggdb->find_album($album->id);
          $album_gallery_ids = $albums->gallery_ids;
          foreach ($album_gallery_ids as $album_gallery_id) {
            if (ctype_digit($album_gallery_id)) {
              if (!in_array($album_gallery_id, $galleries_selected)) {
	              continue;
              }
              $save = $wpdb->insert($wpdb->prefix . 'bwg_album_gallery', array(
                'album_id' => $album_relation[$album->id],
                'is_album' => 0,
                'alb_gal_id' => $galleries_relation[$album_gallery_id],
                'order' => ++$order,
                ), array(
                '%d',
                '%d',
                '%d',
                '%d'
              ));
            }
            else {
              if (!in_array(substr($album_gallery_id, 1), $album_selected)) {
                continue;
              }
              $save = $wpdb->insert($wpdb->prefix . 'bwg_album_gallery', array(
                'album_id' => $album_relation[$album->id],
                'is_album' => 1,
                'alb_gal_id' => $album_relation[substr($album_gallery_id, 1)],
                'order' => ++$order,
               ), array(
               '%d',
               '%d',
               '%d',
               '%d'
              ));
            }
          }
        }
	    }
	  }
    if ($images_relation != array()) {
      if (isset($_POST['import_comments']) && esc_html($_POST['import_comments']) == '1') {
        $comments = $wpdb->get_results("SELECT t1.*, t2.post_name  FROM " . $wpdb->prefix . "comments AS t1 LEFT JOIN " . $wpdb->prefix . "posts AS t2 ON t1.comment_post_ID = t2.ID WHERE t2.comment_count>0 AND t2.post_type='photocrati-comments' ");
        foreach($comments as $comment) {
          $image_id = preg_replace("/[^0-9]/","",$comment->post_name);
          if (array_key_exists($image_id,$images_relation)) {
            $save = $wpdb->insert($wpdb->prefix . 'bwg_image_comment', array(
              'image_id' => $images_relation[$image_id],
              'name' => $comment->comment_author,
              'date' => $comment->comment_date,
              'comment' => $comment->comment_content,
              'url' => $comment->comment_author_url,
              'mail' => $comment->comment_author_email,
              'published' => 1,
            ), array(
              '%d',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%d'
            ));
            $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'bwg_image SET comment_count=comment_count+1 WHERE id="%d"', $images_relation[$image_id]));
          }
        }
      }
    }
    if (is_plugin_active('nextgen-gallery-voting/ngg-voting.php')) {
      if (isset($_POST['import_rating']) && esc_html($_POST['import_rating']) == '1') {
	      $ratings = $model->get_results('nggv_votes');
        foreach ($ratings as $rating) {
          $save = $wpdb->insert($wpdb->prefix . 'bwg_image_rate', array(
	          'image_id' => $images_relation[$rating->pid],
	          'rate' => $rating->vote,
	          'ip' => $rating->ip,
	          'date' => $rating->dateadded,
	          ), array(
	          '%d',
	          '%d',
	          '%s',
	          '%s'
		      ));
          $rates = $wpdb->get_row($wpdb->prepare('SELECT AVG(`rate`) as `average`, COUNT(`rate`) as `rate_count` FROM ' . $wpdb->prefix . 'bwg_image_rate WHERE image_id="%d"', $images_relation[$rating->pid]));
          $wpdb->update($wpdb->prefix . 'bwg_image', array('avg_rating' => $rates->average, 'rate_count' => $rates->rate_count), array('id' => $images_relation[$rating->pid]));
        }
      }
	  }
    if ($save !== FALSE) {
      echo $model->message(__('The data is successfully imported.', 'pgi'), 'updated');
	    $view->display();
    }
  }
}
