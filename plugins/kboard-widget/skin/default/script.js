/**
 * @author http://www.cosmosfarm.com/
 */

jQuery(document).ready(function(){
	jQuery('.kboard-default-widget-list').each(function(){
		jQuery('.kboard-widget-list', this).first().addClass('active-list');
		jQuery('.kboard-widget-button', this).first().addClass('active');
	});
});

function kboard_widget_change(index, tab){
	var wrap = jQuery(tab).parents('.kboard-default-widget-list');
	jQuery('.kboard-widget-button', wrap).removeClass('active');
	jQuery('.kboard-widget-button[data-button-index="'+index+'"]', wrap).addClass('active');
	jQuery('.kboard-widget-list', wrap).removeClass('active-list');
	jQuery('.kboard-widget-list[data-content-index="'+index+'"]', wrap).addClass('active-list');
	return false;
}

window.addEventListener('load', function(){
	
	jQuery('.kboard-widget-list').each(function(){
		var touch_area = this;
		var index = jQuery(this).data('content-index');
		var wrap = jQuery(this).parent('.kboard-default-widget-list');
		var length = jQuery(this).parent('.kboard-default-widget-list').children('.kboard-widget-list').length;
		
		touch_area.addEventListener('touchstart', function(){ // 터치 시작
			var touch = event.touches[0];
			touch_start_X = touch.clientX;
			touch_start_Y = touch.clientY;
		}, false);
		
		touch_area.addEventListener('touchend', function(){ // 터치 끝
			if(event.touches.length == 0){
				var touch = event.changedTouches[event.changedTouches.length-1];
				touch_end_X = touch.clientX;
				touch_end_Y = touch.clientY;
				touch_offset_X = touch_end_X - touch_start_X;
				touch_offset_Y = touch_end_Y - touch_start_Y;
				
				if(Math.abs(touch_offset_X) >= 50){
					jQuery(this).removeClass('active-list');
					jQuery('.kboard-widget-button[data-button-index="'+index+'"]', wrap).removeClass('active');
					
					if(touch_offset_X < 0){ // 왼쪽, 오른쪽 판단
						if(index == length-1){ // 오른쪽에서 왼쪽 (다음 탭)
							jQuery('.kboard-widget-list[data-content-index=0]', wrap).addClass('active-list');
							jQuery('.kboard-widget-button[data-button-index=0]', wrap).addClass('active');
						}
						else{
							jQuery(this).next().addClass('active-list');
							jQuery('.kboard-widget-button[data-button-index="'+(index+1)+'"]', wrap).addClass('active');
						}
					}
					else{
						if(index == 0){ // 왼쪽에서 오른쪽 (이전 탭)
							jQuery('.kboard-widget-list[data-content-index="'+(length-1)+'"]', wrap).addClass('active-list');
							jQuery('.kboard-widget-button[data-button-index="'+(length-1)+'"]', wrap).addClass('active');
						}
						else{
							jQuery(this).prev().addClass('active-list');
							jQuery('.kboard-widget-button[data-button-index="'+(index-1)+'"]', wrap).addClass('active');
						}
					}
				}
			}
		}, false);
	});
	
}, false); // end window.onload