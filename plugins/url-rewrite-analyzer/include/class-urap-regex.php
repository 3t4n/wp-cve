<?php
/**
 * A utility class that can parse a regular expression.
 * This class will parse a regular expression into a tree.
 * If an error occurs, a Urap_Regex_Exception will be thrown.
 * Everything is a Urap_Regex_Piece.
 * A complete regex is a concatenation of pieces in a Urap_Regex_Group.
 * The complete regex is also a Urap_Regex_Group (with index 0).
 * A "repeater" is attached to the piece it applies to, it is not appended to the group.
 * The default __toString() of the main group (or any subgroup) will print out the regex as it was written. The main Url Rewrite Analyzer plugin has an example that will add HTML markup.
 */
class Urap_Regex {

    static function urap_parse( $regex ) {

        // Groups
        $group_counter = 0;
        $current_group = new Urap_Regex_Group( $group_counter );
        $group_stack   = array();

        // Ranges
        $is_in_range = false;
        $range       = '';

        // Repeaters
        $repeat_target = $current_group;

        $regex_len = strlen( $regex );
        for ( $idx = 0; $idx < $regex_len; $idx++ ) {
            $letter             = $regex[ $idx ];
            $is_greedy_switched = false;
            switch ( $letter ) {
                case '.':
                    $repeat_target   = new Urap_Regex_Any();
                    $current_group[] = $repeat_target;
                    break;
                case '$':
                    $repeat_target   = new Urap_Regex_End();
                    $current_group[] = $repeat_target;
                    break;
                // Escaping
                case '\\':
                    $idx++;
                    $repeat_target   = new Urap_Regex_Escape( $regex[ $idx ] );
                    $current_group[] = $repeat_target;
                    break;

                // Repeaters
                case '?':
                    if ( $idx + 1 < $regex_len && '?' === $regex[ $idx + 1 ] ) {
                        $is_greedy_switched = true;
                        $idx++;
                        $letter .= $regex[ $idx ];
                    }
                    $repeater                = new Urap_Regex_Repeater( $letter, 0, 1, $is_greedy_switched );
                    $repeat_target->repeater = $repeater;
                    $repeat_target           = $repeater;
                    break;
                case '*':
                    if ( '?' === $regex[ $idx + 1 ] ) {
                        $is_greedy_switched = true;
                        $idx++;
                        $letter .= $regex[ $idx ];
                    }
                    $repeater                = new Urap_Regex_Repeater( $letter, 0, null, $is_greedy_switched );
                    $repeat_target->repeater = $repeater;
                    $repeat_target           = $repeater;
                    break;
                case '+':
                    if ( '?' === $regex[ $idx + 1 ] ) {
                        $is_greedy_switched = true;
                        $idx++;
                        $letter .= $regex[ $idx ];
                    }
                    $repeater                = new Urap_Regex_Repeater( $letter, 1, null, $is_greedy_switched );
                    $repeat_target->repeater = $repeater;
                    $repeat_target           = $repeater;
                    break;
                case '{':
                    if ( !is_null( $repeat_target ) && preg_match( '/\{(\d*)(,?)(\d*)\}(\??)/', $regex, $repeat_matches, PREG_OFFSET_CAPTURE, $idx ) && $repeat_matches[0][1] === $idx ) {
                        $min_len = $repeat_matches[1][0];
                        $max_len = $repeat_matches[3][0];
                        if ( '' === $repeat_matches[2][0] ) {
                            $max_len = $min_len;
                        }
                        $is_greedy_switched      = ( '' !== $repeat_matches[4][0] );
                        $repeat_target->repeater = new Urap_Regex_Repeater( $repeat_matches[0][0], $min_len, $max_len, $is_greedy_switched );
                        $repeat_target           = $repeat_target->repeater;
                        $idx                    += strlen( $repeat_matches[0][0] ) - 1;
                    } else {
                        $repeat_target   = new Urap_Regex_Char( $letter );
                        $current_group[] = $repeat_target;
                    }
                    break;

                // Grouping
                case '(':
                    $group_counter++;
                    array_push( $group_stack, $current_group );
                    $current_group = new Urap_Regex_Group( $group_counter );
                    break;
                case ')':
                    if ( $prev_group   = array_pop( $group_stack ) ) {
                        $prev_group[]  = $current_group;
                        $repeat_target = $current_group;
                        $current_group = $prev_group;
                    } else {
                        throw new Urap_Regex_Exception( 'Unexpected ")"', $idx, $regex );
                    }
                    break;

                // Ranges
                case '[':
                    if ( $is_in_range ) {
                        throw new Urap_Regex_Exception( 'Unexpected "["', $idx, $regex );
                        return;
                    } else {
                        $is_in_range   = true;
                        $range         = '';
                        $repeat_target = null;
                    }
                    break;
                case ']':
                    if ( $is_in_range ) {
                        $repeat_target   = new Urap_Regex_Range( $range );
                        $current_group[] = $repeat_target;
                        $is_in_range     = false;
                    } else {
                        throw new Urap_Regex_Exception( 'Unexpected "]"', $idx, $regex );
                    }
                    break;

                default:
                    if ( $is_in_range ) {
                        $range .= $letter;
                    } else {
                        $repeat_target   = new Urap_Regex_Char( $letter );
                        $current_group[] = $repeat_target;
                    }
                    break;
            }
        }

        if ( !empty( $group_stack ) ) {
            throw new Urap_Regex_Exception( 'Unexpected end, still ' . count( $group_stack ) . ' open group(s) (missing ")")', $idx, $regex );
        }
        if ( $is_in_range ) {
            throw new Urap_Regex_Exception( 'Unexpected end, still in range (missing "]")', $idx, $regex );
        }

        return $current_group;
    }
}

class Urap_Regex_Exception extends Exception {

    protected $idx   = null;
    protected $regex = null;

    public function __construct( $message, $idx, $regex ) {
        parent::__construct( $message );
        $this->idx   = $idx;
        $this->regex = $regex;
    }

    public function __toString() {
        $regex_pieces = sprintf( '"%s"', substr( $this->regex, 0, $this->idx ) );
        if ( $this->idx < strlen( $this->regex ) ) {
            $regex_pieces .= sprintf(
                ' + "%s" + "%s"',
                $this->regex[ $this->idx ],
                substr( $this->regex, $this->idx + 1 )
            );
        }

        return $this->message . ' at char ' . $this->idx . ' (' . $regex_pieces . ')';
    }
}

class Urap_Regex_Group extends ArrayObject {

    public $counter  = null;
    public $repeater = null;

    public function __construct( $counter = 0 ) {
        $this->counter = $counter;
    }

    public function __toString() {
        $output = '';
        if ( $this->counter !== 0 ) {
            $output .= '(';
        }
        foreach ( $this as $el ) {
            $output .= $el;
        }
        if ( $this->counter !== 0 ) {
            $output .= ')';
        }
        $output .= $this->repeater;
        return $output;
    }
}

class Urap_Regex_Piece {

    public $repeater = null;
    public $value    = null;

    public function __toString() {
        return $this->value . $this->repeater;
    }
}

class Urap_Regex_Char extends Urap_Regex_Piece {

    public function __construct( $value = '' ) {
        $this->value = $value;
    }
}

class Urap_Regex_Escape extends Urap_Regex_Piece {

    public function __construct( $value = '' ) {
        $this->value = $value;
    }

    public function __toString() {
        return '\\' . $this->value . $this->repeater;
    }
}

class Urap_Regex_Special extends Urap_Regex_Piece {

    public $desc = null;

    public function __construct( $value = '', $desc = '' ) {
        $this->value = $value;
        $this->desc  = $desc;
    }
}

class Urap_Regex_Any extends Urap_Regex_Special {

    public function __construct() {
        parent::__construct( '.', 'any' );
    }
}

class Urap_Regex_End extends Urap_Regex_Special {

    public function __construct() {
        parent::__construct( '$', 'end' );
    }
}

class Urap_Regex_Repeater extends Urap_Regex_Piece {

    public $min_len            = null;
    public $max_len            = null;
    public $is_greedy_switched = null;

    public function __construct( $value = '', $min_len = null, $max_len = null, $is_greedy_switched = false ) {
        $this->value              = $value;
        $this->min_len            = $min_len;
        $this->max_len            = $max_len;
        $this->is_greedy_switched = $is_greedy_switched;
    }
}

class Urap_Regex_Range extends Urap_Regex_Piece {

    public function __construct( $value = '' ) {
        $this->value = $value;
    }
}
