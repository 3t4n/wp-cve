import { showToast } from "./notice.js";


class AjaxV1 {

    constructor( params ) {
        this.params = params;

        this.fireAjax();
    }

    fireAjax() {
        jQuery.ajax({
            method: this.params.method || 'POST',
            url: this.params.ajaxUrl,
            data: this.params.data,
            success: this.params.success,
            error: this.params.error,
            complete: this.params.complete || undefined
        });
    }
}


class AjaxV2 {
    constructor( params ) {
        this.params = params;
    }
}


class AjaxV3 {
    constructor( ajaxUrl, method, data, successCallback, errorCallback, completeCallback, autoFire = false, toastArgs = {} ) {
        this.ajaxUrl          = ajaxUrl;
        this.method           = method;
        this.data             = data;
        this.successCallback  = successCallback;
        this.errorCallback    = errorCallback;
        this.completeCallback = completeCallback;
        this.showToast        = toastArgs?.showToast || false;
        this.createNewToast   = toastArgs?.createNewToast || false;
        this.toastDuration    = toastArgs?.toastDuration || false;
        this.showToastHeader  = toastArgs?.showToastHeader || false


        if ( autoFire ) {
            this.fire();
        }
    }

    fire() {
        jQuery.ajax({
            method: this.method,
            url: this.ajaxUrl,
            data: this.data,
            success: (resp) => {
                if ( resp?.data?.message && this.showToast ) {
                    showToast( resp.data.message, resp.data.status, this.toastDuration, '', this.showToastHeader, this.createNewToast );
                }
                this.successCallback( resp );
            },
            error: (err) => {
                if ( err?.responseJSON?.data?.message && this.showToast ) {
                    showToast( err.responseJSON.data.message, err.responseJSON.data.status, this.toastDuration, '', this.showToastHeader, this.createNewToast );
                }
                this.errorCallback( err );
            },
            complete: () => {
                this.completeCallback();
            }
        })
    }

    async asyncFire() {
        try {
            return await jQuery.ajax({
                method: this.method,
                url: this.ajaxUrl,
                data: this.data,
                success: (resp) => {
                    if ( resp?.data?.message && resp.data.message.length && this.showToast ) {
                        showToast( resp.data.message, resp.data.status, this.toastDuration, '', this.showToastHeader, this.createNewToast );
                    }
                    this.successCallback( resp );
                    return resp;
                },
                error: (err) => {
                    console.log( 'error in ajax', err );
                    if ( err?.responseJSON?.data?.message && err.responseJSON.data.message.length && this.showToast ) {
                        showToast( err.responseJSON.data.message, err.responseJSON.data.status, this.toastDuration, '', this.showToastHeader, this.createNewToast );
                    }
                    if ( ! err?.responseJSON?.data?.message && err?.responseText && this.showToast ) {
                        showToast( err.responseText, 'danger', this.toastDuration, '', this.showToastHeader, this.createNewToast );
                    }
                    this.errorCallback( err );
                    return err?.responseJSON ? err.responseJSON : err.responseText;
                },
                complete: () => {
                    this.completeCallback();
                }
            });
        } catch ( err ) {
            return err?.responseJSON ? err.responseJSON : err.responseText;
        }
    }
}

export {
    AjaxV1,
    AjaxV2,
    AjaxV3
}
