jQuery(document).ready(function($) {
    // Create 'keyup_event' tinymce plugin
    tinymce.PluginManager.add('keyup_event', function(editor, url) {
        editor.on('init', function(e) {
            var Content = tinymce.activeEditor.getContent(); // Get the editor content (html)

            SetWriterInfoDetails(Content, true);
        });

        editor.on('keyup', function(e) {            
            var Content = tinymce.activeEditor.getContent(); // Get the editor content (html)

            SetWriterInfoDetails(Content, false);
        });
    });

    // This is needed for running the keyup event in the text (HTML) view of the editor
    // $('#content').load(function(e) {
    //     set_writer_info_details(tinymce.activeEditor);
    // });

    $('#content').on('input keyup', function(e) {
        var Content = tinymce.activeEditor.getContent(); // Get the editor content (html)

        SetWriterInfoDetails(Content, false);
    });
});

function SetWriterInfoDetails(Content, OnLoad) {
    var wordCount = jQuery("#wp-word-count span.word-count").html();

    if (jQuery("#spanWordCount").length > 0) {
        jQuery("#spanWordCount").html(wordCount);

        if (OnLoad == false)
            GetInfoForWriterPanel(Content);
    }
}

function GetInfoForWriterPanel(Content) {
    jQuery.ajax({
        type: 'POST',
        url: "/wp-content/plugins/content-writer/lib/conwr_writer_info.php",
        data : {
			action : 'get_content_info',
			content : escape(Content)
		},

        success: function (obj) {
            if(obj && obj != "") {
                var args = obj.split("|");

                if (args && args.length > 3) {
                    jQuery("#span1stPContainsKW").html(args[0]);
                    jQuery("#span1stKWCount").html(args[1]);
                    jQuery("#span2ndKWCount").html(args[2]);
                    jQuery("#span3rdKWCount").html(args[3]);

                    if (args[1] && args[1] != "" && args[1] != "0")
                        jQuery("#span1stKWCount").addClass("green");
                    else
                        jQuery("#span1stKWCount").removeClass("green");

                    if (args[2] && args[2] != "" && args[2] != "0")
                        jQuery("#span2ndKWCount").addClass("green");
                    else
                        jQuery("#span2ndKWCount").removeClass("green");

                    if (args[3] && args[3] != "" && args[3] != "0")
                        jQuery("#span3rdKWCount").addClass("green");
                    else
                        jQuery("#span3rdKWCount").removeClass("green");

                }
            }
            else {
                if (obj.error)
                    console.log("Error: " + obj.error);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log("Error1: " + xhr.responseText);
        }
    });
}