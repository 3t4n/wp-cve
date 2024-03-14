<?php
function mpsp_slider_posts_shortcode($post){
    // $post is already set, and contains an object: the WordPress post
    global $post;
 //////////////////////////////////////////////////////////////////////////
                                                                        //  
                               //START                                 //
                                                                      //  
                                                                     //
    ///////  MAIN SETTINGS var assign BOX Starts HERE!!! /////////////

    $postid = $post->ID;

    ?>
    <style type="text/css">
      #mpsp_slider_posts_shortcode{
      border-top: 5px solid #A7D476;
    }
    </style>

    <p style='font-weight:bold; font-size:20px;'>[mpsp_posts_slider id='<?php echo $postid; ?>']</p>
    

    <?php


 }


 ?>