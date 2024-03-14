// Updates Buckets Sidebars after Editing or Adding
function update_buckets(){
    if( jQuery('.acf-buckets').length == 0 && jQuery('.acf_buckets').length == 0) { return; }
    setTimeout(function(){
        acf.fields.buckets.fetch();
        tb_remove();
    }, 2000);
}

jQuery(function($){

    $('.acf-buckets span.edit, .acf_buckets span.edit').live('click', function(){
        var edit_url = $(this).attr('data-url');
        tb_show('Edit Bucket', edit_url);
    });

});