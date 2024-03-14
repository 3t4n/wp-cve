function click_boton() {

//var post_id = id;
jQuery.ajax({
    url : my_ajax_url.ajax_url,
    type : 'POST',
    data : {
        action : 'get_awb_cool_options',
        //id : post_id
    },
        success : function( response ) {
           // alert('hola');
        jQuery('#display').html( response );
    }
 });

}

jQuery(document).on('click', '#submitme', function(event){ // use jQuery no conflict methods replace $ with "jQuery"

      event.preventDefault(); // stop post action
      jQuery.ajax({
          type: "POST",
          url: ajaxurl, // or '<?php echo admin_url('admin-ajax.php'); ?>'
          data: {
              action : 'wpcf7_awb_savetool',
              tool_key : jQuery("#wpcf7-Aweber-tool_key").val(),
              idformxx : jQuery("#idformxx").val()
              //jQuery('wpcf7-Aweber[license_key]').val()// or combine serialized form with action ....
                },
            //dataType: "json",
          // beforeSend: function() {
          //     alert('before')
          // },
          success: function( response, data  ){ // response,data, textStatus, jqXHR,

            //jQuery('#display_prueba').html( data.display_prueba );
            jQuery('#panelconfig').html( response );
            var mserror = jQuery("#txcomodin").val();

            jQuery('#display_errortool').html( mserror );
            //alert(data.display_errorlicence);
          },
          error: function(data, textStatus, jqXHR){
              alert(textStatus);
          },
      });
  });

jQuery(document).on('click', '#selgetcampos', function(event){ // use jQuery no conflict methods replace $ with "jQuery"
      event.preventDefault(); // stop post action
      jQuery.ajax({
          type: "POST",
          url: ajaxurl, // or '<?php echo admin_url('admin-ajax.php'); ?>'
          data: {
              action : 'wpcf7_awb_listacampos',
              tool_key : jQuery("#wpcf7-Aweber-tool_key").val(),
              idformxx : jQuery("#idformxx").val(),
              namelista: jQuery("#wpcf7-Aweber-list").val()
              //jQuery('wpcf7-Aweber[license_key]').val()// or combine serialized form with action ....
                },
              //dataType: "json",
          beforeSend: function() {
             jQuery("#panellistamail .spinner").css('visibility', 'visible');
              // alert('before')
          },
          success: function(response){ // response //data, textStatus, jqXHR
             jQuery("#panellistamail .spinner").css('visibility', 'hidden');
          var attrclass =''

            jQuery('#panelconfigcampos').html( response );

            attrclass = jQuery('#panellistamail').attr("class");
            if ( attrclass === 'spt-response-out' ) {
                jQuery('#panelconfigcampos').attr("class",'spt-response-out'
                  );
            } else {
                jQuery('#panelconfigcampos').attr("class",'spt-response-out  spt-valid');
            }

            /*for (i=0;i<data.xxlistacampos.length;i++)
            {
                alert(data.xxlistacampos[i].name);
                jQuery('#listpruebita').append("<option value=\""+data.xxlistacampos[i].name+"\">"+data.xxlistacampos[i].name+"</option>");
            }jQuery('#panelconfigcampos').html( response );
            alert(data.listacamposdinamico);
            alert(data.length);*/

                        //alert(data.display_errorlicence);
          },
          error: function(data, textStatus, jqXHR){
              alert(textStatus);
          },
      });
  });


jQuery(document).on('click', '#activalist', function(event){ // use jQuery no conflict methods replace $ with "jQuery"

  event.preventDefault(); // stop post action

  jQuery.ajax({

    type: "POST",
    url: ajaxurl, // or '<?php echo admin_url('admin-ajax.php'); ?>'
    data: {
        action : 'wpcf7_awb_activalista',
        tool_key : jQuery("#wpcf7-Aweber-tool_key").val(),
        idformxx : jQuery("#idformxx").val(),
        namelista: jQuery("#wpcf7-Aweber-list").val(),
        apicode : jQuery("#wpcf7-Aweber-code").val(),
        //jQuery('wpcf7-Aweber[license_key]').val()// or combine serialized form with action ....
          },
        //dataType: "json",
    beforeSend: function() {
        // alert('before')
        jQuery("#panelcodeapi .spinner").css('visibility', 'visible');

    },
    success: function(response){ // response //data, textStatus, jqXHR
      jQuery("#panelcodeapi .spinner").css('visibility', 'hidden');


      /*for (i=0;i<data.xxlistacampos.length;i++)
      {
          alert(data.xxlistacampos[i].name);
          jQuery('#listpruebita').append("<option value=\""+data.xxlistacampos[i].name+"\">"+data.xxlistacampos[i].name+"</option>");
      }
      alert(data.listacamposdinamico);
      alert(data.length);*/

      jQuery('#panellistamail').html( response );

      var valor = jQuery("#txcomodin2").val();
      var awb_valid ='';
      var attrclass ='';
      if (valor === '1') {
        attrclass = 'spt-response-out spt-valid';
        awb_valid = '<h3 class="title">Aweber Code  <span class="awb valid"><span class="dashicons dashicons-yes"></span>Code Key</span></h3>';
      } else {
        attrclass = 'spt-response-out';
        awb_valid = '<h3 class="title">Aweber Code x <span class="awb invalid"><span class="dashicons dashicons-no"></span>Error: Code Key</span></h3>';
        jQuery('#panelconfigcampos').html( '</span> </span>' );
      }
      jQuery('#panellistamail').attr("class",attrclass);
      jQuery('#spcodeapi').html( awb_valid );


                  //alert(data.display_errorlicence);
    },
    error: function(data, textStatus, jqXHR){
        alert(textStatus);
    },

  });

});


jQuery(document).ready(function($) {
    $('#awb-ajax-get').click(function(){

        var mydata = {
            action: "get_awb_cool_options",
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php

        $.ajax({
            type: "POST",
            url: my_ajax_url.ajax_url,
            dataType: "json",
            data: mydata,
            success: function (data, textStatus, jqXHR) {
                var name = data;
                /*var age = data.age;
                var color = data.favorite_color;*/

                alert(name);

                $('#display').html('<p>La clave es : '+name +' </p>');

            },
            error: function (errorMessage) {

                console.log(errorMessage);
            }

        });

    });

    $('#awb-ajax-save').click(function(){

        var mydata = {

            action: "save_awb_cool_options",
            name: 'Bob Jones',
            age: 35,
            color: 'green'
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.ajax({
            type: "POST",
            url: ajaxurl,

            dataType: "json",
            data: mydata,
            success: function (data, textStatus, jqXHR) {

                if(data === true)
                    $('#display').html('<p>Options Saved!</p>');

            },

            error: function (errorMessage) {

                console.log(errorMessage);
            }

        });

    });

});

(function($){
    $.fn.serializeObject = function(){

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };


        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };
})(jQuery);


