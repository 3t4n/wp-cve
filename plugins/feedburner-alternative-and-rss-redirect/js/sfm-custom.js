SFM = jQuery.noConflict();
SFM( document ).ready(function( $ )
{
  $('#rrs_support_chat').on('click', function() {

    if ($('#support-rrs').length === 0) {
      $('#wpbody-content').append('<script id="support-rrs" src="//code.jivosite.com/widget/Ptia3aTu6F" async></script>');
      setTimeout(function() {
        $('#rrs_support_chat').hide();
      }, 100);
      var loaded = false;
      let loadinter = setInterval(function() {
        if (loaded == true) clearInterval(loadinter);
        if (typeof window.jivo_api !== 'undefined') {
          window.jivo_api.open()
          loaded = true;
        }
      }, 30);
    }

  });

  SFM("body").on("click", "#mainRssconnect", function(){
    var email = SFM(this).parents("form").find("input[type='email']").val();
    var feedid  = SFM(this).parents("form").find("input[name='feed_id']").val();
    var error   = false;
    var regEx   = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

    if(email === '')
    {
      error = true;
    }

    if(!regEx.test(email))
    {
      error = true;
    }
    console.log(email,feedid,error);
    if(!error)
    {
      SFM(this).parents("form").submit();
    }
    else
    {
      alert("Error: Please provide your email address.");
    }
  });

    /* activate redirection */
    SFM('body').on('click', '.activate_redirect', function()
    {
        /*Add Overlay*/
        SFM('body').append("<div class='sfrd_loading_wrapper'>"+SFM(".sfrd_overlayGear").html()+"</div>");

        var  redirection_type=SFM(this).attr('red_type');
        var element=SFM(this);
        var nonce  = jQuery('input[name="ActRedirect_nonce"]').val();
        //console.log(nonce);
        /* check for the redirection type */
        switch(redirection_type)
        {
            case "main_rss" :
                SFM(element).removeClass('sfrd_finish');
                SFM(element).addClass('sfrd_process');
                SFM(element).css('pointer-events', 'none');
                SFM(element).text('Applying redirect, please wait ');
                var data={action:'ActRedirect',rtype:redirection_type,nonce:nonce}
            break;
            case "comment_rss" :
                SFM(element).addClass('sfrd_process_small');
                SFM(element).text('Applying redirect..');
                SFM(element).css('pointer-events', 'none');
                var data={action:'ActRedirect',rtype:redirection_type,nonce:nonce}
            break;
            case "category_rss" :
                SFM(element).addClass('sfrd_process_small');
                SFM(element).text('Applying redirect..');
                SFM(element).css('pointer-events', 'none');
                var cat_id=SFM(element).attr('rcat');
                var data={action:'ActRedirect',rtype:redirection_type,record_id :cat_id ,nonce:nonce}
            break;
            case "author_rss" :
                SFM(element).addClass('sfrd_process_small');
                SFM(element).text('Applying redirect..');
                SFM(element).css('pointer-events', 'none');
                var author_id=SFM(element).attr('rauthor');
                var data={action:'ActRedirect',rtype:redirection_type,record_id :author_id,nonce:nonce }
            break;
            case "custom_rss" :
                SFM('#sfmcustom_link').css('border',"0 none");
                var custom_url=SFM.trim(SFM('#sfmcustom_link').val());
                if (!custom_url)
                {
                  SFM('#sfmcustom_link').css('border',"1px solid #f80000");
                  SFM('.sfrd_customError').slideDown('slow');
                  SFM('.sfrd_customError').find('p').html('This is not a valid url. Please don\'t forget to add the "http://".');
                  return false;
                }
                SFM(element).addClass('sfrd_process_small');
                SFM(element).text('Applying redirect..');
                SFM(element).css('pointer-events', 'none');
                var data={action:'ActRedirect',rtype:redirection_type,curl :custom_url,nonce:nonce }
            break;
        }
        /* send request to server */
        SFM.ajax({
                url: ajax_object.ajax_url,
                type:'post',
                data: data,
                dataType:'json',
                success:function (res)
                {
                    /*Remove overlay*/
                    SFM(".sfrd_loading_wrapper").remove();

                    if(res.response=='success')
                    {
                        /* process the feeds in backend */
                        if (res.res_data.feed_id)
                        {
                            sfmProcessFeeds(res.res_data.feed_id);
                        }
                        res.res_data.feed_url= res.res_data.feed_url.replace(/api\.follow\.it/ig, 'follow.it');

                        switch(redirection_type)
                        {
                            case "main_rss"      :
                                //console.log(res);
                                SFM(element).removeClass('sfrd_process');
                                SFM(element).removeClass('activate_redirect');
                                SFM(element).css('pointer-events', 'none');
                                SFM(element).addClass('sfrd_finish');
                                SFM(element).text('Redirect is active!');
                                SFM(element).parent().find('a.reverse_redirect').attr('feed_id',res.res_data.feed_id);
                                SFM(element).parent().find('a').fadeIn('slow');
                                SFM(element).next().attr('href',res.res_data.feed_url)
                                SFM('.sfm_error').slideUp('slow');

                                //SFM(element).parent().parent().siblings('.sfrd_one').find('#mainRssconnect').attr('href',res.res_data.connect_string);
                                //SFM('.sfrdFeedBurnerBox').find('#mainRssconnect').attr('href',res.res_data.connect_string);
                                SFM(element).parent().parent().siblings('.sfrd_one').find('#mainRssconnect').attr('href',"javascript:");
                                SFM('.sfrdFeedBurnerBox').find('#mainRssconnect').attr('href',"javascript:");
                                SFM(element).parent().parent().siblings('.sfrd_one').find('#mainRssconnect').parents("form#calimingOptimizationForm").find("input[name='feed_id']").val(res.res_data.feed_id);
                                SFM('.sfrdFeedBurnerBox').find('#mainRssconnect').parents("form#calimingOptimizationForm").find("input[name='feed_id']").val(res.res_data.feed_id);

                                if (res.request_data.isfeed)
                                {
                                   SFM('.sfrdFeedBurnerBox').slideDown('slow');
                                   SFM('.sfm_box_main_box1').slideUp();
                                }
                                else
                                {
                                    SFM('.sfm_box_main_box1').slideDown('slow');
                                    SFM('.sfrdFeedBurnerBox').slideUp();
                                }

                           break;
                           case "comment_rss"   :
                           case "category_rss"  :
                           case "author_rss"    :
                                ProcessAfterActive(element,res.res_data);
                           break;
                           case "custom_rss"    :
                                ProcessAfterCustomActive(res.res_data)
                                SFM(element).removeClass('sfrd_process_small');
                                SFM(element).addClass('activate_redirect');
                                SFM(element).css('pointer-events', 'auto');
                                SFM('.sfrd_customError').slideUp();
                                SFM(element).text('Activate Redirect');
                                SFM('#sfmcustom_link').css('border',"0 none");
                                SFM('#sfmcustom_link').val('');

                                updateCustomFeedNos();
                           break;
                       }
                    }
                    else if ((res.response=='invaild_url' || res.response=='wrong_feedUrl') && redirection_type=="custom_rss")
                    {
                       SFM('.sfrd_customError').slideDown('slow');
                       SFM(element).removeClass('sfrd_process_small');
                       SFM(element).text('Activate Redirect');
                       SFM(element).css('pointer-events', 'auto');
                       SFM('.sfrd_customError').find('p').html('This is not a valid url. Please don\'t forget to add the "http://"');
                    }
                    else if (res.response=='exists_url' && redirection_type=="custom_rss")
                    {
                       SFM('.sfrd_customError').slideDown('slow');
                       SFM(element).removeClass('sfrd_process_small');
                       SFM(element).text('Activate Redirect');
                       SFM(element).css('pointer-events', 'auto');
                       SFM('.sfrd_customError').find('p').html('Redirection for this url is already Activated.Please check in above list.');
                    }
                    else if (res.response=='exists_url' && redirection_type!="custom_rss")
                    {
                       if (redirection_type=='main_rss')
                       {
                            SFM(element).removeClass('sfrd_process');
                            SFM(element).text('Click here to activate redirect');
                            SFM(element).css('pointer-events', 'auto');
                       }
                       else
                       {
                            SFM(element).removeClass('sfrd_process_small');
                            SFM(element).text('Activate Redirect');
                            SFM(element).css('pointer-events', 'auto');
                       }
                       SFM('.sfm_error').slideDown('slow');
                       SFM('.sfm_error').find('p').html('Redirection for this url is already Activated.Please check in below list.');
                    }
                    else if (res.response=='diff_url')
                    {
                       SFM('.sfrd_customError').slideDown('slow');
                       SFM(element).removeClass('sfrd_process_small');
                       SFM(element).text('Activate Redirect');
                       SFM(element).css('pointer-events', 'auto');
                       SFM('.sfrd_customError').find('p').html('You can only apply redirects for feeds from this blog. For others, please install this plugin there as well.');
                    }
                    else if (res.response=='wrong_feedUrl')
                    {
                       SFM(element).removeClass('sfrd_process_small');
                       SFM(element).text('Activate Redirect');
                       SFM(element).css('pointer-events', 'auto');
                       SFM('.sfm_error').slideDown('slow');
                       SFM('.sfm_error').find('p').html('We couldn\'t apply a redirect. Not a valid Feed Url,Please try again.');
                       SFM('html, body').animate({scrollTop : 100},800);
                    }
                    else
                    {
                       if (redirection_type=="main_rss") {
                            SFM(element).removeClass('sfrd_process');
                            SFM(element).text('Click here to activate redirect');
                            SFM(element).css('pointer-events', 'auto');
                            SFM('.sfm_error').slideDown('slow');
                            SFM('.sfm_error').find('p').html('We couldn\'t apply a redirect. Please try again.');
                       }
                       else
                       {
                            SFM(element).removeClass('sfrd_process_small');
                            SFM(element).text('Activate Redirect');
                            SFM(element).css('pointer-events', 'auto');
                            SFM('.sfm_error').slideDown('slow');
                            SFM('.sfm_error').find('p').html('We couldn\'t apply a redirect. Please try again.');
                       }
                    }
                }
        });

    });
    /* reverse the redirect */
    SFM('body').on('click', '.reverse_redirect,.reverse_redirect1', function()
    {
      if(confirm('Are you sure want to reverse Redirection ? ')) {
      var element=SFM(this);
      var feed_id=SFM.trim(SFM(element).attr('feed_id'));
      var redirection_type=SFM(element).attr('red_type');
      if (!feed_id)
      {
        return false;
      }
      var nonce  = SFM('input[name="sfmReverseRedirect_nonce"]').val();
      var data={action: "sfmReverseRedirect",feed_id:feed_id,feed_type:redirection_type,nonce:nonce}
      SFM.ajax({
                url: ajax_object.ajax_url,
                type:'post',
                data: data,
                dataType:'json',
                success:function (res)
                {
                    if(res.response=='success')
                    {
                        switch(redirection_type)
                        {
                            case "main_rss"     :
                                SFM('#main_rss').removeClass('sfrd_finish');
                                SFM('#main_rss').addClass('activate_redirect');
                                SFM('#main_rss').css('pointer-events', 'auto');
                                SFM('#main_rss').text('Click here to activate redirect');
                                SFM('#main_rss').attr('title','Click here to activate redirect');
                                SFM(element).parent().find('a.open_new_feed').fadeOut();
                                SFM(element).fadeOut()
                                SFM('.sfm_error').slideUp('slow');
                                SFM('.sfrdFeedBurnerBox').slideUp();
                                SFM('.sfm_box_main_box1').slideUp()
                            break;
                            case "comment_rss"   :
                            case "category_rss"  :
                            case "author_rss"    :
                                ProcessAfterReverse(element,res);
                            break;
                            case "custom_rss"    :
                                SFM(element).parent().parent().slideUp('slow',function(){ SFM(element).parent().parent().remove(); updateCustomFeedNos();});
                                SFM(element).parent().parent().next('.sfrd_green_box1').slideUp('slow',function(){ SFM(element).parent().parent().next('.sfrd_green_box1').remove(); });
                                SFM('#sfmCusCounter').val(parseInt(SFM('#sfmCusCounter').val())-1);
                            break;
                        }
                    }
                    else
                    {
                       SFM(element).removeClass('sfrd_process_small');
                       SFM(element).text('Activate Redirect');
                       SFM('.sfm_error').slideDown('slow');
                       SFM('.sfm_box_main_box1').slideUp('slow');
                       SFM('.sfm_error').find('p').html('Internal Error Please try again.');
                    }
                }
            });
        }
        else
        {
            return false;
        }
    });

    /* show hide instructions pop-up */
    SFM('body').on('click', '.inc_pop', function()
    {
         SFM('.sfrd_popup_overlay').slideDown();
         SFM('.sfrd_popup').fadeIn('slow');
    });
    /* close pop-up */
    SFM('.close_incPopUp').click(function()
    {
         SFM('.sfrd_popup_overlay').slideUp();
         SFM('.sfrd_popup').fadeOut();
    });

});
/* end of ready function */

function ProcessAfterActive(element,res_data)
{
  // console.log(res_data);

    /* make changes in button */
    SFM(element).removeClass('sfrd_process_small');
    SFM(element).css('pointer-events', 'none');
    SFM(element).removeClass('activate_redirect');
    SFM(element).addClass('sfrd_redirect_active');
    SFM(element).text('Redirect is active!');
    SFM(element).parent().find('a').fadeIn('slow');
    SFM(element).parent().find('a.reverse_redirect1').attr('feed_id',res_data.feed_id);

    /* hide the error div */
    SFM('.sfm_error').slideUp();
    SFM('.sfm_error').find('p').html('');

    /* add the feed subscribe url */
    SFM(element).parent().find('.open_new_feed1').attr('href',res_data.feed_url)
    SFM('.sfm_error').slideUp('slow');

    /* create the green box for rss */
    SFM("<div class='sfrd_green_box1'>"+SFM(".sfm_box_main_box1" ).html()+"</div>").insertAfter(SFM(element).parent().parent());

    //SFM(element).parent().parent().next('.sfrd_green_box1').find('#mainRssconnect').attr('href',res_data.connect_string);
    SFM(element).parent().parent().next('.sfrd_green_box1').find('#mainRssconnect').attr('href',"javascript:");
    SFM(element).parent().parent().next('.sfrd_green_box1').find('#mainRssconnect').parents("form#calimingOptimizationForm").find("input[name='feed_id']").val(res_data.feed_id);

    /* change the default display link */
    if ( SFM(element).parent().parent().find('h3').find('span').find('a').length>0)
    {
        SFM(element).parent().parent().find('h3').find('span').find('a').attr("href",res_data.feed_url);
        SFM(element).parent().parent().find('h3').find('span').find('a').attr("feed_id",res_data.feed_id);
        SFM(element).parent().parent().find('h3').find('span').find('a').text(res_data.feed_url);
    }
}

function ProcessAfterCustomActive(res)
{
    var cnt=parseInt(SFM('#sfmCusCounter').val())+1;
    SFM('#sfmCusCounter').val(cnt);
    var feed_url = res.feed_url.replace(/api\.follow\.it/ig, 'follow.it');
    var content='<div class="sfrd_feedmaster_tab sfm_customFeeds" style="display:none">';
    content+='<h3> &ldquo;Custom feed '+cnt+'&rdquo; ';
    content+='<span> <a id="custom_'+res.rid+'" target="_new" title="'+feed_url+'" href="'+feed_url+'">'+feed_url+'</a></span>'
    content+='</h3>';
    content+="<small>";
    content+='<a class="open_new_feed1" title="Open the new feed " target="_new" href="'+feed_url+'">Open the new feed</a>';
    content+='<a class="reverse_redirect1" feed_id="'+res.feed_id+'" red_type="custom_rss" title="Reverse redirect"  href="javascript:void(0);">Reverse redirect</a>';
    content+='<a title="Redirect is active!" class="sfrd_redirect_active" red_type="custom_rss" id="custom_rss" href="javascript:void(0);">Redirect is active!</a>';
    content+="</small>";
    content+="<div class=' clear'></div>";
    content+="</div>"

    SFM('.sfrd_feedmaster_main').append(content);
    var greenbox = "<div class='sfrd_green_box1' style='display:none'>"+SFM(".sfm_box_main_box1" ).html()+"</div>";
    SFM('.sfrd_feedmaster_main').append(greenbox);

    //SFM('#custom_'+res.rid).parent().parent().parent().next('.sfrd_green_box1').find('#mainRssconnect').attr('href',res.connect_string);
    SFM('#custom_'+res.rid).parent().parent().parent().next('.sfrd_green_box1').find('#mainRssconnect').attr('href',"javascript:");
    SFM('#custom_'+res.rid).parent().parent().parent().next('.sfrd_green_box1').find('#mainRssconnect').parents("form#calimingOptimizationForm").find("input[name='feed_id']").val(res.feed_id);
    SFM('.sfrd_feedmaster_main>.sfrd_feedmaster_tab:last,.sfrd_feedmaster_main >.sfrd_green_box1:last').slideDown('slow');
}

function ProcessAfterReverse(element,res_data)
{
    SFM(element).parent().find('a.sfrd_redirect_active').addClass('activate_redirect').css('pointer-events', 'auto').text('Activate Redirect').attr('title','Activate Redirect').removeClass('sfrd_redirect_active');
    SFM(element).parent().find('a.open_new_feed1').fadeOut();
    SFM(element).fadeOut()
    SFM('.sfm_error').slideUp('slow');
    SFM(element).parent().parent().next('.sfrd_green_box1').slideUp();

    if ( SFM(element).parent().parent().find('h3').find('span').find('a').length>0)
    {
       SFM(element).parent().parent().find('h3').find('span').find('a').attr("href",unescape(res_data.feed_url));
       SFM(element).parent().parent().find('h3').find('span').find('a').attr("feed_id",res_data.feed_id);
       SFM(element).parent().parent().find('h3').find('span').find('a').text(unescape(res_data.feed_url));
    }
}
/* update the custom feeds text on deletion and addtion of new custom feeds */
function updateCustomFeedNos()
{
    var cnt=1;
    setTimeout(function() {SFM("div.sfrd_feedmaster_main> div.sfm_customFeeds").each(function(n){
        /* check for valid li element */
        var span_content=SFM(this).find("h3").find('span').html();
        SFM(this).find("h3").html("&ldquo;Custom feed "+cnt+"&rdquo;"+"<span>"+span_content+"</span>");
        cnt++;
    });},700);
}
/* process the feeds in backend */

function sfmProcessFeeds(feed_id)
{
    var nonce  = SFM('input[name="sfmProcessFeeds_nonce"]').val();
    var data={feed_id: feed_id,'action': 'sfmProcessFeeds',nonce:nonce}
    SFM.ajax({
        url: ajax_object.ajax_url,
        type:'post',
        data: data,
        dataType:'json',
        success:function (res)
        {
        }
    });
}
