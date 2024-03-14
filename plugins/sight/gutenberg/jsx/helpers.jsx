/**
 * Compare 2 values
 *
 * @param {mixed} lval Left value.
 * @param {string} operator Operator.
 * @param {mixed} rval Right value.
 *
 * @returns {boolean}
 */
function compare( lval, operator, rval ) {
	let checkResult = true;

	switch ( operator ) {
		case '==':
			checkResult = lval == rval;
			break;
		case '===':
			checkResult = lval === rval;
			break;
		case '!=':
			checkResult = lval != rval;
			break;
		case '!==':
			checkResult = lval !== rval;
			break;
		case '>=':
			checkResult = lval >= rval;
			break;
		case '<=':
			checkResult = lval <= rval;
			break;
		case '>':
			checkResult = lval > rval;
			break;
		case '<':
			checkResult = lval < rval;
			break;
		case 'AND':
			checkResult = lval && rval;
			break;
		case 'OR':
			checkResult = lval || rval;
			break;
		default:
			checkResult = lval;
			break;
	}

	return checkResult;
}

/**
 * Check condition
 *
 * @param {Object} conditions - Conditions array.
 * @param {Object} attributes - Available block attributes.
 * @param {string} relation - Can be one of 'AND' or 'OR'.
 *
 * @returns {Boolean}
 */
function checkCondition( conditions, attributes, relation ) {
	const childRelation = ( 'AND' === relation ) ? 'OR' : 'AND';

	// by default result will be TRUE for relation AND
	// and FALSE for relation OR.
	let result = relation === 'AND';

	conditions.forEach( ( data ) => {
		if ( Array.isArray( data ) ) {
			result = compare( result, relation, checkCondition( data, attributes, childRelation ) );
		} else if ( data.field ) {
			const splitValName = data.field.split( '.' );
			let fieldVal = undefined;

			// check for array values like:
			// toggleListName['option1']
			if ( 2 === splitValName.length && typeof attributes[ splitValName[ 0 ] ] !== 'undefined' && typeof attributes[ splitValName[ 0 ] ][ splitValName[ 1 ] ] !== 'undefined' ) {
				fieldVal = attributes[ splitValName[ 0 ] ][ splitValName[ 1 ] ];
			}

			// check for normal values
			if ( typeof fieldVal === 'undefined' ) {
				fieldVal = attributes[ data.field ];
			}

			// check count
			if ( typeof data.count !== 'undefined' ) {
				const count = fieldVal.split( data['count'] );
				fieldVal = count.length - 1;
			}

			if ( typeof fieldVal !== 'undefined' ) {
				result = compare( result, relation, compare( fieldVal, data.operator, data.value ) );
			}
		}
	} );

	return result;
}

/**
 * Check field visible.
 */
function isFieldVisible(key, config, attributes) {
	var visible;

	// Set visible.
	visible = config.attributes[key]['visible'];

	// Check active_callback in fields.
	if ( visible && config.attributes[key]['active_callback'] && config.attributes[key]['active_callback'].length ) {
		visible = checkCondition( config.attributes[key]['active_callback'], attributes, 'AND' );
	}

	return visible;
}

export { isFieldVisible };
