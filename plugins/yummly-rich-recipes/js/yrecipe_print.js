/*
Plugin Name: Yummly Rich Recipes
Plugin URI: http://plugin.yummly.com
Description: A plugin that adds all the necessary microdata to your recipes, so they will show up in Google's Recipe Search
Version: 4.2
Author: Yummly.com
Author URI: http://www.yummly.com/
License: CC 3.0 http://creativecommons.org/licenses/by/3.0/

Copyright 2009-2016 Yummly
This code is derived from an example provided by Ulrik D. Hansen and licensed under the Creative Commons Attribution License.
You can see the original post at http://www.808.dk/?code-javascript-print
*/

/*
    This section refers to any extensions made to the original work.

    This file is part of Yummly Rich Recipes.

    Yummly Rich Recipes is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Yummly Rich Recipes is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Yummly Rich Recipes. If not, see <http://www.gnu.org/licenses/>.
*/

var yrWin=null;
function yrPrint(id, directory, borderStyle)
{
	var content = document.getElementById(id).innerHTML;

	yrWin = window.open();
	self.focus();

  if (borderStyle) {
    content = content.replace('id="yrecipe-innerdiv"', 'id="yrecipe-innerdiv" style="border:' + borderStyle + ' black"');
  }

	yrWin.document.open();
	yrWin.document.write('<html><head>');
	yrWin.document.write('<link charset=\'utf-8\' href=\'' + directory + 'styles/yrecipe-print.css\' rel=\'stylesheet\' type=\'text/css\' />');
	yrWin.document.write('</head><body">');
	yrWin.document.write('<div id=\'yrecipe-print-container\' >');
	yrWin.document.write(content);
	yrWin.document.write('</div>');
	yrWin.document.write('<script>print()</script>');
	yrWin.document.write('</body></html>');

	yrWin.document.close();

}
