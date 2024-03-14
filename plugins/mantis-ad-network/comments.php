<?php

$afterLocation = get_option('mantis_after');
$location = get_option('mantis_recommend');

if($afterLocation == 'before_comments'){
    mantis_after_render();
}

if($location == 'before_comments'){
    mantis_recommend_render();
}

comments_template();

if($afterLocation == 'after_comments'){
    mantis_after_render();
}

if($location == 'after_comments'){
    mantis_recommend_render();
}
?>