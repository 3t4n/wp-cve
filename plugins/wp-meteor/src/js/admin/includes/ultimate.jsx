/**
 *   WP Meteor Wordpress Plugin
 *   Copyright (C) 2020  Aleksandr Guidrevitch
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

import React from 'react';
import Tooltip from 'react-tooltip';
import Slider from 'react-slider';
import dispatcher from './dispatcher';
import styled from 'styled-components';

const StyledTrack = styled.div`
    background: ${props => props.value > 1 ? '#08CE69' : '#FEA502'};
`;

const Track = (props, state) => <StyledTrack {...props} value={state.value} />;
const Thumb = (props, state) => <div {...props}>{state.valueNow === labels.length - 1 ? '∞' : state.valueNow}</div>;

const labels = [
    'LCP optimization only',
    '1 second delay',
    '2 seconds delay',
    // '3 seconds delay',
    // '4 seconds delay',
    'Delay until first interaction'
];

export default class Simple extends React.Component {
    constructor(props) {
        super(props)
        this.state = { ...props.settings };
        if (!this.state.enabled) {
            this.state.delay = 0;
        }
        this.state.delay = parseInt(this.state.delay);
        if (this.state.delay < 0) {
            this.state.delay = labels.length - 1;
        }
        dispatcher.on('rerender', this.forceUpdate.bind(this));
    }
    onChange = (delay) => {
        this.setState({ delay: delay });
    }
    render() {
        return (
            <>
                <ul>
                    <li>
                        <span className="enabled">

                            <Slider
                                id={this.props.prefix + "-id"}
                                className="slider"
                                defaultValue={this.state.delay}
                                onChange={this.onChange}
                                min={0}
                                max={labels.length - 1}
                                renderTrack={Track}
                                renderThumb={Thumb}
                            />
                            <label htmlFor={this.props.prefix + "-id"}>
                                {labels[this.state.delay]}
                            </label>
                            <input type="hidden" name={this.props.prefix + '[delay]'} value={this.state.delay === labels.length - 1 ? -1 : this.state.delay}></input>
                            <input type="hidden" name={this.props.prefix + '[enabled]'} value={true}></input>
                        </span>
                    </li>
                </ul>
                <Tooltip effect="solid" html={true} border={true} className="tooltip" />
            </>

        );
    }
}
