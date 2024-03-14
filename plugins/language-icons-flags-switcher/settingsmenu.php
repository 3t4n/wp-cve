<?php

// Register Menu



 add_action( 'admin_menu', 'my_plugin_menu_flags' );

 add_action( 'admin_init', 'register_mysettings_language' );



function register_mysettings_language() {

 register_setting( 'myoption-group', 'option_name' );

 register_setting( 'myoption-group', 'option_lr' );

 register_setting( 'myoption-group', 'option_lm' );

 register_setting( 'myoption-group', 'option_lb' );

 register_setting( 'myoption-group', 'option_mt' );

 register_setting( 'myoption-group', 'option_mb' );

 register_setting( 'myoption-group', 'option_rt' );

 register_setting( 'myoption-group', 'option_rm' );

 register_setting( 'myoption-group', 'option_rb' );

 register_setting( 'myoption-group', 'option_float' );

 register_setting( 'myoption-group', 'option_inline' );




$number_of_options = 91;




/*  START OF SQUEEZING opts*/

for ($i=1;$i<=$number_of_options;$i++){


register_setting( 'myoption-group', 'option_'.$i );
register_setting( 'myoption-group', 'option_name'.$i );
register_setting( 'myoption-group', 'option_order'.$i );


}

/* END OF SQUEEZING opts */


 
}


function my_plugin_menu_flags() {

 add_options_page( 'Flag Icons', 'Flag Icons', 'manage_options', 'my_plugin_menu_flags', 'my_plugin_options' );

}



function my_plugin_options() {

?>





<div class="wrap"><br/>

<h2>Flag Icons </h2>



<form method ="post" action ="options.php">

<?php settings_fields( 'myoption-group' ); ?>

<?php do_settings_sections( 'myoption-group' ); ?>




<p><h3>1. <b>Enable</b> flag icons by selecting the desired position on your site:</h3></p>

<table>
<tr><td><input name="option_lr" type="checkbox" value="1" <?php checked( '1', get_option( 'option_lr' ) ); ?> />&nbsp;Top left </td><td><input name="option_mt" type="checkbox" value="1" <?php checked( '1', get_option( 'option_mt' ) ); ?> />&nbsp;Top center </td><td><input name="option_rt" type="checkbox" value="1" <?php checked( '1', get_option( 'option_rt' ) ); ?> /> Top right <br/></td></tr>
<tr><td><input name="option_lm" type="checkbox" value="1" <?php checked( '1', get_option( 'option_lm' ) ); ?> />&nbsp;Center left </td><td></td><td><input name="option_rm" type="checkbox" value="1" <?php checked( '1', get_option( 'option_rm' ) ); ?> /> Center right <br/></td></tr>
<tr><td><input name="option_lb" type="checkbox" value="1" <?php checked( '1', get_option( 'option_lb' ) ); ?> />&nbsp;Bottom left </td><td><input name="option_mb" type="checkbox" value="1" <?php checked( '1', get_option( 'option_mb' ) ); ?> />&nbsp;Bottom center </td><td><input name="option_rb" type="checkbox" value="1" <?php checked( '1', get_option( 'option_rb' ) ); ?> /> Bottom right </td></tr>
</table>
		
<p><h3>2. Make Icons Static or Float on scrolling:</h3></p>

<input name="option_float" type="checkbox" value="1" <?php checked( '1', get_option( 'option_float' ) ); ?> />  Static/Float
<br/>
		
		
<p><h3>3. Align Icons Vertically or Horizontically.:</h3></p>

<input name="option_inline" type="checkbox" value="1" <?php checked( '1', get_option( 'option_inline' ) ); ?> />  Horizontical/Vertical
<br/>


			




	




<p><h3>4. Check the desired languages,add the links to their sites & put the order to appear:</h3></p>



			<style>

			img {margin-bottom:-8px;margin-left:5px; margin-right:5px;}

			</style>


<i>&nbsp; Type the url of the site. i.e. http://www.example.com/english in the textfield</i><br/><br/>
	<div class = "marginset">


<DIV STYLE = "WIDTH:50%;float:left;">
			
			<input type="checkbox" name="option_82" value="1" <?php checked( '1', get_option( 'option_82' ) ); ?>><img src="<?php echo plugins_url( 'img/Afghanistan.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Afghanistan url address" name="option_name82" value="<?php echo get_option('option_name82'); ?>"><input type="number" value="<?php if(trim(get_option('option_order82'))==''){echo "0";}else{echo get_option('option_order82');}?>"  name="option_order82"><br/>
			
			<input type="checkbox" name="option_62" value="1" <?php checked( '1', get_option( 'option_62' ) ); ?>><img src="<?php echo plugins_url( 'img/Albania.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Albania url address" name="option_name62" value="<?php echo get_option('option_name62'); ?>"><input type="number" value="<?php if(trim(get_option('option_order62'))==''){echo "0";}else{echo get_option('option_order62');}?>"  name="option_order62"><br/>

			<input type="checkbox" name="option_52" value="1" <?php checked( '1', get_option( 'option_52' ) ); ?>><img src="<?php echo plugins_url( 'img/algeria.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Algeria url address" name="option_name52" value="<?php echo get_option('option_name52'); ?>"><input type="number" value="<?php if(trim(get_option('option_order52'))==''){echo "0";}else{echo get_option('option_order52');}?>"  name="option_order52"><br/>

			<input type="checkbox" name="option_83" value="1" <?php checked( '1', get_option( 'option_83' ) ); ?>><img src="<?php echo plugins_url( 'img/Aruba.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Aruba url address" name="option_name83" value="<?php echo get_option('option_name83'); ?>"><input type="number" value="<?php if(trim(get_option('option_order83'))==''){echo "0";}else{echo get_option('option_order83');}?>"  name="option_order83"><br/>
			
			<input type="checkbox" name="option_51" value="1" <?php checked( '1', get_option( 'option_51' ) ); ?>><img src="<?php echo plugins_url( 'img/australia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Australia url address" name="option_name51" value="<?php echo get_option('option_name51'); ?>"><input type="number" value="<?php if(trim(get_option('option_order51'))==''){echo "0";}else{echo get_option('option_order51');}?>"  name="option_order51"><br/>
			
			<input type="checkbox" name="option_28" value="1" <?php checked( '1', get_option( 'option_28' ) ); ?>><img src="<?php echo plugins_url( 'img/argentina.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Argentina url address" name="option_name28" value="<?php echo get_option('option_name28'); ?>"><input type="number" value="<?php if(trim(get_option('option_order28'))==''){echo "0";}else{echo get_option('option_order28');}?>"  name="option_order28"><br/>
			
			<input type="checkbox" name="option_63" value="1" <?php checked( '1', get_option( 'option_63' ) ); ?>><img src="<?php echo plugins_url( 'img/Azerbaijan.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Azerbaijan url address" name="option_name63" value="<?php echo get_option('option_name63'); ?>"><input type="number" value="<?php if(trim(get_option('option_order63'))==''){echo "0";}else{echo get_option('option_order63');}?>"  name="option_order63"><br/>
			
			<input type="checkbox" name="option_74" value="1" <?php checked( '1', get_option( 'option_74' ) ); ?>><img src="<?php echo plugins_url( 'img/austria.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Austria url address" name="option_name74" value="<?php echo get_option('option_name74'); ?>"><input type="number" value="<?php if(trim(get_option('option_order74'))==''){echo "0";}else{echo get_option('option_order74');}?>" name="option_order74" ><br/>
			
			<input type="checkbox" name="option_64" value="1" <?php checked( '1', get_option( 'option_64' ) ); ?>><img src="<?php echo plugins_url( 'img/Belarus.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Belarus url address" name="option_name64" value="<?php echo get_option('option_name64'); ?>"><input type="number" value="<?php if(trim(get_option('option_order64'))==''){echo "0";}else{echo get_option('option_order64');}?>"  name="option_order64" ><br/>
			
			<input type="checkbox" name="option_40" value="1" <?php checked( '1', get_option( 'option_40' ) ); ?>><img src="<?php echo plugins_url( 'img/belgium.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Belgium url address" name="option_name40" value="<?php echo get_option('option_name40'); ?>"><input type="number" value="<?php if(trim(get_option('option_order40'))==''){echo "0";}else{echo get_option('option_order40');}?>"  name="option_order40" ><br/>
			
			<input type="checkbox" name="option_44" value="1" <?php checked( '1', get_option( 'option_44' ) ); ?>><img src="<?php echo plugins_url( 'img/bosnia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Bosnia url address" name="option_name44" value="<?php echo get_option('option_name44'); ?>"><input type="number" name="option_order44" value="<?php if(trim(get_option('option_order44'))==''){echo "0";}else{echo get_option('option_order44');}?>"><br/>
			
			<input type="checkbox" name="option_27" value="1" <?php checked( '1', get_option( 'option_27' ) ); ?>><img src="<?php echo plugins_url( 'img/brazil.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Brazil url address" name="option_name27" value="<?php echo get_option('option_name27'); ?>"><input type="number" name="option_order27" value="<?php if(trim(get_option('option_order27'))==''){echo "0";}else{echo get_option('option_order27');}?>"><br/>
			
			<input type="checkbox" name="option_36" value="1" <?php checked( '1', get_option( 'option_36' ) ); ?>><img src="<?php echo plugins_url( 'img/bulgaria.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Bulgaria url address" name="option_name36" value="<?php echo get_option('option_name36'); ?>"><input type="number" value="<?php if(trim(get_option('option_order36'))==''){echo "0";}else{echo get_option('option_order36');}?>"  name="option_order36"><br/>
					
			<input type="checkbox" name="option_65" value="1" <?php checked( '1', get_option( 'option_65' ) ); ?>><img src="<?php echo plugins_url( 'img/Cambodia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Cambodia url address" name="option_name65" value="<?php echo get_option('option_name65'); ?>"><input type="number" value="<?php if(trim(get_option('option_order65'))==''){echo "0";}else{echo get_option('option_order65');}?>"  name="option_order65"><br/>
				
			<input type="checkbox" name="option_35" value="1" <?php checked( '1', get_option( 'option_35' ) ); ?>><img src="<?php echo plugins_url( 'img/canada.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Canada url address" name="option_name35" value="<?php echo get_option('option_name35'); ?>"><input type="number" value="<?php if(trim(get_option('option_order35'))==''){echo "0";}else{echo get_option('option_order35');}?>"  name="option_order35"><br/>
			
			<input type="checkbox" name="option_56" value="1" <?php checked( '1', get_option( 'option_56' ) ); ?>><img src="<?php echo plugins_url( 'img/catalan.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Catalan url address" name="option_name56" value="<?php echo get_option('option_name56'); ?>"><input type="number" value="<?php if(trim(get_option('option_order56'))==''){echo "0";}else{echo get_option('option_order56');}?>"  name="option_order56"><br/>
			
			<input type="checkbox" name="option_15" value="1" <?php checked( '1', get_option( 'option_15' ) ); ?>><img src="<?php echo plugins_url( 'img/china.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter China url address" name="option_name15" value="<?php echo get_option('option_name15'); ?>"><input type="number" value="<?php if(trim(get_option('option_order15'))==''){echo "0";}else{echo get_option('option_order15');}?>"  name="option_order15"><br/>
			
			<input type="checkbox" name="option_17" value="1" <?php checked( '1', get_option( 'option_17' ) ); ?>><img src="<?php echo plugins_url( 'img/cyprus.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Cyprus url address" name="option_name17" value="<?php echo get_option('option_name17'); ?>"><input type="number" value="<?php if(trim(get_option('option_order17'))==''){echo "0";}else{echo get_option('option_order17');}?>"  name="option_order17"><br/>
			
			<input type="checkbox" name="option_50" value="1" <?php checked( '1', get_option( 'option_50' ) ); ?>><img src="<?php echo plugins_url( 'img/croatia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Croatia url address" name="option_name50" value="<?php echo get_option('option_name50'); ?>"><input type="number" value="<?php if(trim(get_option('option_order50'))==''){echo "0";}else{echo get_option('option_order50');}?>"  name="option_order50"><br/>
			
			<input type="checkbox" name="option_18" value="1" <?php checked( '1', get_option( 'option_18' ) ); ?>><img src="<?php echo plugins_url( 'img/czech.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Czech url address" name="option_name18" value="<?php echo get_option('option_name18'); ?>"><input type="number" value="<?php if(trim(get_option('option_order18'))==''){echo "0";}else{echo get_option('option_order18');}?>"  name="option_order18"><br/>
			
			<input type="checkbox" name="option_24" value="1" <?php checked( '1', get_option( 'option_24' ) ); ?>><img src="<?php echo plugins_url( 'img/danish.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Danish url address" name="option_name24" value="<?php echo get_option('option_name24'); ?>"><input type="number" value="<?php if(trim(get_option('option_order24'))==''){echo "0";}else{echo get_option('option_order24');}?>"  name="option_order24"><br/>
			
			<input type="checkbox" name="option_42" value="1" <?php checked( '1', get_option( 'option_42' ) ); ?>><img src="<?php echo plugins_url( 'img/dominician.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Dominician address" name="option_name42" value="<?php echo get_option('option_name42'); ?>"><input type="number" value="<?php if(trim(get_option('option_order42'))==''){echo "0";}else{echo get_option('option_order42');}?>"  name="option_order42"><br/>
			
			<input type="checkbox" name="option_13" value="1" <?php checked( '1', get_option( 'option_13' ) ); ?>><img src="<?php echo plugins_url( 'img/dutch.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Dutch url address" name="option_name13" value="<?php echo get_option('option_name13'); ?>"><input type="number" value="<?php if(trim(get_option('option_order13'))==''){echo "0";}else{echo get_option('option_order13');}?>"  name="option_order13"><br/>
			
			<input type="checkbox" name="option_49" value="1" <?php checked( '1', get_option( 'option_49' ) ); ?>><img src="<?php echo plugins_url( 'img/egypt.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Egypt url address" name="option_name49" value="<?php echo get_option('option_name49'); ?>"><input type="number" value="<?php if(trim(get_option('option_order49'))==''){echo "0";}else{echo get_option('option_order49');}?>"  name="option_order49"><br/>
							
			<input type="checkbox" name="option_1" value="1" <?php checked( '1', get_option( 'option_1' ) ); ?>><img src="<?php echo plugins_url( 'img/english.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter English url address" name="option_name1" value="<?php echo get_option('option_name1'); ?>"><input type="number" value="<?php if(trim(get_option('option_order1'))==''){echo "0";}else{echo get_option('option_order1');}?>"  name="option_order1"><br/>
	
		    <input type="checkbox" name="option_59" value="1" <?php checked( '1', get_option( 'option_59' ) ); ?>><img src="<?php echo plugins_url( 'img/esperado.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Esperado url address" name="option_name59" value="<?php echo get_option('option_name59'); ?>"><input type="number" value="<?php if(trim(get_option('option_order59'))==''){echo "0";}else{echo get_option('option_order59');}?>"  name="option_order59"><br/>
			
			<input type="checkbox" name="option_37" value="1" <?php checked( '1', get_option( 'option_37' ) ); ?>><img src="<?php echo plugins_url( 'img/estonia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Estonia url address" name="option_name37" value="<?php echo get_option('option_name37'); ?>"><input type="number" value="<?php if(trim(get_option('option_order37'))==''){echo "0";}else{echo get_option('option_order37');}?>"  name="option_order37"><br/>
			
			<input type="checkbox" name="option_66" value="1" <?php checked( '1', get_option( 'option_66' ) ); ?>><img src="<?php echo plugins_url( 'img/Ethiopia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Ethiopia url address" name="option_name66" value="<?php echo get_option('option_name66'); ?>"><input type="number" value="<?php if(trim(get_option('option_order66'))==''){echo "0";}else{echo get_option('option_order66');}?>"  name="option_order66"><br/>
					
			<input type="checkbox" name="option_55" value="1" <?php checked( '1', get_option( 'option_55' ) ); ?>><img src="<?php echo plugins_url( 'img/eu.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter EU url address" name="option_name55" value="<?php echo get_option('option_name55'); ?>"><input type="number" value="<?php if(trim(get_option('option_order55'))==''){echo "0";}else{echo get_option('option_order55');}?>"  name="option_order55"><br/>
	
			<input type="checkbox" name="option_25" value="1" <?php checked( '1', get_option( 'option_25' ) ); ?>><img src="<?php echo plugins_url( 'img/finland.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Finland url address" name="option_name25" value="<?php echo get_option('option_name25'); ?>"><input type="number" value="<?php if(trim(get_option('option_order25'))==''){echo "0";}else{echo get_option('option_order25');}?>"  name="option_order25"><br/>
	
			<input type="checkbox" name="option_11" value="1" <?php checked( '1', get_option( 'option_11' ) ); ?>><img src="<?php echo plugins_url( 'img/french.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter French url address" name="option_name11" value="<?php echo get_option('option_name11'); ?>"><input type="number" value="<?php if(trim(get_option('option_order11'))==''){echo "0";}else{echo get_option('option_order11');}?>"  name="option_order11"><br/>
	
			<input type="checkbox" name="option_84" value="1" <?php checked( '1', get_option( 'option_84' ) ); ?>><img src="<?php echo plugins_url( 'img/Georgia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Georgia url address" name="option_name84" value="<?php echo get_option('option_name84'); ?>"><input type="number" value="<?php if(trim(get_option('option_order84'))==''){echo "0";}else{echo get_option('option_order84');}?>"  name="option_order84"><br/>
	
		    <input type="checkbox" name="option_3" value="1" <?php checked( '1', get_option( 'option_3' ) ); ?>><img src="<?php echo plugins_url( 'img/germany.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Germany url address" name="option_name3" value="<?php echo get_option('option_name3'); ?>"><input type="number" value="<?php if(trim(get_option('option_order3'))==''){echo "0";}else{echo get_option('option_order3');}?>"  name="option_order3"><br/>
	
			<input type="checkbox" name="option_2" value="1" <?php checked( '1', get_option( 'option_2' ) ); ?>><img src="<?php echo plugins_url( 'img/greece.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Greece url address" name="option_name2" value="<?php echo get_option('option_name2'); ?>"><input type="number" value="<?php if(trim(get_option('option_order2'))==''){echo "0";}else{echo get_option('option_order2');}?>"  name="option_order2"><br/>

			<input type="checkbox" name="option_85" value="1" <?php checked( '1', get_option( 'option_85' ) ); ?>><img src="<?php echo plugins_url( 'img/Haiti.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Haiti url address" name="option_name85" value="<?php echo get_option('option_name85'); ?>"><input type="number" value="<?php if(trim(get_option('option_order85'))==''){echo "0";}else{echo get_option('option_order85');}?>"  name="option_order85"><br/>
			
			<input type="checkbox" name="option_41" value="1" <?php checked( '1', get_option( 'option_41' ) ); ?>><img src="<?php echo plugins_url( 'img/hongkong.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Hongkong url address" name="option_name41" value="<?php echo get_option('option_name41'); ?>"><input type="number" value="<?php if(trim(get_option('option_order41'))==''){echo "0";}else{echo get_option('option_order41');}?>"  name="option_order41"><br/>
			
			<input type="checkbox" name="option_33" value="1" <?php checked( '1', get_option( 'option_33' ) ); ?>><img src="<?php echo plugins_url( 'img/hungary.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Hungary url address" name="option_name33" value="<?php echo get_option('option_name33'); ?>"><input type="number" value="<?php if(trim(get_option('option_order33'))==''){echo "0";}else{echo get_option('option_order33');}?>"  name="option_order33"><br/>

			<input type="checkbox" name="option_46" value="1" <?php checked( '1', get_option( 'option_46' ) ); ?>><img src="<?php echo plugins_url( 'img/iceland.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Iceland url address" name="option_name46" value="<?php echo get_option('option_name46'); ?>"><input type="number" value="<?php if(trim(get_option('option_order46'))==''){echo "0";}else{echo get_option('option_order46');}?>"  name="option_order46"><br/>
			
			<input type="checkbox" name="option_29" value="1" <?php checked( '1', get_option( 'option_29' ) ); ?>><img src="<?php echo plugins_url( 'img/india.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter India url address" name="option_name29" value="<?php echo get_option('option_name29'); ?>"><input type="number" value="<?php if(trim(get_option('option_order29'))==''){echo "0";}else{echo get_option('option_order29');}?>"  name="option_order29"><br/>
			
			<input type="checkbox" name="option_53" value="1" <?php checked( '1', get_option( 'option_53' ) ); ?>><img src="<?php echo plugins_url( 'img/indonesia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Indonesia address" name="option_name53" value="<?php echo get_option('option_name53'); ?>"><input type="number" value="<?php if(trim(get_option('option_order53'))==''){echo "0";}else{echo get_option('option_order53');}?>"  name="option_order53"><br/>
				
			<input type="checkbox" name="option_58" value="1" <?php checked( '1', get_option( 'option_58' ) ); ?>><img src="<?php echo plugins_url( 'img/iraq.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Iraq url address" name="option_name58" value="<?php echo get_option('option_name58'); ?>"><input type="number" value="<?php if(trim(get_option('option_order58'))==''){echo "0";}else{echo get_option('option_order58');}?>"  name="option_order58"><br/>
			
			<input type="checkbox" name="option_57" value="1" <?php checked( '1', get_option( 'option_57' ) ); ?>><img src="<?php echo plugins_url( 'img/iran.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Iran url address" name="option_name57" value="<?php echo get_option('option_name57'); ?>"><input type="number" value="<?php if(trim(get_option('option_order57'))==''){echo "0";}else{echo get_option('option_order57');}?>"  name="option_order53"><br/>			

			<input type="checkbox" name="option_32" value="1" <?php checked( '1', get_option( 'option_32' ) ); ?>><img src="<?php echo plugins_url( 'img/ireland.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Ireland url address" name="option_name32" value="<?php echo get_option('option_name32'); ?>"><input type="number" value="<?php if(trim(get_option('option_order32'))==''){echo "0";}else{echo get_option('option_order32');}?>"  name="option_order32"><br/>

			<input type="checkbox" name="option_8" value="1" <?php checked( '1', get_option( 'option_8' ) ); ?>><img src="<?php echo plugins_url( 'img/israel.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Israel url address" name="option_name8" value="<?php echo get_option('option_name8'); ?>"><input type="number" value="<?php if(trim(get_option('option_order8'))==''){echo "0";}else{echo get_option('option_order8');}?>"  name="option_order8"><br/>
						
			<input type="checkbox" name="option_12" value="1" <?php checked( '1', get_option( 'option_12' ) ); ?>><img src="<?php echo plugins_url( 'img/italy.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Italy url address" name="option_name12" value="<?php echo get_option('option_name12'); ?>"><input type="number" value="<?php if(trim(get_option('option_order12'))==''){echo "0";}else{echo get_option('option_order12');}?>"  name="option_order12"><br/>


								
</div>
<div style = "float:right;width:50%;">

			<input type="checkbox" name="option_47" value="1" <?php checked( '1', get_option( 'option_47' ) ); ?>><img src="<?php echo plugins_url( 'img/klingon.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Klingon url address" name="option_name47" value="<?php echo get_option('option_name47'); ?>"><input type="number" value="<?php if(trim(get_option('option_order47'))==''){echo "0";}else{echo get_option('option_order47');}?>"  name="option_order47"><br/>
			
			<input type="checkbox" name="option_7" value="1" <?php checked( '1', get_option( 'option_7' ) ); ?>><img src="<?php echo plugins_url( 'img/japan.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Japan url address" name="option_name7" value="<?php echo get_option('option_name7'); ?>"><input type="number" value="<?php if(trim(get_option('option_order7'))==''){echo "0";}else{echo get_option('option_order7');}?>"  name="option_order7"><br/>			

			<input type="checkbox" name="option_14" value="1" <?php checked( '1', get_option( 'option_14' ) ); ?>><img src="<?php echo plugins_url( 'img/korea.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Korea url address" name="option_name14" value="<?php echo get_option('option_name14'); ?>"><input type="number" value="<?php if(trim(get_option('option_order14'))==''){echo "0";}else{echo get_option('option_order14');}?>"  name="option_order14"><br/>
			
			<input type="checkbox" name="option_86" value="1" <?php checked( '1', get_option( 'option_86' ) ); ?>><img src="<?php echo plugins_url( 'img/Kuwait.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Kuwait url address" name="option_name86" value="<?php echo get_option('option_name86'); ?>"><input type="number" value="<?php if(trim(get_option('option_order86'))==''){echo "0";}else{echo get_option('option_order86');}?>"  name="option_order86"><br/>

			<input type="checkbox" name="option_38" value="1" <?php checked( '1', get_option( 'option_38' ) ); ?>><img src="<?php echo plugins_url( 'img/latvia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Latvia url address" name="option_name38" value="<?php echo get_option('option_name38'); ?>"><input type="number" value="<?php if(trim(get_option('option_order38'))==''){echo "0";}else{echo get_option('option_order38');}?>"  name="option_order38"><br/>
			
			<input type="checkbox" name="option_67" value="1" <?php checked( '1', get_option( 'option_67' ) ); ?>><img src="<?php echo plugins_url( 'img/Libya.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter a Libya address" name="option_name67" value="<?php echo get_option('option_name67'); ?>"><input type="number" value="<?php if(trim(get_option('option_order67'))==''){echo "0";}else{echo get_option('option_order67');}?>"  name="option_order67"><br/>
	
			<input type="checkbox" name="option_68" value="1" <?php checked( '1', get_option( 'option_68' ) ); ?>><img src="<?php echo plugins_url( 'img/Luxembourg.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Luxembourg url address" name="option_name68" value="<?php echo get_option('option_name68'); ?>"><input type="number" value="<?php if(trim(get_option('option_order68'))==''){echo "0";}else{echo get_option('option_order68');}?>"  name="option_order68"><br/>
	
			<input type="checkbox" name="option_39" value="1" <?php checked( '1', get_option( 'option_39' ) ); ?>><img src="<?php echo plugins_url( 'img/lithuania.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Lithuania url address" name="option_name39" value="<?php echo get_option('option_name39'); ?>"><input type="number" value="<?php if(trim(get_option('option_order39'))==''){echo "0";}else{echo get_option('option_order39');}?>"  name="option_order39"><br/>
						
			<input type="checkbox" name="option_80" value="1" <?php checked( '1', get_option( 'option_80' ) ); ?>><img src="<?php echo plugins_url( 'img/Myanmar.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Myanmar url address" name="option_name80" value="<?php echo get_option('option_name80'); ?>"><input type="number" value="<?php if(trim(get_option('option_order80'))==''){echo "0";}else{echo get_option('option_order80');}?>"  name="option_order80"><br/>

			<input type="checkbox" name="option_21" value="1" <?php checked( '1', get_option( 'option_21' ) ); ?>><img src="<?php echo plugins_url( 'img/malaysia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Malaysia url address" name="option_name21" value="<?php echo get_option('option_name21'); ?>"><input type="number" value="<?php if(trim(get_option('option_order21'))==''){echo "0";}else{echo get_option('option_order21');}?>"  name="option_order21"><br/>
			
			<input type="checkbox" name="option_87" value="1" <?php checked( '1', get_option( 'option_87' ) ); ?>><img src="<?php echo plugins_url( 'img/Malta.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Malta url address" name="option_name87" value="<?php echo get_option('option_name87'); ?>"><input type="number" value="<?php if(trim(get_option('option_order87'))==''){echo "0";}else{echo get_option('option_order87');}?>"  name="option_order87"><br/>
			
			<input type="checkbox" name="option_69" value="1" <?php checked( '1', get_option( 'option_69' ) ); ?>><img src="<?php echo plugins_url( 'img/Mexico.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Mexico url address" name="option_name69" value="<?php echo get_option('option_name69'); ?>"><input type="number" value="<?php if(trim(get_option('option_order69'))==''){echo "0";}else{echo get_option('option_order69');}?>"  name="option_order69"><br/>
			
			<input type="checkbox" name="option_43" value="1" <?php checked( '1', get_option( 'option_43' ) ); ?>><img src="<?php echo plugins_url( 'img/moldova.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Moldova url address" name="option_name43" value="<?php echo get_option('option_name43'); ?>"><input type="number" value="<?php if(trim(get_option('option_order43'))==''){echo "0";}else{echo get_option('option_order43');}?>"  name="option_order43"><br/>
			
			<input type="checkbox" name="option_78" value="1" <?php checked( '1', get_option( 'option_78' ) ); ?>><img src="<?php echo plugins_url( 'img/mongolia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Mongolia url address" name="option_name78" value="<?php echo get_option('option_name78'); ?>"><input type="number" value="<?php if(trim(get_option('option_order78'))==''){echo "0";}else{echo get_option('option_order78');}?>"  name="option_order78"><br/>
			
			<input type="checkbox" name="option_70" value="1" <?php checked( '1', get_option( 'option_70' ) ); ?>><img src="<?php echo plugins_url( 'img/Morocco.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Morocco url address" name="option_name70" value="<?php echo get_option('option_name70'); ?>"><input type="number" value="<?php if(trim(get_option('option_order70'))==''){echo "0";}else{echo get_option('option_order70');}?>"  name="option_order70"><br/>

			<input type="checkbox" name="option_81" value="1" <?php checked( '1', get_option( 'option_81' ) ); ?>><img src="<?php echo plugins_url( 'img/nepal.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Nepal url address" name="option_name81" value="<?php echo get_option('option_name81'); ?>"><input type="number" value="<?php if(trim(get_option('option_order81'))==''){echo "0";}else{echo get_option('option_order81');}?>"  name="option_order81"><br/>
			
			<input type="checkbox" name="option_79" value="1" <?php checked( '1', get_option( 'option_79' ) ); ?>><img src="<?php echo plugins_url( 'img/New-Zealand.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter NewZealand url address" name="option_name79" value="<?php echo get_option('option_name79'); ?>"><input type="number" value="<?php if(trim(get_option('option_order79'))==''){echo "0";}else{echo get_option('option_order79');}?>"  name="option_order79"><br/>
			
			<input type="checkbox" name="option_26" value="1" <?php checked( '1', get_option( 'option_26' ) ); ?>><img src="<?php echo plugins_url( 'img/norway.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Norway url address" name="option_name26" value="<?php echo get_option('option_name26'); ?>"><input type="number" value="<?php if(trim(get_option('option_order26'))==''){echo "0";}else{echo get_option('option_order26');}?>"  name="option_order26"><br/>
			
			<input type="checkbox" name="option_76" value="1" <?php checked( '1', get_option( 'option_76' ) ); ?>><img src="<?php echo plugins_url( 'img/pakistan.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Pakistan url address" name="option_name76" value="<?php echo get_option('option_name76'); ?>"><input type="number" value="<?php if(trim(get_option('option_order76'))==''){echo "0";}else{echo get_option('option_order76');}?>" name="option_order76" ><br/>
			
			<input type="checkbox" name="option_71" value="1" <?php checked( '1', get_option( 'option_71' ) ); ?>><img src="<?php echo plugins_url( 'img/Palestine.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Palestine url address" name="option_name71" value="<?php echo get_option('option_name71'); ?>"><input type="number" value="<?php if(trim(get_option('option_order71'))==''){echo "0";}else{echo get_option('option_order71');}?>"  name="option_order71"><br/>

			<input type="checkbox" name="option_88" value="1" <?php checked( '1', get_option( 'option_88' ) ); ?>><img src="<?php echo plugins_url( 'img/Paraguay.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Paraguay url address" name="option_name88" value="<?php echo get_option('option_name88'); ?>"><input type="number" value="<?php if(trim(get_option('option_order88'))==''){echo "0";}else{echo get_option('option_order88');}?>"  name="option_order88"><br/>
			
			<input type="checkbox" name="option_89" value="1" <?php checked( '1', get_option( 'option_89' ) ); ?>><img src="<?php echo plugins_url( 'img/Peru.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Peru url address" name="option_name89" value="<?php echo get_option('option_name89'); ?>"><input type="number" value="<?php if(trim(get_option('option_order89'))==''){echo "0";}else{echo get_option('option_order89');}?>"  name="option_order89"><br/>
			
			<input type="checkbox" name="option_16" value="1" <?php checked( '1', get_option( 'option_16' ) ); ?>><img src="<?php echo plugins_url( 'img/poland.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Poland url address" name="option_name16" value="<?php echo get_option('option_name16'); ?>"><input type="number" value="<?php if(trim(get_option('option_order16'))==''){echo "0";}else{echo get_option('option_order16');}?>"  name="option_order16"><br/>
		
			<input type="checkbox" name="option_5" value="1" <?php checked( '1', get_option( 'option_5' ) ); ?>><img src="<?php echo plugins_url( 'img/portugal.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Portugal url address" name="option_name5" value="<?php echo get_option('option_name5'); ?>"><input type="number" value="<?php if(trim(get_option('option_order5'))==''){echo "0";}else{echo get_option('option_order5');}?>"  name="option_order5"><br/>

			<input type="checkbox" name="option_30" value="1" <?php checked( '1', get_option( 'option_30' ) ); ?>><img src="<?php echo plugins_url( 'img/romania.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Romania url address" name="option_name30" value="<?php echo get_option('option_name30'); ?>"><input type="number" value="<?php if(trim(get_option('option_order30'))==''){echo "0";}else{echo get_option('option_order30');}?>"  name="option_order30"><br/>
			
			<input type="checkbox" name="option_19" value="1" <?php checked( '1', get_option( 'option_19' ) ); ?>><img src="<?php echo plugins_url( 'img/russia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Russia url address" name="option_name19" value="<?php echo get_option('option_name19'); ?>"><input type="number" value="<?php if(trim(get_option('option_order19'))==''){echo "0";}else{echo get_option('option_order19');}?>"  name="option_order19"><br/>
			
			<input type="checkbox" name="option_9" value="1" <?php checked( '1', get_option( 'option_9' ) ); ?>><img src="<?php echo plugins_url( 'img/arabic.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Arabic url address" name="option_name9" value="<?php echo get_option('option_name9'); ?>"><input type="number" value="<?php if(trim(get_option('option_order9'))==''){echo "0";}else{echo get_option('option_order9');}?>"  value="<?php if(trim(get_option('option_order9'))==''){echo "0";}else{echo get_option('option_order27');}?>" name="option_order9"><br/>
			
			<input type="checkbox" name="option_48" value="1" <?php checked( '1', get_option( 'option_48' ) ); ?>><img src="<?php echo plugins_url( 'img/serbia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Serbia url address" name="option_name48" value="<?php echo get_option('option_name48'); ?>"><input type="number" value="<?php if(trim(get_option('option_order48'))==''){echo "0";}else{echo get_option('option_order48');}?>"  name="option_order48"><br/>
			
			<input type="checkbox" name="option_22" value="1" <?php checked( '1', get_option( 'option_22' ) ); ?>><img src="<?php echo plugins_url( 'img/singapore.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Singapore url address" name="option_name22" value="<?php echo get_option('option_name22'); ?>"><input type="number" value="<?php if(trim(get_option('option_order22'))==''){echo "0";}else{echo get_option('option_order22');}?>" value="<?php if(trim(get_option('option_order27'))==''){echo "0";}else{echo get_option('option_order27');}?>" name="option_order22"><br/>
			
			<input type="checkbox" name="option_34" value="1" <?php checked( '1', get_option( 'option_34' ) ); ?>><img src="<?php echo plugins_url( 'img/slovakia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Slovakia url address" name="option_name34" value="<?php echo get_option('option_name34'); ?>"><input type="number" value="<?php if(trim(get_option('option_order34'))==''){echo "0";}else{echo get_option('option_order34');}?>"  name="option_order34"><br/>

			<input type="checkbox" name="option_45" value="1" <?php checked( '1', get_option( 'option_45' ) ); ?>><img src="<?php echo plugins_url( 'img/slovenia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Slovenia url address" name="option_name45" value="<?php echo get_option('option_name34'); ?>"><input type="number" value="<?php if(trim(get_option('option_order45'))==''){echo "0";}else{echo get_option('option_order45');}?>"  name="option_order45"><br/>
			
			<input type="checkbox" name="option_6" value="1" <?php checked( '1', get_option( 'option_6' ) ); ?>><img src="<?php echo plugins_url( 'img/spain.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Spain url address" name="option_name6" value="<?php echo get_option('option_name6'); ?>"><input type="number" value="<?php if(trim(get_option('option_order6'))==''){echo "0";}else{echo get_option('option_order6');}?>"  name="option_order6"><br/>

			<input type="checkbox" name="option_90" value="1" <?php checked( '1', get_option( 'option_90' ) ); ?>><img src="<?php echo plugins_url( 'img/South-Africa.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter South-Africa url address" name="option_name90" value="<?php echo get_option('option_name90'); ?>"><input type="number" value="<?php if(trim(get_option('option_order90'))==''){echo "0";}else{echo get_option('option_order90');}?>"  name="option_order90"><br/>
			
			<input type="checkbox" name="option_10" value="1" <?php checked( '1', get_option( 'option_10' ) ); ?>><img src="<?php echo plugins_url( 'img/swedish.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Swedish url address" name="option_name10" value="<?php echo get_option('option_name10'); ?>"><input type="number" value="<?php if(trim(get_option('option_order10'))==''){echo "0";}else{echo get_option('option_order10');}?>"  name="option_order10"><br/>
			
			<input type="checkbox" name="option_61" value="1" <?php checked( '1', get_option( 'option_61' ) ); ?>><img src="<?php echo plugins_url( 'img/switzerland.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Switzerland url address" name="option_name61" value="<?php echo get_option('option_name61'); ?>"><input type="number" value="<?php if(trim(get_option('option_order61'))==''){echo "0";}else{echo get_option('option_order61');}?>"  name="option_order61"><br/>
			
			<input type="checkbox" name="option_72" value="1" <?php checked( '1', get_option( 'option_72' ) ); ?>><img src="<?php echo plugins_url( 'img/Taiwan.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Taiwan url address" name="option_name72" value="<?php echo get_option('option_name72'); ?>"><input type="number" value="<?php if(trim(get_option('option_order72'))==''){echo "0";}else{echo get_option('option_order72');}?>"  name="option_order72" ><br/>
			
			<input type="checkbox" name="option_23" value="1" <?php checked( '1', get_option( 'option_23' ) ); ?>><img src="<?php echo plugins_url( 'img/thai.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Thailand url address" name="option_name23" value="<?php echo get_option('option_name23'); ?>"><input type="number" value="<?php if(trim(get_option('option_order23'))==''){echo "0";}else{echo get_option('option_order23');}?>"  name="option_order23" ><br/>									
										
		    <input type="checkbox" name="option_75" value="1" <?php checked( '1', get_option( 'option_75' ) ); ?>><img src="<?php echo plugins_url( 'img/tunisia.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Tunisia url address" name="option_name75" value="<?php echo get_option('option_name75'); ?>"><input type="number" value="<?php if(trim(get_option('option_order75'))==''){echo "0";}else{echo get_option('option_order75');}?>" name="option_order75" ><br/>
			
			<input type="checkbox" name="option_4" value="1" <?php checked( '1', get_option( 'option_4' ) ); ?>><img src="<?php echo plugins_url( 'img/turkey.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Turkey url address" name="option_name4" value="<?php echo get_option('option_name4'); ?>"><input type="number" value="<?php if(trim(get_option('option_order4'))==''){echo "0";}else{echo get_option('option_order4');}?>" name="option_order4" ><br/>
			
			<input type="checkbox" name="option_54" value="1" <?php checked( '1', get_option( 'option_54' ) ); ?>><img src="<?php echo plugins_url( 'img/ua.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter UA url address" name="option_name54" value="<?php echo get_option('option_name54'); ?>"><input type="number" value="<?php if(trim(get_option('option_order54'))==''){echo "0";}else{echo get_option('option_order54');}?>"  name="option_order54"><br/>	
	
			<input type="checkbox" name="option_60" value="1" <?php checked( '1', get_option( 'option_60' ) ); ?>><img src="<?php echo plugins_url( 'img/ukraine.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Ukraine url address" name="option_name60" value="<?php echo get_option('option_name60'); ?>"><input type="number" value="<?php if(trim(get_option('option_order60'))==''){echo "0";}else{echo get_option('option_order60');}?>" name="option_order60" ><br/>
			
			<input type="checkbox" name="option_20" value="1" <?php checked( '1', get_option( 'option_20' ) ); ?>><img src="<?php echo plugins_url( 'img/usa.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter USA url address" name="option_name20" value="<?php echo get_option('option_name20'); ?>"><input type="number" value="<?php if(trim(get_option('option_order20'))==''){echo "0";}else{echo get_option('option_order20');}?>" name="option_order20" ><br/>	
			
			<input type="checkbox" name="option_91" value="1" <?php checked( '1', get_option( 'option_91' ) ); ?>><img src="<?php echo plugins_url( 'img/Uzbekistan.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Uzbekistan url address" name="option_name91" value="<?php echo get_option('option_name91'); ?>"><input type="number" value="<?php if(trim(get_option('option_order91'))==''){echo "0";}else{echo get_option('option_order91');}?>"  name="option_order91"><br/>
			
			<input type="checkbox" name="option_73" value="1" <?php checked( '1', get_option( 'option_73' ) ); ?>><img src="<?php echo plugins_url( 'img/Vietnam.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Vietnam url address" name="option_name73" value="<?php echo get_option('option_name73'); ?>"><input type="number" value="<?php if(trim(get_option('option_order73'))==''){echo "0";}else{echo get_option('option_order73');}?>"  name="option_order73"><br/>
					
			<input type="checkbox" name="option_31" value="1" <?php checked( '1', get_option( 'option_31' ) ); ?>><img src="<?php echo plugins_url( 'img/wales.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter Wales url address" name="option_name31" value="<?php echo get_option('option_name31'); ?>"><input type="number" value="<?php if(trim(get_option('option_order31'))==''){echo "0";}else{echo get_option('option_order31');}?>" name="option_order31" ><br/>
			


			</div>
			<p>&nbsp;</p>
			<h2>Use Google Translation</h2>
			<input type="checkbox" name="option_77" value="1" <?php checked( '1', get_option( 'option_77' ) ); ?>><img src="<?php echo plugins_url( 'img/unknown.png' , __FILE__ ); ?>" ><input type="url" placeholder="Enter a valid url address" name="option_name77" value="https://translate.google.com/translate?hl=en&sl=auto&tl=en&u=<?php echo site_url(); ?>"  disable><input type="number" value="<?php if(trim(get_option('option_order77'))==''){echo "0";}else{echo get_option('option_order77');}?>" name="option_order77" ><br/>
<p><b>Note:</b> This will take you to google translation site</p>
			
</div>
<br/>
	<p><h3>5. (Optional) Shortcode for manual addition anywhere on your site:</h3></p>	
	 <p><code> [flagicons] </code>  or  <code> &lt;?php echo do_shortcode(&#39;[flagicons]&#39;); ?&gt;</code></p><br/> 

	</div>


<div style = "clear:both;"></div>
<div style = "margin-left:100px;">
<?php submit_button(); ?></div>
</center>
<?php get_option('option_name'); ?>

</form>
<div style = "float:left;padding:0 10px;">

<p><a style = "text-align:right;" href = "http://www.webcraft.gr/muli" target = "blank"> My language is not here! </a></p>
<p><a style = "text-align:right;" href = "http://www.webcraft.gr/muli" target = "blank"> Feedback & contact </a></p>


</div>
</div>

<?php }