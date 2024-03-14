 // CREATE A PUBNUB OBJECT

  function getAgileChannelName(){
return document.location.hostname.replace(/\./g, '')+"_CMS";
}

function openengageRegisterPage(source) {
if(!source)
source = "wordpress";
var windowURL = "https://app.engagebay.com/signup?origin_from=" + source + "&domain_channel=" + getAgileChannelName();
var newwindow = window.open(windowURL,'name','height=600,width=400');
if (window.focus)
{
newwindow.focus();
}
}

jQuery('#loginForm').submit(function() {
   jQuery('#gif').show(); 
   return true;
 });
