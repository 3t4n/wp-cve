import { useEffect, useState, useCallback } from '@wordpress/element';
import { ComboboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch, select } from '@wordpress/data';
import { useRef } from '@wordpress/element';
import { isObject } from 'lodash';
import { debounce } from 'lodash';

import { defaultOptions } from "./default-options";
import { loadOptions } from './load-options.js';
import { prepareFormData } from "./prepare-form-data";

export const PickupPointBlock = ( { checkoutExtensionData, cart, metadata, showFieldCallback, requiredFieldCallback, formDataCallback } ) => {

	const controlRef = useRef();

	const { extensionCartUpdate } = wc.blocksCheckout;

	const settings = wcSettings[ metadata.settingsKey ];

	const autoloadOptions = metadata.autoloadOptions ?? false;

	const { extensionData, setExtensionData } = checkoutExtensionData;

	const validationErrorId = settings.integrationName + '-' + settings.fieldName;

	const { setValidationErrors, clearValidationError } = useDispatch(
		'wc/store/validation'
	);

	const { __internalIncrementCalculating, __internalDecrementCalculating } = useDispatch( 'wc/store/checkout' );

	const [ showField, setShowField ] = useState( false );

	const getSelectedFlexibleShippingMethods = ( selectedRates ) => {
		let selectedFlexibleShippingMethods = [];
		selectedRates.map( ( rate ) => {
			rate.meta_data.some( ( meta ) => {
				if ( meta.key === '_fs_method' && meta.value.method_integration === settings.flexibleShippingIntegration ) {
					selectedFlexibleShippingMethods.push( meta.value );
				}
				return false;
			} );
		} )

		return selectedFlexibleShippingMethods;
	}

	const getSelectedRates = () => {
		let selectedRates = [];
		const shippingRates = cart.shippingRates;

		shippingRates.map( ( shipping_package ) => {
			shipping_package.shipping_rates.filter( ( rate ) => {
				if ( rate.selected ) {
					selectedRates.push( rate );
				}
			} );
		} );

		return selectedRates;
	}

	const [ selectedMethods, setSelectedMethods ] = useState( [] );

	const [ requiredField, setRequiredField ] = useState( false );

	const [
		pointId,
		setPointId,
	] = useState( wcSettings.checkoutData.extensions[ settings.integrationName ][ settings.fieldName ] ?? '' );

	useEffect( () => {
		let selectedRates = getSelectedRates();
		let selectedFlexibleShippingMethods = getSelectedFlexibleShippingMethods(selectedRates);

		setSelectedMethods( selectedFlexibleShippingMethods );

		setShowField( showFieldCallback( selectedFlexibleShippingMethods, selectedRates ) );
		setRequiredField( requiredFieldCallback( selectedFlexibleShippingMethods, selectedRates ) );

	}, [ ...cart.shippingRates ] );

	const validationError = useSelect( ( select ) => {
		const store = select( 'wc/store/validation' );

		return store.getValidationError( validationErrorId );
	} );

	const fieldLabel = __( 'Select pickup point', 'octolize-pickup-point-checkout-blocks' );

	useEffect( () => {
		setExtensionData( settings.integrationName, settings.fieldName, pointId );

		if ( showField && requiredField && pointId.length === 0 ) {
			setValidationErrors( {
				[ validationErrorId ]: {
					message: fieldLabel,
					hidden: false,
				},
			} );
		} else {
			clearValidationError( validationErrorId );
		}

	}, [ pointId ] );

	useEffect( () => {
		if ( ! showField || ! requiredField ) {
			clearValidationError( validationErrorId );
		}

	}, [ ...cart.shippingRates, requiredField  ] );

	const [ filteredValue, setFilteredValue ] = useState( '' );

	const loadOptionsSettings = ( searchString, setPointId, pointId ) => {
		return {
			searchString: searchString,
			ajaxUrl: settings.ajaxUrl,
			formData: formDataCallback(
				prepareFormData( {
					searchString: searchString,
					ajaxAction: settings.ajaxAction,
					nonce: settings.nonce
				} ),
				selectedMethods,
				cart.shippingAddress
			),
			setFilteredOptions: setFilteredOptions,
			setFilteredValue: setFilteredValue,
			setPointId: setPointId,
			pointId: pointId,
		}
	};

	const [ filteredOptions, setFilteredOptions ] = useState( autoloadOptions ? [] : ( wcSettings.checkoutData.extensions[ settings.integrationName ]['default_options'] ?? defaultOptions ) );

	const pointIdChanged = ( pointId ) => {
		setPointId( pointId );
		setExtensionData( settings.integrationName, settings.fieldName, pointId );
		__internalIncrementCalculating();
		extensionCartUpdate( {
			namespace: settings.integrationName,
			data: {
				point_id: pointId,
			},
		} ).finally( () => { __internalDecrementCalculating() } );
	}

	const debouncedLoadOptions = useCallback(
		debounce( ( loadOptionsSettings ) => {
			loadOptions( loadOptionsSettings );
		}, 500 ),
		[ loadOptions ]
	);

	useEffect( () => {
		if ( showField && autoloadOptions ) {
			debouncedLoadOptions( loadOptionsSettings( '' ) );
		}
	}, [ ...cart.shippingRates ] );

	if ( showField && autoloadOptions && filteredOptions.length === 0 ) {
		debouncedLoadOptions( loadOptionsSettings( '', setPointId, pointId ) );
	}

	return (
		<>
			{ showField && <div className="wc-block-components-combobox is-active">
				<ComboboxControl
					className={ 'wc-block-components-combobox-control ' +
						( validationError?.hidden === false
							? ' has-error'
							: '' ) }
					label={ fieldLabel }
					value={ pointId }
					required={ requiredField }
					options={ filteredOptions }
					filteredValue={ filteredValue }
					onFilterValueChange={ ( filterValue ) => {
						if ( filterValue.length ) {
							const activeElement = isObject( controlRef.current )
								? controlRef.current.ownerDocument.activeElement
								: undefined;

							if (
								activeElement &&
								isObject( controlRef.current ) &&
								controlRef.current.contains( activeElement )
							) {
								return;
							}

							if ( ! autoloadOptions ) {
								if ( filterValue.length < 3 ) {
									setFilteredOptions( defaultOptions );
									return;
								}
								debouncedLoadOptions( loadOptionsSettings( filterValue ) );
							}
						}
					} }
					onChange={ pointIdChanged }
					allowReset={ false }
					aria-invalid={ validationError?.message && ! validationError?.hidden }
				/>
				{ validationError?.hidden === false &&
					<div className="wc-block-components-validation-error" role="alert"
						 style={ { 'margin-top': '20px' } }>
						<p id={ validationErrorId }>{ validationError?.message }</p>
					</div>
				}
			</div> }
		</>
	);
};
