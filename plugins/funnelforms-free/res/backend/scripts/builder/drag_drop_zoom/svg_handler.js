const af2_svg_append_path = (draw_svg_selector, path_id, x1, y1, x2, y2) => {
    jQuery(draw_svg_selector).append('<path id="'+path_id+'" d="" class="af2_connection_path" data-x1="'+x1+'" data-y1="'+y1+'" data-x2="'+x2+'" data-y2="'+y2+'"/>');
}

const af2_svg_change_path = (path_selector, d, x1, y1, x2, y2) => {
    if(d != null) jQuery(path_selector).attr('d', d); 
    if(x1 != null) jQuery(path_selector).attr('data-x1', x1);
    if(x2 != null) jQuery(path_selector).attr('data-x2', x2);
    if(y1 != null) jQuery(path_selector).attr('data-y1', y1);
    if(y2 != null) jQuery(path_selector).attr('data-y2', y2);
}

const af2_svg_calc_initial_path = (target, offset_selector) => {
    let offsetElement = jQuery(offset_selector);
    const rect = target.getBoundingClientRect();
    const parentRect = offsetElement[0].getBoundingClientRect();

    const offsetX = parentRect.x;
    const offsetY = parentRect.y;

    const x1 = rect.x + (rect.width / 2) - offsetX;
    const y1 = rect.y + (rect.height / 2)  - offsetY;

    return {x1: x1, y1: y1};
}

const af2_svg_calc_moving_path = (path_selector, movex, movey) => {
    const x1 = parseFloat(jQuery(path_selector).data('x1'));
    const y1 = parseFloat(jQuery(path_selector).data('y1'));
    const x2 = parseFloat(jQuery(path_selector).data('x2')) + movex;
    const y2 = parseFloat(jQuery(path_selector).data('y2')) + movey;

    let d = '';
    d += 'M'+x1+' '+y1+' C ';

    const diffX = x2 - x1;
    const diffY = y2 - y1;

    let xdiff = diffX != 0 ? diffX / 1.5 : 0;
    let ydiff = diffY != 0 ? diffY / 60 : 0;

	xdiff = Math.abs(xdiff);
	
    d += (x1+xdiff) + ' ' + (y1+ydiff) + ',';
    d += (x2-xdiff) + ' ' + (y2-ydiff) + ',';
    d += x2 + ' ' + y2;

    return {x2: x2, y2: y2, d: d};
}

const af2_svg_calc_moved_paths_d = (x1, y1, x2, y2) => {
    let d = '';
    d += 'M'+x1+' '+y1+' C ';

    const diffX = x2 - x1;
    const diffY = y2 - y1;

    let xdiff = diffX != 0 ? diffX / 1.5 : 0;
    let ydiff = diffY != 0 ? diffY / 60 : 0;

	xdiff = Math.abs(xdiff);
	
    d += (x1+xdiff) + ' ' + (y1+ydiff) + ',';
    d += (x2-xdiff) + ' ' + (y2-ydiff) + ',';
    d += x2 + ' ' + y2;

    return d;
}

const af2_svg_redraw = (draw_svg_selector) => {
    const actualSvgContent = jQuery(draw_svg_selector).html();
    jQuery(draw_svg_selector).html(actualSvgContent);
}