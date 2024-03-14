import React from 'react';
import Tooltip from 'react-tooltip';

export default class Simple extends React.Component {
    constructor(props) {
        super(props)
        this.state = { ...props.settings };
    }
    onEnabledChange = (e) => {
        this.setState({
            enabled: e.target.checked
        });
    }
    render() {
        return (
            <ul>
                <li>
                    <span className="enabled">
                        <input type="checkbox"
                            id={this.props.prefix + "-id"}
                            name={this.props.prefix + '[enabled]'}
                            checked={!!this.state.enabled}
                            onChange={this.onEnabledChange} />
                        <label htmlFor={this.props.prefix + "-id"} className={this.state.readonly ? 'readonly' : ''}>
                            {this.props.title}
                        </label>
                        {this.props.settings.description
                            ? 
                            <>
                                <span className="question-mark" data-tip={this.props.settings.description}>⍰</span>
                                <Tooltip effect="solid" html={true} border={true} className="tooltip"/>
                            </>
                            : null}
                        
                    </span>
                </li>
            </ul>
        );
    }
}
