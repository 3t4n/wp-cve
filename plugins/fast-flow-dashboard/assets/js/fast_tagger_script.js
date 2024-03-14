var FastTaggerStats = function(tagNames,usersCount,tagColors)
{
    this.tagNames = tagNames;
    this.usersCount = usersCount;
    this.tagColors = tagColors;
    //this.tagName.forEach(tname => console.log(tname));
    //this.usersCount.forEach(uc => console.log(uc));
    this.numTags = this.tagNames.length;
}

FastTaggerStats.prototype.singleTag = function()
{
    style_str = 'style="color:'+this.tagColors[0]+';"';
    html_str = '<div class="numwidbox full">';
    html_str += '<h1 '+style_str+'>'+this.usersCount[0]+'</h1>';
    html_str += '<h3>'+this.tagNames[0]+'</h3></div>';
    return html_str;
}

FastTaggerStats.prototype.twoToFourTags = function()
{
    html_str =  '';
    for(ind = 0 ; ind < this.numTags ; ind++ )
    {
        style_str = 'style="color:'+this.tagColors[ind]+';"';
        html_str += '<div class="numwidbox half">';
        html_str += '<h1 '+style_str+'>'+this.usersCount[ind]+'</h1>';
        html_str += '<h3>'+this.tagNames[ind]+'</h3>';
        html_str += '</div>';
    }

    return html_str;
}

FastTaggerStats.prototype.moreThanFourTags = function()
{
    html_str =  '';
    html_str += '<ul class="numwidbox list">';
    html_str += '<li><strong><span>Description</span></strong><strong><span>Tags</span></strong></li>';
    for(ind = 0 ; ind < this.numTags ; ind++ )
    {
        style_str = 'style="color:'+this.tagColors[ind]+';"';
        html_str += '<li><span '+style_str+'>'+this.tagNames[ind];
        html_str += '</span><span>'+this.usersCount[ind]+'</span></li>';
    }
    html_str += '</ul>';

    return html_str;
}


FastTaggerStats.prototype.generateOutput = function(outerDiv)
{

  var  htmlstr = "";
    if (this.numTags == 1)
    {
        htmlstr = this.singleTag();

    }else if ((this.numTags >= 2) && (this.numTags <= 4))
    {
        htmlstr = this.twoToFourTags();

    }else if(this.numTags > 4)
    {
        htmlstr = this.moreThanFourTags();
    }

    var id = window.setInterval(function(){

         if(document.readyState != 'complete') return;
         jQuery(outerDiv).html(htmlstr);
         window.clearInterval(id);
    }, 100);


}


jQuery(document).ready(function ($) {

    setInterval(function () {

        jQuery('#message.below-h2').hide('slow', function () {

            jQuery('.user-taxonomies-page #message.below-h2').remove();

        });

    }, 3000);

/*

 * selectize script for filters

 * profile.php

 * user-edit.php

 * Fast Tags

 *  - All Tags

 *  - Tagged Users

 * FastMember integration

 */

	$("#tag_name").selectize({ plugins: ['remove_button'],create: true,maxItems:1});

	$("#parent_tag").selectize({ plugins: ['remove_button'],create: false});

	$("#fast_tag_term").selectize({ plugins: ['remove_button'],create: false});

	$("#fast_tag_type").selectize({ plugins: ['remove_button'],create: false});

	$("#user_tags").selectize({ plugins: ['remove_button'],create:true});

	//used in FM integration

	$('#add_tags').selectize({ plugins: ['remove_button'],dropdownParent: 'body',create: true});
  $('#add_cancelled_tags').selectize({ plugins: ['remove_button'],dropdownParent: 'body',create: true});
  $('#add_refunded_tags').selectize({ plugins: ['remove_button'],dropdownParent: 'body',create: true});



	//$('#add_tags').selectize({ plugins: ['remove_button'],dropdownParent: 'body',create: false});

	//FF plugin

	//$("#widget-fast_tagger_widget-3-tags").selectize({ plugins: ['remove_button'],create: false});

	//$(".tags_field").selectize({ plugins: ['remove_button'],create: false});

    $("div[id*='ff-dashboard']").find(".tags_field").selectize({ plugins: ['remove_button'],create: false});

	//$('.ff_from').datepicker();

	//$('.ff_to').datepicker();

    $('.color-field').each(function(){ $(this).wpColorPicker();});

});
