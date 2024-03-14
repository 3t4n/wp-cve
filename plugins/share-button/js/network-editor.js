
jQuery(document).ready(function($) {

   mbsEditor = function ()
   {
      var maxAdmin;
      var mbIcons;
   }

   mbsEditor.prototype.init = function ()
   {
    this.maxAdmin = window.maxFoundry.maxadmin;

     $(document).on('maxajax_success_network-settings', $.proxy(this.showSettings, this) );
     $(document).on('maxajax_success_save-network', $.proxy(this.settingsUpdated, this) );
     $(document).on('maxajax_success_remove-networksettings', $.proxy(this.settingsUpdated, this));
     $(document).on('maxajax_success_show-customnetworks', $.proxy(this.showCustomNetworks, this));

     $(document).on('maxajax_success_import-customnetworks', $.proxy(this.networksImported, this));

     this.mbIcons = window.maxFoundry.maxIcons;
   }

   mbsEditor.prototype.showSettings = function(e, result, status)
   {
     result = JSON.parse(result);

     if (result.output)
     {
       $('.network_editor .inside').html(result.output);
       if (result.title)
       {
         $('.network_editor .title').html(result.title);
       }
     }

     this.doColorPicker();
     $(document).trigger('mbsocial-editor-settings-loaded');
     window.maxFoundry.maxadmin.initConditionials();
     $('#icon_type').trigger('change'); // manual update, to fix
     window.maxFoundry.maxSocial.form_updated = false; // loading is not changing

   }

   mbsEditor.prototype.doColorPicker = function()
   {
     colorPalette = (mbpro_options.colorPalette !== '' ? mbpro_options.colorPalette : true);

     // colors
     $('.maxbuttons-social .network_editor .mb-color-field').wpColorPicker(
       {
          width: 300,
 				  palettes: colorPalette,
         	change: $.proxy( _.throttle(function(event, ui) {
             event.preventDefault();
             var target = $(event.target);
             var color = ui.color.toString();

             if (color.indexOf('#') === -1)
               color = '#' + color;

             var id = target.attr('id');
             $('#' + id).val(color).trigger('change');

         }, 200), this),
       }
     );
   }

   mbsEditor.prototype.showCustomNetworks = function (e, result, status)
   {
        var result = JSON.parse(result);
        if (result.status == 'error')
        {
          $('.network_editor .inside').html('<h4 class="load-error">' + result.message + '</h4>');
        }
        else {
          $('.network_editor .inside').html(result.output);

          if (result.title)
          {
            $('.network_editor .title').html(result.title);
          }
          $('.network_editor ul li').off();
          $('.network_editor ul li').on('click', $.proxy(this.toggleCustom, this));

        }
   }

   mbsEditor.prototype.networksImported = function (e, result)
   {

       this.notifySaved(e, result);
       window.location.reload(true);
   }

   mbsEditor.prototype.toggleCustom = function (e)
   {
     var target = $(e.target);
     if (! $(target).hasClass('the_network'))
     {
       target = $(target).parents('.the_network');
     }

     if ( $(target).hasClass('selected'))
     {
       $(target).removeClass('selected');
     }
     else {
       $(target).addClass('selected');
     }

     var selected = $('.network_editor ul li.selected');
     var count = $(selected).length;

     $('.cns-toolbar .selected').html(count);

     if (count > 0)
     {
       $('.cns-toolbar button').prop('disabled', false);
     }
     else {
       $('.cns-toolbar button').prop('disabled', true);
     }

     var networks = [];
     $(selected).each(function()
     {
       networks.push($(this).data('network'));

     })
     $('#selected_custom').val(networks.join(','));

   }

   mbsEditor.prototype.settingsUpdated = function(e, result, status)
   {
     if (typeof result !== 'object')
        var parsed_result = JSON.parse(result);

      if (parsed_result.output)
        this.showSettings(e, result, status);

      this.notifySaved(e, result, status);

      if (parsed_result.reload)
      {
        window.location.reload(true);
      }
   }

   mbsEditor.prototype.networkRemoved = function(e, result, status)
   {

   }

   mbsEditor.prototype.notifySaved = function(e,result,status)
   {

     if (typeof result !== 'object')
      var result = JSON.parse(result);
     var modal = window.maxFoundry.maxmodal;

     modal.newModal('save_done');
     modal.setTitle(result.title);
     modal.setContent('');
     modal.show();

     this.form_updated = false; // set updated to false
     window.maxFoundry.maxSocial.form_updated = false;
     window.setTimeout( function () {  modal.fadeOut(); }, 1000);
   }

  	if (typeof window.maxFoundry === 'undefined')
  		window.maxFoundry = {};

  	window.maxFoundry.maxSocialSettings = new mbsEditor();
  	window.maxFoundry.maxSocialSettings.init();
}); // jquery
