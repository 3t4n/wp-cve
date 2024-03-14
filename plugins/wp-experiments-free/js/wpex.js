(function($) {

	$(document).ready(function() {
		if(typeof _wpex_data == "undefined") return;
		////
		// SET UP GUI
		////

		var $slug_box = $("<div id='wpex-edit-slug-box'></div>");
		$slug_box.insertAfter("#wpex-titles-metabox-cont");
		$("<h4 id='wpex-stat-selector'><span class='active' show='0'>&bull;</span><span show='1'>&bull;</span></h4>").appendTo($slug_box);
		$("<h4 id='wpex-title-reset'><a href='#''>[reset stats]</a></h4>").appendTo($slug_box);
		$("<h4 id='wpex-title-add'><a href='#''>+ Add New Title</a></h4>").prependTo($slug_box);

		if(_wpex_data.length > 1)  {
			// First, let's normalize the probabilities because it's been reported that they are over 100% sometimes
			var total_probability = 0;
			var orig_probability = 0;
			for(var x in _wpex_data) {
				total_probability += parseInt(_wpex_data[x].probability);
				if (_wpex_data[x].title == "__WPEX_MAIN__") {
					orig_probability = parseInt(_wpex_data[x].probability);
				}
			}

			var scaleFactor = total_probability > 0 ? (100/total_probability) : 1;
			orig_probability = Math.round(parseInt(orig_probability)*scaleFactor);
			for(var k in _wpex_data) {
				trow = _wpex_data[k];
				trow.probability = Math.round(parseInt(trow.probability)*scaleFactor);
				trow.percent_better = trow.probability - orig_probability;
				wpexSetupInput(trow);
			}
			$("#wpex-stat-selector").show();
			$("#wpex-title-reset").show();
			$("#wpex-main-title").show();
		}


		$("[name=post_title]").change(function() {
			$("#orig-post-title").val($(this).val());
		});
		$("#wpex-title-add > a").click(wpexTitleAdd);
		$("#wpex-title-reset > a").click(wpexResetStats);
		$("#wpex-stat-selector > span").click(function() {
			var $this = $(this);
			var show = $this.attr("show");
			$(".wpex-prob ul").attr("showing", show);
			$this.addClass("active").siblings().removeClass("active");
		});
	});

	wpexSetupInput = function(trow) {
		if(trow.title == "__WPEX_MAIN__") {
			$("#wpex-main-title").addClass("wpex-title-exp");
			$elm = $("#wpex-main-title");
			$("#title-prompt-text").addClass("wpex-title-label").text("Enter title test case here");
			$elm.attr("tabindex",0);
		} else {
			$label = $("<label class='wpex-title-label' for='wpex-titles["+(trow.id ? "_"+trow.id:"")+"]'>Enter title test case here</label>");
			$("#wpex-titlewrap").append($label);

			$elm = $("<input autocomplete='off' type='text' class='wpex-title-exp' "+(trow.id ? "wpex-id='"+trow.id+"'":"")+" name='wpex-titles["+(trow.id ? "_"+trow.id:"")+"]'/>");
			$elm.val(trow.title); //set the title like this to allow for magic escaping
			$elm.attr("tabindex",$("#titlewrap > input").length);
			$("#wpex-titlewrap").append($elm);

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

		$e = $("<div class='wpex-title-exp-addon' />");

		$estats = $("<div class='wpex-stats'>"+trow.clicks+'/'+trow.impressions+"</div>");
		$e.append($estats);

		$esl = $("<div class='wpex-sl'><!--"+trow.stats_str+"--></div>");
		$e.append($esl);

		if(typeof trow.probability !== "undefined") {
			$eprob = $("<div class='wpex-prob'></div>");
			var $ul = $("<ul showing=0></ul>")
			var $li = null;

			if(trow.title == "__WPEX_MAIN__") {
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
					$li.addClass("wpex-negative");
				} else if (trow.percent_better > 0) {
					$li.addClass("wpex-positive");
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

		if(trow.title !== "__WPEX_MAIN__") {
			$edel = $("<div class='wpex-del dashicons dashicons-no'></div>");
			// There is a bug here that I don't want to fix right now. If you create a title in gutenberg, save it, and
			// and then delete it without reloading, it won't actually delete it (because gutenberg doesn't refresh when
			// it saves so we don't get the title id)
			$edel.click(function(){
				$this = $(this);
				var id = $this.parent().prev().attr("wpex-id");
				if(id) {
					$("#wpex-titles-metabox-cont").append("<input type='hidden' name='wpex-removed[]' value='"+id+"' />");
				}
				$this.parent().prev().remove();
				$this.parent().prev().remove();
				$this.parent().prev().remove();
				$this.parent().remove();

				if($(".wpex-title-exp").length == 1) {
					wpexMainTitleRemove();
				}
				if ('wpexproRemoved' in window) {
					wpexproRemoved(id);
				}
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

		$e = $("<div class='wpex-title-exp-pre "+(trow.enabled=='1'?"'":"disabled")+"' title='Test qtip'><input type='checkbox' name='wpex-enabled["+(trow.id ? "_"+trow.id:"")+"]' "+(trow.enabled=='1'?"checked='checked'":"")+"/></div>");
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
			var newtip = wpexStatusQtipContent(this);
			qapi.options.content.html = newtip; // update content stored in options
			qapi.elements.content.html(newtip); // update visible tooltip content
			qapi.render(); // redraw to adjust tooltip borders
		});

		$e.qtip({
			content: function() {
				return wpexStatusQtipContent(this);
			},
			position: {
				my: 'top middle',
				at: 'bottom middle'
			},
			style: { classes: 'qtip-shadow qtip-light'}
		});

		if("wpexproSetupInput" in window) {
			window.wpexproSetupInput(trow, $elm);
		}
	};

	wpexStatusQtipContent = function(elm) {
		if($(elm).hasClass("disabled")) {
			return "Test case is <b>disabled</b>.<br/><em>Click to enable.</em>";
		} else {
			return "Test case is <b>enabled</b>.<br/><em>Click to disable.</em>";
		}
	};

	wpexResetStats = function(ev){
		var data = {
			'action': 'wpex_stat_reset',
			'id': $("#post_ID").val()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		$.post(ajaxurl, data, function(response) {
			window.location.reload();
		});
		return false;
	};

	wpexTitleAdd = function(ev){
		// We are adding one for the first time for this post so setup the original title
		if(!$("#wpex-main-title").hasClass("wpex-title-exp")) {
			wpexSetupInput({id:null,stats_str:"0,0,0,0,0,0,0",title:"__WPEX_MAIN__",clicks:0,impressions:0,enabled:1});
			$("#wpex-stat-selector").show().css("opacity", 0); // we don't need to show it here since it's either already showing or we don't want to show it becuase they are just starting
			$("#wpex-title-reset").show();
			$("#wpex-main-title").show();
		}
		wpexSetupInput({id:null,stats_str:"0,0,0,0,0,0,0",title:"",clicks:0,impressions:0,enabled:1});
		return false;
	};

	wpexMainTitleRemove = function() {
		$(".wpex-title-exp-pre").remove();
		$(".wpex-title-exp-addon").remove();
		$(".wpex-title-exp").removeClass("wpex-title-exp");
		$("#title-prompt-text").removeClass("wpex-title-label").text("Enter title here");
		$("#wpex-main-title").hide();
		$("#wpex-stat-selector").hide();
		$("#wpex-title-reset").hide();
	};
})(jQuery);
