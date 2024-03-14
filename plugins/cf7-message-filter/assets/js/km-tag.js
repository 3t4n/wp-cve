class KmTag {
    #input;
    #options;
    static max_id = 0;
    #id;
    #parent;
    #tag_container
    #new_tag_input;
    #values = [];

    constructor(selector, options = {}) {
        this.#input = document.querySelector(selector);

        // check if the input element exists and is a textarea or input element
        if (!this.#input || !['textarea', 'input'].includes(this.#input.tagName.toLowerCase())) {
            console.error("KmTag: The selector must be a textarea or input element");
            return;
        }
        this.#options = this.#parseOptions(options);

        this.#values = this.#input.value.split(this.#options.delimiter).filter((e) => e.trim().length > 0);

        this.#parent = this.#input.parentNode;
        // hide the input element
        this.#input.style.display = 'none';
        this.#input.setAttribute('type', 'text');
        this.#id = "km-tags-" + (++KmTag.max_id);
        this.#init();
        this.#tag_container = document.querySelector('#' + this.#id);
        this.#new_tag_input = document.querySelector('#' + this.#id + " .km-new-tag-input");
        // this.#new_tag_input.focus();

        this.#addActions()
        this.#rebuildTags();
    }

    /**
     * Removes a value from the values array
     * @param value the value to remove
     * */
    #removeValue(value) {
        const index = this.#values.indexOf(value);
        if (index > -1) {
            this.#values.splice(index, 1);
        }
    }

    /**
     * Removes a value from the values array and rebuilds the tags
     * @param value the value to remove
     */
    removeValue(value) {
        this.#removeValue(value);
        this.#rebuildTags();
        this.#updateValues();
    }

    /**
     * Adds a value to the values array and update the tags
     * @param value
     */
    addValue(value) {
        if (this.#values.includes(value)) return;
        this.#values.push(value);
        this.#addTag(value);
        this.#updateValues();
    }

    /**
     * Adds multiple values to the values array and update the tags
     * @param values
     */
    addValues(values) {
        values.forEach((value) => {
            this.addValue(value);
        })
    }

    /**
     * Rebuilds the tagsy
     */
    #rebuildTags() {
        // remove all the tags
        const tags = document.querySelectorAll('#' + this.#id + " .km-tag-container");
        for (let i = 0; i < tags.length; i++) {
            tags[i].remove();
        }
        this.#values.forEach((value) => {
            this.#addTag(value);
        })
    }

    /**
     * Parses the options and returns the options object
     * @param options
     * @returns {*&{delimiter: string}}
     */
    #parseOptions(options) {
        return {
            ...options,
            ...{
                delimiter: ',', // the delimiter to use when converting the tags to string
                maxItems: -1 // the maximum number of items allowed. -1 means unlimited
            }
        }
    }

    /**
     * Adds event listeners to the close buttons
     */
    #addTagEventListeners() {
        const close_btns = document.querySelectorAll('#' + this.#id + " .km-tag-container__action");
        for (let i = 0; i < close_btns.length; i++) {
            close_btns[i].removeEventListener('click', this.#closeBtnEventListener)
            close_btns[i].addEventListener('click', this.#closeBtnEventListener);
        }

        const inputs = document.querySelectorAll('#' + this.#id + " .km-tag-container__input");
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].removeEventListener('beforeinput', this.#beforeInputEventListener)
            inputs[i].addEventListener('beforeinput', this.#beforeInputEventListener);
            inputs[i].removeEventListener('input', this.#inputEventListener)
            inputs[i].addEventListener('input', this.#inputEventListener);
            // inputs[i].dispatchEvent(new KeyboardEvent('keyup', {key: inputs[i].value}));
        }

    }

    /**
     * The event listener for the close button
     * @param e
     */
    #closeBtnEventListener = (e) => {

        // access the parent element
        const parent = e.target.parentNode;
        // remove the value from the values array
        this.#removeValue(parent.children[0].innerText);
        // remove the parent element
        parent.remove();
        this.#updateValues()

    }

    /**
     * The event listener for the input width. Automatically adjusts the width of the input based on the content
     * @param e
     */
    #beforeInputEventListener = (e) => {
        if ((!e.data || (e.data && e.data.trim() === this.#options.delimiter)) && (e.inputType !== 'deleteContentBackward')) {
            // if ((e.data && e.data.trim() !== this.#options.delimiter) || (e.inputType === 'deleteContentBackward')) {
            e.preventDefault();
        }
    }

    /**
     * The event listener for the input width. Automatically adjusts the width of the input based on the content
     * @param e
     */
    #inputEventListener = (e) => {
        let value = e.target.innerText.trim();
        if (value.length === 0) {
            // remove the tag
            const parent = e.target.parentNode;
            parent.children[1].dispatchEvent(new KeyboardEvent('click', {key: 'Enter'}));
        } else {
            value = value.replace(/&nbsp;/g, ' ');
            const index = this.#values.indexOf(e.target.getAttribute('data-value'));
            if (index > -1) {
                this.#values[index] = value;
            } else {
                this.#values.push(value);
            }
            this.#updateValues()
            e.target.setAttribute("data-value", value);

        }
    }

    /**
     * Update the values in the original input
     */
    #updateValues() {
        this.#values = this.#values.filter((e) => e.trim().length > 0);
        this.#values = this.#values.filter((item, pos) => {
            return this.#values.indexOf(item) === pos;
        })
        this.#input.value = this.#values.join(this.#options.delimiter);
    }

    /**
     * Initializes the tags
     * */
    #init() {
        this.#input.insertAdjacentHTML('beforebegin', `<div class="km-tags" id="${this.#id}">
  <input type="text" value='' class="km-new-tag-input" placeholder="enter text">
</div>`);
    }

    /**
     * Adds the actions to the new tag input
     */
    #addActions() {
        this.#new_tag_input.addEventListener('input', (e) => {
            this.#parseInput(e);
        });

        this.#new_tag_input.addEventListener('keyup', (e) => {
            const keyCode = e.keyCode;
            // check if the enter key was pressed
            if (keyCode === 13) {
                this.#parseInput(e, true);
            }
        });
    }

    #parseInput(e, isEnterPressed = false) {
        let new_values = e.target.value.trim() //.split(this.#options.delimiter);

        // check if the delimiter is present in new_values
        if (new_values.includes(this.#options.delimiter) || isEnterPressed) {
            new_values = new_values.split(this.#options.delimiter).filter((e) => e.trim().length > 0);
            if (new_values.length > 0) {
                const form = e.target.closest('form');
                if (form) {
                    e.preventDefault();
                    // todo: prevent form from submitting
                }
                for (let value of new_values) {
                    value = value.replace(this.#options.delimiter, "")
                    if (value.length > 0 && !this.#values.includes(value)) {
                        this.#values.push(value);
                        this.#addTag(value);
                        this.#updateValues()
                        // count++;
                    }
                }
                e.target.value = '';
            }
        }
    }

    /**
     * Adds a tag to the tags container
     * @param content
     */
    #addTag(content) {
        this.#new_tag_input.insertAdjacentHTML('beforebegin', ` <div class="km-tag-container">
    <div  class="km-tag-container__input" data-value='${content}' contentEditable="true">${this.#encodeEntities(content)}</div>
    <div class="km-tag-container__action">
      &times;
    </div>
  </div>`);
        this.#addTagEventListeners();
    }

    #decodeEntities(encodedString) {
        const textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    }

    #encodeEntities(str) {
        return str.replace(/[\u00A0-\u9999<>\&]/g, function (i) {
            return '&#' + i.charCodeAt(0) + ';';
        });
    }
}