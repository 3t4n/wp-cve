/*!
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
(function(a){a.fn.erpDomNext=function(){return this.next().add(this.next()).add(this.parents().filter(function(){return a(this).next().length>0}).next()).first()};a.fn.erpDomPrevious=function(){return this.prev().find("*:last").add(this.parent()).add(this.prev()).last()};a.fn.getMaxZ=function(b){var c={inc:10,group:"*"};a.extend(c,b);var d=0;a(c.group).each(function(){var e=parseInt(a(this).css("z-index"));d=e>d?e:d});return d};a(function(){})}(jQuery));