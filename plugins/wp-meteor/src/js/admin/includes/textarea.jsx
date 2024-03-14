import React from 'react';
import Tooltip from 'react-tooltip';

export default class Textarea extends React.Component {
    constructor(props) {
        super(props)
        this.state = { ...props.settings };
        this.state.value = Object.values(this.state.value || []).join("\n");
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
                                <div className="description">{this.props.settings.description}</div>
                                <Tooltip effect="solid" html={true} border={true} className="tooltip"/>
                            </>
                            : null}
                        
                    </span>
                    <textarea 
                        name={this.props.prefix + '[value]'}
                        defaultValue={this.state.value}
                    ></textarea>
                </li>
            </ul>
        );
    }
}
