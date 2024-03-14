(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });
	
    
})( jQuery );

jQuery(document).ready(function($){

	
        
    var sbdstoredNoticeId = localStorage.getItem('qcld_ichart_Notice_set');
    var qcld_ichart_Notice_time_set = localStorage.getItem('qcld_ichart_Notice_time_set');

    var notice_current_time = Math.round(new Date().getTime() / 1000);

    if ('ichart-msg' == sbdstoredNoticeId && qcld_ichart_Notice_time_set > notice_current_time  ) {
        $('#message-ichart').css({'display': 'none'});
    }

    $(document).on('click', '#message-ichart .notice-dismiss', function(e){

        var currentDom = $(this);
        var currentWrap = currentDom.closest('.notice');
        currentWrap.css({'display': 'none'});
        localStorage.setItem('qcld_ichart_Notice_set', 'ichart-msg');
        var ts = Math.round(new Date().getTime() / 1000);
        var tsYesterday = ts + (24 * 3600);
        localStorage.setItem('qcld_ichart_Notice_time_set', tsYesterday);
        console.log(tsYesterday)

    });
	
	$('#ichart_shortcode_generator_meta').on('click', function(e){
		$('#ichart-qcld-chart-field-modal').show();
	})
	
	$(document).on( 'click', '.ichart_copy_close', function(){
		$('.ichart-chart-field-modal-icons').show();
		$('.ichart_shortcode_container').hide();
        $('#ichart-qcld-chart-field-modal').hide();
    })
	
	var selector = '';
	
	$('.iChartgetallvalue'). on("click", function(e){
		e.preventDefault();
		var setlabel = [];
		var setvalue = [];
		var setbgcolor = [];
		
		$('#ichart-iChartdatasettable tbody input').each(function(){
			//values[$(this).attr('name')] = $(this).val();
			
			if($(this).attr('name')==='label[]'){
				setlabel.push($(this).val());
			}
			if($(this).attr('name')==='value[]'){
				setvalue.push($(this).val());
			}
			if($(this).attr('name')==='bgcolor[]'){
				setbgcolor.push($(this).val());
			}
		})
		var stus = 0;
		if(setlabel===''){
			stus = 1;
		}
		var bgcolor = "'" + setbgcolor.join("','") + "'";
		var charttype = $('#ichart-charttype').val();
		
		var carttitle = $('#ichart-charttitle').val();
		if(carttitle===''){
			stus = 1;
		}
		var datasetname = $('#ichart-datasetname').val();
		if(datasetname===''){
			stus = 1;
		}
		var backgroundcolor = $('#ichart-backgroundcolor').val();
		var width = $('#ichart-width').val();
		var bordercolor = $('#ichart-bordercolor').val();
		var pointerstyle = $('#ichart-pointerstyle').val();
		var lstyle = $('#ichart-lstyle').val();
		var aspectratio = $('#ichart-aspectratio').val();

		if(stus==1){
			alert('Please fill the form correctly!');
		}else{
			var shortcode = '[qcld-ichart label="'+setlabel+'" aspectratio="'+aspectratio+'" value="'+setvalue+'" type="'+charttype+'" title="'+carttitle+'" datasetname="'+datasetname+'" width="'+width+'" backgroundcolor="'+backgroundcolor+'" bgcolor="'+bgcolor+'" bordercolor="'+bordercolor+'" pointerstyle="'+pointerstyle+'" linestyle="'+lstyle+'"]';
		//tinyMCE.activeEditor.selection.setContent(shortcode);
		//$('#ichart-qcld-chart-field-modal').hide();
		$('.ichart-chart-field-modal-icons').hide();
		$('.ichart_shortcode_container').show();
		$('#ichart_shortcode_container').val(shortcode);
		$('.ichart_copy_close').attr('short-data',shortcode);
		$('#ichart_shortcode_container').select();
		document.execCommand('copy');
		
		}
		
		
	});
	
})