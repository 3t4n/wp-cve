function qm_setTab(tab) {
  jQuery("a.nav-tab-active").toggleClass("nav-tab-active");
  if (tab == 1)
  {
    jQuery("#what_new").show();
    jQuery("#changelog").hide();
    jQuery("#tab_1").toggleClass("nav-tab-active");
  }
  if (tab == 2)
  {
    jQuery("#what_new").hide();
    jQuery("#changelog").show();
    jQuery("#tab_2").toggleClass("nav-tab-active");
  }
}

function qm_validateForm()
{
  var x=document.forms['emailForm']['email'].value;
  if (x==null || x=='')
  {
    document.getElementById('mlw_support_message').innerHTML = '**Email must be filled out!**';
    return false;
  };
  var x=document.forms['emailForm']['username'].value;
  if (x==null || x=='')
  {
    document.getElementById('mlw_support_message').innerHTML = '**Name must be filled out!**';
    return false;
  };
  var x=document.forms['emailForm']['message'].value;
  if (x==null || x=='')
  {
    document.getElementById('mlw_support_message').innerHTML = '**There must be a message to send!**';
    return false;
  };
  var x=document.forms['emailForm']['email'].value;
  var atpos=x.indexOf('@');
  var dotpos=x.lastIndexOf('.');
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
  {
    document.getElementById('mlw_support_message').innerHTML = '**Not a valid e-mail address!**';
    return false;
  }
}
