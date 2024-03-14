jQuery(document).ready(function ($) {

    // Add tabs and tab switch functions
    $(document).ready(function (e){
        var tabs = document.querySelectorAll("ul.nav-tabs > li");
        for (var i = 0; i < tabs.length ; i++) {
            tabs[i].addEventListener("click", switchTab);
        }

        function switchTab(e) {
            e.preventDefault();
            document.querySelector("ul.nav-tabs li.active").classList.remove("active");
            document.querySelector(".tab-pane.active").classList.remove("active");

            var clickTab = e.currentTarget;
            var anchor = e.target;
            var activePanID = anchor.getAttribute("href");

            clickTab.classList.add("active");
            document.querySelector(activePanID).classList.add("active");
        }
    });

    // Add Color Picker to all inputs that have 'color-field' class
    $( '.color-picker' ).wpColorPicker();

    // Add date picker with date formate and other options
    $( '.date-picker' ).datepicker({dateFormat: 'yy-mm-dd', numberOfMonths: 1 });

    // This will check default image src if empty then hide else show image preview box
    $('.image-preview[src=""]').hide();
    $('.image-preview:not([src=""])').show();

    //Open WordPress media selection
    $(document).on('click', '.image-upload', function (e) {
        e.preventDefault();
        var $button = $(this);

        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload an Image',
            library: {
                type: 'image' // mime type
            },
            button: {
                text: 'Select Image'
            },
            multiple: false
        });

        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $button.siblings('.image-upload').val(attachment.url);
            $button.siblings('.image-upload').attr('src',attachment.url);
            $button.siblings().prev('.image-upload').attr('src',attachment.url);
            $button.siblings('.image-preview').show();
        });

        file_frame.open();
    });

    //This will remove image path from getting submitted
    $(document).on('click', '.remove-image', function (e) {
        var $button = $(this);
        var attachment = "";
        $button.siblings('.image-upload').val(attachment.url);
        $button.siblings('.image-upload').attr('src','');
        $button.siblings('.image-preview').hide();
    });

    $(document).ready(function (e){

        var $pro = document.querySelectorAll(".get-pro");

        for (var i = 0; i < $pro.length ; i++) {
        var $tr = $pro[i].closest('tr');
            $($tr).css("background-color","#F3F3F3");
        }

    }); 
});