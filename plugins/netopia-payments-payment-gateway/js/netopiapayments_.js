var $ = jQuery;

function notifyHandle(chosen_file,type,elm){
    var fsize = chosen_file.size,
        fname = chosen_file.name,
        fextension = fname.substring(fname.lastIndexOf('.')+1);
    if (fextension != type){
        toastr.error('This type of files are not allowed! just files with "cer" extensions as PUBLIC KEY and "key" extensions as "PRIVATE KEY" are accepted', 'Error!');
        elm.value = "";
        return false;
    } else if(fsize > 3145728 || fsize <= 0) {
        toastr.error('Please choose a file with a valid size', 'Error!');
        elm.value = "";
        return false;
    } else {
        toastr.success('File is verified', 'success!');
        return true;
    }
}
$('#woocommerce_netopiapayments_live_cer').on('change', function () {
    notifyHandle($(this)[0].files[0],'cer',this);
});

$('#woocommerce_netopiapayments_live_key').on('change', function(){
    notifyHandle($(this)[0].files[0],'key',this);
});
$('#woocommerce_netopiapayments_sandbox_cer').on('change', function () {
    notifyHandle($(this)[0].files[0],'cer',this);
});

$('#woocommerce_netopiapayments_sandbox_key').on('change', function(){
    notifyHandle($(this)[0].files[0],'key',this);
})
