'use strict';

export class ACADPDropdownTermsElement extends HTMLElement {

    /**
     * Element created.
     */
    constructor() {
        super();

        // Set references to the DOM elements used by the component
        this._dropdownInputEl  = null;
        this._dropdownResetBtn = null; 
        this._dropdownListEl   = null;
        this._searchInputEl    = null;        
        this._searchResetBtn   = null; 
        this._searchStatusEl   = null;        

        // Set references to the private properties used by the component
        this._isRendered = false;
    }

	/**
     * Browser calls this method when the element is added to the document.
     * (can be called many times if an element is repeatedly added/removed)
     */
	connectedCallback() { 
        if ( this._isRendered ) true;   
        this._isRendered = true; 

        this._dropdownInputEl  = this.querySelector( '.acadp-dropdown-input input[type=text]' );  
        this._dropdownResetBtn = this.querySelector( '.acadp-dropdown-input button' ); 
        this._dropdownListEl   = this.querySelector( '.acadp-dropdown-list' ); 
        this._searchInputEl    = this.querySelector( '.acadp-dropdown-search input[type=text]' );       
        this._searchResetBtn   = this.querySelector( '.acadp-dropdown-search button' ); 
        this._searchStatusEl   = this.querySelector( '.acadp-dropdown-search-status' );     

        if ( this.type === 'checkbox' && this.required ) {
            this.closest( '.acadp-form-group' ).classList.add( 'acadp-form-validate-checkboxes' );
        }  
        
        this._toggleSelectedTermNames();

        jQuery( this ).on( 'change', '.acadp-form-control', ( event ) => this._loadTermsList( event ) ); 
       
        this._dropdownInputEl.addEventListener( 'click', ( event ) => this._toggleDropdown( event ) ); 
        this._dropdownResetBtn.addEventListener( 'click', ( event ) => this._resetDropdown( event ) );
                  
        this._searchInputEl.addEventListener( 'input', ( event ) => this._searchTerms( event.target.value ) ); 
        this._searchResetBtn.addEventListener( 'click', ( event ) => this._resetSearch( event ) );
	}

     /**
     * Browser calls this method when the element is removed from the document.
     * (can be called many times if an element is repeatedly added/removed)
     */
    disconnectedCallback() {
        jQuery( this ).off( 'change', '.acadp-form-control', ( event ) => this._loadTermsList( event ) );
        
        this._dropdownInputEl.removeEventListener( 'click', ( event ) => this._toggleDropdown( event ) );
        this._dropdownResetBtn.removeEventListener( 'click', ( event ) => this._resetDropdown( event ) );

        this._searchInputEl.removeEventListener( 'input', ( event ) => this._searchTerms( event.target.value ) );
        this._searchResetBtn.removeEventListener( 'click', ( event ) => this._resetSearch( event ) );
    }

    /**
     * Define getters and setters for attributes.
     */   

    get type() {
        return this.getAttribute( 'data-type' ) || 'radio';
    }

    get name() {
        return this.getAttribute( 'data-name' ) || 'acadp_category';
    }

    get taxonomy() {
        return this.getAttribute( 'data-taxonomy' ) || 'acadp_categories';
    }

    get required() {
        return this.getAttribute( 'data-required' ) || false;
    }

    get value() {
        if ( this.type === 'radio' ) {
            let checkedEl = this.querySelector( 'input[type=radio]:checked' );
            return ( checkedEl !== null ) ? checkedEl.value : 0;
        }

        if ( this.type === 'checkbox' ) {
            let values = [];
            this.querySelectorAll( 'input[type=checkbox]:checked' ).forEach(( el ) => {
                values.push( el.value );
            });

            return values;
        }

        return null;
    }

    /**
     * Define private methods.
     */  

    _toggleDropdown( event ) {
        this._dropdownListEl.hidden = ! this._dropdownListEl.hidden;
    }

    _resetDropdown( event ) {
        this.querySelectorAll( '.acadp-form-control:checked' ).forEach(( el ) => {
            el.checked = false;
        });

        this._dropdownInputEl.value = '';
        
        this.removeAttribute( 'has-value' );       

        this._toggleDropdown( event );

        this.dispatchEvent( new CustomEvent( 'acadp.terms.change' ) );
        jQuery( this ).trigger( 'change' ); // An ugly hack for jQuery based event listeners
    }

    _searchTerms( value ) {  
        let matchesFound = false;
        this._searchStatusEl.hidden = true;  

        if ( value ) {
            value = value.trim().toLowerCase();

            this.setAttribute( 'is-searching', true );
            this._searchResetBtn.hidden = false;

            this.querySelectorAll( '.acadp-term-label' ).forEach(( el ) => {
                const termName = el.querySelector( '.acadp-term-name' ).innerHTML;
    
                if ( el.hasAttribute( 'disabled' ) || termName.toLowerCase().indexOf( value.toString() ) === -1 ) {
                    el.hidden = true;
                } else {
                    el.hidden = false;
                    matchesFound = true;
                }            
            });
        } else {
            this.removeAttribute( 'is-searching' );
            this._searchResetBtn.hidden = true;

            this.querySelectorAll( '.acadp-term-label' ).forEach(( el ) => {
                el.hidden = false;
                matchesFound = true;
            });
        }

        if ( ! matchesFound ) {
            this._searchStatusEl.hidden = false;
        }
    }

    _resetSearch( event ) { 
        this._searchInputEl.value = '';
        this._searchTerms( null );
    }

    _toggleSelectedTermNames() {
		let names = [];
		this.querySelectorAll( '.acadp-form-control:checked' ).forEach(( el ) => {
            let termName = el.closest( 'label' ).querySelector( '.acadp-term-name' ).innerHTML;
			names.push( termName );
		});		

        if ( names.length > 0 ) {
            this._dropdownInputEl.value = names.join( ', ' );
            this.setAttribute( 'has-value', true );
        } else {
            this._dropdownInputEl.value = '';
            this.removeAttribute( 'has-value' );
        }        
	}

    _buildList( json, level ) {        
        let html = '<ul class="acadp-terms-group-children" data-level="' + level + '">';

        let attributes = {
            type: this.type,
            name: this.name,
            class: [ 'acadp-form-control', 'acadp-form-' + this.type ]
        }

        if ( this.type === 'radio' && this.required ) {
            attributes.required = true;
            attributes.class.push( 'acadp-form-validate' );
        }

        attributes.class = attributes.class.join( ' ' );

        json.forEach(( item ) => {
            attributes['value'] = item.id;

            html += '<li class="acadp-term">';
            html += '<label class="acadp-term-label" style="padding-inline-start: ' + ( level * 12 ) + 'px;">';
            html += '<input ' +  this._merge( attributes ) + ' />';
            html += '<span class="acadp-term-name">' + item.name + '</span>';
            html += '</label>';
            html += '</li>';
        });

        html += '</ul>';

        return html;
    }

    _merge( obj ) {
        let attributes = '';
        for ( let key in obj ) {
            attributes += ( key + '="' + obj[ key ] + '" ' );
        }

        return attributes;
    }	

    _getApiUrl( parent = 0 ) {
        const siteURL = ( typeof acadp_admin !== 'undefined' ) ? acadp_admin.site_url : acadp.site_url;
        return siteURL + '/wp-json/wp/v2/' +  this.taxonomy + '?parent=' + parent + '&per_page=100';
    }

    /**
     * Define private async methods.
     */    

    async _loadTermsList( event ) { 
        this.dispatchEvent( new CustomEvent( 'acadp.terms.change' ) );

        this._toggleSelectedTermNames();        

        const containerEl = event.target.closest( 'li' );
        const id = parseInt( event.target.value );

        if ( containerEl.classList.contains( 'acadp-terms-children-populated' ) ) return false;
        containerEl.classList.add( 'acadp-terms-children-populated' );

        const spinnerEl = document.createElement( 'div' );
        spinnerEl.className = 'acadp-spinner';

        containerEl.querySelector( 'label' ).appendChild( spinnerEl );

        fetch( this._getApiUrl( id ) )
            .then( response => response.json() )
            .then( json => {
                containerEl.querySelector( '.acadp-spinner' ).remove();

                if ( json.length > 0 ) {
                    const level = parseInt( containerEl.closest( 'ul' ).dataset.level );
                    const list = this._buildList( json, level + 1 );

                    containerEl.insertAdjacentHTML( 'beforeend', list );
                }          
            }); 
    }

}

/**
 * Hide the dropdown menu when clicked outside of the element.
 */
(function( $ ) {	
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {

        document.addEventListener( 'click', ( event ) => {
            const self = event.target.closest( 'acadp-dropdown-terms' );
            document.querySelectorAll( 'acadp-dropdown-terms' ).forEach(( el ) => {
                if ( el !== self ) { 
                    el.querySelector( '.acadp-dropdown-list' ).hidden = true;
                }	
            });		
        });

	});

})( jQuery );

// Register custom element
customElements.define( 'acadp-dropdown-terms', ACADPDropdownTermsElement );