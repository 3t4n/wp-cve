!(function($) {

    "use strict";

    $(document).ready(function() {

        // Variables
        var type = "single",
            mainBody = $("body"),
            mainDocument = $(document);

        // Global
        if(window.default_global_customization_type){
            type = "global";
        }

        // elementor_library / use global
        if(typenow === "elementor_library"){
            type = "global";
        }

        // Getting the editor link
        function openYpEditor(){

            // Variables
            var editorLink,
                id = mainBody.find("#post_ID").val();

            // Get editor link
            editorLink = "admin.php?page=yellow-pencil-editor&href&wyp_page_id=" + id + "&wyp_page_type=" + typenow + "&wyp_mode=" + type;

            // Open Window
            window.open(editorLink, "_blank");

        }


        // attachment post type
        if(mainBody.hasClass("post-type-attachment") == 0 && mainBody.find("#postbox-container-1").length > 0){

            // Append Button
            mainBody.find("#postbox-container-1").prepend("<a class='wyp-btn'><span class='dashicons dashicons-admin-appearance'></span>Edit Page - YellowPencil</a>")

            // Click event
            mainDocument.on("click", ".wyp-btn", function() {

                // Variables
                var form = $("form#post"),
                    url = form.attr('action'),
                    method = form.attr("method");

                // Ajax Save if draft
                if(mainBody.find("#original_post_status").val() == "auto-draft"){
                    openYpEditor();
                    mainBody.find("#save-post").trigger("click");
                }else{
                    openYpEditor();
                }

            });


        // Block Editor Page
        } else if(mainBody.hasClass("block-editor-page")){

            // Check section
            window.wp.data.subscribe( function () {

                // Delay
                setTimeout(function(){

                    // Check if need update
                    if(mainBody.find(".edit-post-header-toolbar").length > 0 && mainBody.find(".wyp-btn").length == 0){

                        // Create a virtual button
                        var button = $('<button type="button" class="components-button has-icon wyp-btn"><span class="dashicons dashicons-admin-appearance"></span></button>');

                        // Add event
                        button.on("click", function(){

                            // Draft
                            if(mainBody.find(".editor-post-save-draft").length > 0){
                                $(".editor-post-save-draft").trigger("click"); // save draft
                            }

                            // Open editor
                            openYpEditor();

                        });

                        // Append button
                        mainBody.find(".edit-post-header-toolbar").append(button);

                    }

                },1);

            });

        }

    });

})(jQuery);
