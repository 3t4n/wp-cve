<?php
/**
 * This file contains the external class BIBTEXPARSE of WIKINDX
 * @see https://wikindx.sourceforge.io/ The WIKINDX SourceForge project
 * 
 * @author The WIKINDX Team
 * @license https://www.isc.org/licenses/ ISC License
 */

/*

(Amendments to file reading Daniel Pozzi for v1.1)

21/08/2004 v1.4 Guillaume Gardey
    - Added PHP string parsing and expand macro features.
    - Fix bug with comments, strings macro.
    - expandMacro = FALSE/TRUE to expand string macros.
    - loadStringMacro($bibtex_string) to load a string. (array of lines)

22/08/2004 v1.4 Mark Grimshaw 
    - a few adjustments to Guillaume's code.

28/04/2005 v1.5 Mark Grimshaw 
    - a little debugging for @preamble

02/05/2005 G. Gardey 
    - Add support for @string macro defined by curly brackets: @string{M12 = {December}}
    - Don't expand macro for bibtexCitation and bibtexEntryType
    - Better support for fields like journal = {Journal of } # JRNL23

03/05/2005 G. Gardey 
    - Fix wrong field value parsing when an entry ends by someField = {value}}

11/06/2005 v1.53 Mark Grimshaw-Aagaard:  
    - Stopped expansion of @string when entry is enclosed in {...} or "..."

v2 ****************************************** v2
						   
30/01/2006 v2.0 Esteban Zimanyi 
    - Add support for @string defined by other strings as in @string( AA = BB # " xx " # C }
    - Add support for comments as defined in Bibtex, i.e., ignores everything that is outside
      entries delimited by '@' and the closing delimiter. In particular, comments in Bibtex do not 
      necessarily have a % at the begining of the line !
      This required a complete rewrite of many functions as well as writing new ones !

31/01/2006 Mark Grimshaw-Aagaard
   - Ensured that @comment{...} is ignored in parseEntry().
   - Modified extractEntries() to ensure that entries where the start brace/parenthesis is on a 
     new line are properly parsed.
	 
10/02/2006 Mark Grimshaw-Aagaard
  - A 4th array, $this->undefinedStrings, is now returned that holds field values that are judged to be undefined strings.  
i.e. they are a non-numeric value that is not defined in a @string{...} entry and not enclosed by braces or double-quotes.
This array will be empty unless the following condition is met:
($this->removeDelimit || $this->expandMacro && $this->fieldExtract)

24/04/2006 Esteban Zimanyi
  - When an undefined string is found in function removeDelimiters return the empty string
  - Return $this->undefinedStrings in the last position to allow compatibility with previous versions
  - Fix management of preamble in function returnArrays

28/12/2015 Michael Winkler
  - Outdated PHP methods replaced
  - Fix for saving line breaks in abstracts and other fields

07/04/2019 Michael Winkler
  - Fix array offset errors in get_line()

15/02/2021 Michael Winkler
  - Include updates from WIKINDX 6.4.1

*/

/**
 * Main parsing class of BibTeXParse
 * 
 * Inspired by an awk BibTeX parser written by Nelson H. F. Beebe over 
 * 20 years ago although little of that remains.
 * 
 * @version 2.1.1
 */
class BIBTEXPARSE {

    public function __construct() {
        $this->preamble = $this->strings = $this->undefinedStrings = $this->entries = array();
        $this->count = 0;
        $this->fieldExtract = TRUE;
        $this->removeDelimit = TRUE;
        $this->expandMacro = FALSE;
        $this->parseFile = TRUE;
        $this->outsideEntry = TRUE;
    }
    
    /**
     * Open bib file
     * 
     * @param string $file
     */
    public function openBib($file) {
        if(!is_file($file)) {
            debug_print_backtrace();
            die;
        }
        $this->fid = fopen($file, 'r');
        $this->parseFile = TRUE;
    }
    
    /**
     * Load a bibtex string to parse it
     * 
     * @param string $bibtex_string
     */
    public function loadBibtexString($bibtex_string) {
        if (is_string($bibtex_string)) {
            $line_array = explode("\n",$bibtex_string);
            $this->bibtexString = self::_setLineBreak($line_array);
        }
        else {
            $this->bibtexString = $bibtex_string;
        }
        $this->parseFile = FALSE;
        $this->currentLine = 0;
    }
    
    /**
     * Set strings macro
     * 
     * @param array $macro_array
     */
    public function loadStringMacro($macro_array) {
        $this->userStrings = $macro_array;
    }
    
    /**
     * Close bib file
     */
    public function closeBib() {
        fclose($this->fid);
    }
    
    /**
     * Get a non-empty line from the bib file or from the bibtexString
     * 
     * @return false|string
     */
    public function getLine() {
        if ($this->parseFile) {
            if (!feof($this->fid)) {
                do {
                    $line = trim(fgets($this->fid));
                }
                while (!feof($this->fid) && !$line);
                return $line;
            }
            return FALSE;
        }
        else {
            while($this->currentLine < count($this->bibtexString) && !isset($line)) {
                $line = trim($this->bibtexString[$this->currentLine]);
                $this->currentLine++;
                // echo $this->currentLine . ' ' . $line . '<br/>';
                return $line;
            }
        }
    }
    
    /**
     * Extract value part of @string field enclosed by double-quotes or braces.
     * 
     * The string may be expanded with previously-defined strings
     * 
     * @param string $string
     * @return string
     */
    public function extractStringValue($string) {
        // $string contains a end delimiter, remove it
        $string = trim( mb_substr( $string, 0, mb_strlen($string) - 1 ) );
        // remove delimiters and expand
        $string = $this->removeDelimitersAndExpand($string);
        return $string;
    }
    
    /**
     * Extract a field
     * 
     * @param string $seg
     * @return array
     */
    public function fieldSplit($seg) {
        // handle fields like another-field = {}
        $array = preg_split("/,\\s*([-_.:,a-zA-Z0-9]+)\\s*={1}\\s*/Uu", $seg, PREG_SPLIT_DELIM_CAPTURE);
        if (!array_key_exists(1, $array)) {
            return array($array[0], FALSE);
        }
        return array($array[0], $array[1]);
    }
    
    /**
     * Extract and format fields
     * 
     * @param string $oldString
     */
    public function reduceFields($oldString) {
        // 03/05/2005 G. Gardey. Do not remove all occurrences, just one
        // * correctly parse an entry ended by: somefield = {aValue}}
        $lg = mb_strlen($oldString);
        $lastChar = mb_substr($oldString, $lg - 1, 1);
        
        if ($lastChar == '}' || $lastChar == ')' || $lastChar == ',') {
            $oldString = mb_substr($oldString, 0, $lg - 1);
        }
        
        $split = preg_split('/=/u', $oldString, 2);
        
        $string = $split[1];
        while ($string) {
            list($entry, $string) = $this->fieldSplit($string);
            $values[] = $entry;
        }
        
        foreach ($values as $value) {
            $pos = mb_strpos($oldString, $value);
            $oldString = self::_mb_substr_replace($oldString, '', $pos, mb_strlen($value));
        }
        $rev = self::_mb_strrev(trim($oldString));
        if ( mb_substr($rev, 0, 1) != ',' ) {
            $oldString .= ',';
        }
        
        $keys = preg_split('/=,/u', $oldString);
        // 22/08/2004 - Mark Grimshaw-Aagaard
        // I have absolutely no idea why this array_pop is required but it is.  Seems to always be 
        // an empty key at the end after the split which causes problems if not removed.
        array_pop($keys);
        foreach ($keys as $key) {
            $value = trim(array_shift($values));
            $rev = self::_mb_strrev($value);
            // remove any dangling ',' left on final field of entry
            if ( mb_substr($rev, 0, 1) == ',' ) {
                $value = rtrim($value, ',');
            }
            if (!$value) {
                continue;
            }
            
            // 28/12/2015 Michael Winkler
            // Replace the control character for line breaks
            $value = self::_replaceLineBreak($value);
            
            // 21/08/2004 G.Gardey -> expand macro
            // Don't remove delimiters now needs to know if the value is a string macro
            // $this->entries[$this->count][strtolower(trim($key))] = trim($this->removeDelimiters(trim($value)));
            $key = strtolower(trim($key));
            $this->entries[$this->count][$key] = trim($value);
        }
    }
    
    /**
     * Start splitting a bibtex entry into component fields.
     * Store the entry type and citation.
     * 
     * @param string $entry
     */
    public function fullSplit($entry) {        
        $matches = preg_split("/@(.*)[{(](.*),/Uu", $entry, 2, PREG_SPLIT_DELIM_CAPTURE); 
        $this->entries[$this->count]['bibtexEntryType'] = mb_strtolower(trim($matches[1]));
        // sometimes a bibtex entry will have no citation key
        if (preg_match("/=/u", $matches[2])) { // this is a field
            $matches = preg_split("/@(.*)\\s*[{(](.*)/Uu", $entry, 2, PREG_SPLIT_DELIM_CAPTURE);
        }
        $this->entries[$this->count]['bibtexCitation'] = $matches[2];
        $this->reduceFields($matches[3]);
    }

    /**
     * Grab a complete bibtex entry
     * 
     * @param string $entry
     */
    public function parseEntry($entry) {
        $lastLine = FALSE;
        if (preg_match("/@(.*)([{(])/Uu", preg_quote($entry), $matches)) {
            if (!array_key_exists(1, $matches)) {
                return $lastLine;
            }
            if (preg_match("/string/ui", trim($matches[1]))) {
                $this->strings[] = $entry;
            }
            elseif (preg_match("/preamble/ui", trim($matches[1]))) {
                $this->preamble[] = $entry;
            }
            elseif (preg_match("/comment/ui", $matches[1])) {
                // MG (31/Jan/2006) -- ignore @comment
            }
            else {
                if ($this->fieldExtract) {
                    $this->fullSplit($entry);
                }
                else {
                    $this->entries[$this->count] = $entry; 
                }
                $this->count++;
            }
            return $lastLine;
        }
    }

    /**
     * Remove delimiters from a string
     *
     * @param string $string
     * @return string
     */
    public function removeDelimiters($string) {
        $StrLen = mb_strlen($string);

        if ($StrLen > 0) {
            $firstChar = mb_substr($string, 0, 1);
            $lastChar = mb_substr($string, $StrLen - 1, 1);

            if ($firstChar == '"' || ($firstChar == '{' && $lastChar == '}')) {
                $string = mb_substr($string, 1);
                $string = mb_substr($string, 0, -1);
            }
            /* Commented out to handle  undelimited strings such as months.  The bibtex entry is expected to be properly formatted.
                    else if (!is_numeric($string) && !array_key_exists($string, $this->strings))
                    { // Undefined string that is not a year
            echo "NO DELIMITERS: $string<P>";
                        if (array_search($string, $this->undefinedStrings) === FALSE)
                         // if not already in the undefinedStrings array
                            $this->undefinedStrings[] = $string;
                        return '';
                    }
            */
        }

        return $string;
    }

    /**
     * This function works like explode('#',$val) but has to take into account whether
     * the character # is part of a string (i.e., is enclosed into "..." or {...} ) 
     * or defines a string concatenation as in @string{ "x # x" # ss # {xx{x}x} }
     * 
     * @param string $val
     * @return string[]
     */
    public function explodeString($val) {
        $valLen = mb_strlen($val);
        $openquote = $bracelevel = $i = $j = 0; 
        while ($i < $valLen) {
            $s = mb_substr($val, $i, 1);
            
            if ($s == '"') {
                $openquote = !$openquote;
            }
            elseif ($s == '{') {
                $bracelevel++;
            }
            elseif ($s == '}') {
                $bracelevel--;
            }
            elseif ( $s == '#' && !$openquote && !$bracelevel ) {
                $strings[] = mb_substr($val, $j, $i - $j);
                $j = $i + 1;
            }
            $i++;
        }
        $strings[] = mb_substr($val, $j);
        return $strings;
    }

    /**
     * This function receives a string and a closing delimiter '}' or ')'
     * and looks for the position of the closing delimiter taking into
     * account the following Bibtex rules:
     * Inside the braces, there can arbitrarily nested pairs of braces,
     *    but braces must also be balanced inside quotes!
     * Inside quotes, to place the " character it is not sufficient
     *    to simply escape with \": Quotes must be placed inside braces.
     *
     * @param string $val
     * @param string $delimitBegin
     * @param string $delimitEnd
     *
     * @return int (0)
     */
    public function closingDelimiter($val, $delimitBegin, $delimitEnd) {
        $valLen = mb_strlen($val);
        $DelimLen = mb_strlen($delimitEnd);

        $openquote = 0;
        $bracelevel = 0;
        $i = 0;
        while ($i < $valLen && ($valLen - $i >= $DelimLen)) {
            $s = mb_substr($val, $i, $DelimLen);
            // a '"' found at brace level 0 defines a value such as "ss{\"o}ss"
            if ($s == '"' && !$bracelevel) {
                $openquote = !$openquote;
            }
            elseif ($s == $delimitBegin) {
                $bracelevel++;
            }
            elseif ($s == $delimitEnd) {
                $bracelevel--;
            }
            if ($s == $delimitEnd && !$openquote && !$bracelevel) {
                return $i;
            }

            $i += $DelimLen;
        }
        return 0;
    }

    /**
     * Remove enclosures around entry field values.  Additionally, expand macros if flag set.
     * 
     * @param string $string
     * @param bool $inpreamble Default is FALSE
     * @return string
     */
    public function removeDelimitersAndExpand($string, $inpreamble = FALSE) {
        // only expand the macro if flag set, if strings defined and not in preamble
        if (!$this->expandMacro || empty($this->strings) || $inpreamble) {
            $string = $this->removeDelimiters($string);
        }
        else {
            $stringlist = $this->explodeString($string);
            $string = "";
            foreach ($stringlist as $str) {
                // trim the string since usually # is enclosed by spaces
                $str = trim($str); 
                // replace the string if macro is already defined
                // strtolower is used since macros are case insensitive
                if ( isset( $this->strings[mb_strtolower($str)] ) ) {
                    $string .= $this->strings[mb_strtolower($str)];
                }
                else { 
                    $string .= $this->removeDelimiters( trim($str) );
                }
            }
        }
        return $string;
    }

    /**
     * This function extract entries taking into account how comments are defined in BibTeX.
     * BibTeX splits the file in two areas: inside an entry and outside an entry, the delimitation 
     * being indicated by the presence of a @ sign. When this character is met, BibTex expects to 
     * find an entry. Before that sign, and after an entry, everything is considered a comment! 
     */
    public function extractEntries() {
        $EntryDelim = '@';
        $ListDelim = array('(' => ')', '{' => '}');
        $inside = $possibleEntryStart = FALSE;
        $entry = '';
        while ($line = $this->getLine()) {
            if ($possibleEntryStart) {
                $line = $possibleEntryStart . $line; 
            }
            if (!$inside && mb_strstr($line, $EntryDelim)) {
                // throw all characters before the '@'
                $line = mb_strstr($line, $EntryDelim);
                
                $IsOpenDelimNotFinded = TRUE;
                foreach ($ListDelim as $do => $dc) {
                    $IsOpenDelimNotFinded &= !mb_strstr($line, $do);
                }
                if ($IsOpenDelimNotFinded) {
                    $possibleEntryStart = $line;
                }
                elseif (preg_match("/$EntryDelim.*([" . preg_quote(implode('', array_keys($ListDelim))) . "])/Uu", preg_quote($line), $matches)) {
                    $inside = TRUE;
                    $delimitBegin = $matches[1];
                    $delimitEnd = $ListDelim[$matches[1]];
                    $possibleEntryStart = FALSE;
                }
            }
            
            if ($inside) {
                $entry .= ' ' . $line;
                if ( $j = $this->closingDelimiter($entry, $delimitBegin, $delimitEnd) ) {
                    // all characters after the delimiter are thrown but the remaining 
                    // characters must be kept since they may start the next entry !!!
                    $lastLine = mb_substr($entry, $j + 1);
                    $entry = mb_substr($entry, 0, $j + 1);
                    // Strip excess whitespaces from the entry 
                    $entry = preg_replace('/\s\s+/u', ' ', $entry);
                    $this->parseEntry($entry);
                    $entry = mb_strstr($lastLine, $EntryDelim);
                    if ($entry) {
                        $inside = TRUE;
                    }
                    else {
                        $inside = FALSE;
                    }
                }
            }
        }
    }

    /**
     * Return arrays of entries etc. to the calling process.
     * 
     * @return array (preamble, strings, entry, undefinedStrings)
     */
    public function returnArrays() {
        foreach ($this->preamble as $value) {
            preg_match("/.*?[{(](.*)/u", $value, $matches);
            $preamble = mb_substr($matches[1], 0, -1);
            $preambles['bibtexPreamble'] = trim($this->removeDelimitersAndExpand(trim($preamble), TRUE));
        }
        if (isset($preambles)) {
            $this->preamble = $preambles;
        }
        if ($this->fieldExtract) {
            // Next lines must take into account strings defined by previously-defined strings
            $strings = $this->strings; 
            // $this->strings is initialized with strings provided by user if they exists
            // it is supposed that there are no substitutions to be made in the user strings, i.e., no # 
            $this->strings = isset($this->userStrings) ? $this->userStrings : array();
            foreach ($strings as $value) {
                // changed 21/08/2004 G. Gardey
                // 23/08/2004 Mark G. account for comments on same line as @string - count delimiters in string value
                $value = trim($value);
                $matches = preg_split("/@\\s*string\\s*([{(])/ui", $value, 2, PREG_SPLIT_DELIM_CAPTURE);
                $matches = preg_split("/=/u", $matches[2], 2, PREG_SPLIT_DELIM_CAPTURE);
                // macros are case insensitive
                $this->strings[mb_strtolower(trim($matches[0]))] = $this->extractStringValue($matches[1]); 
            }
        }
        // changed 21/08/2004 G. Gardey
        // 22/08/2004 Mark Grimshaw-Aagaard - stopped useless looping.
        // removeDelimit and expandMacro have NO effect if !$this->fieldExtract
        if ($this->removeDelimit || $this->expandMacro && $this->fieldExtract) {
            for ($i = 0; $i < count($this->entries); $i++) {
                foreach ($this->entries[$i] as $key => $value) {
                    // 02/05/2005 G. Gardey don't expand macro for bibtexCitation 
                    // and bibtexEntryType
                    if ($key != 'bibtexCitation' && $key != 'bibtexEntryType') {
                        $this->entries[$i][$key] = trim($this->removeDelimitersAndExpand($this->entries[$i][$key])); 
                    }
                }
            }
        }
        
        return array($this->preamble, $this->strings, $this->entries, $this->undefinedStrings);
    }
    
    /**
     * Simulate substr_replace() for multibytes strings
     *
     * @param string $string
     * @param string $replacement
     * @param int $start
     * @param int $length Default is NULL.
     * @param string $encoding Default is NULL.
     *
     * @return string
     */
    private static function _mb_substr_replace($string, $replacement, $start, $length = NULL, $encoding = NULL) {
        if (extension_loaded('mbstring') === FALSE) {
            return (is_null($length) === TRUE) ? substr_replace($string, $replacement, $start) : substr_replace($string, $replacement, $start, $length);
        }
        
        $string_length = (is_null($encoding) === TRUE) ? mb_strlen($string) : mb_strlen($string, $encoding);

        if ($start < 0) {
            $start = max(0, $string_length + $start);
        }
        elseif ($start > $string_length) {
            $start = $string_length;
        }

        if ($length < 0) {
            $length = max(0, $string_length - $start + $length);
        }
        elseif ((is_null($length) === TRUE) || ($length > $string_length)) {
            $length = $string_length;
        }

        if (($start + $length) > $string_length) {
            $length = $string_length - $start;
        }

        if (is_null($encoding) === TRUE) {
            return mb_substr($string, 0, $start) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length);
        }

        return mb_substr($string, 0, $start, $encoding) . $replacement . mb_substr($string, $start + $length, $string_length - $start - $length, $encoding);
    }
    
    /**
     * Simulate strrev() for multibytes strings
     *
     * @param string $str
     *
     * @return string
     */
    private static function _mb_strrev($str) {
        preg_match_all('/./us', $str, $ar);

        return implode('', array_reverse($ar[0]));
    }

    /**
     * Sets a control character for saving line breaks
     * @param array $line_array
     * @return array
     * @since 2.3
     * @author Michael Winkler <michael.mtrv@gmail.com>
     */
    private static function _setLineBreak($line_array) {
        $max = count($line_array);
        
        // Ignore the first line
        for ( $i = 1; $i < $max; $i++ ) {
            $line_before = mb_substr(trim( $line_array[$i-1] ), -2, 2);
            
            // Fix blank lines at the end of the entry
            $line_end = ( isset($line_array[$i][0]) ) ? $line_array[$i][0] : '';

            if ( strpos($line_array[$i], '@') === false &&  // No '@' in the line
                 strpos($line_array[$i], '=') === false &&  // No '=' in the line
                 $line_before !== '},' &&                   // No '},' at the end of the line before
                 $line_end !== '}'                          // No '}' at the beginning of the line
                ) {
                $line_array[$i] = '<LineBreak>' . $line_array[$i];
            }
        }
        return $line_array;
    }
    
    /**
     * Replaces the control character with the original line break
     * @param string $string
     * @return string
     * @since 2.3
     * @author Michael Winkler <michael.mtrv@gmail.com>
     */
    private static function _replaceLineBreak($string) {
        $return = str_replace('<LineBreak>', chr(13) . chr(10), $string);
        return $return;
    }
    
}