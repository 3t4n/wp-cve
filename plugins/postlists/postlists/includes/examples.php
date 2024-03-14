<?php
//-----------------------------------------------------------------------------
/*
  this file will get included by postlists.php
    it contains the examples that will be added 
    when the plugin gets activated for the first time
  this file belongs to postlists version 2.0
*/   
//-----------------------------------------------------------------------------
?>
<?php

//-----------------------------------------------------------------------------

// add examples
function pl_examples_add( &$pl_lists, $list=null ) {

  // load examples
  $examples = pl_examples_get( $list );
  
  // add exmaples
  $count = 0;
  foreach( $examples as $example_name=>$example_values ) {  
    if( $list && $example_name!=$list ) // add specific list
      continue;
    if( !array_key_exists($example_name,$pl_lists) ) { // do not overwrite
      $pl_lists[ $example_name ] = $example_values; // add with defaults
      $count ++;
    }    
  }
  
  return $count;
}

// returns the example lists
function pl_examples_get( $list=null ) {

  // include adminfunctions
  pl_include( 'admin.php' );

  // load defaults
  $defaults = pl_admin_getlistdefaults();
  
  // get a category of the blog as example
  $categories = get_all_category_ids();
  $category = $categories[0];
  
  // the examples
  $examples = array(
    '%MYPOSTS%' => array( // just display the last 5 posts
      'before'=>'These are my latest %count% posts:<ul>', 
      'after'=>'</ul>',
      'entry'=>'<li><a href="%posturl%">%posttitle%</a></li>',
      'noposts'=>'I have no post in this blog :-(',
      'numberposts'=>5, 
      'orderby'=>'post_date', 
      'order'=>'DESC', 
      'post_type'=>'post', 
      'post_status'=>'publish'
    ),
    '<!--MYPOSTSCOMMENT-->' => array( // just show that a html comment as placeholder is also possible
      'before'=>'This list will be hidden (html comment) if the plugin is deactivated, but you can not add it with the visual editor (only with the code-editor)...<br>', 
      'after'=>'',
      'entry'=>'<li><a href="%posturl%">%posttitle%</a></li>',
      'noposts'=>'If you leave this empty, really nothing will be displayed, if there are no posts!',
      'titlelength'=>25,
      'titleincomplete'=>'... (title cut to 25 characters)',
      'numberposts'=>10, 
      'orderby'=>'post_date', 
      'order'=>'DESC', 
      'post_type'=>'post', 
      'post_status'=>'publish'
    ),
    'MYCATEGORYPOSTS' => array( // show posts of a sprecific category (and subcategories) only
      'before'=>'These are my latest %count% posts in the category "<a href="%categoryurl%">%category%</a>" (and subcategories):<br>', 
      'after'=>'',
      'entry'=>'- <a href="%posturl%">%posttitle%</a><br>',
      'noposts'=>'I have no post in category "%category%" :-(',
      'numberposts'=>5, 
      'category'=>$category,
      'subcategories'=>true,
      'orderby'=>'post_date', 
      'order'=>'DESC', 
      'post_type'=>'post', 
      'post_status'=>'publish'
    ),
    'MYMOSTCOMMENTEDPOSTS' => array( // the most commented posts
      'before'=>'These are my %count% most commented posts:<ul>', 
      'after'=>'</ul>',
      'entry'=>'<li><a href="%posturl%">%posttitle% (%comments% comments)</a></li>',
      'noposts'=>'These are my %count% most commented posts:<br>No Comments...',
      'numberposts'=>10, 
      'orderby'=>'comment_count', 
      'order'=>'DESC', 
      'post_type'=>'post', 
      'post_status'=>'publish',
      'comments'=>1
    ),
    'MYLATESTPOSTS24' => array( // the latest posts within last 24 hours
      'before'=>'These are all posts of the last 24 hours:<ul>',
      'after'=>'</ul>',
      'entry'=>'<li>%year%-%monthnum%-%day%, %hour%:%minute%:%second% &raquo; <a href="%posturl%">%posttitle%</a></li>',
      'noposts'=>'No posts today :-(',
      'titlelength'=>25,
      'numberposts'=>0,
      'orderby'=>'post_date', 
      'order'=>'DESC', 
      'post_type'=>'post', 
      'post_status'=>'publish', 
      'post_age_max'=>(60*60*24)
    ),
    'MYLATESTMODIFIEDPOSTS' => array( // the 5 latest modified posts
      'before'=>'These are my latest modified posts:', 
      'after'=>'',
      'entry'=>'<li>%modifieddate% %modifiedtime% modified: <a href="%posturl%">%posttitle%</a></li>',
      'noposts'=>'',
      'numberposts'=>5, 
      'orderby'=>'post_modified', 
      'order'=>'DESC',
      'post_type'=>'post', 
      'post_status'=>'publish'
    ),
    'MYFUTUREPOSTS' => array( // list 3 posts that are published for the future
      'before'=>'This posts will get published soon:',
      'after'=>'',
      'entry'=>'<li>%date%: <a href="%posturl%">%posttitle%</a></li>',
      'noposts'=>'',
      'numberposts'=>3, 
      'post_status'=>'future',
      'orderby'=>'post_date',
      'order'=>'ASC',
      'post_type'=>'post'
    ),
    'MYLATESTPOST' => array( // show only the latest post inline (not as list)
      'before'=>'My latest post is ',
      'after'=>'',
      'entry'=>'"<a href="%posturl%">%posttitle%</a>" (%daydiff% days, %hourdiff% hours, and %minutediff% minutes ago)',
      'noposts'=>'',
      'numberposts'=>1, 
      'orderby'=>'post_date', 
      'order'=>'DESC', 
      'post_type'=>'post', 
      'post_status'=>'publish'
    ),
    'MYATTACHMENTSOFCURRENTPOST' => array( // shows all attachments of the current displaying post
      'before'=>'All attachments of the current post "%parent%": <ul>',
      'after'=>'</ul>',
      'entry'=>'<li><a href="%guid%">%title%</a></li>',
      'noposts'=>'"%parent%" has no attachments',
      'numberposts'=>'', 
      'orderby'=>'post_date', 
      'order'=>'DESC', 
      'post_type'=>'attachment',
      'post_status'=>'',
      'post_parent'=>'THIS'
    ) );

  // user examples  
  $examples = apply_filters( 'ple_examples', $examples );
    
  // defaults
  foreach( $examples as $example_name=>$example_values ) {
    if( $list && $example_name!=$list ) // add specific list
      continue;
    $examples[ $example_name ] = array_merge( $defaults, $example_values ); // add with defaults
  }
    
  // specific list
  if( $list )
    return array( $list => $examples[ $list ] );  
    
  // return examples
  return $examples;
}

//-----------------------------------------------------------------------------

?>
