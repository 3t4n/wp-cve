<?php

if(!function_exists('cg_backend_render_go_to_options')){
    function cg_backend_render_go_to_options(){
        echo <<<HEREDOC
<div id="cgGoTopOptions" class='cg_do_not_remove_when_ajax_load cg_do_not_remove_when_main_empty cg_hide' title="Go to top">
</div>
HEREDOC;
    }
}

?>