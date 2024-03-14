(function($) {
	
	if(!window.codecabin)
		window.codecabin = {};
	
	if(codecabin.DeactivateFeedbackForm)
		return;
	
	codecabin.DeactivateFeedbackForm = function(plugin)
	{
		var self = this;
		var strings = codecabin_deactivate_feedback_form_strings;
		
		this.plugin = plugin;
		
		// Dialog HTML
		var element = $('\
			<div class="codecabin-deactivate-dialog" data-remodal-id="' + plugin.slug + '">\
				<form>\
					<input type="hidden" name="plugin"/>\
					<input type="hidden" name="slug" value="' + plugin.slug + '"/>\
					<input type="hidden" name="version" value="' + plugin.version + '"/>\
					<h2>' + strings.quick_feedback + '</h2>\
					<p>\
						' + strings.foreword + '\
					</p>\
					<ul class="codecabin-deactivate-reasons"></ul>\
					<input name="comments" placeholder="' + strings.brief_description + '"/>\
					<br>\
					<p class="codecabin-deactivate-dialog-buttons">\
						<input type="submit" class="button confirm" value="' + strings.skip_and_deactivate + '"/>\
						<button data-remodal-action="cancel" class="button button-primary">' + strings.cancel + '</button>\
					</p>\
				</form>\
			</div>\
		')[0];
		this.element = element;
		
		$(element).find("input[name='plugin']").val(JSON.stringify(plugin));
		
		$(element).on("click", "input[name='reason']", function(event) {
			$(element).find("input[type='submit']").val(
				strings.submit_and_deactivate
			);
		});
		
		$(element).find("form").on("submit", function(event) {
			self.onSubmit(event);
		});
		
		// Reasons list
		var ul = $(element).find("ul.codecabin-deactivate-reasons");
		for(var key in plugin.reasons)
		{
			var li = $("<li><input type='radio' name='reason'/> <span></span></li>");
			
			$(li).find("input").val(key);
			$(li).find("input").attr("id",key);
			$(li).find("span").html(plugin.reasons[key]);
			
			$(ul).append(li);
		}
		
		// Listen for deactivate
		$("#the-list [data-slug='" + plugin.slug + "'] .deactivate>a").on("click", function(event) {
			self.onDeactivateClicked(event);
		});
		
		$(element).find("input[name='reason']").click( function(){
			
		
			if ($(element).find("input[id='other']:checked").length > 0) {
				console.log('checked');
				$(element).find("input[name='comments']").prop('required',true);
				$(element).find("input[name='comments']").focus(); 
			}else {
				$(element).find("input[name='comments']").prop('required',false);
					console.log('not checked');
			}
		});
	}
	
	codecabin.DeactivateFeedbackForm.prototype.onDeactivateClicked = function(event)
	{
		this.deactivateURL = event.target.href;
		
		event.preventDefault();
		
		if(!this.dialog)
			this.dialog = $(this.element).remodal();
		this.dialog.open();
	}
	
	codecabin.DeactivateFeedbackForm.prototype.onSubmit = function(event)
	{
		var element = this.element;
		var strings = codecabin_deactivate_feedback_form_strings;
		var self = this;
		var data = $(element).find("form").serialize();
		$(element).find("button, input[type='submit']").prop("disabled", true);
		
		
			
		if($(element).find("input[name='reason']:checked").length)
		{


			


			
			$(element).find("input[type='submit']").val(strings.thank_you);

			
			$.ajax({
				type:		"POST",
				url:		"https://extend-wp.com/wp-json/feedback/v2/post",
				data:		data,
				beforeSend: function(data) {								
						console.log(data);
				},				
				complete:	function() {
					
					window.location.href = self.deactivateURL;
				}
			});

		}
		else
		{
			$(element).find("input[type='submit']").val(strings.please_wait);
			window.location.href = self.deactivateURL;
		}
		
		event.preventDefault();
		return false;
	}
	
	$(document).ready(function() {
		
		for(var i = 0; i < codecabin_deactivate_feedback_form_plugins.length; i++)
		{
			var plugin = codecabin_deactivate_feedback_form_plugins[i];
			new codecabin.DeactivateFeedbackForm(plugin);
		}
		
	});
	
	
	
})(jQuery);
