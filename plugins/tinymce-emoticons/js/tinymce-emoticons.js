// TinyMCE Emoticons plugin
// send html to the editor
 function send_wp_editor(html)
 {
     var win = window.dialogArguments || opener || parent || top;
     win.send_to_editor(html);
     // alternatively direct tinyMCE command for insert
     // tinyMCE.execCommand("mceInsertContent", false, html);
 }
 function insert_link(html_link)
 {
     if ((typeof tinyMCE != "undefined") && ( edt = tinyMCE.getInstanceById('content') ) && !edt.isHidden() )
     {
         var sel = edt.selection.getSel();
         if (sel)
         {
             var link = '<a href="' + html_link + '" >' + sel + '</a>';
             send_wp_editor(link);
         }
     }
 }

 // insert image to the editor
 function insert_image(src, title) {
     //var size = document.getElementById('img_size').value;
     var img = '<img src="' + src + '" alt="' + title + '" hspace="5" border="0" />';
     send_wp_editor(img);

 }

jQuery(document).ready(function($)
{
var i;
var doc_height = $(document).height();
var post_area = $('.postarea').position();
var post_area_top = '';
if(post_area != null)
post_area_top = post_area.top;
//console.log(post_area.top);
//var update_height = $('.updated').height();
//var notice_height = $('#notice').height();
//alert(update_height);
var popup_top = post_area_top - 125;
//if(update_height != null) popup_top += update_height + 25;
//if(notice_height != null) popup_top += notice_height + 25;

/* Emoticons Set 1 Popup - Basic */
var popup = "<div class='emoticons-popup' style='width: 286px; height: 143px; padding: 5px; background: #ddd; background: rgba(230,230,230,.9); -moz-box-shadow: 0 0 10px #999; -webkit-box-shadow: 0 0 10px #999; box-shadow: 0 0 10px #999; position: absolute; left: 332px; top:"+popup_top+"px; z-index: 99999999; display: none; overflow: auto;'>";
popup += "<a href='#' title='adore' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/adore.png\",\"adore\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/adore.png' /></a>";
popup += "<a href='#' title='angry' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/angry.png\",\"angry\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/angry.png' /></a>";
popup += "<a href='#' title='baloon' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/baloon.png\",\"baloon\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/baloon.png' /></a>";
popup += "<a href='#' title='bomb' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/bomb.png\",\"bomb\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/bomb.png' /></a>";
popup += "<a href='#' title='boring' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/boring.png\",\"boring\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/boring.png' /></a>";
popup += "<a href='#' title='cake' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/cake.png\",\"cake\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/cake.png' /></a>";
popup += "<a href='#' title='clap' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/clap.png\",\"clap\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/clap.png' /></a>";
popup += "<a href='#' title='cloud' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/cloud.png\",\"cloud\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/cloud.png' /></a>";
popup += "<a href='#' title='coffee' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/coffee.png\",\"coffee\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/coffee.png' /></a>";
popup += "<a href='#' title='confused' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/confused.png\",\"confused\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/confused.png' /></a>";
popup += "<a href='#' title='cry' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/cry.png\",\"cry\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/cry.png' /></a>";
popup += "<a href='#' title='drink' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/drink.png\",\"drink\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/drink.png' /></a>";
popup += "<a href='#' title='gift' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/gift.png\",\"gift\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/gift.png' /></a>";
popup += "<a href='#' title='giggle' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/giggle.png\",\"giggle\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/giggle.png' /></a>";
popup += "<a href='#' title='handshake' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/handshake.png\",\"handshake\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/handshake.png' /></a>";
popup += "<a href='#' title='heart' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/heart.png\",\"heart\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/heart.png' /></a>";
popup += "<a href='#' title='hero' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/hero.png\",\"hero\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/hero.png' /></a>";
popup += "<a href='#' title='hi' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/hi.png\",\"hi\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/hi.png' /></a>";
popup += "<a href='#' title='kiss' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/kiss.png\",\"kiss\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/kiss.png' /></a>";
popup += "<a href='#' title='knife' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/knife.png\",\"knife\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/knife.png' /></a>";
popup += "<a href='#' title='laugh' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/laugh.png\",\"laugh\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/laugh.png' /></a>";
popup += "<a href='#' title='light' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/light.png\",\"light\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/light.png' /></a>";
popup += "<a href='#' title='lookdown' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/lookdown.png\",\"lookdown\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/lookdown.png' /></a>";
popup += "<a href='#' title='mobile' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/mobile.png\",\"mobile\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/mobile.png' /></a>";
popup += "<a href='#' title='moon' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/moon.png\",\"moon\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/moon.png' /></a>";
popup += "<a href='#' title='nice' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/nice.png\",\"nice\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/nice.png' /></a>";
popup += "<a href='#' title='no' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/no.png\",\"no\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/no.png' /></a>";
popup += "<a href='#' title='note' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/note.png\",\"note\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/note.png' /></a>";
popup += "<a href='#' title='notsure' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/notsure.png\",\"notsure\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/notsure.png' /></a>";
popup += "<a href='#' title='peek' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/peek.png\",\"peek\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/peek.png' /></a>";
popup += "<a href='#' title='rainy' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/rainy.png\",\"rainy\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/rainy.png' /></a>";
popup += "<a href='#' title='sad' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/sad.png\",\"sad\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/sad.png' /></a>";
popup += "<a href='#' title='shy' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/shy.png\",\"shy\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/shy.png' /></a>";
popup += "<a href='#' title='sleepy' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/sleepy.png\",\"sleepy\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/sleepy.png' /></a>";
popup += "<a href='#' title='smile' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/smile.png\",\"smile\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/smile.png' /></a>";
popup += "<a href='#' title='snail' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/snail.png\",\"snail\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/snail.png' /></a>";
popup += "<a href='#' title='star' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/star.png\",\"star\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/star.png' /></a>";
popup += "<a href='#' title='sun' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/sun.png\",\"sun\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/sun.png' /></a>";
popup += "<a href='#' title='surprised' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/surprised.png\",\"surprised\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/surprised.png' /></a>";
popup += "<a href='#' title='tease' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/tease.png\",\"tease\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/tease.png' /></a>";
popup += "<a href='#' title='thinking' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/thinking.png\",\"thinking\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/thinking.png' /></a>";
popup += "<a href='#' title='thumbsdown' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/thumbsdown.png\",\"thumbsdown\");'><img style='margin-right: 8px; margin-left: 4px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/thumbsdown.png' /></a>";
popup += "<a href='#' title='thumbsup' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/thumbsup.png\",\"thumbsup\");'><img style='margin-right: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/thumbsup.png' /></a>";
popup += "<a href='#' title='victory' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/victory.png\",\"victory\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/victory.png' /></a>";
popup += "<a href='#' title='weep' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/weep.png\",\"weep\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/weep.png' /></a>";
popup += "<a href='#' title='wine' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/wine.png\",\"wine\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/wine.png' /></a>";
popup += "<a href='#' title='wondering' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-1/wondering.png\",\"wondering\");'><img src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-1/wondering.png' /></a>";
popup += "</div>";

/* Emoticons Set 2 Popup - Animated */
var popup2 = "<div class='emoticons-popup' style='width: 306px; height: 143px; padding: 5px; background: #ddd; background: rgba(230,230,230,.9); -moz-box-shadow: 0 0 10px #999; -webkit-box-shadow: 0 0 10px #999; box-shadow: 0 0 10px #999; position: absolute; left: 332px; top:"+popup_top+"px; z-index: 99999999; display: none; overflow: auto;'>";
popup2 += "<a href='#' title='applause' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/applause.gif\",\"applause\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/applause.gif' /></a>";
popup2 += "<a href='#' title='cheers' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/cheers.gif\",\"cheers\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/cheers.gif' /></a>";
popup2 += "<a href='#' title='clap' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/clap.gif\",\"clap\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/clap.gif' /></a>";
popup2 += "<a href='#' title='clever' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/clever.gif\",\"clever\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/clever.gif' /></a>";
popup2 += "<a href='#' title='coffee' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/coffee.gif\",\"coffee\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/coffee.gif' /></a>";
popup2 += "<a href='#' title='confused' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/confused.gif\",\"confused\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/confused.gif' /></a>";
popup2 += "<a href='#' title='cry' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/cry.gif\",\"cry\");'><img style='margin-right: 10px; margin-bottom: 5px; width: 36px; height: 36px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/cry.gif' /></a>";
popup2 += "<a href='#' title='dont care' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/dontcare.gif\",\"dontcare\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/dontcare.gif' /></a>";
popup2 += "<a href='#' title='eat' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/eat.gif\",\"eat\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/eat.gif' /></a>";
popup2 += "<a href='#' title='giggle' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/giggle.gif\",\"giggle\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/giggle.gif' /></a>";
popup2 += "<a href='#' title='i dont understand' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/idontunderstand.gif\",\"idontunderstand\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/idontunderstand.gif' /></a>";
popup2 += "<a href='#' title='i love money' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/ilovemoney.gif\",\"ilovemoney\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/ilovemoney.gif' /></a>";
popup2 += "<a href='#' title='in love' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/inlove.gif\",\"inlove\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/inlove.gif' /></a>";
popup2 += "<a href='#' title='killer' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/killer.gif\",\"killer\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/killer.gif' /></a>";
popup2 += "<a href='#' title='kiss' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/kiss.gif\",\"kiss\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/kiss.gif' /></a>";
popup2 += "<a href='#' title='laugh' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/laugh.gif\",\"laugh\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/laugh.gif' /></a>";
popup2 += "<a href='#' title='mouth shut' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/mouthshut.gif\",\"mouthshut\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/mouthshut.gif' /></a>";
popup2 += "<a href='#' title='mummy' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/mummy.gif\",\"mummy\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/mummy.gif' /></a>";
popup2 += "<a href='#' title='nice smell' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/nicesmell.gif\",\"nicesmell\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/nicesmell.gif' /></a>";
popup2 += "<a href='#' title='no' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/no.gif\",\"no\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/no.gif' /></a>";
popup2 += "<a href='#' title='perfect' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/perfect.gif\",\"perfect\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/perfect.gif' /></a>";
popup2 += "<a href='#' title='punch' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/punch.gif\",\"punch\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/punch.gif' /></a>";
popup2 += "<a href='#' title='sad' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/sad.gif\",\"sad\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/sad.gif' /></a>";
popup2 += "<a href='#' title='sadbye' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/sadbye.gif\",\"sadbye\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/sadbye.gif' /></a>";
popup2 += "<a href='#' title='slap' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/slap.gif\",\"slap\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/slap.gif' /></a>";
popup2 += "<a href='#' title='sleepy' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/sleepy.gif\",\"sleepy\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/sleepy.gif' /></a>";
popup2 += "<a href='#' title='smile' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/smile.gif\",\"smile\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/smile.gif' /></a>";
popup2 += "<a href='#' title='surprised' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/surprised.gif\",\"surprised\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/surprised.gif' /></a>";
popup2 += "<a href='#' title='sweat' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/sweat.gif\",\"sweat\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/sweat.gif' /></a>";
popup2 += "<a href='#' title='tease' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/tease.gif\",\"tease\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/tease.gif' /></a>";
popup2 += "<a href='#' title='very funny' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/veryfunny.gif\",\"veryfunny\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/veryfunny.gif' /></a>";
popup2 += "<a href='#' title='very sad' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/verysad.gif\",\"verysad\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/verysad.gif' /></a>";
popup2 += "<a href='#' title='victory' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-2/victory.gif\",\"victory\");'><img style='margin-right: 10px; margin-bottom: 5px' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-2/victory.gif' /></a>";
popup2 += "</div>";

/* Emoticons Set 3 Popup - Outlined */
var popup3 = "<div class='emoticons-popup' style='width: 272px; height: 143px; padding: 5px; background: #ddd; background: rgba(230,230,230,.9); -moz-box-shadow: 0 0 10px #999; -webkit-box-shadow: 0 0 10px #999; box-shadow: 0 0 10px #999; position: absolute; left: 343px; top:"+popup_top+"px; z-index: 99999999; display: none; overflow: auto;'>";
popup3 += "<a href='#' title='angel' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/angel.png\",\"angel\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/angel.png' /></a>";
popup3 += "<a href='#' title='angry' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/angry.png\",\"angry\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/angry.png' /></a>";
popup3 += "<a href='#' title='angry sick' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/angry-sick.png\",\"angry-sick\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/angry-sick.png' /></a>";
popup3 += "<a href='#' title='bitter' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/bitter.png\",\"bitter\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/bitter.png' /></a>";
popup3 += "<a href='#' title='concerned' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/concerned.png\",\"concerned\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/concerned.png' /></a>";
popup3 += "<a href='#' title='cool' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/cool.png\",\"cool\");'><img style='margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/cool.png' /></a>";
popup3 += "<a href='#' title='cyclopse' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/cyclopse.png\",\"cyclopse\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/cyclopse.png' /></a>";
popup3 += "<a href='#' title='dead' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/dead.png\",\"dead\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/dead.png' /></a>";
popup3 += "<a href='#' title='depressed' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/depressed.png\",\"depressed\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/depressed.png' /></a>";
popup3 += "<a href='#' title='devil' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/devil.png\",\"devil\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/devil.png' /></a>";
popup3 += "<a href='#' title='disappointed' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/disappointed.png\",\"disappointed\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/disappointed.png' /></a>";
popup3 += "<a href='#' title='excited' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/excited.png\",\"excited\");'><img style='margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/excited.png' /></a>";
popup3 += "<a href='#' title='feeling loved' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/feeling-loved.png\",\"feeling-loved\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/feeling-loved.png' /></a>";
popup3 += "<a href='#' title='geeky' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/geeky.png\",\"geeky\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/geeky.png' /></a>";
popup3 += "<a href='#' title='grumpy' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/grumpy.png\",\"grumpy\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/grumpy.png' /></a>";
popup3 += "<a href='#' title='happy' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/happy.png\",\"happy\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/happy.png' /></a>";
popup3 += "<a href='#' title='happy wink' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/happy-wink.png\",\"happy-wink\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/happy-wink.png' /></a>";
popup3 += "<a href='#' title='hard laugh' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/hard-laugh.png\",\"hard-laugh\");'><img style='margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/hard-laugh.png' /></a>";
popup3 += "<a href='#' title='in love' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/in-love.png\",\"in-love\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/in-love.png' /></a>";
popup3 += "<a href='#' title='joyful' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/joyful.png\",\"joyful\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/joyful.png' /></a>";
popup3 += "<a href='#' title='laughing' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/laughing.png\",\"laughing\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/laughing.png' /></a>";
popup3 += "<a href='#' title='looking shocked' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/looking-shocked.png\",\"looking-shocked\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/looking-shocked.png' /></a>";
popup3 += "<a href='#' title='looking smirk' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/looking-smirk.png\",\"looking-smirk\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/looking-smirk.png' /></a>";
popup3 += "<a href='#' title='looking talking' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/looking-talking.png\",\"looking-talking\");'><img style='margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/looking-talking.png' /></a>";
popup3 += "<a href='#' title='no snitching' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/no-snitching.png\",\"no-snitching\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/no-snitching.png' /></a>";
popup3 += "<a href='#' title='quiet' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/quiet.png\",\"quiet\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/quiet.png' /></a>";
popup3 += "<a href='#' title='sad' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/sad.png\",\"sad\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/sad.png' /></a>";
popup3 += "<a href='#' title='sick' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/sick.png\",\"sick\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/sick.png' /></a>";
popup3 += "<a href='#' title='silly' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/silly.png\",\"silly\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/silly.png' /></a>";
popup3 += "<a href='#' title='sneaky' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/sneaky.png\",\"sneaky\");'><img style='margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/sneaky.png' /></a>";
popup3 += "<a href='#' title='speechless' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/speechless.png\",\"speechless\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/speechless.png' /></a>";
popup3 += "<a href='#' title='surprised' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/surprised.png\",\"surprised\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/surprised.png' /></a>";
popup3 += "<a href='#' title='thumbs down' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/thumbs-down.png\",\"thumbs-down\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/thumbs-down.png' /></a>";
popup3 += "<a href='#' title='thumbs up' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/thumbs-up.png\",\"thumbs-up\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/thumbs-up.png' /></a>";
popup3 += "<a href='#' title='tired' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/tired.png\",\"tired\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/tired.png' /></a>";
popup3 += "<a href='#' title='whistle blower' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/whistle-blower.png\",\"whistle-blower\");'><img style='margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/whistle-blower.png' /></a>";
popup3 += "<a href='#' title='yawning' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/yawning.png\",\"yawning\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/yawning.png' /></a>";
popup3 += "<a href='#' title='yelling' onclick='insert_image(tinyEmoSettings.tinyEmo_url+\"images/emoticons-set-3/yelling.png\",\"yelling\");'><img style='margin-right: 10px; margin-bottom: 5px;' src='"+tinyEmoSettings.tinyEmo_url+"images/emoticons-set-3/yelling.png' /></a>";
popup3 += "</div>";
if(tinyEmoSettings.select_option == 'basic')
$('#wpcontent #poststuff #post-body').append(popup);
if(tinyEmoSettings.select_option == 'animated')
$('#wpcontent #poststuff #post-body').append(popup2);
if(tinyEmoSettings.select_option == 'outlined')
$('#wpcontent #poststuff #post-body').append(popup3);

$('html').click(function(e) {
    var clicked = $(e.target).attr('class');
//var clicked2 = $(e.target).parent().attr('class');
     console.log(clicked);
//console.log(clicked2);
   if((clicked != 'mce-ico mce-i-none') && (clicked != 'mceIcon') && clicked != 'mCSB_dragger_bar'){
   $('.emoticons-popup').hide();
      i=1;
   }
      });

$(document).on("click", ".mce_btnTinyEmo img.mceIcon, .mce-btnTinyEmo button",function(){ 
//console.log(i);   
if(i==0){
   $('.emoticons-popup').hide();
   i=1;
   }
   else{
    $('.emoticons-popup').show();
    i=0;
   }
   //$('.emoticons-popup').show();
  });

$("#tinyemo-options a").click(function(){
$("#tinyemo-options a").removeClass("active");
$(this).addClass("active");
var opt_value = $(this).attr('alt');
$("#tinyemo-options #option-value").attr('value',opt_value);
});
});

		(function($){
        var scroll_visible = false;
        $(window).load(function(){
				/* custom scrollbar fn call */
                   $(".mce_btnTinyEmo, .mce-btnTinyEmo").live("click",function(){
                if(scroll_visible != true){
                $(".emoticons-popup").mCustomScrollbar({
					scrollInertia:150
                });
                scroll_visible = true;
                }
                });
                });
                })(jQuery);