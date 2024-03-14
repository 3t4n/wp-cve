import React from 'react';
import PropTypes from 'prop-types'
import { Link } from 'react-scroll'

import Helpers from '../general/Helpers';
import Icon from '../general/Icon';

const MenuContainer = (props) => {
    let menuStructure = [];

    for ( let group of props.structure ) {
        if ( ! Helpers.dependencyMet(group, props.settings ) ) {
            continue;
        }

        if (group.hasOwnProperty('header')) {
            menuStructure.push({
                header: group.header,
            });
        } else {

            menuStructure.push({
                id: group.id,
                name: group.name,
                icon: group.hasOwnProperty( 'icon' ) ? group.icon : false,
            });
        }
    }

    return (
        <div id="bvs-settings-sidebar">
            <div id="bvs-settings-buttons">
                <button
                    className="button button-primary"
                    disabled={props.savingChanges || !props.settingsChanged}
                    onClick={props.onSaveChanges}
                >{ props.savingChanges ? '...' : 'Save Changes' }</button>
                <button
                    className="button"
                    disabled={props.savingChanges || !props.settingsChanged}
                    onClick={props.onCancelChanges}
                >Cancel Changes</button>
            </div>
            <div id="bvs-settings-menu">
                {
                    menuStructure.map((group, i) => {
                        if (group.hasOwnProperty('header')) {
                            return <div className="bvs-settings-menu-header" key={i}>{group.header}</div>
                        } else {
                            return <Link
                                    to={`bvs-settings-group-${group.id}`}
                                    className="bvs-settings-menu-group"
                                    activeClass="active"
                                    spy={true}
                                    offset={-42}
                                    smooth={true}
                                    duration={400}
                                    key={i}
                                >
                                { group.icon && <Icon type={group.icon} /> } {group.name}
                            </Link>
                        }
                    })
                }
            </div>
        </div>
    );
}

MenuContainer.propTypes = {
    structure: PropTypes.array.isRequired,
    settingsChanged: PropTypes.bool.isRequired,
    savingChanges: PropTypes.bool.isRequired,
    onSaveChanges: PropTypes.func.isRequired,
    onCancelChanges: PropTypes.func.isRequired,
}

export default MenuContainer;