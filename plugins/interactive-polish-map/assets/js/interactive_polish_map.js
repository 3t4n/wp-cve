jQuery(document).ready(function() {

var new_map='';

new_map='<span class="map"><span class="s1"></span><span class="s2"></span><span class="s3"></span><span class="s4"></span><span class="s5"></span><span class="s6"></span><span class="s7"></span><span class="s8"></span><span class="s9"></span></span>';
jQuery("#ipm_type_200 #w li a").append(new_map);

new_map='<span class="map"><span class="s1"></span><span class="s2"></span><span class="s3"></span><span class="s4"></span><span class="s5"></span><span class="s6"></span><span class="s7"></span><span class="s8"></span><span class="s9"></span><span class="s10"></span><span class="s11"></span></span>';
jQuery("#ipm_type_300 #w li a").append(new_map);

new_map='<span class="map"><span class="s1"></span><span class="s2"></span><span class="s3"></span><span class="s4"></span><span class="s5"></span><span class="s6"></span><span class="s7"></span><span class="s8"></span><span class="s9"></span><span class="s10"></span><span class="s11"></span><span class="s12"></span><span class="s13"></span></span>';
jQuery("#ipm_type_400 #w li a").append(new_map);

new_map='<span class="map"><span class="s1"></span><span class="s2"></span><span class="s3"></span><span class="s4"></span><span class="s5"></span><span class="s6"></span><span class="s7"></span><span class="s8"></span><span class="s9"></span><span class="s10"></span><span class="s11"></span><span class="s12"></span><span class="s13"></span><span class="s14"></span></span>';
jQuery("#ipm_type_500 #w li a").append(new_map);

var new_bg=jQuery("<span>");
new_bg.addClass("bg");
jQuery("#w li a").append(new_bg);

});
