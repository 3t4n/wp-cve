<?php 
?>
<body>
<style>
	

	.molla_modal_content{
		font-size:13px;
		display:flex;
		flex-direction:column;
		align-items:left;
		margin: 10px 10%;
	}

	.molla-modal-footer{
		display:flex;
		justify-content:right;
		/* gap:10px; */
	}

	.molla_feedback{
		margin: 8px 0;
		font-weight:500;
	}

	hr{
		margin:0;
	}
	
.molla_modal {
    display: none;
    overflow: hidden;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1050;
    -webkit-overflow-scrolling: touch;
    outline: 0;
	justify-content:center;
	align-items:center;

}



.molla_wpns_modal-content {
    position: relative;
    background-color: #ffffff;
    border: 1px solid #999999;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 6px;
    -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
    box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    outline: 0;
    margin-left: 26%;
    margin-right: 24%;
    margin-top:2%;
    width: 40%;
}

.molla_wpns_close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}




</style>

<!-- The Modal -->
<div id="molla_wpns_feedback_modal" class="molla_modal">

	<!-- Modal content -->
	<div class="molla_wpns_modal-content">
		<h3 style="margin: 3%; text-align:center;"><b>Please give us your feedback</b><span class="molla_wpns_close dashicons dashicons-no" style="cursor: pointer"></span>
		</h3>
		<hr>
		<form name="f" method="post" action="" id="molla_mmp_feedback">

			<?php wp_nonce_field("mo_lla_feedback");?>
			<input type="hidden" name="option" value="molla_feedback"/>
			<div class="molla_modal_content">
				<p style="font-weight:500">Please let us know why you are deactivating the plugin. Your feedback will help us make it better for you and other users</p>
				<div>
					<div class="molla_feedback">
						<input type="radio" id="molla_fbk_1" name="molla_feedback" value="I'll reactivate it later" required>
						<label for="molla_fbk_1">I'll reactivate it later</label>
					</div>
					<div class="molla_feedback">
						<input type="radio" id="molla_fbk_2" name="molla_feedback" value="The plugin is not working" required>
						<label for="molla_fbk_2">The plugin is not working</label>
					</div>
					<div class="molla_feedback">
						<input type="radio" id="molla_fbk_3" name="molla_feedback" value="I could not understand how to use it" required>
						<label for="molla_fbk_3">I could not understand how to use it</label>
					</div>
					<div class="molla_feedback">
						<input type="radio" id="molla_fbk_4" name="molla_feedback" value="specific_feature" required>
						<label for="molla_fbk_4">looking for specific feature</label>
					</div>
					<div class="molla_feedback">
						<input type="radio" id="molla_fbk_5" name="molla_feedback" value="It's not what I am looking for" required>
						<label for="molla_fbk_5">It's not what I am looking for</label>
					</div>
					<div class="molla_feedback">
						<input type="radio" id="molla_fbk_6" name="molla_feedback" value="other" required>
						<label for="molla_fbk_6">Other</label>
					</div>
				</div>
				<br>                        
				<div>
					<div>
						<input type="hidden" id="molla_query_mail" name="molla_query_mail" required value="<?php echo esc_attr($email); ?>" readonly="readonly"/>
						<input type="hidden" name="molla_edit" id="molla_edit" onclick="molla_editName()" value=""/>
						</label>
					</div>
					<textarea id="molla_wpns_query_feedback" name="molla_wpns_query_feedback" rows="2" style="width:100%" placeholder="Tell us!" hidden></textarea>
					<input type="checkbox" name="get_reply" value="reply">Do not reply</input>
				</div>
				<br>
				<div class="molla-modal-footer">
					<input type="submit" name="miniorange_feedback_submit" class="button button-primary button-large"style="background-color:#224fa2; padding: 1% 3% 1% 3%;color: white;cursor: pointer;" value="Submit & Deactivate"/>
					<span width="30%">&nbsp;&nbsp;</span>
					<input type="button" name="molla_skip_feedback"
						   style="background-color:#224fa2; padding: 1% 3% 1% 3%;color: white;cursor: pointer;" value="Skip" onclick="document.getElementById('molla_feedback_form_close').submit();"/>
				</div>
			</div>
				
			<br>
			   
			
		</form>
		<form name="f1" method="post" action="" id="molla_feedback_form_close">
			<?php wp_nonce_field("mo_lla_feedback");?>
			<input type="hidden" name="option" value="molla_skip_feedback"/>
		</form>
	</div>
</div>

</div>

<script>

	jQuery("[name='molla_feedback']").change((e)=>{

	if(jQuery("#molla_fbk_6").is(":checked") || jQuery("#molla_fbk_4").is(":checked")){
		jQuery("#molla_wpns_query_feedback").show();
		jQuery("#molla_wpns_query_feedback").prop("required",true);
	}
	else{
		jQuery("#molla_wpns_query_feedback").hide();
		jQuery("#molla_wpns_query_feedback").prop("required",false);
	}
	});

	jQuery("[type='radio']").show();

	jQuery('#deactivate-miniorange-limit-login-attempts').click(function () {

		var mo_lla_modal = document.getElementById('molla_wpns_feedback_modal');

		var span = document.getElementsByClassName("molla_wpns_close")[0];

		// When the user clicks the button, open the mo2f_modal
		mo_lla_modal.style.display = "flex";
		document.querySelector("#molla_wpns_query_feedback").focus();
		span.onclick = function () {
			mo_lla_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the mo2f_modal, mo2f_close it
		window.onclick = function (event) {
			if (event.target == mo_lla_modal) {
				mo_lla_modal.style.display = "none";
			}
		}
		return false;

	});
</script>

<?php ?>