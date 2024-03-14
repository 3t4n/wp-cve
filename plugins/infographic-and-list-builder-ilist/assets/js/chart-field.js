jQuery(document).ready(function($)
{

	$('.color-field').wpColorPicker();

	$('#ilist_chart'). on("click", function(e){
		e.preventDefault();
		
		$('#ilist-chart-field-modal').show();
		
		
	})
	$( '.ilist-chart-field-modal-close' ).on( 'click', function() {
		
		$('#ilist-chart-field-modal').hide();
	});
	$('#chartaddrow'). on("click", function (e){
		e.preventDefault();
		$("#datasettable tbody").append('<tr><td class="check-column" scope="row"><input type="text" name="label[]" /></td><td class="column-columnname"><input type="number" name="value[]" /></td><td class="column-columnname"><input type="text" name="bgcolor[]" class="color-field" /></td><td class="column-columnname"><a href="javascript:void(0);" class="removerow">Remove</a></td></tr>');
		$('.color-field').wpColorPicker();
	})
	$('#chartremoverow'). on("click", function (e){
		e.preventDefault();
		$('#datasettable').each(function(){
			if($('tbody', this).length > 0){
				$('tbody tr:last', this).remove();
			}else {
				$('tr:last', this).remove();
			}
		});
	})
	$('#datasettable').on ("click", ".removerow", function(e){
		e.preventDefault();
		
		$(this).parent().parent().remove();
	})
//charttype onchange event

	$('.getallvalue'). on("click", function(e){
		e.preventDefault();
		var setlabel = [];
		var setvalue = [];
		var setbgcolor = [];
		
		$('#datasettable tbody input').each(function(){
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
		
		
		var bgcolor = "'" + setbgcolor.join("','") + "'";
		var stus = 0;
		if(setlabel===''){
			stus = 1;
		}
		
		var charttype = $('#charttype').val();
		
		var carttitle = $('#charttitle').val();
		if(carttitle===''){
			stus = 1;
		}
		var datasetname = $('#datasetname').val();
		if(datasetname===''){
			stus = 1;
		}
		var backgroundcolor = $('#backgroundcolor').val();
		var bordercolor = $('#bordercolor').val();
		var pointerstyle = $('#pointerstyle').val();
		var lstyle = $('#lstyle').val();

		if(stus==1){
			alert('Please fill the form correctly!');
		}else{
			var shortcode = '[qcld-chart label="'+setlabel+'" value="'+setvalue+'" type="'+charttype+'" title="'+carttitle+'" datasetname="'+datasetname+'" backgroundcolor="'+backgroundcolor+'" bgcolor="'+bgcolor+'" bordercolor="'+bordercolor+'" pointerstyle="'+pointerstyle+'" linestyle="'+lstyle+'"]';
		$('#ilist_chart').val(shortcode);
		$('#ilist-chart-field-modal').hide();
		}
		
		
	})
	
});