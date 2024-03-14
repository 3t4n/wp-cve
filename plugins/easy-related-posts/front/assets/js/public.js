/*!
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

(function ( $ ) {
	$.fn.erpDomNext = function() {
        return this
            .next()
            .add(this.next())
            .add(this.parents().filter(function() {
                return $(this).next().length > 0;
            }).next()).first();
    };

    $.fn.erpDomPrevious = function() {
        return this
            .prev().find("*:last")
            .add(this.parent())
            .add(this.prev())
            .last();
    };

    $.fn.getMaxZ = function(opt){
    	 /// <summary>
        /// Returns the max zOrder in the document (no parameter)
        /// Sets max zOrder by passing a non-zero number
        /// which gets added to the highest zOrder.
        /// </summary>
        /// <param name="opt" type="object">
        /// inc: increment value,
        /// group: selector for zIndex elements to find max for
        /// </param>
        /// <returns type="jQuery" />
        var def = { inc: 10, group: "*" };
        $.extend(def, opt);
        var zmax = 0;
        $(def.group).each(function() {
            var cur = parseInt($(this).css('z-index'));
            zmax = cur > zmax ? cur : zmax;
        });
        return zmax;
    };

	$(function () {

		// Place your public-facing JavaScript here

	});

}(jQuery));