var avm = document.createElement('script'); avm.type = 'text/javascript'; avm.async = true;
avm.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + `cdn.avmws.com/10${merchant.id}/`;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(avm, s);