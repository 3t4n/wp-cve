import React, { Component } from 'react';

class MultipleCheckboxes extends Component {

    static slug = 'mphb_multiple_checkboxes';

    _onCheckboxChange = ( event ) => {

        const { value, checked } = event.target;
        const oldValue = this.props.value.split(',');
        let newValue;

        if ( checked ) {
            newValue = [ ...oldValue, value ];
        } else {
            newValue = oldValue.filter( box => box !== value ) ;
        }

        this.props._onChange( this.props.name, newValue.join(',') )
    }

    render() {

        let checkboxes_data = this.props.fieldDefinition.options;

        const checkboxes = Object.keys( checkboxes_data ).map( ( id, index ) => {
            return (
                <p className="et-fb-multiple-checkbox" key={ index }>
                    <label htmlFor={ `${this.constructor.slug}-${this.props.name}-checkbox-${id}` }>
                        <input
                            type="checkbox"
                            id={ `${this.constructor.slug}-${this.props.name}-checkbox-${id}` }
                            name={ `${this.constructor.slug}-${this.props.name}-checkbox` }
                            value={ id }
                            data-text={ checkboxes_data[ id ] }
                            onChange={ this._onCheckboxChange }
                            checked={ this.props.value.includes( id.toString() ) }
                        />{ checkboxes_data[ id ] }
                    </label>
                </p>
            );
        });

        return (
            <div className={ `${this.constructor.slug}-wrap et-fb-multiple-checkboxes-wrap` }>
                { checkboxes }
                <input
                    id={ `${this.constructor.slug}-${this.props.name}` }
                    name={ this.props.name }
                    value={ this.props.value }
                    type='hidden'
                />
            </div>
        );
    }
}

export default MultipleCheckboxes;
