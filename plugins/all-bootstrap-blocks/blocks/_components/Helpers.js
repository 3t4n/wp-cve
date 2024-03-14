export const GetClassName = ( classes ) => {
    let newClasses = [];
    classes.forEach(element => {
        if ( typeof element !== 'undefined' && element ) {
            newClasses.push( element );
        }
    });
    return newClasses;
}

export const GetClassNameCol = ( classes ) => {
    let newClasses = [];
    classes.forEach(element => {
        if ( typeof element !== 'undefined' && element ) {
            
            if ( areoi_vars.is_grid ) {
                element = element.replace("col-", 'g-col-');
                element = element.replace("offset-", 'g-start-');
            }

            newClasses.push( element );
        }
    });
    return newClasses;
}

export const GetClassNameStr = ( classes ) => {
    let newClasses = '';
    classes.forEach(element => {
        if ( typeof element !== 'undefined' && element ) {
            newClasses += element + ' ';
        }
    });
    return newClasses;
}

export const GetStyles = ( attributes ) => {

    var devices = [ 'xs', 'sm', 'md', 'lg', 'xl', 'xxl' ];

    let styles = '';

    devices.forEach( device => {
        styles += ( attributes['height_dimension_' + device] ? 'height: ' + attributes['height_dimension_' + device] + attributes['height_unit_' + device] + ';' : '' );
        styles += ( attributes['padding_top_' + device] ? 'padding-top: ' + attributes['padding_top_' + device] + areoi_vars.display_units + ';' : '' );
        styles += ( attributes['padding_right_' + device] ? 'padding-right: ' + attributes['padding_right_' + device] + areoi_vars.display_units + ';' : '' );
        styles += ( attributes['padding_bottom_' + device] ? 'padding-bottom: ' + attributes['padding_bottom_' + device] + areoi_vars.display_units + ';' : '' );
        styles += ( attributes['padding_left_' + device] ? 'padding-left: ' + attributes['padding_left_' + device] + areoi_vars.display_units + ';' : '' );
        styles += ( attributes['margin_top_' + device] ? 'margin-top: ' + attributes['margin_top_' + device] + areoi_vars.display_units + ';' : '' );
        styles += ( attributes['margin_right_' + device] ? 'margin-right: ' + attributes['margin_right_' + device] + areoi_vars.display_units + ';' : '' );
        styles += ( attributes['margin_bottom_' + device] ? 'margin-bottom: ' + attributes['margin_bottom_' + device] + areoi_vars.display_units + ';' : '' );
        styles += ( attributes['margin_left_' + device] ? 'margin-left: ' + attributes['margin_left_' + device] + areoi_vars.display_units + ';' : '' );

        if ( areoi_vars.is_grid ) {
            styles += ( attributes['grid_rows_' + device] ? '--bs-rows: ' + attributes['grid_rows_' + device] + ';' : '' );

            if ( attributes['row_cols_' + device] ) {
                var cols = attributes['row_cols_' + device].match(/\d+$/)[0];
                if ( cols ) {
                    styles += '--bs-columns: ' + cols + ';';
                }
            }

            styles += ( attributes['grid_gap_dimension_' + device] ? '--bs-gap: ' + attributes['grid_gap_dimension_' + device] + attributes['grid_gap_unit_' + device] + ';' : '' );
            styles += ( attributes['grid_row_gap_dimension_' + device] ? '--bs-row-gap: ' + attributes['grid_row_gap_dimension_' + device] + attributes['grid_row_gap_unit_' + device] + ';' : '' );
        
            styles += ( attributes['grid_row_' + device] ? '--bs-grid-row: ' + attributes['grid_row_' + device] + ';' : '' );
        }
    })

    return styles;
}

export const GetRGB = ( values ) => {
    
    let rgb = 'rgba( ' + values.r + ', ' + values.g + ', ' + values.b + ', ' + values.a + ' )';
    
    return rgb;
}

export const GetCols = ( field, key ) => {
    if ( field == 'col-xs' ) {
        field = 'col';
    }
    if ( key == 'xs' ) {
        key = null;
    }
    const device = field + ( key ? '-' + key : '' );

    var cols = [];

    if ( field == 'row-cols' ) {

        cols.push({ label: 'Default', value: null });

        for (var i = 0; i <= areoi_vars.grid_rows; i++ ) {
            if ( i > 0 ) {
                cols.push({ label: i, value: device + '-' + i });
            }
        }

        return cols;
    } else {

        cols.push({ label: 'Default', value: null });

        for (var i = 0; i <= areoi_vars.grid_columns; i++ ) {
            cols.push({ label: i, value: device + '-' + i });
        }

        cols.push({ label: 'Auto', value: device + '-auto' });

        return cols;
    }
}

export const GetGridCols = ( field, key ) => {
    if ( key == 'xs' ) {
        key = null;
    }
    const device = field + ( key ? '-' + key : '' );

    var cols = [];

    cols.push({ label: 'Default', value: null });

    for (var i = 1; i <= areoi_vars.grid_columns; i++ ) {
        cols.push({ label: i, value: device + '-' + i });
    }

    return cols;
}