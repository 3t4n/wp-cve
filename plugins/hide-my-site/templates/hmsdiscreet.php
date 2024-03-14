<?php
//to override this template, create a file named hmsdiscreet.php within your active theme folder. you should probably copy and paste this file's code as a starting point.
?>
<?php if ($mobile_friendly) : ?>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<?php endif ?>
					<style>
						html, body {

							text-align: center;
							height: 100%;
						}
						body { background: #1caff6; font-family:Arial;}
						#form_wrap { background-image: none;background-color: #1493d1;display: block;
margin: 0px auto;
height: 68px;
width: 275px;
position: relative;
top: 0px;
margin-top: 0px;margin-right: 100px;}
						#form_wrap input[type=text], .enter_password {background-image: none; background: white;position: absolute;
top: 13px;
left: 25px;
border: 0px;
width: 150px;
padding-left: 11px;
font-size: 15px;
line-height: 15px;
padding-top: 9px;
height: 30px;
color: rgb(85, 86, 90);
opacity: .9;
padding-right: 10px;}

						#form_wrap input:active, #form_wrap input:focus {outline:0;opacity:1;}
						#form_wrap button {background: none;background-color: #1caff6; width: 56px;
border: 0px;
height: 25px;
position: absolute;
top: 22px;
left: auto;
right:12px;
cursor: pointer;
opacity: 1;color:white;font-weight:bold;
}
						#form_wrap button:hover {opacity:.9}
						#form_wrap button:focus, #form_wrap button:active { outline:0;}
						#form_wrap button:active { opacity:1;}
						#the_hint_wrap {
position: absolute;background: #1493d1;
top: 68px;
color: white;
left: 0px;
width: 225px;
font-weight: normal;
font-family: Arial;
text-align: left;
font-size: 11px;
overflow: hidden;
max-height: 25px;
padding:0px 25px;
padding-bottom:13px;
}
						#the_hint_wrap div {display:inline-block;vertical-align: top;}
						#the_hint_title {padding-right:5px;}
						#the_hint {width: 142px;}
						<?php if ($mobile_friendly) : ?>
							@media only screen and (max-width: 767px) {
							#form_wrap {margin-right:auto;}
						}
						<?php endif ?>						
					</style>
						<!--[if IE]>
						<style>
						#form_wrap input[type=text], .enter_password {
						  line-height:30px;    /* adjust value */
						}
						</style>
						<![endif]-->
					<body>
                    	<?php echo $messagehtml ?>
						<div id='form_wrap'>
							<form method=post>
								<input type=password name='hwsp_motech' placeholder='Password' class='enter_password'>
								<?php echo $hinthtml ?>
								<button type=submit>Log In</button>
							</form>
						</div>
					</body>