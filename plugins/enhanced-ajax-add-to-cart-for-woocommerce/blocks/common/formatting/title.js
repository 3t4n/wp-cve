export const createTitle = ( {
	product = [],
	variation = [],
	titleType = 'full',
} ) => {
	const parentName = product.name;
	var varName = '';

	if ( variation && parentName && titleType !== 'base' ) {
		if ( variation.attributes ) {
			varName = '' + variation.attributes.map( ( attribute ) => " " + attribute.option );
		}
		if ( ( parentName ) && ( varName ) && titleType === 'full' ) {
			return parentName + ' - ' + varName;
		} else if ( varName && titleType === 'att' ) {
			return varName;
		} else {
			if ( product ) {
				if ( ! parentName ) {
					return "Error: product name is null";
				}
				return "Error: Variation attributes are null";
			}
		}
	} else if ( product ) {
		if ( parentName ) {
			return parentName;
		}
		else {
			return "Error: product name is null";
		}
	}
	else {
		return 'Error: product is null';
	}
};

