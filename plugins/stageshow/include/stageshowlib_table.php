<?php
/* 
Description: Code for Table Management Class
 
Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if (!class_exists('StageShowLibTableClass')) 
{
	if (!defined('STAGESHOWLIB_EVENTS_PER_PAGE'))
		define('STAGESHOWLIB_EVENTS_PER_PAGE', 20);
	
	if (!defined('STAGESHOWLIB_DISABLE_POSTCONTROLS')) 
		define('STAGESHOWLIB_DISABLE_POSTCONTROLS', true);
	
	class StageShowLibTableUtilsClass // Define class
	{
		static function GetCurrentPage()
		{ 
			$currentPage = StageShowLibUtilsClass::GetHTTPInteger('request', 'paged', 1);
			$currentPage = StageShowLibUtilsClass::GetHTTPInteger('get', 'paged', $currentPage);
			return $currentPage;
		}

	}
	
	class StageShowLibTableClass // Define class
	{
		const HEADERPOSN_TOP = 1;
		const HEADERPOSN_BOTTOM = 2;
		const HEADERPOSN_BOTH = 3;

		const TABLETYPE_HTML = 'html';
		const TABLETYPE_RTF = 'RTF';
		const TABLETYPE_TEXT = 'text';

		const TABLEPARAM_LABEL = 'Label';
		const TABLEPARAM_TAB = 'Tab';
		const TABLEPARAM_ID = 'Id';
		const TABLEPARAM_TYPE = 'Type';
		const TABLEPARAM_ITEMS = 'Items';
		const TABLEPARAM_TEXT = 'Text';
		const TABLEPARAM_VALUE = 'Value';
		const TABLEPARAM_LIMITS = 'Limits';
		const TABLEPARAM_LEN = 'Len';
		const TABLEPARAM_SIZE = 'Size';
		const TABLEPARAM_LINK = 'Link';
		const TABLEPARAM_LINKTO = 'LinkTo';
		const TABLEPARAM_LINKURL = 'LinkURL';
		const TABLEPARAM_DEFAULT = 'Default';
		const TABLEPARAM_NEXTINLINE = 'Next-Inline';
		const TABLEPARAM_ONCHANGE = 'OnChange';
		const TABLEPARAM_READONLY = 'ReadOnly';
		const TABLEPARAM_ALLOWHTML = 'AllowHTML';
		
		const TABLEPARAM_NAME = 'Name';

		const TABLEPARAM_BUTTON = 'button';
		const TABLEPARAM_DIR = 'Dir';
		const TABLEPARAM_EXTN = 'Extn';
		const TABLEPARAM_FUNC = 'Func';
		const TABLEPARAM_ROWS = 'Rows';
		const TABLEPARAM_COLS = 'Cols';
		const TABLEPARAM_DECODE = 'Decode';
		const TABLEPARAM_CANEDIT = 'CanEdit';
		const TABLEPARAM_ALWAYSEDIT = 'AlwaysEdit';
		const TABLEPARAM_ADDEMPTY = 'AddEmpty';
		const TABLEPARAM_ADDCHECKALL = 'AddCheckall';
		const TABLEPARAM_BLOCKBLANK = 'BlockBlank';
		const TABLEPARAM_DATEMODE = 'datemode';		// past, future or blank
		const TABLEPARAM_HIDEEXTNS = 'HideExtns';
		const TABLEPARAM_BEFORE = 'Before';
		const TABLEPARAM_AFTER = 'After';
		const TABLEPARAM_POSITION = 'Position';
		
		const TABLEENTRY_ARRAY = 'array';
		const TABLEENTRY_CHECKBOX = 'checkbox';
		const TABLEENTRY_FUNCTION = 'function';
		const TABLEENTRY_SELECT = 'select';
		const TABLEENTRY_TEXT = 'text';
		const TABLEENTRY_INTEGER = 'Integer';
		const TABLEENTRY_FLOAT = 'Float';
		const TABLEENTRY_TEXTBOX = 'textbox';
		const TABLEENTRY_VIEW = 'view';
		const TABLEENTRY_READONLY = 'readonly';
		const TABLEENTRY_VALUE = 'value';
		const TABLEENTRY_COOKIE = 'cookie';
		
		const TABLEENTRY_BELOWLAST = 'below';
		
		const TABLEENTRY_NEWROW = true;
		
		const STAGESHOWLIB_EVENTS_UNPAGED = -1;
		
		var $html_tableContents = array();
		var $rowAttr = array();
		var $tableName = '';
		var $tableTags;
		var $divClass;
		var $colId;
		var $rowsPerPage;
		var $columnHeadersId = '';
		var $HeadersPosn;
		
		var $colWidth = array();
		var $colAlign = array();
		var $colClass = array();
		var $columns;
		var $bulkActions;
		var $hideEmptyRows;
		var $spanEmptyCells;
		var $useTHTags;
		var $noAutoComplete;
		var $ignoreEmptyCells;
		
		var $detailsRowsDef;
		var $moreText;
		var $lessText;
		var $hiddenRowsButtonId;
		var $showOptionsID = 0;
		var $hiddenRowClass  = 'stageshowlib_hiderow';
		var $visibleRowClass = '';
		var $allowHiddenTags = true;
		
		var $currRow;
		var $currCol;
		var $maxCol;
		var $rowActive = array();
		var $currentPage = 1;
		var $totalRows;
		var $maxRowsShown;
		var $mergedColumns = array();
		
		var $rowCount = 0;
		
		var $scriptsOutput;
		var $moreScriptsOutput;
		
		var $tableType;
		
		var $dateTimeMode = 'dateseconds';
	
		var $tableUsesSerializedPost = false;
		
		function __construct($newTableType = self::TABLETYPE_HTML) //constructor
		{
			if (!isset($this->tabHeadClass))
				$this->tabHeadClass = "mjstab-tab-inactive";
				
			$this->tableType = $newTableType;
			switch ($this->tableType)
			{
				case self::TABLETYPE_HTML:
				case self::TABLETYPE_RTF:
				case self::TABLETYPE_TEXT:
					break;
					
				default:
					StageShowLibUtilsClass::ShowCallStack();
					StageShowLibEscapingClass::Safe_EchoHTML("<strong><br>Invalid table type ($newTableType) ".get_class($this)." class<br></strong>\n");
					die;
					break;
			}
			
			$this->currRow = 1;
			$this->currCol = 0;
			$this->maxCol = 0;
			$this->HeaderCols = 0;
			$this->isTabbedOutput = false;
			$this->rowActive[$this->currRow] = false;
			$this->hideEmptyRows = true;
			$this->spanEmptyCells = false;
			$this->divClass = '';
			$this->colId = '';
			$this->divClass = '';
			$this->tableTags = '';
			$this->colId = '';
			$this->totalRows = 0;
			$this->rowsPerPage = 0;
			$this->useTHTags = false;
			$this->noAutoComplete = true;
			$this->ignoreEmptyCells = true;
			$this->scriptsOutput = false;
			$this->moreScriptsOutput = false;
			
			$this->detailsRowsDef = array_merge($this->GetDetailsRowsDefinition(), $this->GetDetailsRowsFooter());
				
			$this->moreText = __('Show', 'stageshow');
			$this->lessText = __('Hide', 'stageshow');
			
		}
		
		function SetRowsPerPage($rowsPerPage)
		{
			$this->rowsPerPage = $rowsPerPage;			
			$this->currentPage = StageShowLibTableUtilsClass::GetCurrentPage();			
		}

		function AddHiddenRows($result, $hiddenRowsID, $hiddenRows, $class)
		{
			$this->NewRow($result, 'id="'.$hiddenRowsID.'" class="hiddenRow '.$class.'"');
			$this->AddToTable($result, $hiddenRows);

			$this->maxCol = max($this->maxCol, $this->HeaderCols);
		}

		function GetMainRowsDefinition()
		{
			StageShowLibUtilsClass::UndefinedFuncCallError($this, 'GetMainRowsDefinition');
		}
		
		function CanShowDetailsRow($result, $fieldName)
		{
			return true;
		}
		
		function GetDetailsRowsDefinition()
		{
			return array();
		}
		
		function GetDetailsRowsFooter()
		{
			return array();
		}
		
		function HasHiddenRows()
		{
			// No extended settings
			return (count($this->detailsRowsDef) > 0);
		}
		
		function ExtendedSettingsDBOpts()
		{
			return array();
		}
		
		function NewRow($result, $rowAttr = '')
		{
			// Increment Row ... but only if the current row has data
			if ($this->rowActive[$this->currRow]) 
				$this->currRow++;
				
			$this->currCol = 0;
			$this->rowActive[$this->currRow] = false;
			$this->rowAttr[$this->currRow] = $rowAttr;
		}

		function SetColWidths($newColWidths)
		{
			$this->colWidth = explode(',', ','.$newColWidths);
		}

		function SetColAlign($newColAlign)
		{			
			$this->colAlign = explode(',', ','.$newColAlign);
		}

		function SetColClass($newColClass)
		{			
			$this->colClass = explode(',', ','.$newColClass);
		}

		function SetListHeaders($headerId, $columns = null, $headerPosn = self::HEADERPOSN_BOTH)
		{
			// Save the settings, the headers are actually set by the EnableListHeaders function			
			$this->columnHeadersId = $headerId;

			if ($columns != null)
				$this->columns = $columns;	// Save for possible next call
				
			$this->HeadersPosn = $headerPosn;
			$this->HeaderCols = count($columns);
		}

		function EnableListHeaders()
		{
			if ($this->columnHeadersId === '') return;
			if ($this->columns === null) return;
			
			$columns = $this->columns;	// Use columns from last call
				
			if ($this->showDBIds)
			{
				// Add the ID column
				$columns = array_merge(array('eventID' => 'ID'), $columns); 
			}
				
			if (isset($this->bulkActions))
			{
				// Add the Checkbox column
				$columns = array_merge(array('eventCb' => '<input name="rowSelect-checkall" id="rowSelect-checkall" class="rowSelect-checkall" type="checkbox"  onClick="StageShowLib_updateCheckboxes(this, event)" />'), $columns); 
			}
			
			if ($this->HasHiddenRows() && ($this->hiddenRowsButtonId !== ''))
			{
				$columns = array_merge($columns, array('eventOptions' => $this->hiddenRowsButtonId)); 
			}
				
			$this->mergedColumns = $columns;
		}
		
		function AddCheckBoxToTable($result, $columnDef, $checked=false, $col=0, $checkedValue='checked', $label='', $newRow = false)
		{
			if (is_array($columnDef))
			{
				$inputName = $columnDef[self::TABLEPARAM_ID];
			}
			else
			{
				$inputName = $columnDef;	// Original version just expected the name
				$columnDef = array();
			}
			$inputClass = StageShowLibMigratePHPClass::Safe_str_replace("[]", "", "{$inputName}-checkbox");
			
			if (StageShowLibMigratePHPClass::Safe_substr($inputName, -2) != '[]')
			{
				$inputName .= $this->GetRecordID($result).$this->GetDetailID($result);				
			}

			$params = $checked ? ' checked="yes"' : '';
			$params .= isset($columnDef[self::TABLEPARAM_ONCHANGE]) ? ' onchange="'.$columnDef[self::TABLEPARAM_ONCHANGE].'(this, event)" ' : '';
			
		    $content = "$label<input name=\"$inputName\" id=\"$inputName\" class=\"$inputClass\" type=\"checkbox\" value=\"$checkedValue\" $params/>";
			$this->AddToTable($result, $content, $col, $newRow);
		}

		function AddButtonToTable($result, $columnDef, $value, $col=0, $newRow = false)
		{
			$inputName  = $columnDef[self::TABLEPARAM_ID];
			$inputName .= $this->GetRecordID($result).$this->GetDetailID($result);
							
			$buttonValue = "Submit";
			
		    $content = "<input class=\"button-primary\" name=\"$inputName\" id=\"$inputName\" type=\"submit\" value=\"$buttonValue\" />";
			$this->AddToTable($result, $content, $col, $newRow);
		}

		function AddTextBoxToTable($result, $columnDef, $value, $col=0, $newRow = false)
		{
			$inputName  = $columnDef[self::TABLEPARAM_ID];
			$inputName .= $this->GetRecordID($result).$this->GetDetailID($result);				

			$params  = " name=$inputName";
			$params .= " id=$inputName";

			$params .= isset($columnDef[self::TABLEPARAM_ONCHANGE]) ? ' onchange="'.$columnDef[self::TABLEPARAM_ONCHANGE].'(this,event)" ' : '';
		
			$editRows = isset($columnDef[self::TABLEPARAM_ROWS]) ? $columnDef[self::TABLEPARAM_ROWS] : 1;
			$editCols = isset($columnDef[self::TABLEPARAM_COLS]) ? $columnDef[self::TABLEPARAM_COLS] : 60;
			
			$content = '<textarea rows="'.$editRows.'" cols="'.$editCols.'" '.$params.'>'.$value.'</textarea>';
			
			if ($this->allowHiddenTags)
			{
				$inputName = 'curr'.$inputName;
				
				$params  = " name=$inputName";
				$params .= " id=$inputName";
				$params .= " value=\"$value\"";
				
				$content .= "<input type=\"hidden\" $params />";				
			}
			
			$this->AddToTable($result, $content, $col, $newRow);
		}
		
		function GetInputHTML($inputName, $maxlength, $value, $extraParams='')
		{
			$content = '';
			$content .= "<!-- $extraParams -->\n";
			if ($this->tableUsesSerializedPost)
			{
				if (StageShowLibMigratePHPClass::Safe_strpos($extraParams, 'class="') !== false)
				{
					$extraParams = StageShowLibMigratePHPClass::Safe_str_replace('class="', 'class="stageshowlib_PostVal ', $extraParams);
				}
				else
				{
					$extraParams .= ' class="stageshowlib_PostVal"';					
				}
			}
			else
			{
				$extraParams .= " name=$inputName";
			}
			$extraParams .= " id=$inputName";
			$extraParams .= " value=\"$value\"";
			
			$extraParams .= " maxlength=\"$maxlength\"";
			
			if ($this->noAutoComplete)
				$extraParams .= " autocomplete=\"off\""; 
			
			$content .= "<input type=\"text\" $extraParams />";
			
			if ($this->allowHiddenTags)
			{
				$hiddenInputName = 'curr'.$inputName;
				
				if ($this->tableUsesSerializedPost)
				{
					$baseParams = 'class="stageshowlib_PostVal"';					
				}
				else
				{
					$baseParams = "name=$hiddenInputName";
				}
				$baseParams .= " id=$hiddenInputName";
				$baseParams .= " value=\"$value\"";
				
				$content .= "<input type=\"hidden\" $baseParams />";
			}
						
			return $content;
		}
		
		function AddInputToTable($result, $inputName, $maxlength, $value, $col=0, $newRow = false, $extraParams = '')
		{
			$inputName .= $this->GetRecordID($result).$this->GetDetailID($result);				

			$content = $this->GetInputHTML($inputName, $maxlength, $value, $extraParams);
			
			$this->AddToTable($result, $content, $col, $newRow);
		}

		function AddDivToTable($result, $inputName, $value, $col=0, $newRow = false, $extraParams = '')
		{
			$inputName .= $this->GetRecordID($result).$this->GetDetailID($result);				

			$params  = " name=$inputName";
			$params .= " id=$inputName";
			
			if ($extraParams != '')
			{
				$params .= ' '.$extraParams;
			}			
			
			$content = "<div $params />$value</div>";
			
			if ($this->allowHiddenTags)
			{
				$inputName = 'curr'.$inputName;
				
				$params  = " name=$inputName";
				$params .= " id=$inputName";
				$params .= " value=\"$value\"";
				
				$content .= "<input type=\"hidden\" $params />";
			}
						
			$this->AddToTable($result, $content, $col, $newRow);
		}

		function AddSelectToTable($result, $columnDef, $options, $value='', $col=0, $newRow = false)
		{
			$inputName = $columnDef[self::TABLEPARAM_ID];
			$inputName .= $this->GetRecordID($result).$this->GetDetailID($result);
			if ($this->tableUsesSerializedPost)
			{
				$params = ' class="stageshowlib_PostVal"';
			}
			else
			{
				$params = " name=$inputName";
			}
			$params .= " id=$inputName";
			
			$onChange = isset($columnDef[self::TABLEPARAM_ONCHANGE]) ? ' onchange="'.$columnDef[self::TABLEPARAM_ONCHANGE].'(this,event)" ' : '';
		
			$content = "<select $params $onChange>"."\n";
			foreach ($options as $index => $option)
			{
				$selected = ($index == $value) ? ' selected=""' : '';
				$content .= '<option value="'.$index.'"'.$selected.'>'.$option.'&nbsp;&nbsp;</option>'."\n";
			}
			$content .= "</select>"."\n";

			$this->AddToTable($result, $content, $col, $newRow);
		}

		function AddLinkToTable($result, $content, $link, $col=0, $newRow = false)
		{
			$content = '<a href="'.$link.'">'.$content.'</a>';
			$this->AddToTable($result, $content, $col, $newRow);
		}
	
		function AddShowOrHideButtonToTable($result, $tableId, $rowId, $content, $col=0, $newRow = false)
		{
			$this->OutputMoreButtonScript();
			
			$recordID = $this->GetRecordID($result);
			$moreName = 'more'.$recordID;
			
			$content = '<a id="'.$moreName.'" class="button-secondary" onClick="StageShowLib_HideOrShowRows(\''.$moreName.'\', \''.$rowId.'\')">'.$content.'</a>';
			$this->AddToTable($result, $content, $col, $newRow);
		}

		function AddToTable($result, $content, $col=0, $newRow = false)
		{
			if ($this->ignoreEmptyCells)
			{
			if (!isset($content) || (StageShowLibMigratePHPClass::Safe_strlen($content) == 0)) return;
			}
			
			// Increment Row ... but only if the current row has data
			if ($newRow) 
			{				
				$this->NewRow($result);
			}
			
			if ($col <= 0) 
			{
				$col = ++$this->currCol;
			}
			else
			{
				$this->currCol = $col;
			}
				
			$this->tableContents[$this->currRow][$col] = $content;
			$this->rowActive[$this->currRow] = true;
			$this->maxCol = max($col, $this->maxCol);
		}
		
		function GetOnClickHandler()
		{
			return 'stageshowlib_ClickHeader(this)';
		}
		
		function Output_ColHeader()
		{
			$addSeparator = false;
			$tabParam = ' class="'.$this->tabHeadClass.'"';
			$width = (count($this->mergedColumns) > 1) ? 100/count($this->mergedColumns) : 100;
						
			if ($this->isTabbedOutput)
			{
				$separatorWidth = 1;
				$width -= $separatorWidth;
				$tabParam .= " onclick=".$this->GetOnClickHandler();
				$tabParam .= ' width="'.$width.'%"';
				$tabParam .= ' style="border: 1px solid black;"';
				$separatorParam = ' class=mjstab-tab-gap width="'.$separatorWidth.'%"';
				$separatorParam .= ' style="border-bottom: 1px solid black; background: #f9f9f9;"';				
			}
			
			foreach ($this->mergedColumns as $id => $html_col)
			{
				if ($addSeparator)
				{
					$html_cell = "<th $separatorParam></th>";
					StageShowLibEscapingClass::Safe_EchoHTML($html_cell."\n");
				}
					
				$html_cell = "<th id=$id $tabParam >$html_col</th>";
				StageShowLibEscapingClass::Safe_EchoHTML($html_cell."\n");
				
				$addSeparator = $this->isTabbedOutput;
			}
		}
		
		function ColumnHeaders($atTop = true)
		{
			if (!isset($this->columnHeadersId)) 
				return;

			if ($this->columnHeadersId === '') 
				return;

			if ($atTop)
			{
				if ($this->HeadersPosn === self::HEADERPOSN_BOTTOM) 
					return;
					
				StageShowLibEscapingClass::Safe_EchoHTML("<thead>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("<tr>\n");
				$this->Output_ColHeader();
				StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("</thead>\n");
			}
			else
			{
				if ($this->HeadersPosn === self::HEADERPOSN_TOP) 
					return;
					
				StageShowLibEscapingClass::Safe_EchoHTML("<tfoot>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("<tr>\n");
				$this->Output_ColHeader();
				StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("</tfoot>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("<tbody>\n");
			}
		}
		
		function Header()
		{
			switch ($this->tableType)
			{
				case self::TABLETYPE_HTML:
					if ($this->divClass)
						StageShowLibEscapingClass::Safe_EchoHTML("<div class=$this->divClass>\n");
						
					$html_tab = "<table ";
					if ($this->tableName !== '')
						$html_tab .= 'id="'.$this->tableName.'" ';
					$html_tableTags = $this->tableTags;
					$html_tab .= $html_tableTags.">\n";
					StageShowLibEscapingClass::Safe_EchoHTML($html_tab);

					StageShowLibEscapingClass::Safe_EchoHTML("<tbody>\n");
					
					break;
				case self::TABLETYPE_RTF:
				case self::TABLETYPE_TEXT:
				default:
					break;
			}
			$this->ColumnHeaders();
			$this->ColumnHeaders(false);
		}
		
		function Footer()
		{
			switch ($this->tableType)
			{
				case self::TABLETYPE_HTML:
					StageShowLibEscapingClass::Safe_EchoHTML("</tbody></table>\n");
					if ($this->divClass)
						StageShowLibEscapingClass::Safe_EchoHTML("</div>\n");
					break;
				case self::TABLETYPE_RTF:
				case self::TABLETYPE_TEXT:
				default:
					break;
			}
		}

		function ShowBulkActions( $which = 'top' ) 
		{	
			if (!isset($this->bulkActions)) return '';
			
			$this->OutputCheckboxScript();
			
			if (!is_array($this->bulkActions)) return '';

			$this->OutputBulkActionsScript($this->bulkActions);
			
			$ctrlPosn = $which === 'top' ? '_t' : '_b';
			$buttonId = 'action_'.$this->tableName.$ctrlPosn;
			
			$bulkActions = __('Bulk Actions', 'stageshow');
			
			$onclickParam = "onclick=\"return StageShowLib_confirmBulkAction(this, '$buttonId')\"";
			
			$output  = "<div class='alignleft actions'>\n";
			$output .= "<select id='$buttonId' name='action$ctrlPosn'>\n"; 
			$output .= "<option value='-1' selected='selected'>$bulkActions &nbsp;&nbsp;</option>\n"; 
			foreach ($this->bulkActions as $action => $actionID)
				$output .= "<option value='$action'>$actionID</option>\n"; 
			$output .= "</select>\n"; 
			$output .= "<input type='submit' name='doaction$ctrlPosn' id='doaction$ctrlPosn' $onclickParam class='button-secondary action' value=".__('Apply', 'stageshow')."  />\n";
			$output .= "</div>\n"; 
			
			return $output;
		}
		
		function OutputMoreButtonScript()
		{
			if (isset($this->myPluginObj->moreScriptsOutput)) return;
			$this->myPluginObj->moreScriptsOutput = true;
			$js_more = "
<script>
			
moreText = '{$this->moreText}';
lessText = '{$this->lessText}';

</script>
			";
			StageShowLibEscapingClass::Safe_EchoScript($js_more);
		}
		
		function OutputCheckboxScript()
		{
			if (isset($this->myPluginObj->scriptsOutput)) return;
			$this->myPluginObj->scriptsOutput = true;
						
		}
		
		function OutputBulkActionsScript($bulkActions)
		{
			if (isset($this->myPluginObj->bulkActionScriptsOutput)) return;
			$this->myPluginObj->bulkActionScriptsOutput = true;
						
			$html_bulkActions = "
<script type='text/javascript'>

var confirmActionsArray = new Array(";
			
			foreach ($bulkActions as $action => $actionID)
			{
				if ($this->NeedsConfirmation($action))
				{
					$html_bulkActions .= ("'".$actionID."',");
				}
			}
			
			$html_bulkActions .= ");

</script>
			";
			
			StageShowLibEscapingClass::Safe_EchoScript($html_bulkActions);
		}
		
		function GetCurrentURL() 
		{			
			$currentURL = StageShowLibUtilsClass::GetPageURL();
			$currentURL = $this->myDBaseObj->AddParamAdminReferer($this->caller, $currentURL);
			return $currentURL;
		}
		
		function ShowPageNavigation( $which = 'top' ) 
		{			
			if ($this->rowsPerPage <= 0) 
				return;
			
			// $which is 'top' or 'bottom'
			$output = '';
			if ( $this->totalRows <= $this->rowsPerPage ) 
				return;
				
			$totalPages = (int)(($this->totalRows-1)/$this->rowsPerPage) + 1;
			
			$output .= '<span class="displaying-num">' . sprintf( _n( '1 item', '%s items', $this->totalRows ), number_format_i18n( $this->totalRows ) ) . '</span>';

			$current_url = $this->GetCurrentURL();

			$current_url = remove_query_arg( array( 'hotkeys_highlight_last', 'hotkeys_highlight_first', 'paged' ), $current_url );

			$page_links = array();

			$disable_first = $disable_last = '';
			if ( $this->currentPage == 1 )
				$disable_first = ' disabled';
			if ( $this->currentPage >= $totalPages )
				$disable_last = ' disabled';

			$page_links[] = sprintf( "<a class='%s' title='%s' %s>%s</a>",
				'first-page button' . $disable_first,
				$disable_first === '' ? __('Go to the first page', 'stageshow') : '',
				$disable_first === '' ? 'href='.esc_url( remove_query_arg( 'paged', $current_url ) ) : '',
				'&laquo;'
			);

			$page_links[] = sprintf( "<a class='%s' title='%s' %s>%s</a>",
				'prev-page button' . $disable_first,
				$disable_first === '' ? __('Go to the previous page', 'stageshow') : '',
				$disable_first === '' ? 'href='.esc_url( add_query_arg( 'paged', max( 1, $this->currentPage-1 ), $current_url ) ) : '',
				'&lsaquo;'
			);

			if ( 'bottom' == $which )
				$html_current_page = $this->currentPage;
			else
			{
				$onKeyCodeHandler = ' onkeypress="StageShowLib_SubmitOnReturnKey(this, event);" ';
				$html_current_page = sprintf( "<input class='current-page' title='%s' type='text' name='%s' value='%s' $onKeyCodeHandler size='%d' />",
					__( 'Current page', 'stageshow'),
					'paged',
					$this->currentPage,
					StageShowLibMigratePHPClass::Safe_strlen( $totalPages )
				);
			}

			$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $totalPages ) );
			$page_links[] = '<span class="paging-input">' . sprintf('%1$s '.__('of', 'stageshow').' %2$s', $html_current_page, $html_total_pages ) . '</span>';

			$page_links[] = sprintf( "<a class='%s' title='%s' %s>%s</a>",
				'next-page button' . $disable_last,
				$disable_last === '' ? __('Go to the next page', 'stageshow') : '',
				$disable_last === '' ? 'href='.esc_url( add_query_arg( 'paged', min( $totalPages, $this->currentPage+1 ), $current_url ) ) : '',
				'&rsaquo;'
			);

			$page_links[] = sprintf( "<a class='%s' title='%s' %s>%s</a>",
				'last-page button' . $disable_last,
				$disable_last === '' ? __('Go to the last page', 'stageshow') : '',
				$disable_last === '' ? 'href='.esc_url( add_query_arg( 'paged', $totalPages, $current_url ) ) : '',
				'&raquo;'
			);

			$output .= "\n" . join( "\n", $page_links );

			$page_class = $totalPages < 2 ? ' one-page' : '';

			$html_nav  = "<div class='alignright actions'>\n";
			$html_nav .= "<div class='tablenav-pages{$page_class}'>$output</div>";
			$html_nav .= "</div>";
			
			return $html_nav;
			
		}

		function ShowPageControls($which = 'top')
		{
			switch ($this->tableType)
			{
				case self::TABLETYPE_HTML:
					break;
				case self::TABLETYPE_RTF:
				case self::TABLETYPE_TEXT:
				default:
					return;
			}
						
			$pageControls  = $this->ShowBulkActions($which);
			$pageControls .= $this->ShowPageNavigation($which);
			if ($pageControls != '') 
			{
				$html_bulkActions  = "<!-- ShowPageControls - START -->\n";
				$html_bulkActions .= "<div class='tablenav $which actions'>\n";
				$html_bulkActions .= $pageControls;
				$html_bulkActions .= "</div>\n";
				$html_bulkActions .= "<!-- ShowPageControls - END -->\n";

				StageShowLibEscapingClass::Safe_EchoHTML($html_bulkActions);
			}
		}
		
		function Display()
		{
			$colTag = $this->useTHTags ? 'th' : 'td';
			
			$this->ShowPageControls();
			$this->Header();

			for ($row = 1; $row <= $this->currRow; $row++)
			{
				if ($this->hideEmptyRows && !$this->rowActive[$row]) continue;
				switch ($this->tableType)
				{
					case self::TABLETYPE_HTML:
						if (isset($this->rowAttr[$row]) && ($this->rowAttr[$row] != ''))
						{
							$html_row = "<tr ".$this->rowAttr[$row].">\n";
							StageShowLibEscapingClass::Safe_EchoHTML($html_row);
						}
						else
							StageShowLibEscapingClass::Safe_EchoHTML("<tr>\n");
						break;
					case self::TABLETYPE_RTF:
						break;
					case self::TABLETYPE_TEXT:
					default:
						break;
				}
								
				for ($col = 1; $col <= $this->maxCol; $col++)
				{
					$setWidth = '';
					$setAlign = '';
					$setId = '';
					
					if ($row == 1)
					{
						$setWidth = isset($this->colWidth[$col]) ? ' width="'.$this->colWidth[$col].'"' : '';
						$setAlign = isset($this->colAlign[$col]) ? ' align="'.$this->colAlign[$col].'"' : '';
						$setId = ($this->colId !== '') ? ' id="'.$this->colId.$col.'"' : '';
					}
					
					$setClass = (isset($this->colClass[$col]) && $this->colClass[$col] != '') ? ' class="'.$this->colClass[$col].'"' : '';
					
					$colSpan = '';
					$colSpanCount = 1;
					if ($this->spanEmptyCells)
					{
						for ($nextCol = $col+1; $nextCol <= $this->maxCol; $nextCol++, $colSpanCount++)
						{
							if (isset($this->tableContents[$row][$nextCol])) break;
						}
					}		
										
					if ($colSpanCount > 1)
					{
						$colSpanCount = $this->isTabbedOutput ? (2*($colSpanCount-1))+1 : $colSpanCount;
						$colSpan = ' colspan="'.$colSpanCount.'"';
						if ($this->isTabbedOutput)
						{
							$setClass .= ' style="border-width: 0px 1px 1px 1px; border-color: black; border-style: solid;"';				
						}
					} 
						
					switch ($this->tableType)
					{
						case self::TABLETYPE_HTML:
							$html_table = '<'.$colTag.$colSpan.$setWidth.$setAlign.$setId.$setClass.'>';
							StageShowLibEscapingClass::Safe_EchoHTML($html_table);
							break;
						case self::TABLETYPE_RTF:
							if ($col > 1) StageShowLibEscapingClass::Safe_EchoAttr('\tab ');
						case self::TABLETYPE_TEXT:
						default:
							break;
					}
					$html_tableContents = isset($this->tableContents[$row][$col]) ? $this->tableContents[$row][$col] : "";
					$html_tableContents = StageShowLibMigratePHPClass::Safe_trim($html_tableContents);
					if (StageShowLibMigratePHPClass::Safe_strlen($html_tableContents) == 0) $html_tableContents = "&nbsp;";
					
					switch ($this->tableType)
					{
						case self::TABLETYPE_HTML:
							$html_tableContents .= "</$colTag>\n";
							break;
						case self::TABLETYPE_RTF:
							break;
						case self::TABLETYPE_TEXT:
						default:
							$html_tableContents .= "\t";
							break;
					}
					
					StageShowLibEscapingClass::Safe_EchoHTML($html_tableContents);

					// Skp "Spanned" cells
					$col += ($colSpanCount - 1);
				}			
					
				switch ($this->tableType)
				{
					case self::TABLETYPE_HTML:
						StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
						break;
					case self::TABLETYPE_RTF:
						StageShowLibEscapingClass::Safe_EchoAttr('\par '."\n");
						break;
					case self::TABLETYPE_TEXT:
					default:
						StageShowLibEscapingClass::Safe_EchoAttr("\n");
						break;
				}
			}
			
			$this->Footer();
			$this->ShowPageControls('bottom');
		}
	}
}



