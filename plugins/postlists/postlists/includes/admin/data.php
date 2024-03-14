<?php
//-----------------------------------------------------------------------------
/*
  this file will get included by postlists/includes/admin.php
    it contains functions to get field-definitions and placeholder-descriptions
  this file belongs to postlists version 2.0
*/   
//-----------------------------------------------------------------------------
?>
<?php

//-----------------------------------------------------------------------------

// get field types
function pl_admin_data_getfields_gettypes() {

  $types = array();
  
  // simple types 
  $types['number'] = 0;
  $types['string'] = '';
  $types['text']   = 'X';
  
  // the select field options
  $types['order'] = array( ''=>'',
    'Ascending'=>'ASC','Descending'=>'DESC'
  );
  $types['timediff'] = array( ''=>'',
    '1 hour' => (60*60*1), '3 hours' => (60*60*3), '6 hours' => (60*60*6),
    '12 hours' => (60*60*12), '24 hours' => (60*60*24),
    '1 day' => (60*60*24*1), '2 days' => (60*60*24*2), '3 days' => (60*60*24*3),
    '5 days' => (60*60*24*5), '7 days' => (60*60*24*7), '10 days' => (60*60*24*10),
    '14 days' => (60*60*24*14), '30 days' => (60*60*24*30), '31 days' => (60*60*24*31), 
    '90 days' => (60*60*24*90)
  );
  $types['categories'] = 0;
  if( function_exists('get_categories') ) { // min wp 2.1
    $types['categories'] = array(''=>''); {
      $categories = get_categories(); // list all categories
      foreach( $categories as $category )
        $types['categories'][ $category->name ] = $category->term_id; 
    }
  }
  $types['boolean'] = array( ''=>'',
    'Yes'=>1,
    'No'=>0
  );
  $types['ordercols'] = array( ''=>'',
    'Date'=>'post_date',
    'Modified'=>'post_modified',
    'Title'=>'post_title',
    'Name'=>'post_name',
    'Order'=>'menu_order',
    'Comments'=>'comment_count'
  );
  $types['authors'] = array(''=>''); {
    global $wpdb;
    $author_ids = $wpdb->get_col( "SELECT post_author FROM $wpdb->posts GROUP BY post_author" ); // list all authors that have written a post
    foreach( $author_ids as $author_id )
      $types['authors'][ get_author_name($author_id) ] = $author_id; 
  }
  $types['tags'] = 0;
  if( function_exists('get_tags') ) // min wp 2.2
  {
    $types['tags'] = array(''=>''); {
      $tags = get_tags(); // list all tags
      foreach( $tags as $tag ) 
        $types['tags'][ $tag->name ] = $tag->term_id;
    }
  }
  $types['posttype'] = array( ''=>'',
    'Posts'=>'post', 
    'Pages'=>'page',
    'Attachments'=>'attachment'
  );
  $types['poststatus'] = array( ''=>'',
  	'Published'=>'publish',
  	'Future'=>'future', 
    'Pending'=>'pending',
  	'Draft'=>'draft', 
    'Private'=>'private'
  );
  $types['commentsstatus'] = array( ''=>'',
    'Allowed'=>'open',
    'Disallowed'=>'closed'
  );
  $types['passwordstatus'] = array( ''=>'',
    'Not needed'=>0,
    'Needed'=>1
  );
  $types['thispost'] = array( ''=>'',
    'The current post'=>'THIS'
  );
  $types['parent'] = array( ''=>'',
    'The current post'=>'THIS',
    'No parent post'=>'0'
  );
  $types['metakey'] = $types['string'];
  if( function_exists('get_meta_keys') ) {
    $metakeys = array();
    $allmetakeys = get_meta_keys();
    foreach( $allmetakeys as $metakey ) {
      if( substr($metakey,0,1)!='_' )
        $metakeys[] = $metakey;
    }
    if( count($metakeys)>0 )
      $types['metakey'] = array( ''=>'' );
    foreach( $metakeys as $metakey ) {
      $types['metakey'][ $metakey ] = $metakey;
    }
  }
  
  // return types
  return $types;
}

// get all supported fields with definition and description
function pl_admin_data_getfields( $extend=true ) {
  
  // get field types
  $types = pl_admin_data_getfields_gettypes();
  
  // return the list of fields
  //   array( // list of fields
  //     'the_field_name_here' => array( 'description'=>'the_description_here', // the description
  //        'type' => the_type_here, // a number means numeric, a empty string means string, a string not empty shows a bigger field, a array shows a selectbox
  //        'expert' => a_boolean_here, // if true this field is only visible in expert mode
  //        'placeholders' => 'list_or_post_here', // optional value. if set 'post' means post placeholders are supported, 'list' means that only list palceholders are supported. must be implemented seperately in postlists.php!
  //        'hint' => 'hints_here' // optional a hint
  //     ),
  //     ...
  //   );
  $fields = array(
    'before'=> array('description'=>'This html code will be displayed before the first entry',
      'type'=>$types['text'],
      'expert'=>false,
      'placeholders'=>'list'),
    'after' => array('description'=>'This html code will be displayed after the last entry',
      'type'=>$types['text'],
      'expert'=>false,
      'placeholders'=>'list'),
    'entry' => array('description'=>'This is the html code template for each entry',
      'type'=>$types['text'],
      'expert'=>false,
      'placeholders'=>'post'),
    'noposts' => array('description'=>'This html code will be displayed instead of the list <br>if the minimum number of posts is not reached',
      'type'=>$types['text'],
      'expert'=>false,
      'placeholders'=>'list'),
    'metaplaceholders' => array('description'=>'Search and replace meta placeholders',
      'type'=>$types['boolean'],
      'expert'=>!is_array($types['metakey'])),
    'colplaceholders' => array('description'=>'Search and replace database column placeholders',
      'type'=>$types['boolean'],
      'expert'=>true),
    'titlelength' => array('description'=>'Cut the title after this number of characters',
      'type'=>$types['number'],
      'expert'=>false),
    'titleincomplete' => array('description'=>'This string will get added after a cut title',
      'type'=>$types['string'],
      'expert'=>false),
    'apply_filters' => array('description'=>'Apply content filters of other plugins to post contents',
      'type'=>$types['boolean'],
      'expert'=>true),
    'numberposts' => array('description'=>'The maximum number of posts to display in this list',
      'type'=>$types['number'],
      'expert'=>false),
    'minnumberposts' => array('description'=>'The minimum number of post the list needs to be displayed',
      'type'=>$types['number'],
      'expert'=>false),
    'offset' => array('description'=>'The offset to the first post to display in the list',
      'type'=>$types['number'],
      'expert'=>true),
    'orderby' => array('description'=>'Order the posts in the list by this field',
      'type'=>$types['ordercols'],
      'expert'=>false),
    'order' => array('description'=>'Order the list of posts',
      'type'=>$types['order'],
      'expert'=>false),
    'post_type' => array('description'=>'Show only posts of this type',
      'type'=>$types['posttype'],
      'expert'=>false),
    'post_status' => array('description'=>'Show only posts with this status',
      'type'=>$types['poststatus'],
      'expert'=>false),
    'category' => array('description'=>'Show only posts of this category',
      'type'=>$types['categories'],
      'expert'=>false),
    'subcategories' => array('description'=>'Show posts of subcategories',
      'type'=>$types['boolean'],
      'expert'=>false),
    'comment_status' => array('description'=>'Show only posts where comments are',
      'type'=>$types['commentsstatus'],
      'expert'=>false),
    'post_password' => array('description'=>'Show only posts where a password is',
      'type'=>$types['passwordstatus'],
      'expert'=>false),
    'post_age_max' => array('description'=>'Show only posts with a maximum age of',
      'type'=>$types['timediff'],
      'expert'=>false),
    'post_age_min' => array('description'=>'Show only posts with a minimum age of',
      'type'=>$types['timediff'],
      'expert'=>false),
    'post_future_max' => array('description'=>'Show only posts within maximum this time in the future',
      'type'=>$types['timediff'],
      'expert'=>false),
    'post_future_min' => array('description'=>'Show only posts within minimum this time in the future',
      'type'=>$types['timediff'],
      'expert'=>true),
    'post_modified_max' => array('description'=>'Show only posts modified within maximum',
      'type'=>$types['timediff'],
      'expert'=>false),
    'post_modified_min' => array('description'=>'Show only posts modified within a minimum',
      'type'=>$types['timediff'],
      'expert'=>true),
    'author' => array('description'=>'Show only posts of this author',
      'type'=>$types['authors'],
      'expert'=>false),
    'tag' => array('description'=>'Show only posts with this tag',
      'type'=>$types['tags'],
      'expert'=>false),
    'comments' => array('description'=>'Show only posts with this minimum count of comments',
      'type'=>$types['number'],
      'expert'=>false),
    'include' => array('description'=>'Include this posts to the list',
      'type'=>$types['string'],
      'expert'=>true),
    'exclude' => array('description'=>'Exclude this posts from the list',
      'type'=>$types['thispost'],
      'expert'=>false),
    'load' => array('description'=>'Additionally load this tables',
      'type'=>$types['string'],
      'expert'=>true),
    'select' => array('description'=>'Select the following fields',
      'type'=>$types['string'],
      'expert'=>true),
    'post_parent' => array('description'=>'Show only posts with this parent',
      'type'=>$types['parent'],
      'expert'=>false),
    'meta_key' => array('description'=>'Show only posts with this meta key',
      'type'=>$types['metakey'],
      'expert'=>!is_array($types['metakey'])),
    'meta_value' => array('description'=>'Show only posts with this meta value',
      'type'=>$types['string'],
      'expert'=>!is_array($types['metakey'])),
    'taxonomy' => array('description'=>'Show only posts with this taxonomy id',
      'type'=>$types['string'],
      'expert'=>true),
    'where' => array('description'=>'Show only posts where this statement evaluates to true',
      'type'=>$types['string'],
      'expert'=>true)
  );

  // extensions
  if( $extend )
    $fields = apply_filters( 'ple_fields', $fields );

  return $fields;
}

//-----------------------------------------------------------------------------

// get description for a placeholder
function pl_admin_data_getplaceholderdescription( $placeholdername, $inpost ) {

  // get internal placeholderdescription
  $description = pl_admin_data_getplaceholderdescription_internal( $placeholdername, $inpost );
  
  // extensions
  $description = apply_filters( 'ple_placeholderdescription', $description, $placeholdername, $inpost );
  
  // return description
  return $description;
}
function pl_admin_data_getplaceholderdescription_internal( $placeholdername, $inpost ) {

  // post vars
  if( $inpost ) {
    switch( $placeholdername ) {
      case 'counter':
        return 'numeration of the post startig with 1';
      case 'devcounter':
        return 'numeration of the post startig with 0';
      case 'relativecounter':
      return 'numeration of the post starting after the offset';
      case 'id':
      case 'postid':
        return 'the id of the post';
      case 'post':
      case 'title':
      case 'posttitle':      
        return 'the title of the post';
      case 'url':
      case 'posturl':
        return 'the permalink url of the post';
      case 'content':
        return 'the content of the post until the more-tag';
      case 'excerpt':
        return 'the excerpt of the post';
      case 'fullcontent':
        return 'the complete content of the post';        
      case 'guid':
        return 'the guid of the post';
      case 'author':
        return 'the name of the post author';
      case 'authorid':
        return 'the userid of the post author';
      case 'authorurl':
        return 'the archive url of the post author';
      case 'date':
      case 'postdate':      
        return 'the post date string';
      case 'time':
      case 'posttime':      
        return 'the post time string';
      case 'year':
        return 'the year of the post date';
      case 'monthnum':
        return 'the month of the post date';
      case 'day':
        return 'the day of the post date';
      case 'hour':
        return 'the hour of the post time';
      case 'minute':
        return 'the minutes of the post time';
      case 'second':
        return 'the seconds of the post time';
      case 'seconddiff':
        return 'the seconds of the time difference between now and the post time';
      case 'minutediff':
        return 'the minutes of the time difference between now and the post time';
      case 'hourdiff':
        return 'the hours of the time difference between now and the post time';
      case 'daydiff':
        return 'the days of the time difference between now and the post time';
      case 'modifieddate':
        return 'the last modified date string of the post';
      case 'modifiedtime':
        return 'the last modified time string of the post';
      case 'modifiedyear':
        return 'the year of the last modified date of the post';
      case 'modifiedmonthnum':
        return 'the month of the last modified date of the post';
      case 'modifiedday':
        return 'the day of the last modified date of the post';
      case 'modifiedhour':
        return 'the hour of the last modified time of the post';
      case 'modifiedminute':
        return 'the minutes of the last modified time of the post';
      case 'modifiedsecond':
        return 'the seconds of the last modified time of the post';
      case 'comments':
        return 'the count of comments for this post';
    }  
  }
  
  // list vars
  switch( $placeholdername ) {
    case 'count':
      return 'the count of posts in the list';
    case 'categoryid':    
      return 'if category is configured for the list, the id of the category';
    case 'category':
      return 'if category is configured for the list, the name of the category';
    case 'categoryurl':
      return 'if category is configured for the list, the link to the category archive';
    case 'offset':
      return 'if configured for the list, the offset for this list';
    case 'relativecounterbase':
      return 'the first number, the relative counter starts with';
    case 'parent':
    case 'parenttitle':
      return 'if parent is configured for the list, the name of the parent post';
    case 'parentid':
      return 'if parent configured for the list, the id of the parent post';
    case 'parenturl':
      return 'if parent configured for the list, the url of the parent post';
    case 'parentdate':
      return 'if parent configured for the list, the date of the parent post';
    case 'parenttime':
      return 'if parent configured for the list, the time of the parent post';
    case 'tag':
      return 'if tag is configured for the list, the tag name';
    case 'tagid':
      return 'if tag is configured for the list, the tag id';
    case 'tagurl':
      return 'if tag is configured for the list, the tag archive url';
    case 'tagslug':
      return 'if tag is configured for the list, the tag slug';
    case 'author':
      return 'if author is configured for the list, the author name';
    case 'authorid':
      return 'if author is configured for the list, the author userid';
    case 'authorurl':
      return 'if author is configured for the list, the author archive url';
    case 'date':
      return 'the current date string';
    case 'time':
      return 'the current time string';
    case 'year':
      return 'the current year';
    case 'monthnum':
      return 'the current month';
    case 'day':
      return 'the current day of the month';
    case 'hour':
      return 'the current hour';
    case 'minute':
      return 'the current minutes of the time';
    case 'second':
      return 'the current seconds of the time';
    case 'postfutureminminutes':
      return 'if minimum future time is configured for the list, the minutes of the minimum time the posts have to be in the future';
    case 'postfuturemaxminutes':
      return 'if maximum future time is configured for the list, the minutes of the maximum time the posts have to be in the future';
    case 'postfutureminhours':
      return 'if minimum future time is configured for the list, the hours of the minimum time the posts have to be in the future';
    case 'postfuturemaxhours':
      return 'if maximum future time is configured for the list, the hours of the maximum time the posts have to be to be in the future';
    case 'postfuturemindays':
      return 'if minimum future time is configured for the list, the minimum number of days the posts have to be in the future';
    case 'postfuturemaxdays':
      return 'if maximum future time is configured for the list, the maximum number of days the posts have to be in the future';
    case 'postageminminutes':
      return 'if minimum post age is configured for the list, the minutes of the minimum age the posts have to have';
    case 'postagemaxminutes':
      return 'if maximum post age is configured for the list, the minutes of the maximum age the posts have to have';
    case 'postageminhours':
      return 'if minimum post age is configured for the list, the hours of the minimum age the posts have to have';
    case 'postagemaxhours':
      return 'if maximum post age is configured for the list, the hours of the maximum age the posts have to have';
    case 'postagemindays':
      return 'if minimum post age is configured for the list, the days of the minimum age the posts have to have';
    case 'postagemaxdays':
      return 'if maximum post age is configured for the list, the days of the maximum age the posts have to have';
    case 'postmodifiedminminutes':
      return 'if minimum post modified time is configured for the list, the minutes of the minimum time the posts have to be modified within';
    case 'postmodifiedmaxminutes':
      return 'if maximum post modified time is configured for the list, the minutes of the maximum time the posts have to be modified within';
    case 'postmodifiedminhours':
      return 'if minimum post modified time is configured for the list, the hours of the minimum time the posts have to be modified within';
    case 'postmodifiedmaxhours':
      return 'if maximum post modified time is configured for the list, the hours of the maximum time the posts have to be modified within';
    case 'postmodifiedmindays':
      return 'if minimum post modified time is configured for the list, the days of the minimum time the posts have to be modified within';
    case 'postmodifiedmaxdays':
      return 'if maximum post modified time is configured for the list, the days of the maximum time the posts have to be modified within';
  }  
  
  // meta placeholders
  if( $inpost ) {
    if( substr($placeholdername,0,strlen('meta_'))=='meta_' ) {
      return 'the value of the meta key "'.substr($placeholdername,strlen('meta_')).'" if meta key placeholders are activated for the list';
    }  
  }

  // col placeholders
  if( $inpost ) {
    if( substr($placeholdername,0,strlen('col_'))=='col_' ) {
      return 'the value of the database column "'.substr($placeholdername,strlen('col_')).'" if col placeholders are activated for this list';
    }  
  }
    
  // unknown
  return null;
}

//-----------------------------------------------------------------------------

// get defaults for new lists
function pl_admin_data_getlistdefaults() {

  // current version
  pl_include( 'update.php' );

  // return array // overwrite values
  return array_merge( pl_getlistdefaults(), array(
    'id' => md5( time() ),
    'version' => pl_update_getcurrentversion(),
    'before' => '', 
    'after' => '',
    'entry' => '<li><a href="%posturl%">%posttitle%</a></li>',
    'noposts',    
    'numberposts' => 5,
    'subcategories' => true,
    'post_type' => 'post', 
    'post_status' => 'publish',
    'orderby' => 'post_date', 
    'order' => 'DESC',
    'titlelength',
    'titleincomplete' => '...',
    'apply_filters' => true
  ) );
}

//-----------------------------------------------------------------------------

?>
