import React from 'react';
import Tooltip from 'react-tooltip';
import ContentEditable from 'react-contenteditable';
import dispatcher from './dispatcher';

const raf = window.requestAnimationFrame;
export default class Textarea extends React.Component {
    constructor(props) {
        super(props)
        this.state = { ...props.settings };
        this.state.value = Object.values(this.state.value || []).map(v => `<div>${v}</div>`).join("");
        this.converter = document.createElement('div');
        this.ref = React.createRef();
        this.contentEditableRef = React.createRef();
        dispatcher.on('submit', e => {
            const invalid = this.getInvalidRegExps();
            if (invalid.length) {
                console.error('invalid regexps', invalid);
                e.preventDefault();
                dispatcher.emit('invalid', this.ref);
            }
            this.highlightInvalidRegExps();
        });
    }
    toText() {
        this.converter.innerHTML = this.state.value;
        return Array.from(this.converter.childNodes).map(node => node.textContent.split(/\n/)).flat();
    }
    isValidRegExp(re) {
        try {
            new RegExp(re);
            return true;
        } catch(e) {
            return false;
        }
    }
    getInvalidRegExps() {
        return this.toText().filter(value => !this.isValidRegExp(value));        
    }
    highlightInvalidRegExps() {
        let value = "";
        this.converter.innerHTML = this.state.value;
        Array.from(this.converter.childNodes).forEach(node => {
            const values = node.textContent.split(/\n/);
            values.forEach(re => {
                if (this.isValidRegExp(re)) {
                    value += `<div>${re}</div>`
                } else {
                    value += `<div class="error">${re}</div>`
                }
            });
        })
        this.setState({ value });
    }
    onChange = (e) => {
        this.setState({
            value: e.target.value
        });
    }
    onKeyDown = (e) => {
        console.log("down", e.keyCode);
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        const container = range.commonAncestorContainer;
        const parent = container.parentNode;
        let node;
        if (container.nodeType === 1 && container.getAttribute("class") === "error") {
            node = container;
        } else if (parent.nodeType === 1 && parent.getAttribute("class") === "error") {
            node = parent;
        }

        if (e.keyCode === 13) {
            if (node) {
                if (this.isValidRegExp(node.textContent)) {
                    node.removeAttribute("class");
                }
                const div = document.createElement('div');
                div.appendChild(document.createElement('br')); 
                node.parentNode.insertBefore(div, node.nextSibling);
                range.setStart(div, 0);
                range.collapse(true);
                selection.removeAllRanges();
                selection.addRange(range);
                e.preventDefault();
                return false;
            }
        } else if (e.keyCode === 8) {
            if (range.startContainer.nodeType === 3 && range.startOffset === 0 && range.endContainer === range.startContainer.parentNode.nextSibling && range.endOffset === 0) {
                console.log("whole container is selected");
                range.startContainer.parentNode.remove();
                e.preventDefault();
                return false;
            }
            if (node && range.startContainer === range.endContainer && range.startOffset === 0 && range.endOffset === node.textContent.length) {
                if (node.nextSibling) {
                    range.setStart(node.nextSibling, 0);
                }
                node.remove();
                e.preventDefault();
                return false;
            }
        } 
    }
    onKeyUp = (e) => {
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        let container = range.commonAncestorContainer;
        let parent = container.parentNode;
        console.log("up", e.keyCode, container, parent);
        let node;
        if (container.nodeType === 3 && parent.hasAttribute('contenteditable')) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(container.textContent));
            parent.replaceChild(div, container);
            range.setStart(div, 1);
            range.collapse(true);
            selection.removeAllRanges();
            selection.addRange(range);
            container = div;
        }
        if (container.nodeType === 1) {
            node = container;
        } else if (parent.nodeType === 1) {
            node = parent;
        }
        if (node) {
            if (this.isValidRegExp(node.textContent)) {
                node.removeAttribute("class");
            } else {
                node.setAttribute("class", "error");
            }
        }
    }
    onEnabledChange = (e) => {
        this.setState({
            enabled: e.target.checked
        });
    }
    render() {
        return (
            <ul ref={this.ref}>
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
                    <ContentEditable 
                        ref={this.contentEditableRef} 
                        html={this.state.value} 
                        tagName="div" 
                        onChange={this.onChange}  
                        onKeyUp={this.onKeyUp} 
                        onKeyDown={this.onKeyDown}/>
                    <input type="hidden"
                        name={this.props.prefix + '[value]'}
                        value={this.toText().join("\n")}
                    ></input>
                </li>
            </ul>
        );
    }
}
