/***********************/
/* Init jquery sortable*/
/***********************/
var plo_reorder = function(){
	var contador = 1;
	jQuery("#sortable li").each(
		function(){
			if(contador%2==0){
				jQuery(this).addClass("odd");
			} else {
				jQuery(this).removeClass("odd");
			}
			contador++;
		}
	);
}

jQuery(function(){
	jQuery( "#sortable" ).sortable({
		stop: function(){
			plo_reorder();			
		}
	});
});

jQuery("#sortable li .sort-data").on("mouseover", function(){
	jQuery(this).parent().addClass("border-info");
});

jQuery("#sortable li .sort-data").on("mouseout", function(){
	jQuery(this).parent().removeClass("border-info");
});

jQuery("#btn-save-orden").on("click", function(e){
	e.preventDefault();
	var ordenFinal = "";
	if( in_order_on_ready != 0 ) ordenFinal = in_order_on_ready;
	jQuery("#sortable li").each(function(){
		if(ordenFinal == ""){
			ordenFinal=jQuery(this).attr("cadenaplugin");
		} else {
			ordenFinal=ordenFinal+","+jQuery(this).attr("cadenaplugin");
		}
	});
	jQuery("#nuevoOrdenPlugin").val(ordenFinal);
	jQuery("#ordenPluginForm").submit();
});

jQuery('.sortador .dashicons-arrow-up').click(function(){
	var current = jQuery(this).parent();
	current.removeClass("border-info");
	current.prev().before(current);
	plo_reorder();
});
jQuery('.sortador .dashicons-arrow-down').click(function(){
	var current = jQuery(this).parent();
	current.removeClass("border-info");
	current.next().insertBefore(current);
	plo_reorder();
});
