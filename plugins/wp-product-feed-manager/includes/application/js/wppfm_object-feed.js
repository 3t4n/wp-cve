// noinspection JSUnusedGlobalSymbols

/**
 * Feed Object
 *
 * @param {int}     feedId
 * @param {string}  title               Title of the feed file
 * @param {int}     includeVariations   Does this feed include variations
 * @param {int}     aggregator          Is this an aggregator feed
 * @param {int}     channel             Channel definition
 * @param {string}  mainCategory
 * @param {array}   categoryMapping
 * @param {string}  url
 * @param {string}  dataSource          Source id (for now only WooCommerce is supported)
 * @param {string}  country             Two letter country identifier
 * @param {string}  language
 * @param {string}  currency
 * @param {string}  feedTitle           Google's feed title text
 * @param {string}  feedDescription     Google's feed description text
 * @param {string}  updateSchedule      Updates schedule string
 * @param {array}   feedFilter          Feed filters
 * @param {int}     status              Status of the feed
 * @param {string}  feedType            Type of feed (default Product Feed Type)
 *
 * @constructor
 */
function Feed( feedId, title, includeVariations, aggregator, channel, mainCategory,
	categoryMapping, url, dataSource, country, language, currency, feedTitle,
	feedDescription, updateSchedule, feedFilter, status, feedType ) {

	this.feedId            = feedId;
	this.title             = title;
	this.mainCategory      = mainCategory;
	this.categoryMapping   = categoryMapping;
	this.includeVariations = includeVariations;
	this.isAggregator      = aggregator;
	this.feedTitle         = feedTitle;
	this.feedDescription   = feedDescription;
	this.url               = url;
	this.dataSource        = dataSource;
	this.channel           = channel;
	this.country           = channel !== 3 ? country : 'NL'; // for Beslist.nl only register the Netherlands
	// noinspection DuplicatedCode
	this.language          = language;
	this.currency          = currency;
	this.status            = status;
	this.updateSchedule    = updateSchedule;
	this.feedFilter        = feedFilter;
	this.attributes        = [];
	this.promotions        = [];
	this.feedType          = feedType;

	// objects functions
	this.addAttribute                                  = addAttributeToFeed;
	this.getAttributeIdByName                          = getAttributeId;
	this.activateAttribute                             = activateFeedsAttribute;
	this.checkIfCustomNameExists                       = checkCustomName;
	this.countCombinedFields                           = countAttributesCombinedFields;
	this.deactivateAttribute                           = deactivateFeedsAttribute;
	this.deactivateCategory                            = deactivateCategoryMap;
	this.activateCategory                              = activateCategoryMap;
	this.setStaticAttributeValue                       = setAttributesStaticValue;
	this.setSourceValue                                = setAttributesSourceValue;
	this.setAlternativeSourceValue                     = setAlternativeAttributesSourceValue;
	this.setCombinedOutputValue                        = setAttributesCombinedOutputValue;
	this.setCategoryValue                              = setAttributesCategoryValue;
	this.setFeedFilter                                 = setFeedFilterValue;
	this.setPromotionInputValue                        = setPromotionInputValue;
	this.setPromotionSelectValue                       = setPromotionSelectValue;
	this.mapCategory                                   = mapACategory;
	this.changeCustomFeedCategoryMap                   = changeCustomCategoryMap;
	this.changeIncludeVariations                       = changeIncludeVariationsValue;
	this.changeAggregator                              = changeAggregatorValue;
	this.resetNrQueries                                = rstNrQueries;
	this.incrNrQueries                                 = addNrQueries;
	this.decrNrQueries                                 = substrNrQueries;
	this.addConditionValue                             = addAttributesCondition;
	this.addValueQueryValue                            = addAttributesValueQuery;
	this.removeAttribute                               = removeAttributeFromFeed;
	this.removeValueConditionValue                     = removeAttributesCondition;
	this.removeValueQueryValue                         = removeAttributesValueQuery;
	this.removeAlternativeSourceValue                  = removeAlternativeSource;
	this.removeCombinedOutputValue                     = removeAttributesCombinedOutputValue;
	this.removeEditValueValue                          = removeAttributesEditValue;
	this.removeFeedFilter                              = removeFeedFilterValue;
	this.getAttributesQueriesObject                    = getAttributesQueries;
	this.getCombinedOutputValue                        = getAttributesCombinedOutputValue;
	this.getValueQueryValue                            = getAttributesValueQueries;
	this.getAttributesSourceObject                     = getAttributesSources;
	this.getSourceObject                               = getSourceData;
	this.getAlternativeSourcesObject                   = getAlternativeSources;
	this.getAttributesValueObject                      = getAttributesValues;
	this.getFeedFilter                                 = getFeedFilterValue;
	this.getFeedType                                   = getFeedType;
	this.setMainCategory                               = setCategories;
	this.setUpdateSchedule                             = setSchedule;
	this.setCustomCategory                             = setFeedCustomCategory;
	this.setCountry                                    = changeCountry;
	this.clearSourceValue                              = clearAttributesSourceValue;
	this.cleanAttributes                               = cleanUnusedAttributes;
	this.clearAllAttributes                            = clearAttributes;
	this.clearPromotions                               = clearPromotions;
	this.addChangeValue                                = addAttributesChangeValue;
	this.addPromotion                                  = addPromotion;
	this.addPromotionElement                           = addPromotionElement;
	this.getPromotionProductsEligibleForPromotionValue = promotionProductsEligibleForPromotionValue;
	this.getPromotionCouponCodeRequiredValue           = promotionCouponCodeRequiredValue;
	this.deletePromotion                               = deletePromotion;
	this.duplicatePromotion                            = duplicatePromotion;
}

// noinspection DuplicatedCode
function FeedAttribute( attributeId, fieldName, advisedSource, value, fieldLevel, isActive,
	nrQueries, nrValueEdits, nrValueConditions ) {

	this.rowId             = attributeId;
	this.fieldName         = fieldName;
	this.advisedSource     = advisedSource;
	this.value             = value;
	this.fieldLevel        = fieldLevel;
	this.isActive          = isActive;
	this.nrQueries         = nrQueries;
	this.nrValueEdits      = nrValueEdits;
	this.nrValueConditions = nrValueConditions;
}

function CategoryMap( shopCategory, feedCategories ) {

	this.shopCategoryId = shopCategory;
	this.feedCategories = feedCategories;
}

function addAttributeToFeed( attributeId, fieldName, advisedSource, value, fieldLevel, isActive, queryLevel, valuesLevel, valueConditions ) {

	// I'm not sure why the code below was necessary, but it causes the edit feed pages for normal feeds and review feeds to not work
	// if ( ! value ) {
	// 	this.removeAttribute( attributeId );
	// 	return;
	// }
	//
	var found = this.attributes.find( obj => obj.fieldName === fieldName );

	if ( found ) { // if attribute already exists, remove it first
		this.removeAttribute( found.rowId );
	}

	var attribute = new FeedAttribute( attributeId, fieldName, advisedSource, value, fieldLevel, isActive, queryLevel, valuesLevel, valueConditions );
	this.attributes.push( attribute );
}
function removeAttributeFromFeed( rowId ) {

	for ( var i = 0; i < this.attributes.length; i ++ ) {

		if ( this.attributes[ i ].rowId === rowId ) {

			this.attributes.splice( i, 1 );
		}
	}
}

function mapACategory( categorySelectorId, category ) {

	var selectedLevel  = categorySelectorId.match( /(\d+)$/ )[ 0 ]; // next level
	var sc             = categorySelectorId.replace( '_' + selectedLevel, '' );
	var shopCategoryId = sc.match( /(\d+)$/ )[ 0 ];
	var mo             = JSON.parse( this.categoryMapping );

	// check if this.categoryMapping already has a mapped category with this id
	var categoryMapForGivenId = jQuery.grep(
		mo,
		function( e ) {
			return e.shopCategoryId === shopCategoryId;
		}
	);

	if ( categoryMapForGivenId.length > 0 ) {

		var oldString = categoryMapForGivenId[ 0 ].feedCategories;

		categoryMapForGivenId[ 0 ].feedCategories = wppfm_addNewItemToCategoryString(
			selectedLevel,
			oldString,
			category,
			' > '
		);
	} else { // this category map does not exist so add a new one to the array

		var catMap = new CategoryMap();

		catMap.shopCategoryId = shopCategoryId;
		catMap.feedCategories = category;

		mo.push( catMap );
	}

	this.categoryMapping = JSON.stringify( mo );
}

function changeCustomCategoryMap( shopCategoryId, feedCategories ) {

	var mo = JSON.parse( this.categoryMapping );

	// check if this.categoryMapping already has a mapped category with this id
	var categoryMapForGivenId = jQuery.grep(
		mo,
		function( e ) {
			return e.shopCategoryId === shopCategoryId;
		}
	);

	if ( categoryMapForGivenId.length > 0 ) {

		categoryMapForGivenId[ 0 ].feedCategories = feedCategories;
	} else { // this category map does not exist so add a new one to the array

		var catMap = new CategoryMap();

		catMap.shopCategoryId = shopCategoryId;
		catMap.feedCategories = feedCategories;

		mo.push( catMap );
	}

	this.categoryMapping = JSON.stringify( mo );
}

function changeIncludeVariationsValue( selectedValue ) {

	this.includeVariations = selectedValue ? '1' : '0';
}

function changeAggregatorValue( selectedValue ) {

	this.isAggregator = selectedValue ? '1' : '0';
}

function activateCategoryMap( shopCategoryId, channelUsesOwnCategory ) {

	// get the currently stored mapping
	var mo = this.categoryMapping.length > 0 ? JSON.parse( this.categoryMapping ) : [];

	// test if this.categoryMapping already has a mapped category with this id
	var categoryMapForGivenId = jQuery.grep(
		mo,
		function( e ) {
			return e.shopCategoryId === shopCategoryId.toString();
		}
	);

	// only store this mapping if it has not been registered already to this id
	if ( categoryMapForGivenId.length < 1 ) {

		var catMap = new CategoryMap();

		catMap.shopCategoryId = shopCategoryId.toString();
		catMap.feedCategories = channelUsesOwnCategory ? 'wp_ownCategory' : 'wp_mainCategory';

		mo.push( catMap );

		this.categoryMapping = JSON.stringify( mo );
	}
}

function deactivateCategoryMap( shopCategoryId ) {

	var mo = this.categoryMapping.length > 0 ? JSON.parse( this.categoryMapping ) : [];

	if ( mo.length > 1 ) {

		var index = wppfm_arrayObjectIndexOf(
			mo,
			shopCategoryId.toString(),
			'shopCategoryId'
		);

		mo.splice( index, 1 ); // remove the category object

		this.categoryMapping = JSON.stringify( mo );
	} else {

		this.categoryMapping = [];
	}
}

function getAttributeId( name ) {

	if ( ! this.attributes ) {
		return false;
	}

	var attributeId = false;

	for ( var i = 0; i < this.attributes.length; i ++ ) {

		if ( this.attributes[ i ][ 'fieldName' ] === name ) {

			attributeId = this.attributes[ i ][ 'rowId' ];
			break;
		}
	}

	if ( attributeId === false ) { // seems like a custom field

		attributeId = this.attributes.length;
	}

	return attributeId;
}

function setAttributesStaticValue( attributeId, level, combinationLevel, newValue ) {
	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_storeSourceValue( level, currentValue, 'static', newValue );

	this.attributes[ attributeId ][ 'isActive' ] = ! ! this.attributes[ attributeId ][ 'value' ];
}

function setAttributesSourceValue( attributeId, sourceLevel, newSource ) {
	var currentValue                          = this.attributes[ attributeId ][ 'value' ];
	this.attributes[ attributeId ][ 'value' ] = wppfm_storeSourceValue(
		sourceLevel,
		currentValue,
		'source',
		newSource
	);
}

function setAlternativeAttributesSourceValue( attributeId, level, value ) {

	var attributeValueObject = {};
	var attributeArray       = [];

	if ( this.attributes[ attributeId ][ 'value' ] ) {
		attributeValueObject = JSON.parse(
			this.attributes[ attributeId ][ 'value' ]
		);

		attributeValueObject.a = addOrRemoveValueInValueString(
			attributeValueObject.a,
			value,
			level,
			'add'
		);
	} else {

		var o  = {};
		o[ 1 ] = value;

		attributeArray.push( o );
		attributeValueObject.a = attributeArray;
	}

	this.attributes[ attributeId ][ 'value' ] = JSON.stringify( attributeValueObject );
}

function setAttributesCombinedOutputValue(
	attributeId, sourceLevel, newCombinedValue ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];
	console.log( currentValue );

	this.attributes[ attributeId ][ 'value' ] = wppfm_storeCombinedValue(
		sourceLevel,
		currentValue,
		newCombinedValue
	);
}

function getAttributesCombinedOutputValue( attributeId, sourceLevel ) {

	var attributeObject = this.attributes[ attributeId ][ 'value' ] ? JSON.parse( this.attributes[ attributeId ][ 'value' ] ) : {};

	if ( attributeObject && attributeObject.m && attributeObject.m[ sourceLevel ] && attributeObject.m[ sourceLevel ].s && attributeObject.m[ sourceLevel ].s.f ) {

		return attributeObject.m[ sourceLevel ].s.f;
	} else {

		return '';
	}
}

function removeAttributesCombinedOutputValue(
	attributeId, sourceLevel, combinedLevel ) {

	var currentValue                          = this.attributes[ attributeId ][ 'value' ];
	this.attributes[ attributeId ][ 'value' ] = wppfm_removeCombinedValue(
		sourceLevel,
		combinedLevel,
		currentValue
	);
}

function countAttributesCombinedFields( attributeId ) {

	if ( this.attributes[ attributeId ][ 'value' ] ) {

		var attributeValueObject = JSON.parse(
			this.attributes[ attributeId ][ 'value' ]
		);

		if ( attributeValueObject.hasOwnProperty( 'f' ) ) {

			return wppfm_countObjectItems( attributeValueObject.f );
		} else {

			return 0;
		}
	} else {

		return 0;
	}
}

function setAttributesCategoryValue( attributeId, newCategory ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_storeSourceValue(
		0,
		currentValue,
		'',
		newCategory
	);
}

function rstNrQueries( attributeId ) {

	this.attributes[ attributeId ][ 'nrQueries' ] = 0;
}

function addNrQueries( attributeId ) {

	var currentNr = this.attributes[ attributeId ][ 'nrQueries' ];

	this.attributes[ attributeId ][ 'nrQueries' ] = currentNr + 1;
}

function substrNrQueries( attributeId ) {

	var currentNr = this.attributes[ attributeId ][ 'nrQueries' ];

	if ( currentNr > 0 ) {
		this.attributes[ attributeId ][ 'nrQueries' ] = currentNr - 1;
	}
}

function addAttributesChangeValue(
	attributeId, sourceLevel, valueEditorLevel, value ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_storeValueChange(
		sourceLevel,
		valueEditorLevel,
		value,
		'add',
		currentValue
	);
}

function addAttributesCondition(
	attributeId, newCondition, sourceLevel, conditionLevel ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_storeConditionValue(
		sourceLevel,
		conditionLevel,
		currentValue,
		newCondition
	);
}

function addAttributesValueQuery(
	attributeId, sourceLevel, queryLevel, queryToAdd ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_storeQueryValue(
		sourceLevel,
		queryLevel,
		currentValue,
		queryToAdd
	);
}

function setFeedFilterValue( value ) {

	this.feedFilter = value;
}

function setPromotionInputValue( inputId, value ) {
	console.log(value);
}

function setPromotionSelectValue( selectorId, value ) {
	console.log(value)
}

function removeFeedFilterValue( filterLevelToRemove ) {

	this.feedFilter = removeFeedFilterLevel(
		this.feedFilter,
		filterLevelToRemove
	);
}

function removeAttributesCondition( attributeId, sourceLevel, conditionLevel ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_removeConditionValue(
		sourceLevel,
		conditionLevel,
		currentValue
	);
}

function removeAttributesValueQuery( attributeId, sourceLevel, queryLevel ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_removeQueryValue(
		sourceLevel,
		queryLevel,
		currentValue
	);
}

function removeAttributesEditValue(
	attributeId, sourceLevel, valueEditorLevel ) {

	var currentValue = this.attributes[ attributeId ][ 'value' ];

	this.attributes[ attributeId ][ 'value' ] = wppfm_removeEditValuesValue(
		sourceLevel,
		valueEditorLevel,
		currentValue
	);
}

function removeAlternativeSource( attributeId, level ) {

	var attributeValueObject = {};

	if ( this.attributes[ attributeId ][ 'value' ] ) {

		attributeValueObject = JSON.parse(
			this.attributes[ attributeId ][ 'value' ]
		);

		attributeValueObject.a = addOrRemoveValueInValueString(
			attributeValueObject.a,
			'',
			level,
			'remove'
		);

		if ( ! attributeValueObject.a ) {
			delete attributeValueObject.a;
		}

		this.attributes[ attributeId ][ 'value' ] = JSON.stringify(
			attributeValueObject
		);
	}
}

/**
 * Gets the queries from a specific attribute
 *
 * @param {int} attributeId
 * @param {int} sourceLevel
 * @returns {array} containing the queries
 */
function getAttributesQueries( attributeId, sourceLevel ) {

	var attributeString = this.attributes[ attributeId ][ 'value' ];
	var queries         = {};

	if ( attributeString ) {

		var o = JSON.parse( attributeString );

		if ( o && o.hasOwnProperty( 'm' ) && o.m[ sourceLevel ] && o.m[ sourceLevel ].hasOwnProperty( 'c' ) && o.m[ sourceLevel ].c.length > 0 ) {

			queries = o.m[ sourceLevel ].c;
		}
	}

	return queries;
}

function getAttributesValueQueries( attributeId, sourceLevel ) {

	var attributeString = this.attributes[ attributeId ][ 'value' ];
	var queries         = {};

	if ( attributeString ) {

		var attributeObject = JSON.parse( attributeString );

		if ( attributeObject && attributeObject.v && attributeObject.v[ sourceLevel ] && attributeObject.v[ sourceLevel ].hasOwnProperty( 'q' ) && attributeObject.v[ sourceLevel ].q.length > 0 ) {

			queries = attributeObject.v[ sourceLevel ].q;
		}
	}

	return queries;
}

function getAttributesValues( attributeId ) {

	var attributeString = this.attributes[ attributeId ][ 'value' ];
	var values          = {};

	if ( attributeString ) {

		var attributeObject = JSON.parse( attributeString );

		if ( attributeObject && 'v' in attributeObject && attributeObject.v.length > 0 ) {

			values = attributeObject.v;
		}
	}

	return values;
}

function getFeedFilterValue( feedId ) {

}

function getAlternativeSources( attributeId ) {

	var attributeString = this.attributes[ attributeId ][ 'value' ];
	var values          = {};

	if ( attributeString ) {

		var attributeObject = JSON.parse( attributeString );

		if ( attributeObject && 'a' in attributeObject && attributeObject.a.length > 0 ) {

			values = attributeObject.a;
		}
	}

	return values;
}

/**
 * Returns an object filled with all the required data for a specific feed row
 *
 * @param {string} rowId
 * @returns {object} containing the required data
 */
function getSourceData( rowId ) {

	var data = {};

	// add the basic data to the object
	data.rowId         = rowId;
	data.fieldName     = this.attributes[ rowId ][ 'fieldName' ];
	data.advisedSource = this.attributes[ rowId ][ 'advisedSource' ];

	// get the attribute data
	var attributeString = this.attributes[ rowId ][ 'value' ];

	// and put the attribute data in the object
	if ( attributeString ) {

		var attributeDataObject = JSON.parse( attributeString );

		data.mapping         = attributeDataObject && 'm' in attributeDataObject ? attributeDataObject.m : [];
		data.changeValues    = attributeDataObject && 'v' in attributeDataObject ? attributeDataObject.v : [];
		data.customCondition = ! ! (
			attributeDataObject && 't' in attributeDataObject
		);
	} else {

		data.mapping         = [];
		data.changeValues    = [];
		data.customCondition = false;
	}

	return data;
}

function getAttributesSources( attributeId ) {

	var attributeString = this.attributes[ attributeId ][ 'value' ];
	var source          = {};

	if ( attributeString ) {

		var attributeObject = JSON.parse( attributeString );

		if ( attributeObject && 's' in attributeObject ) {

			source = attributeObject.s;
		}
	}

	return source;
}

function addOrRemoveValueInValueString( values, value, level, add ) {

	var valueArray = values ? values : [];

	if ( add === 'add' ) {

		var o             = {};
		var queriesMemory = '';
		o[ level ]        = value;

		// if the value also has a query than store it first
		if ( valueArray[ level - 1 ] && 'q' in valueArray[ level - 1 ] ) {

			queriesMemory = valueArray[ level - 1 ][ 'q' ];
		}

		// remove values with the same key
		if ( level in o ) {

			valueArray.splice( level - 1, 1 );
		}

		// if there were queries stored, then put them back in to the new value
		if ( queriesMemory ) {
			o[ 'q' ] = queriesMemory;
		}

		valueArray.push( o );
	} else { // remove

		valueArray.splice( level - 1, 1 );

		// re-sort the queries
		valueArray = wppfm_resortObject( valueArray );
	}

	if ( wppfm_countObjectItems( valueArray ) > 0 ) {

		return valueArray;
	} else {

		return '';
	}
}

function clearAttributesSourceValue( attributeId, sourceLevel ) {

	if ( this.attributes[ attributeId ][ 'value' ] ) {

		this.attributes[ attributeId ][ 'value' ] = wppfm_storeSourceValue(
			sourceLevel,
			this.attributes[ attributeId ][ 'value' ],
			'clear',
			''
		);
	} else {

		this.attributes[ attributeId ][ 'value' ] = '';
	}
}

function setFeedCustomCategory( rowId, category ) {

	console.log( this.attributes );
	console.log( category );

	// set the main category
	this.mainCategory = category;

	var id = rowId ? rowId : getCustomCategoryAttributeId( this.attributes, category );

	// when an output is initialised as category, then set the category in the correct row
	if ( id ) {

		console.log( id );

		var o = this.attributes[ id ][ 'value' ] ? JSON.parse( this.attributes[ id ][ 'value' ] ) : {};

		o.t = category;

		this.attributes[ id ][ 'value' ] = JSON.stringify( o );
	}
}

function changeCountry( selectedCountry ) {

	this.country = selectedCountry;
}

function getCustomCategoryAttributeId( attr ) {

	for ( var i = 0; i < attr.length; i ++ ) {

		console.log( attr[ i ] );

		var catObject = attr[ i ][ 'value' ] ? JSON.parse( attr[ i ][ 'value' ] ) : {};

		console.log( catObject );

		if ( catObject.hasOwnProperty( 't' ) ) {

			return i;
		}
	}
}

function setCategories( level, category, channel ) {
	if ( this.attributes[ 3 ] !== undefined ) {
		var categoryAttributeObject = this.attributes[ 3 ][ 'value' ] ? JSON.parse( this.attributes[ 3 ][ 'value' ] ) : {};
		var categoryDelimiter       = wppfm_category_separator( channel );

		var categoryString = this.mainCategory;
		var categoryLevel  = this.attributes[ 3 ][ 'value' ] !== undefined ? categoryString.split( categoryDelimiter ).length : 0;

		var selectedLevel = level.match( /(\d+)$/ )[ 0 ];

		if ( selectedLevel === '0' ) {
			this.mainCategory               = category;
			this.attributes[ 3 ][ 'value' ] = '{"t":"' + category + '"}';
		} else {
			if ( categoryLevel <= selectedLevel ) {
				this.mainCategory              += categoryDelimiter + category;
				this.attributes[ 3 ][ 'value' ] = '{"t":"' + categoryAttributeObject.t + categoryDelimiter + category + '"}';
			} else {
				var pos = 0;

				for ( var i = 0; i < selectedLevel; i ++ ) {
					pos = categoryString.indexOf( categoryDelimiter, pos + 1 );
				}

				categoryString = categoryString.substring( 0, pos );

				if ( category !== '0' ) {
					this.mainCategory               = categoryString + categoryDelimiter + category;
					this.attributes[ 3 ][ 'value' ] = '{"t":"' + categoryString + categoryDelimiter + category + '"}';
				} else {
					this.mainCategory               = categoryString;
					this.attributes[ 3 ][ 'value' ] = '{"t":"' + categoryString + '"}';
				}
			}
		}
	}
}

function setSchedule( days, hours, minutes, frequency ) {

	this.updateSchedule = days + ':' + hours + ':' + minutes + ':' + frequency;
}

function activateFeedsAttribute( attributeId ) {

	this.attributes[ attributeId ][ 'isActive' ] = true;
}

function deactivateFeedsAttribute( attributeId ) {

	if ( this.attributes[ attributeId ][ 'fieldLevel' ] !== '1' ) {

		var attributeDataObject                      = this.attributes[ attributeId ][ 'value' ] ? JSON.parse( this.attributes[ attributeId ][ 'value' ] ) : {};
		this.attributes[ attributeId ][ 'isActive' ] = false;

		// clear the meta value but only  if it not contains the category string
		if ( attributeDataObject && ! 't' in attributeDataObject ) {
			this.attributes[ attributeId ][ 'value' ] = '';
		}
	} else {

		this.attributes[ attributeId ][ 'isActive' ] = true; // any required attribute should always stay active
	}
}

function checkCustomName( name ) {

	var result = false;

	for ( var i = 0; i < this.attributes.length; i ++ ) {

		if ( this.attributes[ i ][ 'fieldName' ] === name ) {
			result = true;
		}
	}

	return result;
}

function getFeedType() {
	return this.feedType;
}

function removeAttribute( attributeId ) {

	this.attributes.splice( attributeId, 1 );
}

function cleanUnusedAttributes() {

	if ( this.attributes.length > 0 ) {

		for ( var i = 0; i < this.attributes.length; i ++ ) {

			if ( true !== this.attributes[ i ][ 'isActive' ] ) {

				this.attributes.splice( i, 1 );
				i --; // reset i for the removed attribute
			} else if ( undefined === this.attributes[ i ][ 'advisedSource' ] && '' === this.attributes[ i ][ 'value' ] ) {

				this.attributes.splice( i, 1 );
				i --; // reset i for the removed attribute
			}
		}
	}
}

function clearAttributes() {
	this.attributes = [];
}

function addPromotion( promotion = [] ) {
	return this.promotions.push( promotion );
}

function addPromotionElement( promotionId, key, value ) {
	if ( this.promotions.length <= promotionId ) {
		this.promotions.push( [] );
	}

	var index = this.promotions[promotionId].findIndex( element => element.meta_key === key );

	if ( index !== - 1 ) {
		this.promotions[promotionId].splice( index, 1 );
	}

	if ( value ) {
		var o = {};
		o.meta_key = key;
		o.meta_value = value;
		this.promotions[promotionId].push(o);
	}
}

function deletePromotion( promotionId ) {
	this.promotions.splice( promotionId, 1 );

	for( var i = 0; i < this.promotions.length; i++ ) {
		this.promotions[i][0].meta_value = i;
	}
}

function duplicatePromotion( promotionId ) {
	var newPromotion = jQuery.extend(true, [], this.promotions[promotionId]);
	newPromotion[0].meta_value = this.promotions.length;

	this.promotions.push(newPromotion);
}

function clearPromotions() {
	this.promotions = [];
}

function promotionProductsEligibleForPromotionValue( promotionId ) {
	for ( var i = 0; i < this.promotions[promotionId].length; i ++ ) {
		if ( 'product_applicability' === this.promotions[promotionId][i].meta_key ) {
			return this.promotions[promotionId][i].meta_value;
		}
	}

	return '';
}

function promotionCouponCodeRequiredValue( promotionId ) {
	for ( var i = 0; i < this.promotions[promotionId].length; i ++ ) {
		if ( 'offer_type' === this.promotions[promotionId][i].meta_key ) {
			return this.promotions[promotionId][i].meta_value;
		}
	}

	return '';
}
