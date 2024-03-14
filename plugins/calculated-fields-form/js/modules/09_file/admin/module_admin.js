fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'file' ] = {
	'tutorial' : 'https://cff.dwbooster.com/documentation#file-module',
	'toolbars'		: {
		'file' : {
			'label' : 'Handling of Files and Their Properties (Experimental)',
			'buttons' : [
                {
                    "value" : "PDFPAGESNUMBER",
                    "code" : "PDFPAGESNUMBER(",
                    "tip" : "<p>Returns the pages number in a PDF file. It receives the &quot;Upload File&quot; field name or the numeric component of the field name and returns the number of pages.<br><br> Ex. <strong>PDFPAGESNUMBER(fieldname123|n);</strong> or <strong>PDFPAGESNUMBER(123);</strong><br><br>If the Upload File field accepts multiple files, the operation result would be an array with the number of pages on files.</p>"
                },
                {
                    "value" : "IMGDIMENSION",
                    "code" : "IMGDIMENSION(",
                    "tip" : "<p>Returns an object with two attributes: width and height of an image. It receives the &quot;Upload File&quot; field name or the numeric component of the field name and returns an object with the dimensions of the image selected.<br><br> Ex. <strong>IMGDIMENSION(fieldname123|n);</strong> or <strong>IMGDIMENSION(123);</strong><br><br>If the Upload File field accepts multiple files, the operation result would be an array objects, one of the per selected file.</p>"
                },
                {
                    "value" : "VIEWFILE",
                    "code" : "VIEWFILE(",
                    "tip" : "<p>Displays the files into a tag. Pass the &quot;Upload File&quot; field name or the numeric component of the field name and the tag id where display the files. <br><br> Ex. <strong>VIEWFILE(fieldname123|n, &quot;tag-id&quot;);</strong> or <strong>VIEWFILE(123, &quot;tag-id&quot;);</strong><br><br>If the Upload File field accepts multiple files, the operation will include multiple viewers.</p>"
                },
                {
                    "value" : "CSVTOJSON",
                    "code" : "CSVTOJSON(",
                    "tip" : "<p>Takes the client CSV file and converts it into a JSON object you can use with the equations and DS fields.</p><p>CSVTOJSON(field name or text in CSV format, arguments(optional))</p><p>Pass the &quot;Upload File&quot; field name or the numeric component of the field name, or the CSV content directly, and the arguments object with CSV attributes like, headline (the CSV file includes headline or not), delimiter (the columns delimiter symbol, uses comma by default), quote (the quote symblo to enclose text columns, uses double-quote by default). </p><p>Ex. <strong>CSVTOJSON(fieldname123|n);</strong><br><br><strong> CSVTOJSON(123);</strong><br><br><strong>CSVTOJSON(fieldname123|n,{headline:1,delimiter:&quot;,&quot;});</strong></p>"
                },
				{
                    "value" : "JSONTOCSV",
                    "code" : "JSONTOCSV(",
                    "tip" : "<p>Takes an array or JSON object and generates a CSV you can store on computer.</p><p>JSONTOCSV(array or JSON, columns delimiter (optional), CSV file name (optional))</p><p>Pass the array or JSON object, the columns delimiter symbol (the plugin uses the comma symbol by default), and the file name (Like data.csv). If the file name is omitted, the operation returns the output in CSV format but does not generate the file</p><p>Ex. <strong>JSONTOCSV([{a:1,b:2}, {a:34,b:8},{a:7,b:2}], &quot;,&quot;, &quot;data.csv&quot;);</strong><br><br><strong></p>"
                },

            ]
		}
	}
};