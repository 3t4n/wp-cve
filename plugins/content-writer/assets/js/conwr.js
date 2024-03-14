(function($) {

	$(document).ready(function() {

		if(typeof _conwr_data == "undefined") return;
		////
		// SET UP GUI
		////

		var $slug_box = $("<div id='conwr-edit-slug-box'></div>");
		$slug_box.insertBefore("#edit-slug-box");
		$("<h4 id='conwr-stat-selector'><span class='active' show='0'>&bull;</span><span show='1'>&bull;</span></h4>").appendTo($slug_box);
		$("<h4 id='conwr-title-reset'><a href='#''>[reset stats]</a></h4>").appendTo($slug_box);
		$("<h4 id='conwr-title-add'><a href='#''>+ Add New Title</a></h4>").prependTo($slug_box);

		if(_conwr_data.length > 1)  {
			// First, let's normalize the probabilities because it's been reported that they are over 100% sometimes
			var total_probability = 0;
			var orig_probability = 0;
			for(var x in _conwr_data) {
				total_probability += parseInt(_conwr_data[x].probability);
				if (_conwr_data[x].title == "__CONWR_MAIN__") {
					orig_probability = parseInt(_conwr_data[x].probability);
				}
			}

			var scaleFactor = total_probability > 0 ? (100/total_probability) : 1;
			orig_probability = Math.round(parseInt(orig_probability)*scaleFactor);
			for(var k in _conwr_data) {
				trow = _conwr_data[k];
				trow.probability = Math.round(parseInt(trow.probability)*scaleFactor);
				trow.percent_better = trow.probability - orig_probability;
				conwrSetupInput(trow);
			}
			$("#conwr-stat-selector").show();
			$("#conwr-title-reset").show();
		}


		$("[name=post_title]").change(function() {
			$("#orig-post-title").val($(this).val());
		});
		$("#conwr-title-add > a").click(conwrTitleAdd);
		$("#conwr-title-reset > a").click(conwrResetStats);
		$("#conwr-stat-selector > span").click(function() {
			var $this = $(this);
			var show = $this.attr("show");
			$(".conwr-prob ul").attr("showing", show);
			$this.addClass("active").siblings().removeClass("active");
		});
	});

	conwrSetupInput = function(trow) {
		if(trow.title == "__CONWR_MAIN__") {
			$("#title").addClass("conwr-title-exp");
			$elm = $("#title");
			$("#title-prompt-text").addClass("conwr-title-label").text("Enter title test case here");
			$elm.attr("tabindex",0);
		} else {
			$label = $("<label class='conwr-title-label' for='conwr-titles["+(trow.id ? "_"+trow.id:"")+"]'>Enter title test case here</label>");
			$("#titlewrap").append($label);

			$elm = $("<input autocomplete='off' type='text' class='conwr-title-exp' "+(trow.id ? "conwr-id='"+trow.id+"'":"")+" name='conwr-titles["+(trow.id ? "_"+trow.id:"")+"]'/>");
			$elm.val(trow.title); //set the title like this to allow for magic escaping
			$elm.attr("tabindex",$("#titlewrap > input").length);
			$("#titlewrap").append($elm);

			if(trow.title) {
				$label.hide();
			}

			$elm.focus(function(){
				$(this).prev().prev().hide();
			});

			$elm.blur(function(){
				if($(this).val() === "") {
					$(this).prev().prev().show();
				} else {
					$(this).prev().prev().hide();
				}
			});
			$label.click(function(){
				$(this).next().next().focus();
			});
		}

		$e = $("<div class='conwr-title-exp-addon' />");

		$estats = $("<div class='conwr-stats'>"+trow.clicks+'/'+trow.impressions+"</div>");
		$e.append($estats);

		$esl = $("<div class='conwr-sl'><!--"+trow.stats_str+"--></div>");
		$e.append($esl);

		if(typeof trow.probability !== "undefined") {
			$eprob = $("<div class='conwr-prob'></div>");
			var $ul = $("<ul showing=0></ul>")
			var $li = null;

			if(trow.title == "__CONWR_MAIN__") {
				$li = $("<li><span>0%</span></li>");
				$li.qtip({
					content: 'This title is the first title',
					position: {
						my: 'top middle',
						at: 'bottom middle'
					},
					style: { classes: 'qtip-shadow qtip-light' }
				})
				$ul.append($li);
			} else {
				$li = $("<li><span>"+trow.percent_better+"%</span></li>");
				if (trow.percent_better < 0) {
					$li.addClass("conwr-negative");
				} else if (trow.percent_better > 0) {
					$li.addClass("conwr-positive");
				}
				$li.qtip({
					content: 'This title is performing '+trow.percent_better+'% better than the first title',
					position: {
						my: 'top middle',
						at: 'bottom middle'
					},
					style: { classes: 'qtip-shadow qtip-light' }
				})
				$ul.append($li);
			}

			$li = $("<li><span>"+trow.probability+"%</span></li>");
			$li.qtip({
				content: 'This title is being shown '+trow.probability+'% of the time',
				position: {
					my: 'top middle',
					at: 'bottom middle'
				},
				style: { classes: 'qtip-shadow qtip-light' }
			})
			$ul.append($li);

			$eprob.append($ul);
			$e.append($eprob);
		}

		if(trow.title !== "__CONWR_MAIN__") {
			$edel = $("<div class='conwr-del dashicons dashicons-no'></div>");
			$edel.click(function(){
				$this = $(this);
				var id = $this.parent().prev().attr("conwr-id");
				if(id) {
					$("form#post").append("<input type='hidden' name='conwr-removed[]' value='"+id+"' />");
				}
				$this.parent().prev().remove();
				$this.parent().prev().remove();
				$this.parent().prev().remove();
				$this.parent().remove();

				if($(".conwr-title-exp").length == 1) {
					conwrMainTitleRemove();
				}

				//conwrproRemoved(id);
			});
			$edel.qtip({
				content: 'Remove this title',
				position: {
					my: 'top middle',
					at: 'bottom middle'
				},
				style: { classes: 'qtip-shadow qtip-light' }
			});
			$e.append($edel);
		}

		$e.insertAfter($elm);

		$esl.sparkline('html', { type:'bar', barColor:'#AAA', height: "16px", tooltipFormatter: function(sl, pts, fs){
			days = ["Today","Yesterday","2 days ago","3 days ago","4 days ago","5 days ago","6 days ago"];
			day = Math.abs(fs[0].offset-6);
			if(fs[0].value == 1) {
				return "<b>"+days[day]+ ":</b> 1 view";
			}else{
				return "<b>"+days[day]+ ":</b> " +fs[0].value + " views";
			}
		}});

		$estats.qtip({
			content: function() {
				ms = $(this).text().match(/(\d+)\/(\d+)/);
				if(ms) {
					p = Math.round( (ms[1]/ms[2]) * 1000) / 10;
					if(isNaN(p)) {
						str = (isNaN(p) ? "0" : p) + "%";
					} else {
						str = p+"%";
					}
					str += "<br/>";
					str +=  ms[1] + " view" + ((ms[1] == "1") ? "" : "s") + "<br/>";
					str +=  ms[2] + " impression"+ ((ms[2] == "1") ? "" : "s");
					return str;
				}
				return false;
			},
			position: {
				my: 'top middle',
				at: 'bottom middle'
			},
			style: { classes: 'qtip-shadow qtip-light' }
		});

		$("<div class='cf'/>").insertAfter($e);

		$e = $("<div class='conwr-title-exp-pre "+(trow.enabled=='1'?"'":"disabled")+"' title='Test qtip'><input type='checkbox' name='conwr-enabled["+(trow.id ? "_"+trow.id:"")+"]' "+(trow.enabled=='1'?"checked='checked'":"")+"/></div>");
		$e.insertBefore($elm);
		$e.click(function(){
			if($(this).hasClass("disabled")) {
				$(this).removeClass("disabled");
				$(this).children("input").get(0).checked = true;
			} else {
				$(this).addClass("disabled");
				$(this).children("input").get(0).checked = false;
			}
			var qapi = $(this).data('qtip');
			var newtip = conwrStatusQtipContent(this);
			qapi.options.content.html = newtip; // update content stored in options
			qapi.elements.content.html(newtip); // update visible tooltip content
			qapi.render(); // redraw to adjust tooltip borders
		});

		$e.qtip({
			content: function() {
				return conwrStatusQtipContent(this);
			},
			position: {
				my: 'top middle',
				at: 'bottom middle'
			},
			style: { classes: 'qtip-shadow qtip-light'}
		});

		if("conwrproSetupInput" in window) {
			window.conwrproSetupInput(trow, $elm);
		}
	};

	conwrStatusQtipContent = function(elm) {
		if($(elm).hasClass("disabled")) {
			return "Test case is <b>disabled</b>.<br/><em>Click to enable.</em>";
		} else {
			return "Test case is <b>enabled</b>.<br/><em>Click to disable.</em>";
		}
	};

	conwrResetStats = function(ev){
		var data = {
			'action': 'conwr_stat_reset',
			'id': $("#post_ID").val()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post(ajaxurl, data, function(response) {
			window.location.reload();
		});
		return false;
	};

	// conwrHideSaleNag = function(ev, nagId){
	// 	var data = {
	// 		'action': 'conwr_hide_nag',
	// 		'id': $(ev.target).data("nag-id")
	// 	};

	// 	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	// 	$.post(ajaxurl, data, function(response) {
	// 		$(ev.target).closest("div.update-nag").remove();
	// 	});
	// 	return false;
	// };

	conwrTitleAdd = function(ev){
		// We are adding one for the first time for this post so setup the orginal title
		if(!$("#title").hasClass("conwr-title-exp")) {
			conwrSetupInput({id:null,stats_str:"0,0,0,0,0,0,0",title:"__CONWR_MAIN__",clicks:0,impressions:0,enabled:1});
			$("#conwr-stat-selector").show().css("opacity", 0); // we don't need to show it here since it's either already showing or we don't want to show it becuase they are just starting
			$("#conwr-title-reset").show();
		}
		conwrSetupInput({id:null,stats_str:"0,0,0,0,0,0,0",title:"",clicks:0,impressions:0,enabled:1});
		return false;
	};

	conwrMainTitleRemove = function() {
		$(".conwr-title-exp-pre").remove();
		$(".conwr-title-exp-addon").remove();
		$(".conwr-title-exp").removeClass("conwr-title-exp");
		$("#title-prompt-text").removeClass("conwr-title-label").text("Enter title here");
		$("#conwr-stat-selector").hide();
		$("#conwr-title-reset").hide();
	};
})(jQuery);
