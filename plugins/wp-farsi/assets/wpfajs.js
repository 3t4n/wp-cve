var c = ("۰,۱,۲,۳,۴,۵,۶,۷,۸,۹,Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec,ژانویه,فوریه,مارس,آوریل,مه,ژوئن,جولای,آگوست,سپتامبر,اکتبر,نوامبر,دسامبر").split(",");
var d = ("0,1,2,3,4,5,6,7,8,9,فرو,ارد,خرد,تیر,مرد,شهر,مهر,آبا,آذر,دی,بهم,اسف,فرو,ارد,خرد,تیر,مرد,شهر,مهر,آبا,آذر,دی,بهم,اسف").split(",");
jQuery(document).ready(function(){
jQuery("#timestampdiv,.timestamp-wrap,.inline-edit-date,.jj,.mm,.aa,.hh,.mn,.ss").html(function(a,b){
jQuery.each(c,function(a,c){b=b.replace(new RegExp(c,'g'),d[a])});return b});
jQuery("#mm option[value='"+jQuery('#hidden_mm').val()+"']").attr("selected","selected")});