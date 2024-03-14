<?php
	Class ExcelWriter
	{
			
		var $fp=null;
		var $error;
		var $state="CLOSED";
		var $newRow=false;
		
		/*
		* @Params : $file  : file name of excel file to be created.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false	 
		*/
		 
		function __construct($file="")
		{
			return $this->open($file);
		}
		
		/*
		* @Params : $file  : file name of excel file to be created.
		* 			if you are using file name with directory i.e. test/myFile.xls
		* 			then the directory must be existed on the system and have permissioned properly
		* 			to write the file.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false	 
		*/
		function open($file)
		{
			if($this->state!="CLOSED")
			{
				$this->error="Error : Another file is opend .Close it to save the file";
				return false;
			}	
			
			if(!empty($file))
			{
				$this->fp=@fopen($file,"w+");
			}
			else
			{
				$this->error="Usage : New ExcelWriter('fileName')";
				return false;
			}	
			if($this->fp==false)
			{
				$this->error="Error: Unable to open/create File.You may not have permmsion to write the file.";
				return false;
			}
			$this->state="OPENED";
			fwrite($this->fp,$this->GetHeader());
			return $this->fp;
		}
		
		function close()
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if($this->newRow)
			{
				fwrite($this->fp,"</tr>");
				$this->newRow=false;
			}
			
			fwrite($this->fp,$this->GetFooter());
			fclose($this->fp);
			$this->state="CLOSED";
			return ;
		}
		/* @Params : Void
		*  @return : Void
		* This function write the header of Excel file.
		*/
		 							
		function GetHeader()
		{
			$header = <<<EOH
				<html xmlns:o="urn:schemas-microsoft-com:office:office"
				xmlns:x="urn:schemas-microsoft-com:office:excel"
				xmlns="http://www.w3.org/TR/REC-html40">
				<head>
				<meta http-equiv=Content-Type content="text/html; charset=utf-8">
				<meta name=ProgId content=Excel.Sheet>
				<!--[if gte mso 9]><xml>
				 <o:DocumentProperties>
				  <o:LastAuthor>Sriram</o:LastAuthor>
				  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
				  <o:Version>10.2625</o:Version>
				 </o:DocumentProperties>
				 <o:OfficeDocumentSettings>
				  <o:DownloadComponents/>
				 </o:OfficeDocumentSettings>
				</xml><![endif]-->
				<style>
				<!--table
					{mso-displayed-decimal-separator:"\.";
					mso-displayed-thousand-separator:"\,";}
				@page
					{margin:1.0in .75in 1.0in .75in;
					mso-header-margin:.5in;
					mso-footer-margin:.5in;}
				tr
					{mso-height-source:auto;}
				col
					{mso-width-source:auto;}
				br
					{mso-data-placement:same-cell;}
				.style0
					{
					mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:8.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
				td
					{mso-style-parent:style0;
					padding-top:1px;
					padding-right:1px;
					padding-left:1px;
					mso-ignore:padding;
					color:windowtext;
					font-size:8.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					border:none;
					mso-background-source:auto;
					mso-pattern:auto;
					mso-protection:locked visible;
					white-space:nowrap;
					mso-rotate:0;}
				.xl24
					{mso-style-parent:style0;
					 white-space:normal;
					 }
				 .x324
					{
					 mso-style-parent:style0;
					 white-space:normal;
					 font-weight:bold;
					
					}
				.clasecabecera
					{mso-style-parent:style0;
					color:#000000;
					border-top:.5pt solid yellow;
					border-right:.5pt solid yellow;
					border-bottom:.5pt solid yellow;
					border-left:.5pt solid yellow;
					background-color: #E5E5E5;
					mso-pattern:black none;}
				.titulares
					{mso-style-parent:style0;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;
					background:#F2F2F2;
					mso-pattern:black none;}
				-->
				</style>
				<!--[if gte mso 9]><xml>
				 <x:ExcelWorkbook>
				  <x:ExcelWorksheets>
				   <x:ExcelWorksheet>
					<x:Name>srirmam</x:Name>
					<x:WorksheetOptions>
					 <x:Selected/>
					 <x:ProtectContents>False</x:ProtectContents>
					 <x:ProtectObjects>False</x:ProtectObjects>
					 <x:ProtectScenarios>False</x:ProtectScenarios>
					</x:WorksheetOptions>
				   </x:ExcelWorksheet>
				  </x:ExcelWorksheets>
				  <x:WindowHeight>10005</x:WindowHeight>
				  <x:WindowWidth>10005</x:WindowWidth>
				  <x:WindowTopX>120</x:WindowTopX>
				  <x:WindowTopY>135</x:WindowTopY>
				  <x:ProtectStructure>False</x:ProtectStructure>
				  <x:ProtectWindows>False</x:ProtectWindows>
				 </x:ExcelWorkbook>
				</xml><![endif]-->
				</head>
				<body link=blue vlink=purple>
				<table x:str border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse;table-layout:fixed;'>
EOH;
			return $header;
		}
		function GetFooter()
		{
			return "</table></body></html>";
		}
		
		/*
		* @Params : $line_arr: An valid array 
		* @Return : Void
		*/
		 
		//escribir la cabecera
		function writeCabecera($line_arr)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if(!is_array($line_arr))
			{
				$this->error="Error : Argument is not valid. Supply an valid Array.";
				return false;
			}
			fwrite($this->fp,"<tr>");
			foreach($line_arr as $col)
				fwrite($this->fp,"<td class=clasecabecera width=64 >$col</td>");
			fwrite($this->fp,"</tr>");
		}
		//escribir la cabecera de los grupos activos
		function writeCabeceraGrupo($grupo, $celdas)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			fwrite($this->fp,"<tr>");
			fwrite($this->fp,"<td class=clasecabecera width='64' colspan='".$celdas ."'>".$grupo."</td>");
			fwrite($this->fp,"</tr>");
		}
		
		function writeLineTotales($line_arr)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if(!is_array($line_arr))
			{
				$this->error="Error : Argument is not valid. Supply an valid Array.";
				return false;
			}
			fwrite($this->fp,"<tr>");
			foreach($line_arr as $col)
				//fwrite($this->fp,"<td class=xl24 width=64 >".trim($col)."</td>");
				if (is_numeric($col)){
					fwrite($this->fp,"<td x:num class=x324 width=64 style='background-color:#FFFF66' >$col</td>");
				} else {
					fwrite($this->fp,"<td class=x324 width=64  style='background-color:#FFFF66' >".$col."</td>");
				}
			fwrite($this->fp,"</tr>");
		}
		//escribe los datos
		function writeLine($line_arr)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if(!is_array($line_arr))
			{
				$this->error="Error : Argument is not valid. Supply an valid Array.";
				return false;
			}
			fwrite($this->fp,"<tr>");
			foreach($line_arr as $col)
				//fwrite($this->fp,"<td class=xl24 width=64 >".trim($col)."</td>");
				if (is_numeric($col)){
					fwrite($this->fp,"<td x:num class=xl24  width=64 >".$col.		
"</td>");
				} else {
					fwrite($this->fp,"<td class=xl24 width=64 >".$col."</td>");
				}
			fwrite($this->fp,"</tr>");
		}
		//*****************************************************************************
		//Cabecera para SPE
		function cabecera_SPE()
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			
			$cadena =	"<tr>
							<td rowspan=2 class='titulares'>Box#</td>
							<td rowspan=2 class='titulares'>PO#</td>
							<td rowspan=2 class='titulares'>Quantity</td>
							<td rowspan=2 class='titulares'>ID</td>
							<td rowspan=2 class='titulares'>Code</td>
							<td colspan=4 class='titulares' style='border-left:none;width:500pt'>Description</td>
							<td rowspan=2 class='titulares'>Unit Net Weight (Kgms)</td>
							<td rowspan=2 class='titulares'>Net Weight per item (Kgms)</td>
						</tr>";
			$cadena =	$cadena . 
						"<tr>
							<td class='titulares' style='border-top:none;border-left:none'>Model</td>
							<td class='titulares' style='border-top:none;border-left:none'>Style</td>
							<td class='titulares' style='border-top:none;border-left:none'>Color</td>
							<td class='titulares' style='border-top:none;border-left:none'>Clip</td>
						</tr>";
			
			fwrite($this->fp, $cadena);
		}
		
		//Pie para SPE
		function pie_SPE()
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			
			$cadena =	"
						<tr>
						</tr>
						<tr>
							<td colspan=2>Total</td>
							<td class='titulares' style='background:#F2F2F2;'></td>
						</tr>
						<tr>
						</tr>
						";
			$cadena =	$cadena . 
						"
						<tr>
							<td colspan=5></td>
							<td colspan=2>Total Net Weight / Peso Neto Total</td>
							<td></td>
							<td>Kgms.</td>
						</tr>
						<tr>
							<td colspan=5></td>
							<td colspan=2>Packaging / Empaque</td>
							<td></td>
							<td>Kgms.</td>
						</tr>
						<tr>
							<td colspan=5></td>
							<td colspan=2>Total Gross Weight / Peso Bruto Total</td>
							<td></td>
							<td>Kgms.</td>
						</tr>
						";
			
			fwrite($this->fp, $cadena);
		}
		//*****************************************************************************
		
		/*
		* @Params : Void
		* @Return : Void
		*/
		function writeRow()
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if($this->newRow==false)
				fwrite($this->fp,"<tr>");
			else
				fwrite($this->fp,"</tr><tr>");
			$this->newRow=true;	
		}
		/*
		* @Params : $value : Coloumn Value
		* @Return : Void
		*/
		function writeCol($value)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			fwrite($this->fp,"<td class=xl24 width=64 >".trim($value)."</td>");
		}
	}
?>