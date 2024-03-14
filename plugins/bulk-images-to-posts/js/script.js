jQuery(document).ready(function($){

    Dropzone.autoDiscover = false;
    var BipDropzone = new Dropzone("#the-drop", { 
      paramName: "bipImage",
      uploadMultiple: false, // The name that will be used to transfer the file
      previewsContainer: '#the-dropped',
      clickable: '#the-dropped',
      method: "post",
      addRemoveLinks: false,
      autoProcessQueue: true,
    });
	
// Remove files from dropzone BipDropzone.removeAllFiles();

   $('#bip-upload-form').submit(function() { 
      $(this).ajaxSubmit({
         success: function(){
            $('#saveResult').html("<div id='saveMessage' class='successModal'></div>");
            $('#saveMessage').append("<p>Changes saved...</p>").show();
            BipDropzone.removeAllFiles();
         }, 
         timeout: 5000
      }); 
      setTimeout("jQuery('#saveMessage').hide('slow');", 5000);
      return false; 
   });
    
		
    $('.check:button').click(function(){
        $('input:checkbox').removeAttr('checked'); 
        $(".checkbox-container input[type='checkbox']").each(function() {
            $(this).parent("li").children("ul").hide(600);
                  $('#bip-upload-form').ajaxSubmit({
         success: function(){
            $('#saveResult').html("<div id='saveMessage' class='successModal'></div>");
            $('#saveMessage').append("<p>Changes saved...</p>").show();
            BipDropzone.removeAllFiles();
         }, 
         timeout: 5000
      }); 
      setTimeout("jQuery('#saveMessage').hide('slow');", 5000);
      return false; 
         });
    })

   $(".checkbox-container input[type='checkbox']").each(function() {
        if ($(this).is(':checked')) { 
            $(this).parent("li").children("ul").css('display', 'block');;
           }
    }); 

 $(".checkbox-container input[type='checkbox']").change(function() {
        if ($(this).is(':checked')) { 
            $(this).parent("li").children("ul").show(600);
            var checkBox = $(this);

          $('#bip-upload-form').ajaxSubmit({
         success: function(){
                checkBox.parent("li").addClass('saved');
                BipDropzone.removeAllFiles();
                setTimeout(function () { 
                    checkBox.parent("li").removeClass('saved');
                }, 2000);
            }, 
         timeout: 5000
      });

           } else {  
            $(this).parent("li").children("ul").hide(600);
            $(this).parent("li").children("ul").find('input:checkbox').each(function() {
              $(this).removeAttr('checked');   
            });
            var checkBox = $(this);
$('#bip-upload-form').ajaxSubmit({
         success: function(){
                checkBox.parent("li").addClass('removed');
                BipDropzone.removeAllFiles();
                setTimeout(function () { 
                    checkBox.parent("li").removeClass('removed');
                }, 2000);
            }, 
         timeout: 5000
      });
            }
    });    
});