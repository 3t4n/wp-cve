<?php $args = array(
    'post_type' => 'page',
    'post_status' => 'publish'
); 
$pages = get_pages($args); 
?>	
<script>
jQuery(document).ready(function($) {
    var counter = jQuery('.findif').html();

    $("#addrow").on("click", function () {
        var newRow = $("<div class='row'>");
        var cols = "";
		var inc=0;
		var inc = inc+1;
		
        cols += '<div class="col-lg-6"><textarea  class="form-control" name="search[' + counter + ']" placeholder="Search text"></textarea></div>';
        cols += '<div class="col-lg-6"><textarea  class="form-control" name="replace[' + counter + ']" placeholder="Replace Text With"></textarea></div>';
        cols += '<div class="col-lg-8"><select name="page[' + counter + ']"><option value="allpage"">All Page</option><?php foreach ($pages as $page) {$title = $page->post_title;$id = $page->ID; echo '<option value='.$id.'>'.$title. "</option>";} ?></select></div>';
		cols += '<input type="hidden" name="row' + counter + '">';

        cols += '<div class="col-lg-4"><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></div>';
        newRow.append(cols);
        $(".fart").append(newRow);
		counter = (counter - 1) + 2;
		document.getElementById("id").value = counter;
    });
	
	
    $(".row").on("click", ".ibtnDel", function (event) {
        $(this).closest(".row").remove();       
        counter -= 1
    });


});


</script>