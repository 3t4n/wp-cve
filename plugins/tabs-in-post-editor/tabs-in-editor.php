<?php
/*
Plugin Name: Tabs In Editor
Plugin URI: http://aahacreative.com/our-projects/tabs-wordpress-post-editor/
Description: Write code in the WP post editor? Hate not being able to use [tab]s? Now you can.
Version: 1.1
Author: Aaron Harun
Author URI: http://aaron.md
*/


	if(strpos($_SERVER['PHP_SELF'], '-new.php')|| $_REQUEST['post'] != ''){
		add_action('admin_footer', 'add_indent');
	}

	function add_indent(){

	?>
	<script language="JavaScript" type="text/javascript"><!--
		eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(l($,D,W,1o){4 c;4 T;4 B=\'\\n\';$.1i.1s=l(){c=U;T=c[0];6(!$.v.12)c.1f(A);a c.1l(A);6($.v.19||$.v.12)B=\'\\r\\n\';H U};$.1i.1q=l(){c=U;6(!$.v.12)c.1j(\'1f\',A);a c.1j(\'1l\',A);c=k;T=k;H U};l A(e){6(e.1g==13){4 5=d().5;4 F=T.2.7(0,5).11(\'\\n\');F=(F==-1?0:F+1);4 y=T.2.7(F,5).f(/^\\t+/g);6(y!=k){e.1h();4 N=16();4 C=B;1u(4 i=0;i<y[0].b;i++)C+=\'\\t\';T.2=T.2.7(0,5)+C+T.2.7(5);z(5+C.b,5+C.b);14(N)}}a 6(e.1g==9){e.1h();4 N=16();4 3=d();6(3.5!=3.8&&T.2.Y(3.5,1)==\'\\n\')3.5++;4 y=T.2.7(3.5,3.8).f(/\\n/g);6(y!=k){4 q=T.2.7(0,3.5).11(B);4 u=(q!=-1?q:0);6(!e.1k){4 p=T.2.7(u,3.8).S(/\\n/g,\'\\n\\t\');T.2=(q==-1?\'\\t\':\'\')+T.2.7(0,u)+p+T.2.7(3.8);z(3.5+1,3.8+y.b+1)}a{4 i=(T.2.Y((q!=-1?q+B.b:0),1)==\'\\t\'?1:0);4 X=T.2.7(u,3.8).f(/\\n\\t/g,\'\\n\');6(q==-1&&T.2.Y(0,1)==\'\\t\'){T.2=T.2.Y(1);X.1n(0)}4 p=T.2.7(u,3.8).S(/\\n\\t/g,\'\\n\');T.2=T.2.7(0,u)+p+T.2.7(3.8);z(3.5-i,3.8-(X!=k?X.b:0))}}a{6(!e.1k){T.2=T.2.7(0,3.5)+\'\\t\'+T.2.7(3.5);z(3.5+1,3.5+1)}a{4 w=T.2.7(0,3.5).11(\'\\n\');4 o=(w==-1?0:w);4 h=T.2.7(o+1).1H(\'\\n\');6(h==-1)h=T.2.b;a h+=o+1;6(w==-1){4 f=T.2.7(o,h).f(/^\\t/);4 p=T.2.7(o,h).S(/^\\t/,\'\')}a{4 f=T.2.7(o,h).f(/\\n\\t/);4 p=T.2.7(o,h).S(/\\n\\t/,\'\\n\')}T.2=T.2.7(0,o)+p+T.2.7(h);6(f!=k)z(3.5-(3.5-1>w?1:0),3.8-((3.5-1>w||3.5!=3.8)?1:0))}}14(N)}}l 16(){H{O:c.O(),Q:T.Q}}l 14(15){c.O(15.O+T.Q-15.Q)}l z(5,8){6(!$.v.19){T.1z(5,8);c.1w()}a{4 G=T.2.7(0,5).f(/\\r/g);G=(G!=k?G.b:0);4 E=T.2.7(5,8).f(/\\r/g);E=(E!=k?E.b:0);4 3=T.1a();3.1E(s);3.1D(\'M\',5-G);3.V(\'M\',8-5-E);3.1B()}};l d(){6(!$.v.19){H{5:T.1A,8:T.1C}}a{4 d=D.1I.1F().1v();4 j=D.1d.1a();j.1m(T);j.1e("1r",d);4 m=D.1d.1a();m.1m(T);m.1e("P",d);4 J=1b,L=1b,I=1b;4 1c,K,18,R,Z,10;1c=K=j.x;18=R=d.x;Z=10=m.x;1G{6(!J){6(j.17("P",j)==0){J=s}a{j.V("M",-1);6(j.x==1c){K+="\\r\\n"}a{J=s}}}6(!L){6(d.17("P",d)==0){L=s}a{d.V("M",-1);6(d.x==18){R+="\\r\\n"}a{L=s}}}6(!I){6(m.17("P",m)==0){I=s}a{m.V("M",-1);6(m.x==Z){10+="\\r\\n"}a{I=s}}}}1x((!J||!L||!I));H{5:K.b,8:K.b+R.b}}}})(1t,1p,1y);',62,107,'||value|range|var|start|if|substring|end||else|length|textarea|selection_range||match||i_e||before_range|null|function|after_range||i_s|tab|index||true||start_tab|browser|i_o|text|matches|set_focus|key_handler|lb|tabs||m_e|line|m_s|return|after_finished|before_finished|untrimmed_before_text|selection_finished|character|scroll_fix|scrollTop|StartToEnd|scrollHeight|untrimmed_selection_text|replace||this|moveEnd||removed|substr|after_text|untrimmed_after_text|lastIndexOf|opera||fix_scroll|obj|fix_scroll_pre|compareEndPoints|selection_text|msie|createTextRange|false|before_text|body|setEndPoint|keydown|keyCode|preventDefault|fn|unbind|shiftKey|keypress|moveToElementText|push|undefined|document|unindent|EndToStart|indent|jQuery|for|duplicate|focus|while|window|setSelectionRange|selectionStart|select|selectionEnd|moveStart|collapse|createRange|do|indexOf|selection'.split('|'),0,{}))


		function toggle_editor_tabs(e){
			if(jQuery(e).data('disabled') != 1){
				jQuery(e).data('disabled',1);
				jQuery(e).attr('value','Enable Tabs');
				jQuery("textarea#content").unindent();
			}else{
				jQuery(e).data('disabled',0);
				jQuery(e).attr('value','Disable Tabs');
				jQuery("textarea#content").indent();
			}

		}

		jQuery(document).ready(function () {
			jQuery("#ed_toolbar").append('<input id="ed_tabs" class="ed_button" type="button" value="Disable Tabs" onclick="toggle_editor_tabs(this)";/>');
			jQuery("textarea#content").indent();

		});
	//--></script>

	<?php

}


?>
