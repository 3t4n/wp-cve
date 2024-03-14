<!-- <div class="lws_cl_div_title_plugins">
    <h3 class="lws_cl_title_plugins"> <?php esc_html_e('Medias', 'lws-cleaner'); ?>
    </h3>
    <p class="lws_cl_text_base" style="padding-bottom:0px">
        <?php echo wp_kses(__('Find here a list of seemingly <strong>unused medias</strong>. Those are considered unused when <strong>not</strong> in: page content, posts or other types ; featured images, Image widget, Galery, Icon ; WooCommerce, DIVI, Elementor, Beaver Builde, Visual Composer...', 'lws-cleaner'), array('strong' => array())); ?>
    </p>
    <p class="lws_cl_text_base">
        <?php echo wp_kses(__('You can <strong>exclude medias</strong> from the list (those that you find are useful for your website) by clicking on the "Ignore" button which will add those in the media list below. You will not be able to delete by mistake.', 'lws-cleaner'), array('strong' => array())); ?>
    </p>
</div>

<h2 class="lws_cl_media_title"><?php esc_html_e('Unused medias cleaning', 'lws-cleaner'); ?>
</h2>
<form method="post">
    <?php $table->prepare_items();
    $table_ignored->prepare_items();
    $table->display();?>
</form>

<h2 class="lws_cl_media_title"><?php esc_html_e('Medias to keep / ignored', 'lws-cleaner'); ?>
</h2>
<form method="post">
    <?php $table_ignored->display();?>
</form>
<form id="lws_cl_form_delete" method="POST">
    <input type="hidden" name="lws_cl_delete_attachment">
</form>

<form id="lws_cl_form_ignore" method="POST">
    <input type="hidden" name="lws_cl_ignore_attachment">
</form>

<form id="lws_cl_form_unignore" method="POST">
    <input type="hidden" name="lws_cl_unignore_attachment">
</form>

<script>
    function lws_cl_ignore_element(button) {
        let value = button.value;
        button.children[0].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.setAttribute('disabled', true);
        var data = {
            action: "lws_cleaner_ignore_element",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lws_cleaner_ignoreelmt')); ?>',        
            data: value,
        };
        jQuery.post(ajaxurl, data, function(response) {
            document.getElementById('lws_cl_form_ignore').submit();
        });
    }

    function lws_cl_delete_element(button) {
        let value = button.value;
        button.children[0].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.setAttribute('disabled', true);
        var data = {
            action: "lws_cleaner_delete_element",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lws_cleaner_dlteelmt')); ?>',        
            data: value,
        };
        jQuery.post(ajaxurl, data, function(response) {
            document.getElementById('lws_cl_form_delete').submit();
        });
    }

    function lws_cl_unignore_element(button) {
        let value = button.value;
        button.children[0].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.setAttribute('disabled', true);
        var data = {
            action: "lws_cleaner_unignore_element",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('lws_cleaner_unignrelmt')); ?>',        
            data: value,
        };
        jQuery.post(ajaxurl, data, function(response) {
            document.getElementById('lws_cl_form_unignore').submit();
        });
    }
</script>

<script>
    jQuery(document).ready(function() {
        const tfoots = document.getElementsByTagName('tfoot');
        Array.from(tfoots).forEach((e) => {
            e.remove()
        });
        document.querySelectorAll('#bulk-action-selector-bottom').forEach(function(e) {
            e.parentNode.parentNode.remove();
        });

        document.querySelectorAll('.action.column-action > div.row-actions').forEach((e) => {
            e.remove()
        })
    });
</script>

<script>
    jQuery('.toggle-row').on('click', function() {
        this.parentElement.parentElement.querySelector('td.action.column-action').classList.toggle(
            'lws_cl_list_open');
    });

    document.querySelectorAll('.check-column > input')
        .forEach((check) => {
            check.addEventListener('change', function() {
                if (this.checked) {
                    this.style.border = '2px solid #006EDC';
                } else {
                    this.style.border = '2px solid black';
                }
            });
        });

    document.querySelectorAll('.manage-column.column-cb.check-column > input')
        .forEach((check) => {
            check.addEventListener('change', function() {
                if (this.checked) {
                    this.style.border = '2px solid #006EDC';
                } else {
                    this.style.border = '2px solid black';
                }
            });
        });
</script> -->