import { mpgGetState } from "../js/helper.js";
import {translate} from '../lang/init.js';

class Upload {
    constructor(file) {
        this.file = file;

        jQuery("#progress-wrp .progress-bar").css("width", 0);
        jQuery("#progress-wrp .status").text("0%");
        jQuery('#progress-wrp').show();
    }

    getType() {
        return this.file.type;
    }
    getSize() {
        return this.file.size;
    }
    getName() {
        return this.file.name;
    }
    doUpload() {

        return new Promise((resolve, reject) => {


            var that = this;
            var formData = new FormData();
            // add assoc key values, this will be posts values
            formData.append("file", this.file, this.getName());
            formData.append("upload_file", true);
            formData.append("action", "mpg_upload_file");
            formData.append('projectId', mpgGetState('projectId'))
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                xhr: function () {
                    var myXhr = jQuery.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress', that.progressHandling, false);
                    }
                    return myXhr;
                },
                success: function (data) {
                    jQuery('#progress-wrp').hide();
                    jQuery("#progress-wrp .progress-bar").css("width", 0);
                    resolve(data);
                },


                error: function (xhr, ajaxOptions, thrownError) {

                   if(xhr.status === 400 || xhr.status === 500) {


                        toastr.error(
                            translate['Looks like you attempt to use large source file, that reached memory allocated to PHP or reached max_post_size. Please, increase memory limit according to documentation for your web server. For additional information, check .log files of web server or'] + `<a target="_blank" style="text-decoration: underline" href="https://docs.themeisle.com/article/1443-500-internal-server-error"> ${translate['read our article']}</a>.`,
                            translate['Server settings limitation'], { timeOut: 30000 });
                    
                    }else{
                        reject(thrownError)
                    }
                },
                async: true,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                timeout: 60000
            });
        })
    }
    progressHandling(event) {
        var percent = 0;
        var position = event.loaded || event.position;
        var total = event.total;

        if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
        }
        // update progressbars classes so it fits your code
        jQuery("#progress-wrp .progress-bar").css("width", +percent + "%");
        jQuery("#progress-wrp .status").text(percent + "%");
    }
}


export { Upload };