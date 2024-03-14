var IRP_preview=true;
var IRP_labelColor='';
var IRP_defaults = settings_data;

function IRP_changeRelatedBox() {
    if(!IRP_preview) {
        return;
    }

    var t=IRP_val('template-template');
    if (t === undefined) {
        return;
    }
    t=IRP_defaults[t].borderColorLabel;
    jQuery('#template-borderColorLabel').text(t);

    data=IRP_aval('template-');
    data=jQuery.extend({
        'action': 'do_action'
        , 'irp_action': 'ui_box_preview'
        , 'rewritePostsDays': IRP_val('irpRewritePostsDays')
    }, data);
    //console.log(data);

    var request=jQuery.ajax({
        url: ajaxurl
        , method: "POST"
        , data: data
        , dataType: "html"
    });

    request.done(function(html) {
        jQuery("#relatedBoxExample").html(html);
    });
}

(function() {
    function IRP_changeTheme() {
        IRP_preview=false;
        t=IRP_val('template-template');
        t=IRP_defaults[t];
        isProTheme=false;
        console.log(t);
        jQuery.each(t, function(k,v) {
            if(IRP_stripos(k, 'color')!==false) {
                var $k=jQuery('[name=template-'+k+']');
                if (v.length == 0) {
                    v = '#464646';
                }
                $k.val(v).trigger("change");
            } else if(k=='proTheme') {
                isProTheme=(v+''.toLowerCase()=='true');
            }
        });

        jQuery("[name*='Color']").each(function(i,v) {
            var $cbo=jQuery(this);
            $cbo.prop("disabled", isProTheme);
        });
        jQuery("[id*='ColorLabel']").each(function(i,v) {
            var $lbl=jQuery(this);
            if(IRP_labelColor!='') {
                IRP_labelColor=$lbl.css('color');
            }
            if(isProTheme) {
                $lbl.css('color', '#ccc');
            } else {
                $lbl.css('color', IRP_labelColor);
            }
        });
        jQuery('.irp-submit').prop("disabled", isProTheme);
        IRP_preview=true;
    }

    jQuery(function() {
        IRP_preview=false;
        var array=['irpRewritePostsDays', 'irpRewritePostsInBoxCount', 'irpRewriteBoxesCount'];
        for(i=0; i<array.length; i++) {
            if(jQuery('[name='+array[i]+']').length>0) {
                jQuery('[name='+array[i]+']').change(function() {
                    IRP_changeRelatedBox();
                });
            }
        }
        jQuery("[name^='template-']").change(function() {
            if(!IRP_preview) {
                return;
            }

            var $self=jQuery(this);
            var name=$self.attr('name');
            if(name=='template-template') {
                IRP_changeTheme();
            }
            IRP_changeRelatedBox();
        });
        IRP_preview=true;
        IRP_changeRelatedBox();
    });
})();