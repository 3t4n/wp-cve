(function(e) {

    "use strict";
    e(function() {

        if (0 < e("#commentform").length) {
            e("#commentform").attr("enctype", "multipart/form-data")
        }

        e("#comment_image_reloaded").change(function() {
            if ("" !== e.trim(e(this).val())) {
                var t, n;
                t = e(this).val().split(".");
                n = t[t.length - 1].toString().toLowerCase();
                if ("png" === n || "gif" === n || "jpg" === n || "jpeg" === n) {
                    e("#comment-image-reloaded-error").hide()
                } else {
                    e("#comment-image-reloaded-error").html(cm_imgs.fileTypeError).show();
                    e(this).val("");
                    return
                }

                if (window.FileReader && window.File && window.FileList && window.Blob) {
                    // console.log( this.files[0].size );
                    // console.log( cm_imgs.limitFileSize );
                    // console.log( cm_imgs.limitFileSize / 1048576 + ' MB' );

                    if (cm_imgs.limitFileSize > this.files[0].size) {
                        e("#comment-image-reloaded-error").hide()
                    } else {
                        e("#comment-image-reloaded-error")
                            .html( cm_imgs.fileSizeError + ' ' + parseInt(cm_imgs.limitFileSize / 1048576) + " MB")
                            .css( { 'color':'#bb0404', 'font-style':'italic'} )
                            .show();
                        e(this).val("");
                        return
                    }

                    //if(cm_imgs.limitFileCount < )
                }

            }
        })

    })

})(jQuery)