<h2 class="lws_cl_media_title"><?php esc_html_e('Files cleaning', 'lws-cleaner'); ?>
</h2>
<div class="lws_cl_div_loading_files">
    <img style="vertical-align:sub; margin-right:5px"
        src="<?php echo esc_url(plugins_url('images/loading_black.svg', __DIR__))?>"
        alt="" width="54px" height="54px">
</div>

<div id="" class="lws_cl_dir_accordion_header">
    <div class="lws_cl_thead"> <?php esc_html_e('Directory / File', 'lws-cleaner'); ?>
    </div>
    <div class="lws_cl_thead"> <?php esc_html_e('Size', 'lws-cleaner'); ?>
    </div>
    <div class="lws_cl_thead"> <?php esc_html_e('Native?', 'lws-cleaner'); ?>
    </div>
    <div class="lws_cl_thead"> <?php esc_html_e('Action', 'lws-cleaner'); ?>
    </div>
</div>
<div class="lws_cl_div_files">
    <script>
        // Load files when called. Only once.
        var loading_files = (function(){
            var executed = false;
            return function load_files_cleaner(){
                if (!executed){
                    executed = true;
                    jQuery('.lws_cl_dir_accordion_header').addClass("lws_hidden");
                    var data = {
                        action: "lws_cleaner_recursive_reading",
                        _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('cleaner_recursive_reading')); ?>',        
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery(".lws_cl_div_loading_files").addClass("lws_hidden");
                        jQuery('.lws_cl_dir_accordion_header').removeClass("lws_hidden");
                        jQuery(".lws_cl_div_files").append(response);
                        var acc = document.getElementsByClassName("lws_cl_dir_accordion");
                        var arr = [].slice.call(acc);

                        for (let button of arr) {
                            button.children[0].addEventListener("click", function() {
                                var img = this.children[2];
                                /* Toggle between hiding and showing the active panel */
                                var panel = this.parentElement.nextElementSibling;
                                var div = this.parentElement;
                                if (div.classList.contains('lws_cl_dir_accordion_black')) {
                                    div.classList.toggle('lws_cl_accordion_1st');
                                    div.nextElementSibling.classList.toggle('lws_cl_1st_table')

                                } else if (div.classList.contains('lws_cl_dir_accordion_blue')) {
                                    div.classList.toggle('lws_cl_accordion_2nd');
                                    div.nextElementSibling.classList.toggle('lws_cl_2nd_table')
                                }

                                img.classList.toggle('lws_cl_chevron_flip');
                                if (panel.style.display === "inherit") {
                                    panel.style.display = "none";
                                } else {
                                    panel.style.display = "inherit";
                                }
                            });
                        };
                    });
                }
            };
        })();
        
    </script>
</div>



<script>
    function delete_element(button) {
        event.stopPropagation();
        var path = button.value;
        var b = button;
        var type;
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_cl_validated_button');
        button.setAttribute('disabled', true);
        if (button.classList.contains('lws_is_file')) {
            type = 'file';
        } else if (button.classList.contains('lws_is_dir')) {
            type = 'dir';
        }
        var data = {
            action: "lws_cleaner_delete",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lws_cleaner_deletefiles')); ?>',        
            lws_cl_path: path,
            lws_cl_type: type,
        };
        jQuery.post(ajaxurl, data, function(response) {
            b.children[0].classList.add('hidden');
            b.children[2].classList.remove('hidden');
            b.children[1].classList.add('hidden');
            b.classList.add('lws_cl_validated_button');
        });
    }
</script>