(function(){

	tinymce.PluginManager.add('txshortcodes', function( editor, url ) {
		editor.addButton('txshortcodes', {
					title : 'TX Shortcodes', // title of the button
					//image : '../wp-content/plugins/tx-toolkit/tx-shortcode.png',  // path to the button's image
					icon : 'tx-mce-icon',  // path to the button's image					
			onclick: function() {
				
				var $form = jQuery("#txshortcodes-form");
				jQuery.colorbox({inline:true, href:"#tx-shortcode-form"});

			}
		});
	});

	/*
	jQuery(function(){
	
		console.log("Yo console");
		//Insert tx shortcode media button
		window.addEventListener("load", function(){
			document.getElementById("insert-tx-button").onclick = function(){	
			
				var $form = jQuery("#txshortcodes-form");
				jQuery.colorbox({inline:true, href:"#tx-shortcode-form"});
			}
		});
	});
	*/
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form_tx = jQuery('<div id="txshortcodes-form"><div id="tx-shortcode-form"><table id="txshortcodes-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>TX Shortcodes</h2></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list" width="50%"><span id="columns">Columns</span></td><td class="shortcode-list"><span id="deviders">Divider</span></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list"><span id="spacer">Spacer</span></td><td class="shortcode-list"><span id="testimonials">Testimonials</span></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list"><span id="buttons">Butons</span></td><td class="shortcode-list"><span id="calltoact">Call To Act</span></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list"><span id="services">Services</span></td><td class="shortcode-list"><span id="portfolios">Portfolios</span></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list"><span id="recentposts">Posts</span></td><td class="shortcode-list"><span id="heading">Heading</span></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list"><span id="wooprods">Product Carousel <small>(WooCommerce)</small></span></td><td class="shortcode-list"><span id="itrans-slider">i-trans Slider</span></td>\
			</tr>\
			<!-- <tr>\
				<td class="shortcode-list"><span id="tximage">Image</small></span></td><td class="shortcode-list">&nbsp;</td>\
			</tr> -->\
			<tr>\
				<td class="shortcode-list"><span id="animation">Animate</span></td><td class="shortcode-list"><span id="fancyblock">Fancy Block</span></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list"><span id="txteam">Team</span></td><td class="shortcode-list"><span id="videoslider">Fullscreen Video/Image Slider</span></td>\
			</tr>\
			<tr>\
				<td class="shortcode-list"><span id="txyoutube">YouTube Video</span></td><td class="shortcode-list"><span id="txprogress">Skill/Progress Bar</span></td>\
			</tr>\
		</table>\
		<div class="nx-sh-cancel">\
			<input type="button" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</div>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		W = W - 80;
		H = H - 84;		
		
		var table = form_tx.find('#txshortcodes-table');
		form_tx.appendTo('body').hide();
		//form_tx.appendTo('body');
		
		//call columns
		form_tx.find('#columns').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-column-form"});
			}, 500);
		});

		//call deviders
		form_tx.find('#deviders').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-devider-form"});
			}, 500);
		});
		
		//call Heding
		form_tx.find('#heading').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-heading-form"});
			}, 500);
		});			
		
		//call deviders
		form_tx.find('#testimonials').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-testimonial-form"});
			}, 500);
		});	
		
		//call buttons
		form_tx.find('#buttons').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-button-form"});
			}, 500);
		});	
		
		//call calltoact
		form_tx.find('#calltoact').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-calltoact-form"});
			}, 500);
		});
		
		//call Services
		form_tx.find('#services').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-service-form"});
			}, 500);
		});									
				
		//call portfolio
		form_tx.find('#portfolios').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-portfolio-form"});
			}, 500);
		});	
		
		//call blog
		form_tx.find('#recentposts').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-blog-form"});
			}, 500);
		});	
		
		//call spacer
		form_tx.find('#spacer').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-spacer-form"});
			}, 500);
		});
		
		//Woocommerce Products
		form_tx.find('#wooprods').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-wooprods-form"});
			}, 500);
		});	
		
		//i-trans slider
		form_tx.find('#itrans-slider').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-slider-form"});
			}, 500);
		});	
		
		//Animation
		form_tx.find('#animation').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-animation-form"});
			}, 500);
		});
		
		//Fancy Block
		form_tx.find('#fancyblock').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-fancyblock-form"});
			}, 500);
		});						

		//Insert Image
		form_tx.find('#tximage').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-image-form"});
			}, 500);
		});	
		
		//Insert team
		form_tx.find('#txteam').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-team-form"});
			}, 500);
		});	
		
		//Insert video slider
		form_tx.find('#videoslider').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-vslider-form"});
			}, 500);
		});	
		
		//Insert YouTube video
		form_tx.find('#txyoutube').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-youtube-form"});
			}, 500);
		});	
		
		//Insert Progress video
		form_tx.find('#txprogress').click(function(){			
			setTimeout(function() {
				jQuery.colorbox({inline:true, href:"#tx-progressbar-form"});
			}, 500);
		});																				
		
		form_tx.find('.modal-close').click(function(){
			jQuery.colorbox.close();
		});
		
	});
	
	
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form_portfolio = jQuery('<div id="portfolio-form"><div id="tx-portfolio-form"><table id="portfolio-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Portfolio</h2></td>\
			</tr>\
			<tr>\
				<th><label for="portfolio-style">Portfolio Style</label></th>\
				<td><select name="style" id="portfolio-style">\
					<option value="default">Default</option>\
					<option value="gallery">Gallery</option>\
				</select><br />\
				<small>Specify the portfolio style, "Gallery" style will not work with carousel.</small></td>\
			</tr>\
			<tr>\
				<th><label for="portfolio-items">Number of item</label></th>\
				<td><input type="number" max="12" min="0" id="portfolio-items" name="items" value="4" /><br />\
				<small>Specify the number of portfolio items to show.</small></td>\
			</tr>\
			<tr>\
				<th><label for="portfolio-columns">Number of columns</label></th>\
				<td><input type="number" min="1" max="4" name="columns" id="portfolio-columns" value="4" /><br />\
				<small>Specify number of portfolio columns.</small></td>\
			</tr>\
			<tr>\
				<th><label for="portfolio-hidecat">Show/hide item category labels</label></th>\
				<td><select name="hidecat" id="portfolio-hidecat">\
					<option value="no">Show category labels</option>\
					<option value="yes">Hide category labels</option>\
				</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="portfolio-hideexcerpt">Show/hide item excerpt</label></th>\
				<td><select name="hideexcerpt" id="portfolio-hideexcerpt">\
					<option value="no">Show excerpt</option>\
					<option value="yes">Hide excerpt</option>\
				</select>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="portfolio-showpage">Show Pagination</label></th>\
				<td><select name="showpage" id="portfolio-showpage">\
					<option value="no">No</option>\
					<option value="yes">Yes</option>\
				</select><br />\
				<small>Pagination will not work with carousel</small>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="portfolio-carusel">Show as carousel</label></th>\
				<td><select name="carusel" id="portfolio-carusel">\
					<option value="no">No</option>\
					<option value="yes">Yes</option>\
				</select><br />\
				<small>Number of items must be greater then number of column</small>\
				</td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="portfolio-submit" class="button-primary" value="Insert Portfolio" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		
		
		var table = form_portfolio.find('#portfolio-table');
		form_portfolio.appendTo('body').hide();
		
		
		// handles the click event of the submit button
		form_portfolio.find('#portfolio-submit').click(function(){

			var portfolio_style = table.find('#portfolio-style').val(); 
			var number_of_item = table.find('#portfolio-items').val(); 
			var number_of_column = table.find('#portfolio-columns').val(); 
			var hide_cat = table.find('#portfolio-hidecat').val();
			var hide_excerpt = table.find('#portfolio-hideexcerpt').val();
			var show_page = table.find('#portfolio-showpage').val();			
			var show_carusel = table.find('#portfolio-carusel').val(); 			
			
			
			var shortcode = '[tx_portfolio style="'+portfolio_style+'" items="'+number_of_item+'" columns="'+number_of_column+'" hide_cat="'+hide_cat+'" hide_excerpt="'+hide_excerpt+'" show_pagination="'+show_page+'" carousel="'+show_carusel+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});
		


		form_portfolio.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
	
	});	
	

	/*
	* Blog Posts
	*/	

	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form_blog = jQuery('<div id="blog-form" class="tx-sh-form"><div id="tx-blog-form"><table id="blog-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Posts</h2></td>\
			</tr>\
			<tr>\
				<th><label for="blog-ids">Category Id (optional)</label></th>\
				<td><input type="text" name="ids" id="blog-ids" value="" /><br />\
				<small>Add ids of categories to filter, keep it blank for all categories</small></td>\
			</tr>\
			<tr>\
				<th><label for="blog-items">Number of item</label></th>\
				<td><input type="number" max="12" min="0" id="blog-items" name="items" value="4" /><br />\
				<small>Specify the number of recent posts to show.</small></td>\
			</tr>\
			<tr>\
				<th><label for="blog-columns">Number of columns</label></th>\
				<td><input type="number" min="1" max="4" name="columns" id="blog-columns" value="4" /><br />\
				<small>Specify number of columns.</small></td>\
			</tr>\
			<tr>\
				<th><label for="blog-hidecat">Show/hide item category labels</label></th>\
				<td><select name="hidecat" id="blog-hidecat">\
					<option value="show">Show category labels</option>\
					<option value="hide">Hide category labels</option>\
				</select><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="blog-showpage">Show Pagination</label></th>\
				<td><select name="showpage" id="blog-showpage">\
					<option value="no">No</option>\
					<option value="yes">Yes</option>\
				</select><br />\
				<small>Pagination will not work with carousel</small>\
				</td>\
			</tr>\
			<tr>\
				<th><label for="blog-carusel">Show as carousel</label></th>\
				<td><select name="carusel" id="blog-carusel">\
					<option value="no">No</option>\
					<option value="yes">Yes</option>\
				</select><br />\
				<small>Number of items must be greater then number of column</small>\
				</td>\
			</tr>\
			</table>\
		<p class="submit">\
			<input type="button" id="blog-submit" class="button-primary" value="Insert Posts" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		
		
		var table = form_blog.find('#blog-table');
		form_blog.appendTo('body').hide();
		
		
		// handles the click event of the submit button
		form_blog.find('#blog-submit').click(function(){

			var number_of_item = table.find('#blog-items').val(); 
			var number_of_column = table.find('#blog-columns').val();
			var show_hide_cat = table.find('#blog-hidecat').val();
			var category_id = table.find('#blog-ids').val();
			var show_page = table.find('#blog-showpage').val();			
			var show_carusel = table.find('#blog-carusel').val(); 			
			
			var shortcode = '[tx_blog items="'+number_of_item+'" columns="'+number_of_column+'" showcat="'+show_hide_cat+'" category_id="'+category_id+'" show_pagination="'+show_page+'" carousel="'+show_carusel+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});
		
		form_blog.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
	
	});		
	
	
	/*
	* Columns form
	*/
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form_column = jQuery('<div id="column-form" class="tx-sh-form"><div id="tx-column-form"><table id="column-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Columns</h2></td>\
			</tr>\
			<tr>\
				<th><label for="column-size">Column Size</label></th>\
				<td><select name="size" id="column-size">\
					<option value="1/2">2 columns in a row</option>\
					<option value="1/3">3 columns in a row</option>\
					<option value="1/4">4 columns in a row</option>\
				</select><br />\
				<small>specify the column size, you can fruther manually edit them, 2/3 and 3/4 also can be used.</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="column-submit" class="button-primary" value="Insert Columns" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_column.find('#column-table');
		form_column.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_column.find('#column-submit').click(function(){
			
			var columns = table.find('#column-size').val(); 	
			var shortcode = '[tx_row]<br/>';
			
			if(columns=='1/2')
			{
				shortcode += '[tx_column size="1/2"]Content[/tx_column]<br/>[tx_column size="1/2"]Content[/tx_column]';
			}else if(columns=='1/3')
			{
				shortcode += '[tx_column size="1/3"]Content[/tx_column]<br/>[tx_column size="1/3"]Content[/tx_column]<br/>[tx_column size="1/3"]Content[/tx_column]';
			} else if(columns=='1/4')
			{
				shortcode += '[tx_column size="1/4"]Content[/tx_column]<br/>[tx_column size="1/4"]Content[/tx_column]<br/>[tx_column size="1/4"]Content[/tx_column]<br/>[tx_column size="1/4"]Content[/tx_column]';
			}
			
			shortcode += '<br/>[/tx_row]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_column.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
	
	
	/*
	* heading form
	*/
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form_heading = jQuery('<div id="heading-form" class="tx-sh-form"><div id="tx-heading-form"><table id="heading-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Heading</h2></td>\
			</tr>\
			<tr>\
				<th><label for="heading-style">Heading Style</label></th>\
				<td><select name="style" id="heading-style">\
					<option value="default">Default</option>\
				</select><br />\
				<small>Select the heading style</small></td>\
			</tr>\
			<tr>\
				<th><label for="heading-text">Heading Text</label></th>\
				<td><input type="text" name="text" id="heading-text" value="Heading Text" /><br />\
				<small>Specify the heading text.</small></td>\
			</tr>\
			<tr>\
				<th><label for="heading-tag">Heading Tag</label></th>\
				<td><select name="tag" id="heading-tag">\
					<option value="h1">H1</option>\
					<option value="h2" selected>H2</option>\
					<option value="h3">H3</option>\
					<option value="h4">H4</option>\
					<option value="h5">H5</option>\
					<option value="h6">H6</option>\
				</select><br />\
				<small>Select the Heading tag.</small></td>\
			</tr>\
			<tr>\
				<th><label for="heading-align">Text Alignment</label></th>\
				<td><select name="align" id="heading-align">\
					<option value="left">Left</option>\
					<option value="center">Center</option>\
					<option value="right">right</option>\
				</select><br />\
				<small>Select heading text alignment</small></td>\
			</tr>\
			<tr>\
				<th><label for="heading-size">Heading Size</label></th>\
				<td><input type="number" name="size" id="heading-size" min="0" max="120" value="24" /><br />\
				<small>Heading font size in px</small></td>\
			</tr>\
			<tr>\
				<th><label for="heading-margin">Heading Margin</label></th>\
				<td><input type="number" name="margin" id="heading-margin" min="0" max="120" value="24" /><br />\
				<small>Heading bottom margin in px</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="heading-submit" class="button-primary" value="Insert Heading" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_heading.find('#heading-table');
		form_heading.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_heading.find('#heading-submit').click(function(){
			
			var style = table.find('#heading-style').val();
			var heading_text = table.find('#heading-text').val();			
			var tag = table.find('#heading-tag').val();
			var size = table.find('#heading-size').val();
			var margin = table.find('#heading-margin').val();
			var align = table.find('#heading-align').val();			
													
			var shortcode = '[tx_heading style="'+style+'" heading_text="'+heading_text+'" tag="'+tag+'" size="'+size+'" margin="'+margin+'" align="'+align+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_heading.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
		
	
	
	/*
	* deviders form
	*/
	jQuery(function(){
		var form_devider = jQuery('<div id="devider-form" class="tx-sh-form"><div id="tx-devider-form"><table id="devider-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Dividers</h2></td>\
			</tr>\
			<tr>\
				<th><label for="devider-style">Divider Style</label></th>\
				<td><select name="style" id="devider-style">\
					<option value="default">Default</option>\
				</select><br />\
				<small>specify the divider style</small></td>\
			</tr>\
            <tr>\
				<th><label for="devider-padding">Divider Margin</label></th>\
				<td><input type="number" name="padding" id="devider-padding" min="0" max="120" value="24" /><br />\
				<small>Top and bottom margin in px</small></td>\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="devider-submit" class="button-primary" value="Insert Devider" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_devider.find('#devider-table');
		form_devider.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_devider.find('#devider-submit').click(function(){
			
			var deviderpadding = table.find('#devider-padding').val(); 	
			var shortcode = '[tx_divider size="'+deviderpadding+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_devider.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
	
	
	/*
	* testimonials form
	*/
	jQuery(function(){
		var form_testimonial = jQuery('<div id="testimonial-form" class="tx-sh-form"><div id="tx-testimonial-form"><table id="testimonial-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Testimonials</h2></td>\
			</tr>\
			<tr>\
				<th><label for="testimonial-style">Testimonial Style</label></th>\
				<td><select name="style" id="testimonial-style">\
					<option value="default">Default</option>\
				</select><br />\
				<small>specify the testimonial style</small></td>\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="testimonial-submit" class="button-primary" value="Insert Testimonials" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_testimonial.find('#testimonial-table');
		form_testimonial.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_testimonial.find('#testimonial-submit').click(function(){
			
			var testimonial_style = table.find('#testimonial-style').val(); 	
			var shortcode = '[tx_testimonial style="'+testimonial_style+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_testimonial.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
	
	
	/*
	* buttons form
	*/
	jQuery(function(){
		var form_button = jQuery('<div id="button-form" class="tx-sh-form"><div id="tx-button-form"><table id="button-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Buttons</h2></td>\
			</tr>\
			<tr>\
				<th><label for="button-style">Button Style</label></th>\
				<td><select name="style" id="button-style">\
					<option value="default">Default</option>\
				</select><br />\
				<small>specify the button style</small></td>\
			</tr>\
			<tr>\
				<th><label for="button-text">Button text</label></th>\
				<td><input type="text" name="text" id="button-text" value="Know More.." /><br />\
				<small>specify the button text.</small></td>\
			</tr>\
			<tr>\
				<th><label for="button-url">Button url</label></th>\
				<td><input type="text" name="url" id="button-url" value="" /><br />\
				<small>specify the button url.</small></td>\
			</tr>\
			<tr>\
				<th><label for="button-color">Button Color</label></th>\
				<td><input type="text" class="color" name="color" id="button-color" value="">\<br />\
				<small>Select button background color</small></td>\
			</tr>\
			<tr>\
				<th><label for="button-textcolor">Button Text Color</label></th>\
				<td><input type="text" class="color" name="textcolor" id="button-textcolor" value="">\<br />\
				<small>Select button text color</small></td>\
			</tr>\
			<tr>\
				<th><label for="button-target">Target</label></th>\
				<td><select name="target" id="button-target">\
					<option value="self">Same Window</option>\
					<option value="blank">New Window</option>\
				</select><br />\
				<small>Open link in same window or new window</small></td>\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="button-submit" class="button-primary" value="Insert Button" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_button.find('#button-table');
		
		tx_color_picker(form_button.find('.color'));
		
		form_button.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_button.find('#button-submit').click(function(){
			
			var button_style = table.find('#button-style').val();
			var button_text = table.find('#button-text').val();
			var button_url = table.find('#button-url').val();
			var button_color = table.find('#button-color').val();
			var button_textcolor = table.find('#button-textcolor').val();
			var button_target = table.find('#button-target').val();
			
			 	
			var shortcode = '[tx_button style="'+button_style+'" text="'+button_text+'" url="'+button_url+'" color="'+button_color+'" textcolor="'+button_textcolor+'" target="'+button_target+'"]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});
		
		form_button.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
	
	
	/*
	* calltoact form
	*/
	jQuery(function(){
		var form_calltoact = jQuery('<div id="calltoact-form" class="tx-sh-form"><div id="tx-calltoact-form"><table id="calltoact-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Call To Act</h2></td>\
			</tr>\
			<tr>\
				<th><label for="calltoact-text">Call to act text</label></th>\
				<td><input type="text" name="text" id="calltoact-text" value="Call To Act Text" /><br />\
				<small>Specify the Call toa ct text.</small></td>\
			</tr>\
            <tr>\
				<th><label for="calltoact-button-text">Button text</label></th>\
				<td><input type="text" name="button-text" id="calltoact-button-text" value="Know More.." /><br />\
				<small>Specify the calltoact text.</small></td>\
			</tr>\
			<tr>\
				<th><label for="calltoact-url">Call to act url</label></th>\
				<td><input type="text" name="url" id="calltoact-url" value="" /><br />\
				<small>specify the calltoact button url.</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="button-submit" class="button-primary" value="Insert Call to act" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_calltoact.find('#calltoact-table');
		
		//tx_color_picker(form_calltoact.find('.color'));
		
		form_calltoact.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_calltoact.find('#button-submit').click(function(){
			
			var calltoact_text = table.find('#calltoact-text').val();
			var calltoact_button_text = table.find('#calltoact-button-text').val();
			var calltoact_url = table.find('#calltoact-url').val();
	 	
			var shortcode = '[tx_calltoact button_text="'+calltoact_button_text+'" url="'+calltoact_url+'"]'+calltoact_text+'[/tx_calltoact]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});	
		form_calltoact.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});			
		
	});	
	
	
	/*
	* Services form
	*/
	jQuery(function(){
		var form_services = jQuery('<div id="services-form" class="tx-sh-form"><div id="tx-service-form"><table id="services-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Services</h2></td>\
			</tr>\
			<tr>\
				<th><label for="services-style">Services Style</label></th>\
				<td><select name="style" id="services-style">\
					<option value="default">Default (Circle)</option>\
					<option value="curved">Curved Corner</option>\
					<option value="square">Square</option>\
				</select><br />\
				<small>Specify the services style</small></td>\
			</tr>\
			<tr>\
				<th><label for="services-title">Services Title</label></th>\
				<td><input type="text" name="title" id="services-title" value="Services Title" /><br />\
				<small>Specify the Call toa ct text.</small></td>\
			</tr>\
			<tr>\
				<th><label for="services-icon">Services Icons</label></th>\
				<td><div class="awedrop">'+tx_font_awesome_include('tx-fa-icons')+'</div><br /><input type="text" name="icon" id="services-icon" value="" /></td>\
			</tr>\
			<tr>\
				<th><label for="services-content">Services Text</label></th>\
				<td><textarea name="content" id="services-content">Services content</textarea><br />\
				<small>Services content</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="button-submit" class="button-primary" value="Insert Services" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_services.find('#services-table');
		
		form_services.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_services.find('#button-submit').click(function(){
			
			var services_style = table.find('#services-style').val();
			var services_title = table.find('#services-title').val();
			var services_icon = table.find('#services-icon').val();
			var services_content = table.find('#services-content').val();
	 	
			var shortcode = '[tx_services style="'+services_style+'" title="'+services_title+'" icon="'+services_icon+'"]'+services_content+'[/tx_services]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_services.find('.tx-fa-icons .fa').click(function(){
			jQuery('.tx-fa-icons .active').removeClass('active');
			jQuery(this).addClass('active');
			//console.log( jQuery(this).data('value') );
			var tx_icon = jQuery(this).data('value');
			jQuery('#services-icon').val(tx_icon);
		});				
		
		form_services.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});			
		
	});	
					
	
	/*
	* spacer form
	*/
	jQuery(function(){
		var form_spacer = jQuery('<div id="spacer-form" class="tx-sh-form"><div id="tx-spacer-form"><table id="spacer-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Spacer</h2></td>\
			</tr>\
			<tr>\
				<th><label for="spacer-size">Spacer Size (height in px)</label></th>\
				<td><input type="number" min="0" max="120" name="size" id="spacer-size" value="16" /><br />\
				<small>Use spacer to manage vertical gaps</small></td>\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="spacer-submit" class="button-primary" value="Insert Spacer" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_spacer.find('#spacer-table');
		form_spacer.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_spacer.find('#spacer-submit').click(function(){
			
			var spacer_size = table.find('#spacer-size').val(); 	
			var shortcode = '[tx_spacer size="'+spacer_size+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_spacer.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
	
	
	/*
	* wooproducts form
	*/
	jQuery(function(){
		var form_wooprods = jQuery('<div id="wooprods-form" class="tx-sh-form"><div id="tx-wooprods-form"><table id="wooprods-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>WooCommerce Products Carousel</h2></td>\
			</tr>\
			<tr>\
				<th><label for="wooprods-type">Product Listing Type</label></th>\
				<td><select name="style" id="wooprods-type">\
					<option value="product_categories">Product Categories</option>\
					<option value="recent_products">Recent Products</option>\
					<option value="featured_products">Featured Products</option>\
					<option value="sale_products">Products On Sale</option>\
					<option value="best_selling_products">Best Selling Products</option>\
					<option value="top_rated_products">Top Rated Products</option>\
					<option value="products">Products By Ids</option>\
				</select><br />\
				<small>Specify product listing type</small></td>\
			</tr>\
			<tr>\
				<th><label for="wooprods-ids">Category/Product Ids (optional)</label></th>\
				<td><input type="text" name="ids" id="wooprods-ids" value="" /><br />\
				<small>Comma separeted category or product ids (works with "Product Categories" and "Products By Ids" )</small></td>\
			</tr>\
			<tr>\
				<th><label for="wooprods-columns">Number Of Columns</label></th>\
				<td><input type="number" min="1" max="4" name="coumns" id="wooprods-columns" value="4" /><br />\
				<small>Number of columns or items visible</small></td>\
			</tr>\
			<tr>\
				<th><label for="wooprods-items">Number Of Items</label></th>\
				<td><input type="number" min="1" max="16" name="items" id="wooprods-items" value="8" /><br />\
				<small>Total number of items</small></td>\
			</tr>\
        </table>\
		<div class="nx-sh-cancel">\
			<input type="button" id="wooprods-submit" class="button-primary" value="Insert Wooprods" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</div>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_wooprods.find('#wooprods-table');
		form_wooprods.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_wooprods.find('#wooprods-submit').click(function(){
			
			var wooprods_type = table.find('#wooprods-type').val();
			var wooprods_ids = table.find('#wooprods-ids').val();
			var wooprods_columns = table.find('#wooprods-columns').val();			
			var wooprods_items = table.find('#wooprods-items').val(); 
				
			var shortcode = '[tx_prodscroll type="'+wooprods_type+'" ids="'+wooprods_ids+'" columns="'+wooprods_columns+'" items="'+wooprods_items+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_wooprods.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
	
	
	/*
	* Slider
	*/
	jQuery(function(){
		var form_slider = jQuery('<div id="slider-form" class="tx-sh-form"><div id="tx-slider-form"><table id="slider-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>i-trans Slider</h2></td>\
			</tr>\
			<tr>\
				<th><label for="slider-style">Slider Style</label></th>\
				<td><select name="style" id="slider-style">\
					<option value="default">Default</option>\
				</select><br />\
				<small>Select slider style</small></td>\
			</tr>\
			<tr>\
				<th><label for="slider-category">Slider Category</label></th>\
				<td><select name="category" id="slider-category">\
					<option value="">All</option>'+tx_slider_cat()+'\
				</select><br />\
				<small>Select slider category for category based multiple slider(optional)</small></td>\
			</tr>\
			<tr>\
				<th><label for="slider-transition">Slider Transition</label></th>\
				<td><select name="transition" id="slider-transition">\
						<option value="slide">Slide</option>\
						<option value="fade">Fade</option>\
						<option value="backSlide">Back Slide</option>\
						<option value="goDown">Go Down</option>\
						<option value="fadeUp">Fade Up</option>\
					</select><br />\
				<small>Select slider transition effect</small></td>\
			</tr>\
			<tr>\
				<th><label for="slider-items">Number Of Items (slides)</label></th>\
				<td><input type="number" min="1" max="16" name="items" id="slider-items" value="4" /><br />\
				<small>Number of slides in the slider</small></td>\
			</tr>\
			<tr>\
				<th><label for="slider-delay">Delay</label></th>\
				<td><input type="number" min="1000" max="16000" name="delay" step="500" id="slider-delay" value="8000" /><br />\
				<small>Duration between slides in miliseconds</small></td>\
			</tr>\
			<tr>\
				<th><label for="slider-parallax">Parallax</label></th>\
				<td><select name="parallax" id="slider-parallax">\
					<option value="yes">Yes</option>\
					<option value="no">No</option>\
				</select><br />\
				<small>Turn on parallax effect (only works with header slider through page/post meta &quot;Other Slider Plugin Shortcode&quot; )</small></td>\
			</tr>\
			<tr>\
				<th><label for="slider-align">Content Alignment</label></th>\
				<td><select name="align" id="slider-align">\
					<option value="left">Left</option>\
					<option value="center">Center</option>\
					<option value="right">Right</option>\
				</select></td>\
			</tr>\
			<tr>\
				<th><label for="slider-title">Show/Hide Title</label></th>\
				<td><select name="title" id="slider-title">\
					<option value="show">Show</option>\
					<option value="hide">Hide</option>\
				</select></td>\
			</tr>\
			<tr>\
				<th><label for="slider-desc">Show/Hide Details</label></th>\
				<td><select name="desc" id="slider-desc">\
					<option value="show">Show</option>\
					<option value="hide">Hide</option>\
				</select></td>\
			</tr>\
			<tr>\
				<th><label for="slider-link">Show/Hide Link</label></th>\
				<td><select name="link" id="slider-link">\
					<option value="show">Show</option>\
					<option value="hide">Hide</option>\
				</select></td>\
			</tr>\
			<tr>\
				<th><label for="slider-textbg">Text Background Style</label></th>\
				<td><select name="textbg" id="slider-textbg">\
					<option value="shadow">Shadowed Text</option>\
					<option value="transparent">Semi-transparent Background</option>\
					<option value="softvignette">Soft Vignette</option>\
					<option value="hardvignette">Hard Vignette</option>\
					<option value="darkoverlay">Dark Overlay</option>\
					<option value="pattern">Pixel Pattern</option>\
				</select></td>\
			</tr>\
			<tr>\
				<th><label for="slider-height">Slider Height</label></th>\
				<td><input type="number" min="400" max="800" name="height" id="slider-height" value="420" /><br />\
			</tr>\
		</table>\
		<div class="nx-sh-cancel">\
			<input type="button" id="slider-submit" class="button-primary" value="Insert Slider" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</div>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_slider.find('#slider-table');
		form_slider.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_slider.find('#slider-submit').click(function(){
			
			var style = table.find('#slider-style').val();
			var category = table.find('#slider-category').val();
			var slider_items = table.find('#slider-items').val();			
			var slider_delay = table.find('#slider-delay').val(); 
			var slider_parallax = table.find('#slider-parallax').val(); 
			var slider_transition = table.find('#slider-transition').val(); 
			
			var slider_title = table.find('#slider-title').val(); 
			var slider_desc = table.find('#slider-desc').val(); 
			var slider_link = table.find('#slider-link').val();
			var slider_align = table.find('#slider-align').val();
			var slider_height = table.find('#slider-height').val();		
			var slider_textbg = table.find('#slider-textbg').val();						 												
				
			var shortcode = '[tx_slider style="'+style+'" category="'+category+'" delay="'+slider_delay+'" parallax="'+slider_parallax+'" items="'+slider_items+'" transition="'+slider_transition+'" title="'+slider_title+'" desc="'+slider_desc+'" link="'+slider_link+'" align="'+slider_align+'" height="'+slider_height+'" textbg="'+slider_textbg+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_slider.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});
	
	/*
	* Animation form
	*/
	jQuery(function(){
		var form_animation = jQuery('<div id="animation-form" class="tx-sh-form"><div id="tx-animation-form"><table id="animation-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Animate</h2></td>\
			</tr>\
			<tr>\
				<th><label for="animation-style">Animation Style</label></th>\
				<td><select name="style" id="animation-style">\
					<option value="bounce">bounce</option>\
					<option value="shake">shake</option>\
					<option value="tada">tada</option>\
					<option value="swing">swing</option>\
					<option value="pulse">pulse</option>\
					<option value="flip">flip</option>\
					<option value="fadeIn">fadeIn</option>\
					<option value="fadeInUp">fadeInUp</option>\
					<option value="fadeInDown">fadeInDown</option>\
					<option value="fadeInLeft">fadeInLeft</option>\
					<option value="fadeInRight">fadeInRight</option>\
					<option value="bounceIn" selected="selected">bounceIn</option>\
					<option value="bounceInDown">bounceInDown</option>\
					<option value="bounceInUp">bounceInUp</option>\
					<option value="bounceInLeft">bounceInLeft</option>\
					<option value="bounceInRight">bounceInRight</option>\
					<option value="rollIn">rollIn</option>\
				</select><br />\
				<small>Select the animation style</small></td>\
			</tr>\
            <tr>\
				<th><label for="animation-duration">Animation Duration</label></th>\
				<td><input type="number" name="duration" id="animation-duration" min=".1" max="5" value="1" step=".1" /><br />\
				<small>Animation duration in seconds</small></td>\
			</tr>\
            <tr>\
				<th><label for="animation-delay">Animation Delay</label></th>\
				<td><input type="number" name="delay" id="animation-delay" min=".1" max="5" value=".4" step=".1" /><br />\
				<small>Animation delay in seconds</small></td>\
			</tr>\
			<tr>\
				<th><label for="animation-inline">Inline</label></th>\
				<td><select name="inline" id="animation-inline">\
					<option value="no">No</option>\
					<option value="yes">Yes</option>\
				</select><br />\
				<small>Turn this option to YES and animated element will be wrapped in SPAN instead of DIV.</small></td>\
			</tr>\
			<tr>\
				<th><label for="animation-content">Content</label></th>\
				<td><textarea name="content" id="animation-content">Content ...</textarea><br />\
				<small>Enter your contents here, supports nested shortcode.</small></td>\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="animation-submit" class="button-primary" value="Insert animation" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_animation.find('#animation-table');
		form_animation.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_animation.find('#animation-submit').click(function(){
			
			var animation = table.find('#animation-style').val();
			var duration = table.find('#animation-duration').val();
			var delay = table.find('#animation-delay').val();
			var inline = table.find('#animation-inline').val();
			var content = table.find('#animation-content').val();
				
			var shortcode = '[tx_animate animation="'+animation+'" duration="'+duration+'" delay="'+delay+'" inline="'+inline+'"]'+content+'[/tx_animate]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_animation.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
		
	/*
	* Fancyblock form
	*/
	jQuery(function(){
		var form_fancyblock = jQuery('<div id="fancyblock-form" class="tx-sh-form"><div id="tx-fancyblock-form"><table id="fancyblock-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Fancy Block</h2></td>\
			</tr>\
            <tr>\
				<th><label for="fancyblock-height">Height</label></th>\
				<td><input type="number" name="height" id="fancyblock-height" min="1" max="1200" value="" /><br />\
				<small>Block height, <b>Keep it blank for auto height</b></small></td>\
			</tr>\
            <tr>\
				<th><label for="fancyblock-padding">Top/Bottom Padding</label></th>\
				<td><input type="number" name="padding" id="fancyblock-padding" min="1" max="600" value="32" /><br />\
				<small>Block Top?bottom padding</b></small></td>\
			</tr>\
			<tr>\
				<th><label for="fancyblock-bgcolor">Background Color</label></th>\
				<td><input type="text" class="color" name="bgcolor" id="fancyblock-bgcolor" value="">\<br />\
				<small>Select background color</small></td>\
			</tr>\
            <tr>\
				<th><label for="fancyblock-overlay">Overlay Layer Tranparancy</label></th>\
				<td><select name="overlay" id="fancyblock-overlay">\
					<option value="0.1">0.0</option>\
					<option value="0.1">0.1</option>\
					<option value="0.2">0.2</option>\
					<option value="0.3">0.3</option>\
					<option value="0.4" selected="selected">0.4</option>\
					<option value="0.5">0.5</option>\
					<option value="0.6">0.6</option>\
					<option value="0.7">0.7</option>\
					<option value="0.8">0.8</option>\
					<option value="0.9">0.9</option>\
				</select><br />\
				<small>Overlay Layer Tranparancy, <b>.9 least transparent and .1 heighest</b></small></td>\
			</tr>\
			<tr>\
				<th><label for="fancyblock-bgurl">Background Image URL</label></th>\
				<td><input type="text" name="bgurl" id="fancyblock-bgurl" /><br />\
				<input type="button" class="tx-button" name="tx-img-upload" id="tx-upload-button" value="Upload Image">\
			</tr>\
			<tr>\
				<th><label for="fancyblock-bgattachment">Background Attachment</label></th>\
				<td><select name="bgattachment" id="fancyblock-bgattachment">\
					<option value="fixed">Fixed</option>\
					<option value="scroll">Scroll</option>\
				</select><br />\
				<small>Select background movement</small></td>\
			</tr>\
			<tr>\
				<th><label for="fancyblock-bgsize">Background Size</label></th>\
				<td><select name="bgsize" id="fancyblock-bgsize">\
					<option value="cover">Cover</option>\
					<option value="auto">Auto</option>\
				</select><br />\
				<small>Select background size</small></td>\
			</tr>\
			<tr>\
				<th><label for="fancyblock-fullwidth">Force Full Width</label></th>\
				<td><select name="fullwidth" id="fancyblock-fullwidth">\
					<option value="no">No</option>\
					<option value="yes">Yes</option>\
				</select><br />\
				<small>Forces the container fullwidth, can be used as fullwidth row. Will not work with sidebars on. </small></td>\
			</tr>\
			<tr>\
				<th><label for="fancyblock-content">Content</label></th>\
				<td><textarea name="content" id="fancyblock-content">Content ...</textarea><br />\
				<small>Enter your contents here, supports nested shortcode.</small></td>\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="fancyblock-submit" class="button-primary" value="Insert fancyblock" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_fancyblock.find('#fancyblock-table');
		
		tx_color_picker(form_fancyblock.find('.color'));		
		
		form_fancyblock.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_fancyblock.find('#fancyblock-submit').click(function(){
			
			var height = table.find('#fancyblock-height').val();
			var padding = table.find('#fancyblock-padding').val();
			var bgcolor = table.find('#fancyblock-bgcolor').val();
			var overlay = table.find('#fancyblock-overlay').val();			
			var bgurl = table.find('#fancyblock-bgurl').val();
			var attachment = table.find('#fancyblock-bgattachment').val();
			var bgsize = table.find('#fancyblock-bgsize').val();
			var fullwidth = table.find('#fancyblock-fullwidth').val();
			var content = table.find('#fancyblock-content').val();
			
				
			var shortcode = '[tx_fancyblock height="'+height+'" padding="'+padding+'" bgcolor="'+bgcolor+'" overlay="'+overlay+'" bgurl="'+bgurl+'" attachment="'+attachment+'" bgsize="'+bgsize+'" fullwidth="'+fullwidth+'" ]'+content+'[/tx_fancyblock]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			//Insert tx shortcode via media button
			//wp.media.editor.insert(shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_fancyblock.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
		
		
		var file_frame;
		form_fancyblock.find('#tx-upload-button').click(function(event){

			event.preventDefault();
		
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
			  file_frame.open();
			  return;
			}
		
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});
		
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  attachment = file_frame.state().get('selection').first().toJSON();
		
			  // Do something with attachment.id and/or attachment.url here
			  table.find('#fancyblock-bgurl').val(attachment.url); 	
			  
			});
		
			// Finally, open the modal
			file_frame.open();

			
		});		
			
	});	
	
		
	/*
	* team form
	*/
	jQuery(function(){
		var form_team = jQuery('<div id="team-form" class="tx-sh-form"><div id="tx-team-form"><table id="team-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Insert Team</h2></td>\
			</tr>\
			<tr>\
				<th><label for="team-columns">Number of Columns</label></th>\
				<td><select name="columns" id="team-columns">\
					<option value=2>2</option>\
					<option value=3>3</option>\
					<option value=4 selected="selected">4</option>\
				</select><br />\
				<small>Number of columns</small></td>\
			</tr>\
			<tr>\
				<th><label for="team-items">Number of Item</label></th>\
				<td><input type="number" min="2" max="20" name="items" id="team-items" value="4" /><br />\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="team-submit" class="button-primary" value="Insert Team" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_team.find('#team-table');
		form_team.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_team.find('#team-submit').click(function(){
			
			var team_columns = table.find('#team-columns').val(); 
			var team_items = table.find('#team-items').val(); 			
			
			var shortcode = '[tx_team columns="'+team_columns+'" items="'+team_items+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			//send_to_editor(shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});
		
		form_team.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});
	

	/*
	* vslider form
	*/
	jQuery(function(){
		var form_vslider = jQuery('<div id="vslider-form" class="tx-sh-form"><div id="tx-vslider-form"><table id="vslider-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Video/Hero Slider</h2><br /><b style="font-size: 12px; color: #911919;">Must be used with wide layout. Might not work properly in boxed layout</b></td>\
			</tr>\
            <tr>\
				<th><label for="vslider-height">Slider Height in %</label></th>\
				<td><input type="number" name="height" id="vslider-height" min="10" max="100" value="60" /><br />\
				<small>Video slider height in percent(%), <b>% of window height</b></small></td>\
			</tr>\
            <tr>\
				<th><label for="vslider-reduct">Height Reduction in px</label></th>\
				<td><input type="number" name="reduct" id="vslider-reduct" min="1" max="300" value="0" /><br />\
				<small>Slider height reduction in pixel(px), <b>used for header or a bottom bar</b>\
				<br />example: i-max, i-excel, i-craft has ( Header+topbar ) : 126px</small></td>\
			</tr>\
            <tr>\
				<th><label for="vslider-vurl">YouTube Video URL</label></th>\
				<td><input type="url" name="vurl" id="vslider-vurl" value="" /><br />\
				<small>YouTube Video URL</b></small></td>\
			</tr>\
            <tr>\
				<th><label for="vslider-overlay">Overlay Layer</label></th>\
				<td><select name="overlay" id="vslider-overlay">\
					<option value="none">None</option>\
					<option value="vignette">Vignette</option>\
					<option value="pixel">Pixel Pattern</option>\
				</select><br />\
				<small>Overlay Layer Tranparancy, <b>.9 least transparent and .1 heighest</b></small></td>\
			</tr>\
			<tr>\
				<th><label for="vslider-bgurl">Background Image URL</label></th>\
				<td><input type="text" name="bgurl" id="vslider-bgurl" /><br />\
				<input type="button" class="tx-button" name="tx-img-upload" id="tx-upload-button" value="Upload Image">\
			</tr>\
			<tr>\
				<th><label for="vslider-bgattachment">Background Attachment</label></th>\
				<td><select name="bgattachment" id="vslider-bgattachment">\
					<option value="fixed">Fixed</option>\
					<option value="scroll">Scroll</option>\
				</select><br />\
				<small>Select background movement</small></td>\
			</tr>\
			<tr>\
				<th><label for="vslider-bgsize">Background Size</label></th>\
				<td><select name="bgsize" id="vslider-bgsize">\
					<option value="cover">Cover</option>\
					<option value="auto">Auto</option>\
				</select><br />\
				<small>Select background size</small></td>\
			</tr>\
			<tr>\
				<th><label for="vslider-imgurl">Logo/Image URL</label></th>\
				<td><input type="text" name="imgurl" id="vslider-imgurl" /><br />\
				<input type="button" class="tx-button" name="tx-img-upload2" id="tx-upload-button2" value="Upload Image">\
			</tr>\
			<tr>\
				<th><label for="vslider-title">Title</label></th>\
				<td><input type="text" name="title" id="vslider-title" /><br />\
				<small>Enter Title</small></td>\
			</tr>\
			<tr>\
				<th><label for="vslider-content">Content</label></th>\
				<td><textarea name="content" id="vslider-content">Content ...</textarea><br />\
				<small>Enter your contents here.</small></td>\
			</tr>\
			<tr>\
				<th><label for="vslider-linktext">Link Text (Button)</label></th>\
				<td><input type="text" name="linktext" id="vslider-linktext" /><br />\
				<small>Enter Link Text</small></td>\
			</tr>\
			<tr>\
				<th><label for="vslider-linkurl">Link URL</label></th>\
				<td><input type="url" name="linkurl" id="vslider-linkurl" /><br />\
				<small>Enter Link URL</small></td>\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="vslider-submit" class="button-primary" value="Insert Slider" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_vslider.find('#vslider-table');
		
		tx_color_picker(form_vslider.find('.color'));		
		
		form_vslider.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_vslider.find('#vslider-submit').click(function(){
			
			var height = table.find('#vslider-height').val();
			var reduct = table.find('#vslider-reduct').val();
			var vurl = table.find('#vslider-vurl').val();
			//var bgcolor = table.find('#vslider-bgcolor').val();
			var overlay = table.find('#vslider-overlay').val();			
			var bgurl = table.find('#vslider-bgurl').val();
			var attachment = table.find('#vslider-bgattachment').val();
			var bgsize = table.find('#vslider-bgsize').val();
			var imgurl = table.find('#vslider-imgurl').val();
			var title = table.find('#vslider-title').val();
			var content = table.find('#vslider-content').val();
			var linktext = table.find('#vslider-linktext').val();
			var linkurl = table.find('#vslider-linkurl').val();				
			
				
			var shortcode = '[tx_vslider height="'+height+'" reduct="'+reduct+'" vurl="'+vurl+'" overlay="'+overlay+'" bgurl="'+bgurl+'" attachment="'+attachment+'" bgsize="'+bgsize+'" imgurl="'+imgurl+'" title="'+title+'"  linktext="'+linktext+'" linkurl="'+linkurl+'"]'+content+'[/tx_vslider]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			//Insert tx shortcode via media button
			//wp.media.editor.insert(shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});

		form_vslider.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
		
		
		var file_frame;
		form_vslider.find('#tx-upload-button').click(function(event){

			event.preventDefault();
			
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
			  file_frame.open();
			  return;
			}
		
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});
		
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  attachment = file_frame.state().get('selection').first().toJSON();
		
			  // Do something with attachment.id and/or attachment.url here
			  table.find('#vslider-bgurl').val(attachment.url); 	
			  
			});
			// Finally, open the modal
			file_frame.open();
			
		});
		
		//Logo image URL
		var file_frame2;
		form_vslider.find('#tx-upload-button2').click(function(event){

			event.preventDefault();
			
			// If the media frame already exists, reopen it.
			if ( file_frame2 ) {
			  file_frame2.open();
			  return;
			}
			
			// Create the media frame.
			file_frame2 = wp.media.frames.file_frame2 = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});
		
			// When an image is selected, run a callback.
			file_frame2.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  attachment = file_frame2.state().get('selection').first().toJSON();
		
			  // Do something with attachment.id and/or attachment.url here
			  table.find('#vslider-imgurl').val(attachment.url); 	
			  
			});
			// Finally, open the modal
			file_frame2.open();
		});			
			
	});	
	
	
	/*
	* youtube form
	*/
	jQuery(function(){
		var form_youtube = jQuery('<div id="youtube-form" class="tx-sh-form"><div id="tx-youtube-form"><table id="youtube-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Insert YouTube Video</h2></td>\
			</tr>\
			<tr>\
				<th><label for="youtube-url">YouTube Video URL</label></th>\
				<td><input type="url" name="url" id="youtube-url" value="" /><br />\
				<small>Enter the YouTube Video URL</small></td>\
			</tr>\
            <tr>\
				<th><label for="youtube-width">Width</label></th>\
				<td><input type="number" name="width" id="youtube-width" value=""><br />\
				<small>Leave it empty for responsive video</small></td>\
			</tr>\
			<tr>\
				<th><label for="youtube-controls">Show Controls</label></th>\
				<td><input type="checkbox" name="controls" id="youtube-controls" value="1" checked><br />\
				<small>Turn On/OFF video controls</small></td>\
			</tr>\
			<tr>\
				<th><label for="youtube-autoplay">Autoplay</label></th>\
				<td><input type="checkbox" name="autoplay" id="youtube-autoplay" value="0" /><br />\
				<small>Turn On/OFF autoplay</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="button-submit" class="button-primary" value="Insert YouTube Video" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_youtube.find('#youtube-table');
		
		//tx_color_picker(form_calltoact.find('.color'));
		
		form_youtube.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_youtube.find('#button-submit').click(function(){
			
			var youtube_url = table.find('#youtube-url').val();
			var youtube_width = table.find('#youtube-width').val();
			var youtube_controls = table.find('#youtube-controls').val();
			var youtube_autoplay = table.find('#youtube-autoplay').val();
			
			if(table.find('#youtube-controls').attr('checked')) {
				youtube_controls = 1;
			} else {
				youtube_controls = 0
			}
			
			if(table.find('#youtube-autoplay').attr('checked')) {
				youtube_autoplay = 1;
			} else {
				youtube_autoplay = 0;
			}			
	 	
			var shortcode = '[tx_youtube youtube_url="'+youtube_url+'" width="'+youtube_width+'" controls="'+youtube_controls+'" autoplay="'+youtube_autoplay+'"]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});	
		form_youtube.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});			
		
	});	
		
		
	/*
	* image form
	*/
	jQuery(function(){
		var form_image = jQuery('<div id="image-form" class="tx-sh-form"><div id="tx-image-form"><table id="image-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Insert Image</h2></td>\
			</tr>\
			<tr>\
				<th><label for="image-width">Image Width</label></th>\
				<td><input type="number" min="60" max="1200" name="width" id="image-width" value="600" /><br />\
			</tr>\
			<tr>\
				<th><label for="image-height">Image Height</label></th>\
				<td><input type="number" min="60" max="1200" name="height" id="image-height" value="600" /><br />\
			</tr>\
			<tr>\
				<th><label for="image-alt">Alternate Text</label></th>\
				<td><input type="text" name="alttext" id="image-alt" /><br />\
			</tr>\
			<tr>\
				<th><label for="image-url">Image URL</label></th>\
				<td><input type="text" name="url" id="image-url" /><br />\
				<input type="button" class="tx-button" name="tx-img-upload" id="tx-upload-button" value="Upload Image">\
			</tr>\
        </table>\
		<p class="submit">\
			<input type="button" id="image-submit" class="button-primary" value="Insert Image" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_image.find('#image-table');
		form_image.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_image.find('#image-submit').click(function(){
			
			var image_width = table.find('#image-width').val(); 
			var image_height = table.find('#image-height').val(); 			
			var image_url = table.find('#image-url').val();
			
			var shortcode = '[tx_image width="'+image_width+'" height="'+image_height+'" url="'+image_url+'"]<br/>';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});
		
		var file_frame;
		
		form_image.find('#tx-upload-button').click(function(event){

			event.preventDefault();
		
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
			  file_frame.open();
			  return;
			}
		
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});
		
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  attachment = file_frame.state().get('selection').first().toJSON();
		
			  // Do something with attachment.id and/or attachment.url here
			  table.find('#image-url').val(attachment.url); 	
			  
			});
		
			// Finally, open the modal
			file_frame.open();

			
		});			

		form_image.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
			
	});	
	

	/*
	* progressbar form
	*/
	jQuery(function(){
		var form_progressbar = jQuery('<div id="progressbar-form" class="tx-sh-form"><div id="tx-progressbar-form"><table id="progressbar-table" class="form-table">\
			<tr>\
				<td class="tx-heading" colspan="2"><h2>Insert Progress/Skill Bar</h2></td>\
			</tr>\
			<tr>\
				<th><label for="progressbar-skill_name">Name</label></th>\
				<td><input type="text" name="skill_name" id="progressbar-skill_name" value="HTML" /><br />\
				<small>Enter the skill/label for the bar</small></td>\
			</tr>\
			<tr>\
				<th><label for="progressbar-percent">Bar Percent</label></th>\
				<td><input type="text" name="percent" id="progressbar-percent" value="72" class="tx-range-prev txPrevi" />\
				<input type="range" min="1 max="100" value="72" step="1" class="txRange tx-range-slider"><br />\
				<small>Enter percent of the bar</small></td>\
			</tr>\
			<tr>\
				<th><label for="progressbar-barcolor">Bar Color</label></th>\
				<td><input type="text" class="color" name="barcolor" id="progressbar-barcolor" value="#dd9933">\<br />\
				<small>Select color for the bar</small></td>\
			</tr>\
			<tr>\
				<th><label for="progressbar-trackcolor">Track Color</label></th>\
				<td><input type="text" class="color" name="trackcolor" id="progressbar-trackcolor" value="#ddcaa8">\<br />\
				<small>Select track color</small></td>\
			</tr>\
			<tr>\
				<th><label for="progressbar-barheight">Bar Height</label></th>\
				<td><input type="text" name="barheight" id="progressbar-barheight" value="32" class="tx-range-prev txPrevi" />\
				<input type="range" min="24" max="48" value="32" step="1" class="txRange tx-range-slider"><br />\
				<small>Height of the bar</small></td>\
			</tr>\
			<tr>\
				<th><label for="progressbar-candystrip">Turn Off Candystrip Animation</label></th>\
				<td><select name="candystrip" id="progressbar-candystrip">\
					<option value="no">No</option>\
					<option value="yes">Yes</option>\
				</select><br />\
				<small>Turn Off Candystrip Animation</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="button-submit" class="button-primary" value="Insert Skill/Progress Bar" name="submit" />\
			<input type="button" id="modal-close" class="modal-close button-primary" value="Cancel" name="Cancel" />\
		</p>\
		<div class="tnext-bottom-lebel">'+tx_footer_include()+'</div>\
		</div></div>');
		
		var table = form_progressbar.find('#progressbar-table');
		
		tx_color_picker(form_progressbar.find('.color'));
		
		form_progressbar.appendTo('body').hide();
		
		// handles the click event of the submit button
		form_progressbar.find('#button-submit').click(function(){
			
			var skill_name = table.find('#progressbar-skill_name').val();
			var percent = table.find('#progressbar-percent').val();
			var barcolor = table.find('#progressbar-barcolor').val();
			var trackcolor = table.find('#progressbar-trackcolor').val();
			var barheight = table.find('#progressbar-barheight').val();
			var candystrip = table.find('#progressbar-candystrip').val();			
			
			var shortcode = '[tx_progressbar skill_name="'+skill_name+'" percent="'+percent+'" barcolor="'+barcolor+'" trackcolor="'+trackcolor+'" barheight="'+barheight+'" candystrip="'+candystrip+'"]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			jQuery.colorbox.close();
		});	
	
		form_progressbar.find('#modal-close').click(function(){
			jQuery.colorbox.close();
		});	
		
		jQuery(document).ready(function ($) {
			
			$( "input.txRange" ).each(function( index ) {
				
				var txRange = $(this);
				var txPrevi = $(this).prev( ".txPrevi" );
				
				txRange.bind("input", function() {
					var newRange = txRange.val(); 
					txPrevi.val(newRange);
				});				
			});			

		});
	});		
			
	

})();


jQuery(window).resize( function() {
	tx_resize_thickbox();
});

function tx_resize_thickbox() {
	var TB_WIDTH;
	var TB_HEIGHT;
	jQuery(document).find('#TB_window').width( TB_WIDTH ).height( TB_HEIGHT ).css( 'margin-left', - TB_WIDTH / 2 );
}


