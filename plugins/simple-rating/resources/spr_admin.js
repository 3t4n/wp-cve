var $j = jQuery, pcl, number, type, space;
type = spr_ajax_object.spr_type;
scale = spr_ajax_object.scale;
function initiate()
{
    $j(".spr_rating_piece").mouseenter(function(event)
    {
        rating_wroking = spr_ajax_object.rating_working;
        pcl = [];
        numb = event.target.id;
        numb = (parseInt(numb.replace('spr_piece_', '')));
        for (var i = 1; i <= scale; i++) {
            pcl[i] = ($j("#spr_piece_" + i).attr('class')).replace('spr_rating_piece ', '');
        }
        for (var i = 1; i <= scale; i++) {
            $j("#spr_piece_" + i).addClass('spr_' + type + '_empty');
        }
        $j(".spr_rating_piece").removeClass('spr_' + type + '_full_voting');
        $j(".spr_rating_piece").removeClass('spr_' + type + '_half_voting');
        for (i = 1; i <= numb; i++) {
            $j("#spr_piece_" + i).addClass('spr_' + type + '_full_voted');
        }
    }).mouseleave(function() {
        $j(".spr_rating_piece").removeClass('spr_' + type + '_full_voted');
        $j(".spr_rating_piece").removeClass('spr_' + type + '_half_voted');
        for (var i = 1; i <= scale; i++) {
            $j("#spr_piece_" + i).addClass(pcl[i]);
        }
    }
    );
}

$j("#spr_shape").change(function(event)
{
    for (var i = 1; i <= scale; i++) {
        $j("#spr_piece_" + i).removeClass('spr_' + type + '_empty');
    }
    $j(".spr_rating_piece").removeClass('spr_' + type + '_full_voting');
    $j(".spr_rating_piece").removeClass('spr_' + type + '_half_voting');
    for (i = 1; i <= scale; i++) {
        $j("#spr_piece_" + i).removeClass('spr_' + type + '_full_voting');
    }
    type = $j("#spr_color option:selected").val() + $j("#spr_shape option:selected").val();
    for (i = 1; i <= scale; i++) {
        $j("#spr_piece_" + i).addClass('spr_' + type + '_full_voting');
    }
})

$j("#spr_color").change(function(event)
{
    for (var i = 1; i <= scale; i++) {
        $j("#spr_piece_" + i).removeClass('spr_' + type + '_empty');
    }
    $j(".spr_rating_piece").removeClass('spr_' + type + '_full_voting');
    $j(".spr_rating_piece").removeClass('spr_' + type + '_half_voting');
    for (i = 1; i <= scale; i++) {
        $j("#spr_piece_" + i).removeClass('spr_' + type + '_full_voting');
    }
    type = $j("#spr_color option:selected").val() + $j("#spr_shape option:selected").val();
    for (i = 1; i <= scale; i++) {
        $j("#spr_piece_" + i).addClass('spr_' + type + '_full_voting');
    }
})
$j("#spr_alignment").change(function(event)
{
    $j("#spr_container").css('text-align', $j("#spr_alignment option:selected").val());
})

$j("#spr_scale").on('input', function(event)
{
    scale = $j("#spr_scale").val();
    if (scale >= 3 && scale <= 10) {

        $j("#spr_shapes").html('');
        for (i = 1; i <= scale; i++) {
            $j("#spr_shapes").html($j("#spr_shapes").html() + '<span id="spr_piece_' + i + '" class="spr_rating_piece spr_' + type + '_full_voting"></span>');
            $j("#spr_piece_" + i).addClass();
        }
        initiate();
    }
    else {
        $j("#spr_shapes").html('');
        for (i = 1; i <= 5; i++) {
            $j("#spr_shapes").html($j("#spr_shapes").html() + '<span id="spr_piece_' + i + '" class="spr_rating_piece spr_' + type + '_full_voting"></span>');
            $j("#spr_piece_" + i).addClass();
        }
        initiate();
    }
})
$j("#spr_vote_count_color").on('input', function(event)
{
    if ($j("#spr_vote_count_color").val() == '' || !$j("#spr_vote_count_color").val().match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/g)) {
        $j("#spr_votes").css('color', '');
        $j('.pickcolor').css('background', '');
    }
    else {
        $j("#spr_votes").css('color', $j("#spr_vote_count_color").val());
        $j('#spr_vote_count_color_box').css('background', $j("#spr_vote_count_color").val());
    }
})

$j("#spr_show_vote_count").change(function(event)
{
    if ($j(this).is(":checked")) {
        $j("#spr_votes").html('5 votes');
    }
    else
        $j("#spr_votes").html('');
})

$j("#spr_vc_italic").change(function(event)
{
    if ($j(this).is(":checked")) {
        $j("#spr_votes").css('font-style', 'italic');
    }
    else
        $j("#spr_votes").css('font-style', '');
})
$j("#spr_show_in_loops").change(function(event)
{
    if (!$j(this).is(":checked")) {
        $j("#spr_loop_on_hp").removeAttr("checked");
    }


})
$j("#spr_loop_on_hp").change(function(event)
{
    if ($j(this).is(":checked")) {
        $j("#spr_show_in_loops").attr("checked", "checked");
    }

})

$j("#spr_vc_bold").change(function(event)
{
    if ($j(this).is(":checked")) {
        $j("#spr_votes").css('font-weight', '700');
    }
    else
        $j("#spr_votes").css('font-weight', '');
})

jQuery(document).ready(function($) {
    initiate();
    $j("#spr_votes").css('color', $j('#spr_vote_count_color').val());
    $j("#spr_container").css('text-align', $j("#spr_alignment option:selected").val());
    if ($j('#spr_vc_italic').is(":checked")) {
        $j("#spr_votes").css('font-style', 'italic');
    }
    else
        $j("#spr_votes").css('font-style', '');
    if ($j('#spr_vc_bold').is(":checked")) {
        $j("#spr_votes").css('font-weight', '700');
    }
    else
        $j("#spr_votes").css('font-weight', '');

    $('.pickcolor').click(function(e) {
        colorPicker = $(this).next('div');
        input = $("#spr_vote_count_color");
        clicked = $(this);

        $.farbtastic($(colorPicker), function(a) {
            $(input).val(a);
            $(clicked).css('background', a);
            $j("#spr_votes").css('color', a);
        });

        colorPicker.show();
        e.preventDefault();

        $(document).mousedown(function() {
            $(colorPicker).hide();
        });
    });
});

