<?php
/**
 * Advanced Ordernumbers generic helper class (e-commerce system agnostic)
 * Reinhold Kainhofer, Open Tools, office@open-tools.net
 * @copyright (C) 2012-2015 - Reinhold Kainhofer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

if ( !defined( 'ABSPATH' ) && !defined('_JEXEC') ) { 
	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' );
}
// NULL function to indicate translatable strings without actually doing any translation
function trl($string) {
	return $string;
}

class OrdernumberHelper {
	static $_version = "0.1";
	protected $callbacks = array();
	public $_styles = array(
		'counter-table-class' => "table-striped",
		'counter-table-style' => "",
		'variable-table-class' => "",
		'variable-table-style' => "",
	);
	protected $flags = array(
		'extract-counter-settings' => 1,
	);
	/**
	 * An array containing all language keys for the translations used in the JavaScript code.
	 * Make sure to set those in the ajax_ordernumber JavaScript array!
	 */
	public $jstranslations = array(
		"ORDERNUMBER_JS_NOT_AUTHORIZED", "ORDERNUMBER_JS_INVALID_COUNTERVALUE", "ORDERNUMBER_JS_JSONERROR", 
		"ORDERNUMBER_JS_NEWCOUNTER", "ORDERNUMBER_JS_EDITCOUNTER", "ORDERNUMBER_JS_DELETECOUNTER",
		"ORDERNUMBER_JS_ADD_FAILED", "ORDERNUMBER_JS_MODIFY_FAILED", "ORDERNUMBER_JS_DELETE_FAILED",
		);
		
	/**
	 * The URL to call for AJAX calls
	 */
	public $ajax_url = "";
   
	function __construct() {
		// Set up 
		$this->registerCallback ("setupDateTimeReplacements", array($this, "setupDateTimeReplacements"));
	}
	
	static function getHelper() {
		static $helper = null;
		if (!$helper) {
			$helper = new OrdernumberHelper();
		}
		return $helper;
    }
    
    public function setFlag($flag, $value) {
		$this->flags[$flag] = $value;
    }
    public function getFlag($flag, $default) {
		if (isset($this->flags[$flag])) {
			return $this->flags[$flag];
		} else {
			return $default;
		}
    }
    public function unsetFlat($flag) {
		unset($this->flags[$flag]);
    }
	
	function getStyle($key) {
		if (isset($this->_styles[$key])) {
			return $this->_styles[$key];
		} else {
			return '';
		}
	}
	/* Callback handling */
	
	/**
	 * Register a callback for one of the known callback hooks. 
	 * Valid callbacks are (together with their arguments):
	 *   - translate($string)
	 *   - getCounter($type, $countername, $default)
	 *   - setCounter($type, $countername, $value)
	 *   - setupDateTimeReplacements(&$reps, $details, $nrtype);
	 *   - setupStoreReplacements(&$reps, $details, $nrtype);
	 *   - setupOrderReplacements(&$reps, $details, $nrtype);
	 *   - setupUserReplacements(&$reps, $details, $nrtype);
	 *   - setupShippingReplacements(&$reps, $details, $nrtype);
	 *   - setupThirdPartyReplacements(&$reps, $details, $nrtype);
	 
	 *   - urlPath($path, $type)
	 *  @param string $callback 
	 *     The name of the callback hook (string)
	 *  @param function $func 
	 *     The function (usually a member of the plugin object) for the callback
	 *  @return none
	 */
	public function registerCallback($callback, $func) {
		$this->callbacks[$callback] = $func;
	}
	
	public function __($string) {
		if (isset($this->callbacks["translate"])) {
			return call_user_func_array($this->callbacks['translate'], func_get_args());
		} else {
			return $string;
		}
	}

	/**
	 * Provide human-readable default values for the translatable strings.
	 * Some systems use the translation key as fallback if no translation is found,
	 * so we need to convert it to a human-readable value.
	 */
	public function readableString($string) {
		static $readable_strings = array(
			"PLG_ORDERNUMBER_COUNTERLIST_HEADER_VALUE"   => 'Counter value',
			"PLG_ORDERNUMBER_COUNTERLIST_HEADER_COUNTER" => 'Counter name',
			"PLG_ORDERNUMBER_COUNTERLIST_ADD"            => 'Add new counter',
			"PLG_ORDERNUMBER_COUNTERLIST_GLOBAL"         => '[Global]',
			"PLG_ORDERNUMBER_REPL_IFVAR"                 => 'If variable ...',
			"PLG_ORDERNUMBER_REPL_IFVAL"                 => 'Value',
			"PLG_ORDERNUMBER_REPL_SETVAR"                => 'Set variable ...',
			"PLG_ORDERNUMBER_REPL_TOVAL"                 => 'to value ...',
			"PLG_ORDERNUMBER_REPL_NOCUSTOMVARS"          => 'No custom variables have been defined.',
			"PLG_ORDERNUMBER_REPL_ADDVAR"                => 'Add new custom variable',
			"PLG_ORDERNUMBER_REPL_OP_NOCOND"             => 'No condition',
			"PLG_ORDERNUMBER_REPL_OP_CONTAINS"           => 'contains',
			"PLG_ORDERNUMBER_REPL_OP_STARTS"             => 'starts with',
			"PLG_ORDERNUMBER_REPL_OP_ENDS"               => 'ends with'
		);
		// Use the human-readable text as default rather than the generic identifier.
		// Otherwise, one always has to create a language file for every language, as 
		// the fallback would be the identifier.
		if (isset($readable_strings[$string]))
			return $readable_strings[$string];
		else
			return $string;
	}

	public function urlPath($type, $file) {
		if (isset($this->callbacks['urlPath'])) {
			return call_user_func_array($this->callbacks['urlPath'], func_get_args());
		} else {
			throw new Exception('No callback defined for urlPath(type, file)!');
		}
	}
	
	protected function replacementsCallback ($func, &$reps, $details, $nrtype) {
		if (isset($this->callbacks[$func])) {
			return call_user_func_array($this->callbacks[$func], array(&$reps, $details, $nrtype));
		}
	}

	protected function getCounter($type, $countername, $default) {
		if (isset($this->callbacks['getCounter'])) {
			return call_user_func_array($this->callbacks['getCounter'], func_get_args());
		} else {
			throw new Exception('No callback defined for getCounter(type, countername, default)!');
		}
	}
	
	protected function setCounter($type, $countername, $value) {
		if (isset($this->callbacks['getCounter'])) {
			return call_user_func_array($this->callbacks['setCounter'], func_get_args());
		} else {
			throw new Exception('No callback defined for setCounter(type, countername, value)!');
		}
	}
	
	public function getAllCounters($type) {
		if (isset($this->callbacks['getAllCounters'])) {
			return call_user_func_array($this->callbacks['getAllCounters'], func_get_args());
		} else {
			throw new Exception ('No callback defined for getAllCounters(type)!');
		}
	}
	
	public static function transposeCustomVariables($cvar) {
		if (is_object($cvar)) 
			$cvar = (array)$cvar;
		if (!is_array($cvar))
			$cvar = array();
		// The customvars are stored in transposed form (for technical reasons, since there is no trigger 
		// called when the corresponding form field from the plugin param is saved)
		$customvars = array();
        
		if (!empty($cvar)) {
			$keys = array_keys($cvar);
			foreach (array_keys($cvar[$keys[0]]) as $i) {
				$entry = array();
				foreach ($keys as $k) {
					$entry[$k] = $cvar[$k][$i];
				}
				$customvars[] = $entry;
			}
		}
		return $customvars;
	}
	
	/* Return a random "string" of the given length taken from the given alphabet */
	protected static function randomString($alphabet, $len) {
		$alen = strlen($alphabet);
		$r = "";
		for ($n=0; $n<$len; $n++) {
			$r .= $alphabet[mt_rand(0, $alen-1)];
		}
		return $r;
	}

	protected function replaceRandom ($match) {
		/* the regexp matches (random)(Type)(Len) as match, Type and Len is optional */
		$len = ($match[3]?$match[3]:1);
		// Fallback: If no Type is given, use Digit
		$alphabet = "0123456789";
		// Select the correct alphabet depending on Type
		switch (strtolower($match[2])) {
			case "digit": $alphabet = "0123456789"; break;
			case "hex": $alphabet = "0123456789abcdef"; break;
			case "letter": $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; break;
			case "uletter": $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; break;
			case "lletter": $alphabet = "abcdefghijklmnopqrstuvwxyz"; break;
			case "alphanum": $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; break;
		}
		return self::randomString ($alphabet, $len);
	}
	
	public function getDateTime($utime) {
		$time = new DateTime();
		$time->setTimestamp($utime);
		return $time;
	}

	public function setupDateTimeReplacements (&$reps, $details, $nrtype) {
		$utime = microtime(true);
		$time = $this->getDateTime($utime);
		$reps["[year]"] = $time->format ("Y");
		$reps["[year2]"] = $time->format ("y");
		$reps["[month]"] = $time->format("m");
		$reps["[monthname]"] = $time->format("F");
		$reps["[monthname3]"] = $time->format("M");
		$reps["[week]"] = $time->format("W");
		$reps["[weeknumberyear]"] = $time->format("o");
		$reps["[day]"] = $time->format("d");
		$reps["[dayofyear]"] = $time->format("z")+1;
		$reps["[weekday]"] = $time->format("N");
		$reps["[weekdayname3]"] = $time->format("D");
		$reps["[weekdayname]"] = $time->format("l");
		$reps["[hour]"] = $time->format("H");
		$reps["[hour12]"] = $time->format("h");
		$reps["[ampm]"] = $time->format("a");
		$reps["[minute]"] = $time->format("i");
		$reps["[second]"] = $time->format("s");
		$milliseconds = (int)(1000*($utime - (int)$utime));
		$millisecondsstring = sprintf('%03d', $milliseconds);
		$reps["[decisecond]"] = $millisecondsstring[0];
		$reps["[centisecond]"] = substr($millisecondsstring, 0, 2);
		$reps["[millisecond]"] = $millisecondsstring;
	}

	protected function setupReplacements($nrtype, $details) {
		$reps = array();
		// The following callbacks directly modify the replacements!
		$this->replacementsCallback("setupDateTimeReplacements", $reps, $details, $nrtype);
		$this->replacementsCallback("setupStoreReplacements", $reps, $details, $nrtype);
		$this->replacementsCallback("setupOrderReplacements", $reps, $details, $nrtype);
		$this->replacementsCallback("setupUserReplacements", $reps, $details, $nrtype);
		$this->replacementsCallback("setupShippingReplacements", $reps, $details, $nrtype);
		$this->replacementsCallback("setupThirdPartyReplacements", $reps, $details, $nrtype);
		return $reps;
	}

	protected function setupCustomVariables ($nrtype, $order, $reps, $customvars) {
		foreach ($customvars as $c) {
			$conditionvar = strtolower($c['conditionvar']);
			$op = $c['conditionop'];
			
			$found = false;
			$match = false;
			$compareval = null;
			
			if (isset($reps[$conditionvar])) {
				$found = true;
				$compareval = $reps[$conditionvar];
			} elseif (isset($reps['['.$conditionvar.']'])) {
				$found = true;
				$compareval = $reps['['.$conditionvar.']'];
			}/* elseif ($order && $compareval = $order->getData($conditionvar)) {
				// TODO: Handle order property
				$found = true;
			}*/ else {
				// TODO: Handly other possible properties!
				// TODO: Print out warning that variable could not be found.
			}
			if ($found) {
				$condval = $c['conditionval'];
				switch ($op) {
					case 'nocondition':
							$match = true; break;
					case 'equals': 
							if (is_array($compareval)) {
								$match = ($condval == '') && empty($compareval);
							} else {
								$match = ($compareval == $condval); 
							}
							break;
					case 'contains':
							if (is_array($compareval)) {
								$match = in_array($condval, $compareval);
							} else {
								$match = strpos ($compareval, $condval);
							}
							break;
					case 'smaller':
							$match = ($compareval<$condval); break;
					case 'smallerequal':
							$match = ($compareval<=$condval); break;
					case 'larger':
							$match = ($compareval>$condval); break;
					case 'largerequal':
							$match = ($compareval>=$condval); break;
					case 'startswith':
							$match = (substr("$compareval", 0, strlen("$condval")) === "$condval"); break;
					case 'endswith':
							$match = (substr("$compareval", -strlen("$condval")) === "$condval"); break;
				}
			} elseif (empty($conditionvar)) {
				$match = true;
			}
			if ($match) {
				$varname = '['.strtolower($c['newvar']).']';
				$reps[$varname] = $c['newval'];
			}
		}
		return $reps;
	}

	// Allow the user to override the format like any other custom variable:
	protected function setupNumberFormatString($fmt, $type, $order, $reps) {
		if (isset($reps['['.$type.'_format]'])) {
			return $reps['['.$type.'_format]'];
		} else {
			return $fmt;
		}
	}
	
	protected function no_array($v) {
		return !is_array($v);
	}
	protected function doReplacements ($fmt, $reps) {
		// First, replace all random...[n] fields. This needs to be done with a regexp and a callback:
		$fmt = preg_replace_callback ('/\[(random)(.*?)([0-9]*?)\]/i', array($this, 'replaceRandom'), $fmt);
		// Only use string-valued variables for replacement (array-valued variables can be used in custom variable definitions!)
		$reps = array_filter($reps, array($this, "no_array") );
		return str_ireplace (array_keys($reps), array_values($reps), $fmt);
	}
	
	protected function extractCounterSettings ($fmt, $type, $ctrsettings) {
		// Some e-Commerce systems use 'yes' for true, others use 1 => correct everything to 1
		if ($ctrsettings["${type}_global"] == 'yes') {
			$ctrsettings["${type}_global"] = 1;
		}
		
		$parts=array($fmt);
		if ($this->getFlag('extract-counter-settings', true)) {
			// First, extract all counter settings, i.e. all strings of the form [#####:startval/increment] or [####/increment:startval]
			$regexp = '%\[(#+)(/([0-9]+))?(:([0-9]+))?(/([0-9]+))?\]%';
		
			if (preg_match($regexp, $fmt, $counters)) {
				// $counters is an array of the form:
				// Array (
				// 		[0] => [#####:100/3]
				// 		[1] => #####
				// 		[2] => 
				// 		[3] => 
				// 		[4] => :100
				// 		[5] => 100
				// 		[6] => /3
				// 		[7] => 3
				// )
				$ctrsettings["${type}_padding"] = strlen($counters[1]);
				if (!empty($counters[2])) {
					// $counters[2] contains the whole "/n" part, while $counters[3] contains just the step itself
					$ctrsettings["${type}_step"] = $counters[3]; 
				}
				if (!empty($counters[4])) {
					// $counters[4] contains the whole ":n" part, while $counters[5] contains just the start value itself
					$ctrsettings["${type}_start"] = $counters[5]; 
				}
			
				if (!empty($counters[6])) {
					// $counters[6] contains the whole ":n" part, while $counters[7] contains just the start value itself
					$ctrsettings["${type}_step"] = $counters[7]; 
				}
			
				$fmt = preg_replace($regexp, "#", $fmt);
			}
			// Split at a | to get the number format and a possibly different counter increment format
			// If a separate counter format is given after the |, use it, otherwise reuse the number format itself as counter format
			$parts = explode ("|", $fmt);
		}
		$ctrsettings["${type}_format"] = $parts[0];
		$ctrsettings["${type}_counter"] = ($ctrsettings["${type}_global"]==1)?"":$parts[(count($parts)>1)?1:0];
		
		return $ctrsettings;
	}

	/**
	 * Create a number of given type for the given format. Optionally, custom variable definitions and counter formatting can be passed.
	 *   @param fmt The Format of the number (containing variables as [variable] and the counter as # or [####:initial/step])
	 *   @param type The type of the number format, typically order_number, invoice_number, etc. (depending on the e-commerce suite)
	 *   @param order The e-commerce-suite specific object describing the order. This will simply be passed on to the replacement hooks function for further data extraction during variable setup
	 *   @param customvars Definitions (conditions and values) for custom variables. An array of arrays with keys conditionvar, conditionop, conditionval, newvar, newval
	 *   @param ctrsettings Counter formatting defaults (will be overridden by an explicit counter formating variable of [####:initial/step] in the format). Array keys are: $type_format, $type_counter, $type_global, $type_padding, $type_step, $type_start
	 *   @return A new number for the given format. The incremented counter has been properly stored.
	 */
	public function createNumber ($fmt, $type, $order, $customvars, $ctrsettings) {
		$reps   = $this->setupReplacements ($type, $order);
		$reps   = $this->setupCustomVariables ($type, $order, $reps, $customvars);
		$format = $this->setupNumberFormatString($fmt, $type, $order, $reps);
		$format = $this->doReplacements($format, $reps);
		$ctrsettings = $this->extractCounterSettings ($format, $type, $ctrsettings);

		// Increment the counter only if the format contains a placeholder for it!
		if (strpos($ctrsettings["${type}_format"], "#") !== false) {
			$countername = $ctrsettings["${type}_counter"];
			// Look up the current counter
			$count = $this->getCounter($type, $countername, $ctrsettings["${type}_start"] - $ctrsettings["${type}_step"]) + $ctrsettings["${type}_step"];
			$this->setCounter($type, $countername, $count);
			// return the format with the counter inserted
			$number = str_replace ("#", sprintf('%0' . $ctrsettings["${type}_padding"] . 's', $count), $ctrsettings["${type}_format"]);
		} else {
			$number = $ctrsettings["${type}_format"];
		}
		return $number;
	}
	
	
	/**
	 * Create the counter modification HTML table
	 *     @param $type string
	 *          
	 *     @param
	 */
	public function counter_modification_create_table($type, $counters) {
        $html=array();
        $html[] = "<img src='" . $this->urlPath ('images', 'loading.gif') . "' class='ordernumber-loading' />";
        $html[] = "<table class=\"ordernumber-countertable " . $this->getStyle('counter-table-class') . "\" " . $this->getStyle('counter-table-style') . " id='ordernumber-countertable-" . $type . "'>";
        $html[] = "<thead>";
        $html[] = "	<tr>";
        $html[] = "		<th class='counter_format'>" . $this->__ ('PLG_ORDERNUMBER_COUNTERLIST_HEADER_COUNTER')."</th>";
        $html[] = "		<th class='counter_value'>" . $this->__ ('PLG_ORDERNUMBER_COUNTERLIST_HEADER_VALUE'). "</th>";
        $html[] = "		<th class='counter_buttons'></th>";
        $html[] = "	</tr>";
        $html[] = "</thead>";
        $html[] = "	<colgroup><col class='counter_type'><col style=\"text-align: center\" ><col ></colgroup>";
        $html[] = "<tbody>";
        foreach ($counters as $c) {
			$cc = (object)$c;
            $html[] = $this->counter_modification_create_row ($type, $cc->name, $cc->value);
        }
        $html[] = "</tbody>";
        $html[] = "<tfoot>";
        $html[] = "	<tr class='addcounter_row'>";
        $html[] = "		<td colspan=3 class='counter_add'>";
        $html[] = "			<div class='ordernumber-counter-addbtn ordernumber-btn' onClick='ajaxAddCounter(this, " . json_encode($type).")'>";
        $html[] = "				<div class='ordernumber-ajax-loading'>";
        $html[] = "					<img src='" . $this->urlPath('images', 'icon-16-new.png') . "' class='ordernumber-counter-addbtn' />";
        $html[] = "				</div>" . $this->__('PLG_ORDERNUMBER_COUNTERLIST_ADD');
        $html[] = "			</div>";
        $html[] = "		</td>";
        $html[] = "  </tr>";
        $html[] = "</tfoot>";
        $html[] = "</table>";
        return implode("\n", $html);
    }

    public function counter_modification_create_row ($type, $counter, $value) {
		$html=array();
		$html[] = "	<tr class='counter_row counter_row_$type'>";
		$html[] = "		<td class='counter_format'>" . htmlentities((string)(($counter=="")?($this->__ ('PLG_ORDERNUMBER_COUNTERLIST_GLOBAL')):$counter)) . "</td>";
		$html[] = "		<td class='counter_value'>" . htmlentities((string)$value) . "</td>";
		$html[] = "		<td class='counter_buttons'>";
		$html[] = "			<div class='ordernumber-ajax-loading'>";
		$html[] = "				<img src='" . $this->urlPath('images', 'icon-16-edit.png') . "' class='ordernumber-counter-editbtn ordernumber-btn' ";
		$html[] = "					onClick='ajaxEditCounter(this, " . json_encode($type) . ", ".json_encode($counter).", " . json_encode($value). ")' />";
		$html[] = "			</div>";
		$html[] = "			<div class='ordernumber-ajax-loading'>";
		$html[] = "				<img src='" . $this->urlPath ('images', 'icon-16-delete.png') . "' class='ordernumber-counter-deletebtn ordernumber-btn' ";
		$html[] = "					onClick='ajaxDeleteCounter(this, ".json_encode($type).", ".json_encode($counter).", " . json_encode($value) . ")' />";
		$html[] = "			</div>";
		$html[] = "		</td>";
		$html[] = "	</tr>";
		return implode("\n", $html);
	}
	
	/**
	 * Create the html table (with AJAX) to define and modify custom variable definitions.
	 * The returned HTML code assumes that the caller properly adds the ordernumber.css 
	 * and ordernumber.js to the page and loads the jQuery library.
	 *    @param name string
	 *        The HTML post/request variable name for the control.
	 *    @param variables array
	 *        The current list of custom variable replacements
	 *
	 *    @retval string
	 *        The HTML code for the custom variable definition table.
	 */
    public function custom_variables_create_table($name, $variables) {
        $html=array();
//         $html[] = "<pre>Variables: ".print_r($variables,1)."</pre>";
        $html[] = '<table id="ordernumber_variables_template" style="display:none">';
        $html[] = $this->custom_variables_create_row($name, array(), 'disabled');
        $html[] = '</table>';
        
        $html[] = '<table id="ordernumber_variables" class="ordernumber_variables ' . $this->getStyle('variable-table-class') . '" cellspacing="0" ' . $this->getStyle('variable-table-style') . '>';
        $columns = array(
            'variables_ifvar'    => $this->__('PLG_ORDERNUMBER_REPL_IFVAR'),
            'variables_ifop'     => '',
            'variables_ifval'    => $this->__('PLG_ORDERNUMBER_REPL_IFVAL'),
            'variables_then'     => '',
            'variables_thenvar'  => $this->__('PLG_ORDERNUMBER_REPL_SETVAR'),
            'variables_thenval'  => $this->__('PLG_ORDERNUMBER_REPL_TOVAL'),
            'sort'     => '',
            'variables_settings' => '',
        );
        $html[] = '	<thead>';
        $html[] = '		<tr class="ordernumber_variables_header">';
        foreach ( $columns as $key => $column ) {
        	$html[] = '<th class="' . $key . '">' . htmlspecialchars( $column ) . '</th>';
        }
        $html[] = '		</tr>';
        $html[] = '		<tr id="ordernumber-replacements-empty-row" class="oton-empty-row-notice ' . (empty($variables)?"":"rowhidden") . '">';
        $html[] = '			<td class="oton-empty-row-notice" colspan="8">';
        $html[] = '				<em>' . $this->__('PLG_ORDERNUMBER_REPL_NOCUSTOMVARS') . '</em>';
        $html[] = '				<input type="hidden" name="' . $name . '" value="" ' . (empty($variables))?'':'disabled' . '>';
        $html[] = '			</td>';
        $html[] = '		</tr>';
        $html[] = '	</thead>';
        $html[] = '	<colgroup>';
        foreach ($columns as $key => $column) {
        	$html[] = '<col class="' . $key . '" />';
        }
        $html[] = '	</colgroup>';
        $html[] = '';
        $html[] = '	<tbody>';
        foreach ($variables as $var) {
        	$html[] = $this->custom_variables_create_row($name, $var);
        }
        $html[] = '	</tbody>';
        $html[] = '	<tfoot>';
        $html[] = '		<tr class="addreplacement_row">';
        $html[] = '			<td colspan=8 class="variable_add">';
        $html[] = '				<div class="ordernumber-variables-addbtn ordernumber-btn" onClick="ordernumberVariablesAddRow(\'ordernumber_variables_template\', \'ordernumber_variables\')">';
        $html[] = '					<div class="ordernumber-ajax-loading"><img src="' . $this->urlPath('images', 'icon-16-new.png' ) . '" class="ordernumber-counter-addbtn" /></div>';
        $html[] = $this->__('PLG_ORDERNUMBER_REPL_ADDVAR');
        $html[] = '				</div>';
        $html[] = '			</td>';
        $html[] = '		</tr>';
        $html[] = '	</tfoot>';
        $html[] = '</table>';
        return implode("\n", $html);
    }
    
    public function custom_variables_create_row($name, $values = array(), $disabled = '') {
        $operator = (isset($values['conditionop'])?$values['conditionop']:'');
        $operators = array(
            'nocondition'  => $this->__('PLG_ORDERNUMBER_REPL_OP_NOCOND'),
            'equals'       => '=', 
            'contains'     => $this->__('PLG_ORDERNUMBER_REPL_OP_CONTAINS'), 
            'smaller'      => '<',
            'smallerequal' => '<=',
            'larger'       => '>',
            'largerequal'  => '>=', 
            'startswith'   => $this->__('PLG_ORDERNUMBER_REPL_OP_STARTS'),
            'endswith'     => $this->__('PLG_ORDERNUMBER_REPL_OP_ENDS'),
        );
        $html  = '
        <tr>
        	<td class="variables_ifvar"><input name="' . $name . '[conditionvar][]" value="' . (isset($values['conditionvar'])?htmlentities($values['conditionvar']):'') . '" ' . htmlentities($disabled) . '/></td>
        	<td class="variables_ifop"      ><select name="' . $name . '[conditionop][]" ' . htmlentities($disabled) . ' style="width: 100px">';
        foreach ($operators as $op => $opname) {
        	$html .= '		<option value="' . $op . '" ' . (($op === $operator)?'selected':'') . '>' . htmlspecialchars($opname) . '</option>';
        }
        $html .= '</select></td>
        	<td class="variables_ifval"   ><input name="' . $name . '[conditionval][]" value="' . (isset($values['conditionval'])?$values['conditionval']:'') . '" ' . htmlentities($disabled) . '/></td>
        	<td class="variables_then">=></td>
        	<td class="variables_thenvar"><input name="' . $name . '[newvar][]"       value="' . (isset($values['newvar'])?$values['newvar']:'') .       '" ' . htmlentities($disabled) . '/></td>
        	<td class="variables_thenval"><input name="' . $name . '[newval][]"       value="' . (isset($values['newval'])?$values['newval']:'') .       '" ' . htmlentities($disabled) . '/></td>
        	<td class="sort"></td>
        	<td class="variables_settings"><img src="' . $this->urlPath('images', 'icon-16-delete.png' ) . '" class="ordernumber-replacement-deletebtn ordernumber-btn"></td>
        </tr>';
        return $html;
    }

    /**
     * Modify the json that contains JavaScript setup code to be used by ordernumber.js
     */
	function addCustomJS(&$json) {}
	function appendJS() {}
	function createJSSetup() {
		static $done = 0; // <= prevent double js code
		$json = array();
		$json['ajax_url'] = $this->ajax_url;
		
		foreach ($this->jstranslations as $key) {
			$json[$key] = $this->__($key);
		}
		$this->addCustomJS($json);
		$js='/* <![CDATA[ */
var ajax_ordernumber = ' . json_encode($json) . ';
';
		$js .= $this->appendJS();
		$js .= '/* ]]> */
';
		$done = 1;
		return $js;
	}
	
	public function ajax_counter_delete($type, $counter) {
		// TODO: Check if counter value has changed meanwhile
		$json = array('action' => 'delete_counter', 'success' => 0);
		$json['success'] = $this->deleteCounter($type, $counter);
		return $json;
	}

	public function ajax_counter_add ($type, $counter, $value) {
		// TODO: Check if counter value has changed meanwhile
		$json = array('action' => 'add_counter', 'success' => 0);
		if ($this->getCounter($type, $counter, -1) != -1) {
			// Counter already exists => error message
			$json['error'] = sprintf($this->__('Counter "%s" already exists, cannot create again.'), $counter);
		} else {
			$json['success'] = $this->setCounter($type, $counter, $value);
			$json['row']  = $this->counter_modification_create_row($type, $counter, $value);
		}
		return $json;
	}
	
	public function ajax_counter_set ($type, $counter, $value) {
		$json = array('action' => 'set_counter', 'success' => 0);
		$json['success'] = $this->setCounter($type, $counter, $value);
		$json['row']  = $this->counter_modification_create_row($type, $counter, $value);
		return $json;
	}
}
