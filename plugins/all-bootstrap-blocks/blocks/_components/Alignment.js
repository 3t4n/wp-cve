import classNames from 'classnames';

import { __, _x } from '@wordpress/i18n';

import { useSelect } from '@wordpress/data';

import { store as blockEditorStore } from '@wordpress/block-editor';

import {
    alignNone,
    positionCenter,
    positionLeft,
    positionRight,
    stretchFullWidth,
    stretchWide,
} from '@wordpress/icons';

export const BLOCK_ALIGNMENTS_CONTROLS = {
    none: {
        icon: 'align-none',
        title: __( 'None', 'Alignment option' ),
    },
    wide: {
        icon: stretchWide,
        title: __( 'Wide width' ),
    },
    full: {
        icon: stretchFullWidth,
        title: __( 'Full width' ),
    },
    left: {
        icon: positionLeft,
        title: __( 'Align left' ),
    },
    center: {
        icon: positionCenter,
        title: __( 'Align center' ),
    },
    right: {
        icon: positionRight,
        title: __( 'Align right' ),
    },
};

export const DEFAULT_CONTROL = 'none';

export const POPOVER_PROPS = {
    isAlternate: true,
};

const Alignment = ( areoi, attributes, onChange ) => {

    const activeAlignmentControl = BLOCK_ALIGNMENTS_CONTROLS[ attributes.align ];
    const defaultAlignmentControl =
        BLOCK_ALIGNMENTS_CONTROLS[ DEFAULT_CONTROL ];

    const UIComponent = areoi.components.ToolbarDropdownMenu;
    const commonProps = {
        icon: activeAlignmentControl
            ? activeAlignmentControl.icon
            : defaultAlignmentControl.icon,
        label: __( 'Align' ),
    };

    function getItems( onClose )
    {
        var output = [];

        for ( const [key, value] of Object.entries( BLOCK_ALIGNMENTS_CONTROLS ) ) {
            
            var isSelected = key === attributes.align || ( ! attributes.align && key === 'none' );
            
            var new_output = (
                <areoi.components.MenuItem
                    key={ key }
                    icon={ value.icon }
                    iconPosition="left"
                    className={ classNames(
                        'components-dropdown-menu__menu-item',
                        {
                            'is-active': isSelected,
                        }
                    ) }
                    isSelected={ isSelected }
                    onClick={ () => {
                        onChange( 'align', key );
                        onClose();
                    } }
                    role="menuitemradio"
                >
                    { value.title }
                </areoi.components.MenuItem>
            );
            output.push( new_output );
        }

        return output;
    }

    const extraProps = {
        toggleProps: { describedBy: __( 'Change alignment' ) },
        popoverProps: POPOVER_PROPS,
        children: ( { onClose } ) => {
            return (
                <>
                    <areoi.components.MenuGroup className="block-editor-block-alignment-control__menu-group">
                        { getItems( onClose ) }
                    </areoi.components.MenuGroup>
                </>
            );
        },
    };

    return <UIComponent { ...commonProps } { ...extraProps } />;
}

export default Alignment;