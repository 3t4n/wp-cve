<?php
 if ( -1 == $number ) {
    /* default options go here */
    $title = 'Categories';
    $showPostCount = 'yes';
    $catSort = 'catName';
    $catSortOrder = 'ASC';
    $postSort = 'postTitle';
    $postSortOrder = 'ASC';
    $defaultExpand='';
    $number = '%i%';
    $expand='1';
    $customExpand='';
    $customCollapse='';
    $postTitleLength='0';
    $inExclude='include';
    $inExcludeCats='';
    $showPosts='yes';
    $linkToCat='yes';
    $animate='1';
    $debug='0';
    $showEmptyCat=false;
    $catfeed='none';
    $taxonomy='category';
    $post_type='post';
    $olderThan=0;
    $excludeAll='0';
    $addMisc=false;
    $showTopLevel=true;
    $postsBeforeCats=false;
    $addMiscTitle='Miscellaneous';
  } else {
    $title = esc_attr( $options[$number][ 'title' ] );
    $showPostCount = $options[$number]['showPostCount'];
    $expand = $options[$number]['expand'];
    $customExpand = $options[$number]['customExpand'];
    $customCollapse = $options[$number]['customCollapse'];
    $postTitleLength = $options[$number]['postTitleLength'];
    $inExcludeCats = $options[$number]['inExcludeCats'];
    $inExclude = $options[$number]['inExclude'];
    $catSort = $options[$number]['catSort'];
    $catSortOrder = $options[$number]['catSortOrder'];
    $postSort = $options[$number]['postSort'];
    $postSortOrder = $options[$number]['postSortOrder'];
    $defaultExpand = $options[$number]['defaultExpand'];
    $showPosts = $options[$number]['showPosts'];
    $linkToCat = $options[$number]['linkToCat'];
    $animate = $options[$number]['animate'];
    $debug = $options[$number]['debug'];
    $showEmptyCat = $options[$number]['showEmptyCat'];
    $showTopLevel = $options[$number]['showTopLevel'];
    $postsBeforeCats = $options[$number]['postsBeforeCats'];
    $catfeed = $options[$number]['catfeed'];
    $taxonomy = $options[$number]['taxonomy'];
    $post_type = $options[$number]['post_type'];
    $olderThan = $options[$number]['olderThan'];
    $excludeAll = $options[$number]['excludeAll'];
    $addMisc = $options[$number]['addMisc'];
    $addMiscTitle = $options[$number]['addMiscTitle'];
  }
?>
