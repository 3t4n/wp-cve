<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: PostLists
Version: 2.0.2
Plugin URI: http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html
Description: This WordPress plugin provides placeholders for configurable dynamic lists of posts, that can be used in posts, pages, widgets or template files. After activation please go to "Manage" and then to the submenu "<a href="edit.php?page=postlists">PostLists</a>", to manage your lists.
Author: Ren&eacute; Ade
Author URI: http://www.rene-ade.de
*/
//-----------------------------------------------------------------------------
?>
<?php

//-----------------------------------------------------------------------------

if( !function_exists('pl_plugin_basename') ) {
  function pl_plugin_basename() {
    return plugin_basename(__FILE__);
  }
}

//-----------------------------------------------------------------------------

// parse args
function pl_parseargs( $args, $defaults=null ) {
  if( function_exists('wp_parse_args') ) // min wp 2.2
    return wp_parse_args( $args, $defaults );
   
  // fallback 
  if( $defaults )
    $args = array_merge( $defaults, $args );
  return $args;
}

//-----------------------------------------------------------------------------

// get defaults for pl_getposts(...)
function pl_getposts_getdefaults() {
  global $wpdb;
  
  // return defaults
  return array(
    'load', // additional tables to load
    'select' => '*', 'distinct' => true,// cols to load
    'groupby' => $wpdb->posts.'.ID', // group by
    'taxonomy', // taxonomy
    'numberposts', 'offset', // numberposts and offset
    'category', 'subcategories', // category
    'orderby' => 'post_date', 'order' => 'DESC',  // order
    'include', 'exclude', // include, exclude posts
    'meta_key', 'meta_value', // meta
    'post_type' => 'post', 'post_status' => 'publish', // type and status
    'comment_status', // comment status
    'post_password', // password status
    'post_future_min', 'post_future_max', // post future time
    'post_age_min', 'post_age_max', // post age 
    'post_modified_min', 'post_modified_max', // post modified 
    'post_parent', // post parent
    'author', // auhtor
    'tag', // tag
    'comments', // number of comments
    'where' // additional where clause
  );
}
     
// get all posts matching the args      
//   this is just a extended version of get_posts(...) in wp-includes/post.php
function pl_getposts( $args ) {
  global $wpdb;

  // get args, use defaults for not set values
  $args = pl_parseargs( $args, pl_getposts_getdefaults() );
  // extract args
  extract( $args, EXTR_SKIP );

  // inclusions
  $inclusions = '';
  if( !empty($include) ) {
    $incposts = preg_split('/[\s,]+/',$include);
    $numberposts = count($incposts); 
    if ( count($incposts) ) {
      foreach ( $incposts as $incpost ) {
        if (empty($inclusions))
          $inclusions = ' AND ( ID = ' . intval($incpost) . ' ';
        else
          $inclusions .= ' OR ID = ' . intval($incpost) . ' ';
      }
    }
  }
  if( !empty($inclusions) )
    $inclusions .= ')';
    
  // exclusions
  $exclusions = '';
  if( !empty($exclude) ) {
    $exposts = preg_split('/[\s,]+/',$exclude);
    if( count($exposts) ) {
      foreach( $exposts as $expost ) {
        if(empty($exclusions))
          $exclusions = ' AND ( ID <> ' . intval($expost) . ' ';
        else
          $exclusions .= ' AND ID <> ' . intval($expost) . ' ';
      }
    }
  }
  if( !empty($exclusions) )
    $exclusions .= ')';

  // categories
  $categories = array();
  if( isset($category) && !empty($category) && $category!=0 ) {
    $categories = preg_split( '/[\s,]+/', $category );
  }
  if( (bool)$subcategories ) { // get subcategories if needed
    foreach( $categories as $category ) {
      $category = abs( intval( $category ) );
      if( $category!=0 ) {
        $categories = array_merge( $categories, get_term_children($category,'category') );
      }
    }
  }
  
  // tags
  $tags = array();
  if( isset($tag) && !empty($tag) && $tag!=0 ) {
    $tags = preg_split( '/[\s,]+/', $tag );
  } 
  
  // password
  if( isset($post_password) && is_numeric($post_password) )
    $post_password = (bool)$post_password; // has to hace password or not // this does not support numeric passwords!
  if( isset($post_password) && is_string($post_password) && strlen($post_password)<=0 )
    $post_password = null; // empty: does not mather
  
  // tables
  if( isset($load) && is_array($load) )
    $load = implode(', ',$load);  
  
  // query
  $query  = "SELECT ".($distinct?"DISTINCT":"")." $select FROM $wpdb->posts ";
  $query .= count($categories)==0 && count($tags)==0 ? '' : ", $wpdb->term_relationships, $wpdb->term_taxonomy  ";
  $query .= empty( $load ) ? '' : ', '.$load.' ';
  $query .= empty( $meta_key ) ? '' : ", $wpdb->postmeta ";
  $query .= " WHERE 1 ";
  $query .= empty( $taxonomy ) ? '' : " AND taxonomy='$taxonomy'";
  $query .= empty( $post_type ) ? '' : "AND post_type = '$post_type' ";
  $query .= empty( $post_status ) ? '' : "AND post_status = '$post_status' ";
  $query .= empty( $comment_status ) ? '' : "AND comment_status = '$comment_status' ";
  $query .= $post_password===null ? '' : "AND ".
              ( $post_password===true ? "NOT ( post_password='' OR post_password IS NULL ) " : 
              ( $post_password===false ? "( post_password='' OR post_password IS NULL ) " : 
              "post_password = '$post_password' " ) );
  $query .= !isset($post_future_min) || $post_future_min==0 ? '' : "AND post_date>='".gmdate('Y-m-d H:i:s',(current_time('timestamp')+$post_future_min))."' ";
  $query .= !isset($post_future_max) || $post_future_max==0 ? '' : "AND post_date<='".gmdate('Y-m-d H:i:s',(current_time('timestamp')+$post_future_max))."' ";
  $query .= !isset($post_age_min) || $post_age_min==0 ? '' : "AND post_date<='".gmdate('Y-m-d H:i:s',(current_time('timestamp')-$post_age_min))."' ";
  $query .= !isset($post_age_max) || $post_age_max==0 ? '' : "AND post_date>='".gmdate('Y-m-d H:i:s',(current_time('timestamp')-$post_age_max))."' ";
  $query .= !isset($post_modified_min) || $post_modified_min==0 ? '' : "AND post_modified<='".gmdate('Y-m-d H:i:s',(current_time('timestamp')-$post_modified_min))."' ";
  $query .= !isset($post_modified_max) || $post_modified_max==0 ? '' : "AND post_modified>='".gmdate('Y-m-d H:i:s',(current_time('timestamp')-$post_modified_max))."' ";
  $query .= !isset($author) || $author==0 ? '' : "AND post_author = $author ";
  $query .= "$exclusions $inclusions " ;
  if( count($tags)>0 ) {
    $query .= "AND ( 0 ";
    foreach( $tags as $tag ) 
      $query .= "OR ($wpdb->posts.ID = $wpdb->term_relationships.object_id AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id AND $wpdb->term_taxonomy.term_id = $tag ) ";
    $query .= ") ";      
  }
  if( count($categories)>0 ) {
    $query .= "AND ( 0 ";
    foreach( $categories as $category ) 
      $query .= "OR ($wpdb->posts.ID = $wpdb->term_relationships.object_id AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id AND $wpdb->term_taxonomy.term_id = $category ) ";
    $query .= ") ";      
  }
  $query .= ( !isset($post_parent) || $post_parent==='' ) ? '' : "AND $wpdb->posts.post_parent = '$post_parent' ";
  if( !empty($meta_key) ) {
    $query .= empty( $meta_key ) ? '' : " AND ($wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key = '$meta_key' ";
    if( !empty($meta_value) )
      $query .= "AND $wpdb->postmeta.meta_value = '$meta_value'";
    $query .= " )";
  }
  $query .= $comments==0 ? '' : " AND comment_count>=$comments";
  $query .= empty( $where ) ? '' : " AND $where";
  $query .= empty( $groupby ) ? '' : " GROUP BY $groupby ";
  $query .= empty( $orderby ) ? '' : " ORDER BY $orderby $order";
  if( $numberposts>0 ) // no offset without numberposts. numberposts -1 would work as a hack...
    $query .= " LIMIT ".(isset($offset)&&$offset?"$offset, ":'')."$numberposts";

  // extensions
  $query = apply_filters( 'ple_query', $query, $args );
  
  // get results (the posts matching the query)
  $posts = $wpdb->get_results( $query );
  
  // extensions
  $posts = apply_filters( 'ple_posts', $posts, $args );
  
  // update post caches
  update_post_caches( $posts );
  
  // return posts
  return $posts;
}

//-----------------------------------------------------------------------------

// get supported placeholders
function pl_getsupportedplaceholders( $post=null, $args=true, $extend=true ) {

  $placeholders = array();
  
  // placeholders
  $placeholders = array_merge( $placeholders, array(
    'count', 'offset', 'relativecounterbase', 
    'categoryid', 'category', 'categoryurl',
    'parent', 'parentid', 'parenturl', 'parentdate', 'parenttime',
    'tag', 'tagid', 'tagslug', 'tagurl',
    'author', 'authorid', 'authorurl',
    'date', 'time',
    'year', 'monthnum', 'day', 
    'hour', 'minute', 'second',
    'postfutureminminutes', 'postfuturemaxminutes', 
    'postfutureminhours', 'postfuturemaxhours', 
    'postfuturemindays', 'postfuturemaxdays',
    'postageminminutes', 'postagemaxminutes', 
    'postageminhours', 'postagemaxhours', 
    'postagemindays', 'postagemaxdays',
    'postmodifiedminminutes', 'postmodifiedmaxminutes', 
    'postmodifiedminhours', 'postmodifiedmaxhours', 
    'postmodifiedmindays', 'postmodifiedmaxdays'
  ) );
    
  // post placeholders
  if( $post ) {
    // add post placeholders
    $placeholders = array_merge( $placeholders, array(
      'counter', 'devcounter', 'relativecounter', 
      'id', 'postid', 'post', 'title', 'posttitle', 
      'url', 'posturl', 'guid',
      'content', 'excerpt', 'fullcontent', 
      'author', 'authorid', 'authorurl',
      'comments',      
      'date', 'time',      
      'postdate', 'posttime',      
      'year', 'monthnum', 'day', 
      'hour', 'minute', 'second',
      'modifieddate', 'modifiedtime',
      'modifiedyear', 'modifiedmonthnum', 'modifiedday', 
      'modifiedhour', 'modifiedminute', 'modifiedsecond',
      'daydiff', 'hourdiff', 'minutediff', 'seconddiff'          
    ) );
  }
  
  // meta placeholders
  if( $post && (!is_array($args)&&$args) || $args['metaplaceholders'] ) {
    global $wpdb;
    $meta_keys = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key ORDER BY meta_key" );
    if( is_array($meta_keys) ) {
      foreach( $meta_keys as $meta_key ) {      
        if( substr($meta_key,0,1)!='_' )
          $placeholders[] = 'meta_'.$meta_key;
      }
    }
  }
  
  // col placeholders
  if( $post && (!is_array($args)&&$args) || $args['colplaceholders'] ) {
    if( is_object($post) ) {
      $post_members = array_keys( get_object_vars($post) );
      foreach( $post_members as $post_member )      
        $placeholders[] = 'col_'.$post_member;
    }
  }
  
  // extensions
  if( $extend )
    $placeholders = apply_filters( 'ple_placeholders', $placeholders, $post, $args );
    
  // return placeholders
  return $placeholders;
}

// returns the value for a single placeholder
//   the value of post can be null (do not use post-placeholders)
function pl_getplaceholdervalue( $name, $args, $posts, $post=null ) { 

  // get placeholderstring
  $value = pl_getplaceholdervalue_internal( $name, $args, $posts, $post );

  // extensions
  $value = apply_filters( 'ple_placeholdervalue', $value, $name, $args, $posts, $post );

  // return
  return $value;
}
function pl_getplaceholdervalue_internal( $name, $args, $posts, $post=null ) { 

  // post vars
  if( $post ) {
    switch( $name ) {
      case 'counter':
        return $post['pl_counter']+1;
      case 'devcounter':
        return $post['pl_counter'];
      case 'relativecounter':
        return ((int)$args['offset'])+$post['pl_counter']+1;
      case 'id':
      case 'postid':
        return $post->ID;
      case 'post':
      case 'title':
      case 'posttitle':      
        if( ((int)$args['titlelength']) && ((int)$args['titlelength'])>0 ) { // cut title if maxlength is set
          $title = substr(get_the_title($post->ID),0,((int)$args['titlelength']));
          if( strlen($title)!=strlen(get_the_title($post->ID)) )
            $title.= $args['titleincomplete'];
          return $title;
        }
        else
          return get_the_title($post->ID);
      case 'url':
      case 'posturl':
        return get_permalink($post->ID);
      case 'guid':
        return get_the_guid($post->ID);
      case 'content':
        if( preg_match('/<!--more(.*?)?-->/',$post->post_content,$matches) ) {
          $content = explode( $matches[0], $post->post_content, 2 );
          return $args['apply_filters'] ? apply_filters('the_content',$content[0]) : $content[0];
        }
        else
          return $args['apply_filters'] ? apply_filters('the_content',$post->post_content) : $post->post_content;
      case 'excerpt':
        return $args['apply_filters'] ? apply_filters('the_excerpt',$post->post_excerpt) : $post->post_excerpt; 
      case 'fullcontent':      
        return $args['apply_filters'] ? apply_filters('the_content',$post->post_content) : $post->post_content;
      case 'author':
        return get_author_name( $post->post_author );
      case 'authorid':
        return $post->post_author;  
      case 'authorurl':
        $author = get_userdata( $post->post_author );
        return get_author_posts_url( $author->ID, $author->user_nicename );
      case 'date':
      case 'postdate':      
        return mysql2date( get_option('date_format'), $post->post_date );      
      case 'time':
      case 'posttime':      
        return mysql2date( get_option('time_format'), $post->post_date );      
      case 'year':
        return date('Y',strtotime($post->post_date));
      case 'monthnum':
        return date('m',strtotime($post->post_date));
      case 'day':
        return date('d',strtotime($post->post_date));
      case 'hour':
        return date('H',strtotime($post->post_date));
      case 'minute':
        return date('i',strtotime($post->post_date));
      case 'second':
        return date('s',strtotime($post->post_date));
      case 'seconddiff':
        return str_pad( floor(abs((current_time('timestamp')-strtotime($post->post_date))/(1)))%(60), 2 ,'0', STR_PAD_LEFT );
      case 'minutediff':
        return str_pad( floor(abs((current_time('timestamp')-strtotime($post->post_date))/(60)))%(60), 2 ,'0', STR_PAD_LEFT );
      case 'hourdiff':
        return str_pad( floor(abs((current_time('timestamp')-strtotime($post->post_date))/(60*60)))%(24), 2 ,'0', STR_PAD_LEFT );
      case 'daydiff':
        return (floor(abs((current_time('timestamp')-strtotime($post->post_date))/(60*60*24))));
      case 'modifieddate':
        return mysql2date( get_option('date_format'), $post->post_modified );      
      case 'modifiedtime':
        return mysql2date( get_option('time_format'), $post->post_modified );      
      case 'modifiedyear':
        return date('Y',strtotime($post->post_modified));
      case 'modifiedmonthnum':
        return date('m',strtotime($post->post_modified));
      case 'modifiedday':
        return date('d',strtotime($post->post_modified));
      case 'modifiedhour':
        return date('H',strtotime($post->post_modified));
      case 'modifiedminute':
        return date('i',strtotime($post->post_modified));
      case 'modifiedsecond':
        return date('s',strtotime($post->post_modified));
      case 'comments':
        return $post->comment_count;
    }  
  }
  
  // list vars
  switch( $name ) {
    case 'count':
      return count($posts);
    case 'categoryid':    
      return ((int)$args['category']);
    case 'category':
      return get_catname(((int)$args['category']));
    case 'categoryurl':
      return get_category_link(((int)$args['category']));               
    case 'offset':
      return ((int)$args['offset']);
    case 'relativecounterbase':
      return ((int)$args['offset'])+1;
    case 'parent':
    case 'parenttitle':    
      return get_the_title(((int)$args['post_parent']));
    case 'parentid':
      return ((int)$args['post_parent']);
    case 'parenturl':
      return get_permalink( (int)$args['post_parent'] );
    case 'parentdate':
      $postid = (int)$args['post_parent'];
      $parent = get_post( $postid );
      return mysql2date( get_option('date_format'), $parent->post_date );
    case 'parenttime':
      $postid = (int)$args['post_parent'];
      $parent = get_post( $postid );
      return mysql2date( get_option('time_format'), $parent->post_date );
    case 'tag':
      $tag = get_tag( (int)$args['tag'] );
      if( !$tag )
        return '';
      return $tag->name;
    case 'tagslug':
      $tag = get_tag( (int)$args['tag'] );
      if( !$tag )
        return '';
      return $tag->slug;      
    case 'tagid':
      return ((int)$args['tag']);
    case 'tagurl':
      return get_tag_link( (int)$args['tag'] );
    case 'author':
      return get_author_name(((int)$args['author']));
    case 'authorid':
      return ((int)$args['author']);
    case 'authorurl':
      $author = get_userdata( (int)$args['author'] );
      return get_author_posts_url( $author->ID, $author->user_nicename );
    case 'date':
      return date( get_option('date_format'), current_time('timestamp') );      
    case 'time':
      return date( get_option('time_format'), current_time('timestamp') );      
    case 'year':
      return date( 'Y', current_time('timestamp') );
    case 'monthnum':
      return date( 'm', current_time('timestamp') );
    case 'day':
      return date( 'd', current_time('timestamp') );
    case 'hour':
      return date( 'H', current_time('timestamp') );
    case 'minute':
      return date( 'i', current_time('timestamp') );
    case 'second':
      return date( 's', current_time('timestamp') );
    case 'postfutureminminutes':
      return ((int)$args['post_future_min']/(60));
    case 'postfuturemaxminutes':
      return ((int)$args['post_future_max']/(60));
    case 'postfutureminhours':
      return ((int)$args['post_future_min']/(60*60));
    case 'postfuturemaxhours':
      return ((int)$args['post_future_max']/(60*60));
    case 'postfuturemindays':
      return ((int)$args['post_future_min']/(60*60*24));
    case 'postfuturemaxdays':
      return ((int)$args['post_future_max']/(60*60*24));
    case 'postageminminutes':
      return ((int)$args['post_age_min']/(60));
    case 'postagemaxminutes':
      return ((int)$args['post_age_max']/(60));
    case 'postageminhours':
      return ((int)$args['post_age_min']/(60*60));
    case 'postagemaxhours':
      return ((int)$args['post_age_max']/(60*60));
    case 'postagemindays':
      return ((int)$args['post_age_min']/(60*60*24));
    case 'postagemaxdays':
      return ((int)$args['post_age_max']/(60*60*24));
    case 'postmodifiedminminutes':
      return ((int)$args['post_modified_min']/(60));
    case 'postmodifiedmaxminutes':
      return ((int)$args['post_modified_max']/(60));
    case 'postmodifiedminhours':
      return ((int)$args['post_modified_min']/(60*60));
    case 'postmodifiedmaxhours':
      return ((int)$args['post_modified_max']/(60*60));
    case 'postmodifiedmindays':
      return ((int)$args['post_modified_min']/(60*60*24));
    case 'postmodifiedmaxdays':
      return ((int)$args['post_modified_max']/(60*60*24));
  }  
  
  // col placeholders
  if( $post && $args['colplaceholders'] ) { 
    if( substr($name,0,strlen('col_'))=='col_' ) { // if starts with col_
      $col = substr( $name, strlen('col_') ); // strip col_
      return $post->$col;
    }
  }
  
  // meta placeholders
  if( $post && $args['metaplaceholders'] ) {
    if( substr($name,0,strlen('meta_'))=='meta_' ) { // if starts with meta_        
      $meta_key = substr( $name, strlen('meta_') ); // strip meta_      
      $meta_values = get_post_custom_values( $meta_key, $post->ID );
      if( $meta_values ) {
        if( count($meta_values)>1 ) {
    			$meta_values = array_map( 'trim', $meta_values );
    			$meta_values = implode( ', ', $meta_values );
        }
        else {
          $meta_values = $meta_values[0];
        }
      }
      return $meta_values;
    }
  }    
  
  return false;
}

// returns the string with replaced placeholders
//   the value of post can be null (do not support post-placeholders for this string)
function pl_getstring( $string, $args, $posts, $post=null ) {
  
  // check if there are anyplaceholders (save performance if there are no placeholders)
  if( strpos($string,'%')===false ) 
    return $string;

  // get all known placeholders 
  $placeholders = pl_getsupportedplaceholders( $post, $args, true );
    
  // replace placeholders
  foreach( $placeholders as $placeholder ) {
    if( strpos($string,'%'.$placeholder.'%')!==false ) { // replace only if the placeholder exists (no need to calculate value if not needed)
      // get placeholder string
      $value = pl_getplaceholdervalue( $placeholder, $args, $posts, $post ); // get value
      // replace placeholder
      $string = str_replace( '%'.$placeholder.'%', $value, $string ); // replace placeholders to value
    }
  }
  
  // return string
  return $string;
}

//-----------------------------------------------------------------------------

// get environment
function pl_environment( $args ) {
  
  // current post
  if( array_key_exists('post_parent',$args) && strpos($args['post_parent'],'THIS')!==false ) 
    $args['post_parent'] = str_replace( 'THIS', get_the_ID(), $args['post_parent'] );
  if( array_key_exists('include',$args) && strpos($args['include'],'THIS')!==false ) 
    $args['include'] = str_replace( 'THIS', get_the_ID(), $args['include'] );
  if( array_key_exists('exclude',$args) && strpos($args['exclude'],'THIS')!==false ) 
    $args['exclude'] = str_replace( 'THIS', get_the_ID(), $args['exclude'] );
    
  return $args;
}

//-----------------------------------------------------------------------------

// get complete args array, with default options for a list
function pl_getlistdefaults() {

  // return defauloptions
  return array_merge( pl_getposts_getdefaults(), array(
    'before' => '', 
    'after' => '',
    'entry' => '<li><a href="%posturl%">%posttitle%</a></li>',
    'noposts',
    'minnumberposts' => 1,
    'titlelength',
    'titleincomplete',
    'apply_filters' => false,
    'metaplaceholders' => false,
    'colplaceholders' => false
  ) );
}

//-----------------------------------------------------------------------------

// returns the postlist string by passing the list args
function pl_getlist( $args ) {

  // the output string
  $output = null;
    
  // fill args with missing keys
  $args = pl_parseargs( $args, pl_getlistdefaults() );  

  // get env
  $args = pl_environment( $args );
  
  // extensions
  $args = apply_filters( 'ple_args', $args );
  
  // check if allowed to get native list
  $ple_getlist = apply_filters( 'ple_getlist', true, $args );
  
  // if get list
  if( $ple_getlist ) {
  
    // get posts matching args
    $posts = pl_getposts( $args );
    if( is_array($posts) ) {

      // count
      $count = count( $posts );
      
      // check if there are posts
      $min = $args['minnumberposts'];
      if( empty($min) )
        $min = 1;
      if( $count>=$min ) {
        
        // create output string
        $output = '';
        $output.= pl_getstring( $args['before'], $args, $posts, null ); // before
        $counter = 0;
        foreach( $posts as $post ) {
          $post->pl_counter = $counter; // add counter to post
          $output.= pl_getstring( $args['entry'], $args, $posts, $post ); // entry
          $counter++;
        }
        $output.= pl_getstring( $args['after'], $args, $posts, null ); // after
      
      }
      else { 
      
        // no posts
        if( strlen($args['noposts'])>0 ) { // check if noposts-string is used
          $output = pl_getstring( $args['noposts'], $args, $posts, null );
        }  
        
      }
    }
    else {
    
      // posts is not array
      $output = false;
      
    } 
     
  }
  
  // extensions
  $output = apply_filters( 'ple_list', $output, $args, $ple_getlist );
  
  // comment // if you dont like this comment, you may remove it :-(
  if( is_string($output) ) {
    $output = '<!-- '.
              'WordPress Plugin PostLists by Rene Ade'.
              ' - '.
              'http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html'.
              ' -->'.
              $output;
  }
  
  // return output string
  return $output;
}

// returns the postliststring by passing the placeholder name
function pl_getlist_byplaceholder( $placeholdername ) {

  // get args
  $pl_lists = get_option( 'pl_lists' );
  if( !is_array($pl_lists) )
    return false;
  if( !array_key_exists($placeholdername,$pl_lists) )
    return null;
  
  // get list by args
  return pl_getlist( $pl_lists[$placeholdername] );
}

//-----------------------------------------------------------------------------

// array sort function, longest first
function pl_replacelistplaceholders_sort( $name1, $name2 ) {

  return strlen($name2)-strlen($name1);
}

// replace all postlist-placeholders
function pl_replacelistplaceholders( $string ) {
  
  // get lists
  $pl_lists = get_option( 'pl_lists' );
  if( !is_array($pl_lists) )
    return $string;
    
  // replace placeholders
  $pl_list_names = array_keys( $pl_lists );
  usort( $pl_list_names, 'pl_replacelistplaceholders_sort' ); // support placeholders like "test" and "testtest" at the same time
  foreach( $pl_list_names as $list_name ) {
    if( strpos($string,$list_name)!==false ) { // first check if the placeholder exists (do not get list if not needed)
      $list_string = pl_getlist( $pl_lists[$list_name] ); // get list once
      $string = str_replace( $list_name, $list_string, $string ); // replace all placeholders for this list
    }
  }
  
  // return string
  return $string;
}

// replace all postlist-placeholders - called for posts (and pages)
function pl_replacelistplaceholders_posts( $string ) {

  // get config
  $pl_config = get_option( 'pl_config' );
  // check setting
  if( $pl_config['process']['posts'] )
    return pl_replacelistplaceholders( $string );
  else
    return $string; // do not process string
}

// replace all postlist-placeholders - called for widgets
function pl_replacelistplaceholders_widgets( $string ) {

  // get config
  $pl_config = get_option( 'pl_config' );
  // check setting
  if( $pl_config['process']['widgets'] )
    return pl_replacelistplaceholders( $string );
  else
    return $string; // do not process string
}

//-----------------------------------------------------------------------------

// outputs the postliststring
//   use this function in templatefiles
//   this is the only function supported for public calls
//   pass the placeholder of the list you like to display as first param
//   example:
//       if( function_exists("pl_postlist") ) 
//         pl_postlist("MYPLACEHOLDER"); 
function pl_postlist( $placeholdername ) {
 
  // output list 
  echo pl_getlist_byplaceholder( $placeholdername );
}

//-----------------------------------------------------------------------------

// for extensions
function pl_getlists() {

  // return all lists
  return get_option( 'pl_lists' );
}

//-----------------------------------------------------------------------------

// add a submenu page to the adminmenu edit 
function pl_adminmenu() {
 
  // include file containing admin interface
  pl_include( 'admin.php' );

  // register the submenu page
	add_submenu_page( 'edit.php', 'PostLists', 'PostLists', 10/*ADMIN_ONLY*/, 'postlists', 'pl_admin' ); 
}

//-----------------------------------------------------------------------------

// include a file
function pl_include( $file ) {

  // include it
  return include_once( dirname(__FILE__).'/includes/'.$file );
}

//-----------------------------------------------------------------------------

// initialize
function pl_init() {
  
  // notify extensions init
  do_action( 'ple_init' );
  
  // done
  return;
}
      
//-----------------------------------------------------------------------------

// plugin activation
function pl_activate() {

  // activated first time
  $first = true;

  // add config option if needed
  $pl_config = get_option( 'pl_config' );
  if( is_array($pl_config) && array_key_exists('initialized',$pl_config) ) // check if was initialized before
    $first = !$pl_config['initialized']; // if initialized, its not activated first time
  if( !$pl_config )
    add_option( 'pl_config', array() );
    
  // add lists option if needed
  $pl_lists = get_option( 'pl_lists' );
  if( !$pl_lists ) {
    $pl_lists = array();
    add_option( 'pl_lists', $pl_lists );
  }
    
  // init if needed (first time of activation)
  if( $first ) {
    // set defaults
    update_option( 'pl_config', array(
      'versions'=>0, // not updated
      'initialized'=>true, // now it will be initialized
      'permanent'=>false, // remove settings if plugin gets manually deactivated
      'process'=>array( // process all known strings
        'posts'=>true,
        'widgets'=>true
      ),
      'expertmode'=>false // do not activate the expertmode by default
    ) );
    // add example lists    
    if( count($pl_lists)==0 ) {        
      pl_include( 'examples.php' );
      pl_examples_add( $pl_lists );
      update_option( 'pl_lists', $pl_lists ); // update list with examples
    }    
  }
  
  // force update
  pl_include( 'update.php' );
  pl_update( true );
  
  // all done
  return; 
}

// plugin deactivation
function pl_deactivate() {

  // unregister options if allowed
  $pl_config = get_option( 'pl_config' );
  if( !$pl_config['permanent'] ) { // delete all settings if permanent-flag is not set
    delete_option('pl_config');
    delete_option('pl_lists');
  }
  
  // all done  
  return;
}

//-----------------------------------------------------------------------------

// use plugin website as update website if update is needed
function pl_updateurl( $file ) {
  if( $file!=pl_plugin_basename() ) // only for this plugin
    return;
    
  // check if update url needed
  $update_plugins = get_option( 'update_plugins' );
  if( $update_plugins && is_array($update_plugins->response) && array_key_exists($file,$update_plugins->response) ) {

    // get url
    $plugin_url = null;
    $plugin_data = implode( '', file(__FILE__) ); // read this plugin file
    if( $plugin_data )
     	preg_match( '|Plugin URI:(.*)$|mi', $plugin_data, $plugin_url ); // extract plugin website url
    
    // set corrent url
    if( $plugin_url && is_array($plugin_url) && array_key_exists(1,$plugin_url) ) {
      if( strpos(trim($plugin_url[1]),'http://')===0 ) {
        $update_plugins->response[$file]->url = trim($plugin_url[1]);
        update_option( 'update_plugins', $update_plugins ); // save
      }
    }
  }
}

//-----------------------------------------------------------------------------

// activation and deactivation
add_action( 'activate_'.pl_plugin_basename(),   'pl_activate' );
add_action( 'deactivate_'.pl_plugin_basename(), 'pl_deactivate' );

// initialization
add_action( 'init', 'pl_init');
  
// menus
add_action( 'admin_menu', 'pl_adminmenu' );

// update
add_action( 'after_plugin_row', 'pl_updateurl', -1 );

// filter text to replace placeholders
add_filter( 'the_content',     'pl_replacelistplaceholders_posts'   );
add_filter( 'the_content_rss', 'pl_replacelistplaceholders_posts'   );
add_filter( 'the_excerpt',     'pl_replacelistplaceholders_posts'   );
add_filter( 'the_excerpt_rss', 'pl_replacelistplaceholders_posts'   );
add_filter( 'widget_text',     'pl_replacelistplaceholders_widgets' );

//-----------------------------------------------------------------------------

?>