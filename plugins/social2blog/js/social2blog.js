<script type="text/javascript">
jQuery(document).on( 'click', '.cat-adv-notice-s2w .notice-dismiss', function() {

    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'social2blog_closeadvnotice'
        }
    })

})
</script>
