<?php 
	$savedmeta = get_option('la_words_rotator');
	?>
	<div class="rotaing-words-wrap" id="compactviewer"> 
		<!-- <div class="se-pre-con"></div> -->
		<div class="se-saved-con"></div>
		<div class="overlay-message">
		    <p>Changes Saved..!</p>
		</div>
		<div class="plugin-title">
			<h2 class="wdo-main-heading">CSS3 Rotating Words - WordPress Plugin <img src="<?php echo plugin_dir_url( __FILE__ ); ?>../images/rotating-icon.png"></h2>
			<h3 class="wdo-sub-heading">Welcome! You are going to create something awesome with this plugin. Add multiple words in sentence with animations which changes after intervals.</h3>
		</div>
		 
		<div id="accordion">
		<?php if (isset($savedmeta['rotwords'])) {?>
			<?php foreach ($savedmeta['rotwords'] as $key => $data) {?>

			<h3 class="tab-head"> 
				<?php echo ( $data['rw_group_name'] != "" ) ? $data['rw_group_name'] : 'Rotating Words' ; ?>
				<button title="Get Shortcode For This Group of Words" class="button-primary fullshortcode" id="<?php echo $data['counter']; ?>"><span title="Get Shortcode" class="dashicons dashicons-shortcode"></span>   <?php _e( 'Get Shortcode', 'la-wordsrotator' ); ?></button>
				<button class="button btnadd"><span title="Add New Group of Words" class="dashicons dashicons-plus-alt"></span></button>&nbsp;
				<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete This Group of Words"></span></button>
			</h3>
			<div class="tab-content">
				<table class="form-table">
					<tr>
						<td style="width:30%">
							<strong><?php _e( 'Group Name', 'la-wordsrotator' ); ?></strong> 
						</td>
						<td style="width:30%"> 
							
							<input class="rw-group-name widefat form-control" type="text" value="<?php echo $data['rw_group_name']; ?>">	
						</td>

						<td style="width:40%">
							<p class="description"><?php _e( 'Name this group. This would be for your reference which would be shown on red tab. Default: Rotating Words', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>

					<tr>
						<td style="width:30%">
							<strong><?php _e( 'Give Start Sentence', 'la-wordsrotator' ); ?></strong> 
						</td>
						<td class="get-terms" style="width:30%">
							
							<textarea cols="10" rows="5" class="static-sen form-control" placeholder="Sentence before rotating words"><?php echo stripslashes($data['stat_sent']); ?></textarea>		
						</td>

						<td style="width:40%">
							<p class="description"><?php _e( 'Write starting sentence.Leave empty if have no starting words', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>

	  				<tr>
	  					<td> <strong> <?php _e( 'Add Words(these will be rotating)', 'la-wordsrotator' ); ?> </strong></td>
	  					<td>
	  						<textarea cols="10" rows="5" class="rotating-words form-control" placeholder="first,second,third"><?php echo stripslashes($data['rot_words']); ?></textarea> 
	  					</td>
	  					<td>
	  						<p class="description"><?php _e( 'Comma separated list of words', 'la-wordsrotator' ); ?>.</p>
	  					</td>
	  				</tr>
					<tr>
						<td>
							<strong><?php _e( 'Give Ending Sentence', 'la-wordsrotator' ); ?></strong> 
						</td>
						<td class="get-terms">
							
							<textarea cols="10" rows="5" class="end-sen form-control" placeholder="Sentence after rotating words"><?php echo stripslashes($data['end_sent']); ?></textarea>		
						</td>

						<td>
							<p class="description"><?php _e( 'Write a ending sentence.Leave empty if have no ending words', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>

				    <tr>
				        <td>
				            <strong>
				            	<?php _e( 'Sentence Alignment', 'la-wordsrotator' ); ?><a href="https://webdevocean.com/product/css3-rotating-words-wordpress-plugin/" target="_blank"><i style="color:red;"> (Pro Feature) </i></a>
				            </strong>
				        </td>
				        <td>
				            <select class="rw-textalign form-control">
				              <option value="default"><?php _e( 'Default', 'la-wordsrotator' ); ?></option>
				              <option value="left"><?php _e( 'Left', 'la-wordsrotator' ); ?></option>
				              <option value="center"><?php _e( 'Center', 'la-wordsrotator' ); ?></option>
				              <option value="right"><?php _e( 'Right', 'la-wordsrotator' ); ?></option>
				            </select>
				        </td>
				        <td>
				            <p class="description"><?php _e( 'Select in which direction sentence should be align.', 'la-wordsrotator' ); ?></p>
				        </td>
				    </tr>
					<tr>
						<td>
							<strong><?php _e( 'Sentence and Words Font Size', 'la-wordsrotator' ); ?><a href="https://webdevocean.com/product/css3-rotating-words-wordpress-plugin/" target="_blank"><i style="color:red;"> (Pro Feature) </i></a></strong>
						</td>
						<td class="get-terms">
							<input type="number" class="font form-control" value=""> 		
						</td>

						<td>
							<p class="description"><?php _e( 'Set font size for words and sentence.Default 45px' ); ?>.</p>
						</td>
					</tr>

					<tr>
	  					<td> 
	  						<strong ><?php _e( 'Sentence Color', 'la-wordsrotator' ); ?>
	  							<a href="https://webdevocean.com/product/css3-rotating-words-wordpress-plugin/" target="_blank"><i style="color:red;"> (Pro Feature) </i></a>
	  						</strong>
	  					</td>
	  					<td class="insert-picker">
	  						<input type="text" class="my-colorpicker" value="">
	  					</td>
	  					<td>
	  						<p class="description"><?php _e( 'Choose color for the sentence.Default #000', 'la-wordsrotator' ); ?>.</p>
	  					</td>
	  				</tr>

					<tr>
						<td>
							<strong ><?php _e( 'Animation Effect', 'la-wordsrotator' ); ?></strong>
						</td>
						<td>
							<select class="animate form-control">
								<option value="zoomIn" <?php if ( $data['animation_effect'] == 'zoomIn' ) echo 'selected="selected"'; ?>>Zoom In</option>
								<option value="fade" <?php if ( $data['animation_effect'] == 'fade' ) echo 'selected="selected"'; ?>>Fade</option>
								<option value="flipCube" <?php if ( $data['animation_effect'] == 'flipCube' ) echo 'selected="selected"'; ?>>Flip Cube</option>
								<option value="flipUp" <?php if ( $data['animation_effect'] == 'flipUp' ) echo 'selected="selected"'; ?>>Flip Up</option>
								<option value="flip" <?php if ( $data['animation_effect'] == 'flip' ) echo 'selected="selected"'; ?>>Flip</option>
								<option value="fade">Rotate 1 (PRO Version)</option>
								<option value="fade">Typing (PRO Version)</option>
								<option value="fade">Rotate 2 (PRO Version)</option>
								<option value="fade">Loading Bar (PRO Version)</option>
								<option value="fade">Loading Bar (PRO Version)</option>
								<option value="fade">Slide (PRO Version)</option>
								<option value="fade">Clip (PRO Version)</option>
								<option value="fade">Zoom (PRO Version)</option>
								<option value="fade">Rotate 3 (PRO Version)</option>
								<option value="fade">Scale (PRO Version)</option>
								<option value="fade">Push (PRO Version)</option>
							</select>
						</td>
						<td>
							<p class="description"><?php _e( 'Select Animation effect for words', 'la-wordsrotator' ); ?>.</p>
							<a style="color:#428bca;font-weight: bold;" href="http://demo.webdevocean.com/css3-rotating-words-demo/" target="_blank">All Animation Effects</a>
						</td>
					</tr>
					<tr>
						<td style="width:30%">
							<strong ><?php _e( 'Animation Speed', 'la-wordsrotator' ); ?></strong>
						</td>
						<td style="width:30%">
							<input type="number" class="animate-speed form-control" value="<?php echo $data['animation_speed']; ?>">
						</td>	
						<td style="width:40%">
							<p class="description"><?php _e( 'Select animation speed for words.Large value would slower animation.Default 1250', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>
				</table>
				<div class="ctrl-btn-group">
					<button title="Add New Group of Words" class="button-primary add-new-btm"><span class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New Group', 'la-wordsrotator' ); ?></button>
					<button title="Remove This Group of Words" class="del-btm"><span class="dashicons dashicons-remove"></span><?php _e( 'Remove Group', 'la-wordsrotator' ); ?></button>
					<button title="Get Shortcode For This Group of Words" class="bottom-shortcode" id="<?php echo $data['counter']; ?>"><span class="dashicons dashicons-shortcode"></span><?php _e( 'Get Shortcode', 'la-wordsrotator' ); ?></button>
				</div>
			</div>
				<?php } ?>
			<?php } else { ?>
			<h3 class="tab-head">
				<button title="Get Shortcode For This Group of Words" class="button-primary fullshortcode" id="1"><span title="Get Shortcode" class="dashicons dashicons-shortcode"></span>   <?php _e( 'Get Shortcode', 'la-wordsrotator' ); ?></button>
				<button class="button btnadd"><span title="Add New Group of Words" class="dashicons dashicons-plus-alt"></span></button>&nbsp;
				<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete This Group of Words"></span></button>
				Rotating Words
			</h3>
			<div class="tab-content">
				<table class="form-table">
					<tr>
						<td style="width:30%">
							<strong><?php _e( 'Group Name', 'la-wordsrotator' ); ?></strong> 
						</td>
						<td style="width:30%">
							
							<input class="rw-group-name widefat form-control" type="text">	
						</td>

						<td style="width:40%">
							<p class="description"><?php _e( 'Name this group. This would be for your reference which would be shown on red tab. Default: Rotating Words', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>

					<tr>
						<td>
							<strong><?php _e( 'Give Start Sentence', 'la-wordsrotator' ); ?></strong> 
						</td>
						<td class="get-terms">
							
							<textarea cols="10" rows="5" class="static-sen form-control" placeholder="Sentence before rotating words"></textarea>		
						</td>

						<td>
							<p class="description"><?php _e( 'Write a starting sentence.Leave empty if have no starting words', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>



	  				<tr>
	  					<td> <strong> <?php _e( 'Add Words(these will be rotating)', 'la-wordsrotator' ); ?> </strong></td>
	  					<td>
	  						<textarea cols="10" rows="5" class="rotating-words form-control" placeholder="first,second,third"></textarea> 
	  					</td>
	  					<td>
	  						<p class="description"><?php _e( 'Comma separated list of words', 'la-wordsrotator' ); ?>.</p>
	  					</td>
	  				</tr>

	  				<tr>
						<td>
							<strong><?php _e( 'Give Ending Sentence', 'la-wordsrotator' ); ?></strong> 
						</td>
						<td class="get-terms">
							
							<textarea cols="10" rows="5" class="end-sen form-control" placeholder="Sentence after rotating words"></textarea>		
						</td>

						<td>
							<p class="description"><?php _e( 'Write a ending sentence.Leave empty if have no ending words', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>

				    <tr>
				        <td>
				            <strong>
				            	<?php _e( 'Sentence Alignment', 'la-wordsrotator' ); ?><a href="https://webdevocean.com/product/css3-rotating-words-wordpress-plugin/" target="_blank"><i style="color:red;"> (Pro Feature) </i></a>
				            </strong>
				        </td>
				        <td>
				            <select class="rw-textalign form-control">
				              <option value="default"><?php _e( 'Default', 'la-wordsrotator' ); ?></option>
				              <option value="left"><?php _e( 'Left', 'la-wordsrotator' ); ?></option>
				              <option value="center"><?php _e( 'Center', 'la-wordsrotator' ); ?></option>
				              <option value="right"><?php _e( 'Right', 'la-wordsrotator' ); ?></option>
				            </select>
				        </td>
				        <td>
				            <p class="description"><?php _e( 'Select in which direction sentence should be align.', 'la-wordsrotator' ); ?></p>
				        </td>
				    </tr>
					<tr>
						<td>
							<strong><?php _e( 'Sentence and Words Font Size', 'la-wordsrotator' ); ?><a href="https://webdevocean.com/product/css3-rotating-words-wordpress-plugin/" target="_blank"><i style="color:red;"> (Pro Feature) </i></a></strong>
						</td>
						<td class="get-terms">
							<input type="number" class="font form-control" value=""> 		
						</td>

						<td>
							<p class="description"><?php _e( 'Set font size for words and sentence.Default 45px' ); ?>.</p>
						</td>
					</tr>

					<tr>
	  					<td> 
	  						<strong ><?php _e( 'Sentence Color', 'la-wordsrotator' ); ?>
	  							<a href="https://webdevocean.com/product/css3-rotating-words-wordpress-plugin/" target="_blank"><i style="color:red;"> (Pro Feature) </i></a>
	  						</strong>
	  					</td>
	  					<td class="insert-picker">
	  						<input type="text" class="my-colorpicker" value="">
	  					</td>
	  					<td>
	  						<p class="description"><?php _e( 'Choose color for the sentence.Default #000', 'la-wordsrotator' ); ?>.</p>
	  					</td>
	  				</tr>

					<tr>
						<td>
							<strong ><?php _e( 'Animation Effect', 'la-wordsrotator' ); ?></strong>
						</td>
						<td>
							<select class="animate form-control">
								<option value="zoomIn">Zoom In</option>
								<option value="fade">Fade</option>
								<option value="flipCube">Flip Cube</option>
								<option value="flipUp">Flip Up</option>
								<option value="flip">Flip</option>
								<option value="fade">Rotate 1 (PRO Version)</option>
								<option value="fade">Typing (PRO Version)</option>
								<option value="fade">Rotate 2 (PRO Version)</option>
								<option value="fade">Loading Bar (PRO Version)</option>
								<option value="fade">Loading Bar (PRO Version)</option>
								<option value="fade">Slide (PRO Version)</option>
								<option value="fade">Clip (PRO Version)</option>
								<option value="fade">Zoom (PRO Version)</option>
								<option value="fade">Rotate 3 (PRO Version)</option>
								<option value="fade">Scale (PRO Version)</option>
								<option value="fade">Push (PRO Version)</option>
							</select>
						</td>
						<td>
							<p class="description"><?php _e( 'Select Animation effect for words', 'la-wordsrotator' ); ?>.</p>
							<a style="color:#428bca;font-weight: bold;" href="http://demo.webdevocean.com/css3-rotating-words-demo/" target="_blank">All Animation Effects</a>
						</td>
					</tr>

					<tr>
						<td>
							<strong ><?php _e( 'Animation Speed', 'la-wordsrotator' ); ?></strong>
						</td>
						<td>
							<input type="number" class="animate-speed form-control">
						</td>
						<td>
							<p class="description"><?php _e( 'Select animation speed for words.Large value would slower animation.Default 1250.', 'la-wordsrotator' ); ?>.</p>
						</td>
					</tr>

				</table>
				<div class="ctrl-btn-group">
					<button title="Add New Group of Words" class="button-primary add-new-btm"><span class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New Group', 'la-wordsrotator' ); ?></button>
					<button title="Remove This Group of Words" class="del-btm"><span class="dashicons dashicons-remove"></span><?php _e( 'Remove Group', 'la-wordsrotator' ); ?></button>
					<button title="Get Shortcode For This Group of Words" class="bottom-shortcode" id="1"><span class="dashicons dashicons-shortcode"></span><?php _e( 'Get Shortcode', 'la-wordsrotator' ); ?></button>
				</div>
			</div>
			<?php } ?>
		</div>
			<hr style="margin-top: 20px;">
			<button class="btn btn-success position-fixed save-meta" ><?php _e( 'Save Changes', 'la-wordsrotator' ); ?></button>
			<a style="text-decoration:none;"  href="https://webdevocean.com/product/css3-rotating-words-wordpress-plugin/" target="_blank"><h4 style="padding: 10px;background: #860c4adb;color: #fff;margin-top: 50px;text-align:center;font-size:24px;">Buy Pro Version in $10 To Unlock More Features</h4></a><br>
		</div>