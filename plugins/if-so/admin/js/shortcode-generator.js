class ShortcodeGenerator {
    constructor(prefix, formSelector, shortcodeSelector, errorSelector, instantChange=false) {
      this.prefix = prefix
      this.formSelector = formSelector
      this.shortcodeSelector = shortcodeSelector
      this.errorSelector = errorSelector
      this.instantChange = instantChange
  
      this.formElement = document.querySelector(formSelector)
      this.shortcodeElement = document.querySelector(shortcodeSelector)
      this.errorElement = document.querySelector(errorSelector)
  
      if ( this.instantChange ) {
        window.addEventListener('countdown-generator-ready', () => { this.submitHandler() }, {once: true})
        this.formElement.addEventListener('change', () => { this.submitHandler() })
      }
  
      setTimeout(() => this.stickResultsToSidebar(), 0)
    }
    
  
    submitHandler(event) {
      let formdata = new FormData(this.formElement)
      
      this.errorElement.classList.remove('active')
      this.errorElement.innerHTML = '' // clear previous errors
      this.shortcodeElement.value = '' // clear previous shortcode
  
      try {
        let shortcode = this.generateShortcode(formdata)
        this.afterSubmitHandler(shortcode)
      } catch (errors) {
        this.errorElement.classList.add('active')
        let errorLabels = errors.map(e => this.createErrorLabel(e))
        errorLabels.forEach(t => this.errorElement.appendChild(t))
        this.afterSubmitErrorHandler(errors)
      }
    }
  
    afterSubmitHandler(shortcode) {
      this.shortcodeElement.value = shortcode
    }
    afterSubmitErrorHandler(errors) {
      // do nothing
    }
  
    createErrorLabel(text) {
      let label = document.createElement('label')
      label.appendChild(document.createTextNode(text))
      return label
    }
  
    renderContent(content) {
      return new Promise((resolve, reject) => {
        jQuery.post(ajaxurl, {
          'action': 'render_preview_content',
          'ifso_render_preview_nonce': _nonce,
          'render_content': content
        })
        .done(function(result) {
          resolve(result)
        })
        .fail(function(xhr, status, error) {
          reject(error)
        })
      })
    }
  
    generateShortcode(formdata) {
      let shortcode = '[' + this.prefix
      let reduceData = this.iteratorToArray(formdata)
      let errors = []
    
      reduceData.forEach(entry => {
        let category = entry.shift()
        let values = entry
        let operator = this.getOperator(category)
        let result
  
        try {
          result = operator(category, values, reduceData)
          shortcode += result
        } catch (error) {
          errors.push(error)
        }
      })
  
      if ( errors.length > 0 ) throw(errors)
      return shortcode + ']'
    }
  
    getOperator(category) {
      let operator = this.operators.find(op => op[0].includes(category))
      if ( operator ) return operator[1]
      return this.defaultOperator
    }
  
    joinOperatorNoFilter = (cat, vals, separator)  => ` ${cat}="${vals.join(separator)}"`
    joinOperator = (cat, vals, separator) => ` ${cat}="${vals.filter(v => v !== '').join(separator)}"`
    defaultOperator = (cat, vals) => this.joinOperator(cat, vals, ',')
    omitDefault = (cat, vals, defaultValue, separator) => {
      if (  vals.join(separator) === defaultValue ) return ''
      return this.defaultOperator(cat, vals)
    }
    
    iteratorToArray(iterator, reduceDuplicates=true) {
      let result = []
      if ( reduceDuplicates ) {
        let categories = []
        for (const entry of iterator) {
          let catIndex = categories.findIndex(val => val === entry[0])
          if ( catIndex !== -1 ) {
            result[catIndex].push(entry[1])
          } else {
            result.push(entry)
            categories.push(entry[0])
          }
        }
      } else {
        for (const entry of iterator) {
          result.push(entry)
        }
      }
      return result
    }
  
    inputSelectorAll(inputName, inputValue, inputType) {
      let InputNameQuery = `input[name="${inputName}"]`
      let selectNameQuery = `select[name="${inputName}"]`
      let valueQuery = inputValue !== undefined ? `[value="${inputValue}"]` : ``
      let typeQuery = inputType !== undefined ? `[type="${inputType}"]` : ``
      return document.querySelectorAll(
        this.formSelector + ' ' + InputNameQuery + valueQuery + typeQuery
        + ',' + 
        this.formSelector + ' ' + selectNameQuery + valueQuery + typeQuery)
    }
  
    fieldsetSelectorAll(fieldsetName) {
      let nameQuery = `fieldset[name="${fieldsetName}"]`
      return document.querySelectorAll(this.formSelector + ' ' + nameQuery)
    }
  
    toggleInputs(inputName, inputValue) {
      let inputs = this.inputSelectorAll(inputName, inputValue)
      inputs.forEach(input => input.disabled = !input.disabled)
    }
  
    toggleFieldsets(name, forceValue) {
      let fieldsets = this.fieldsetSelectorAll(name)
      fieldsets.forEach(fieldset => {
        if ( forceValue !== undefined ) {
          fieldset.disabled = !forceValue
        } else {
          fieldset.disabled = !fieldset.disabled
        }
      })
    }
  
    toggleInputsByMultipleRadio(inputName, radioArr, inputValue) {
      let elements = this.inputSelectorAll(inputName, inputValue)
      let changeValue = this.radioCondition(radioArr)
      elements.forEach(el => el.disabled = !changeValue)
    }
  
    toggleFieldsetsByMultipleRadio(fieldsetName, radioArr) {
      let fieldsets = this.fieldsetSelectorAll(fieldsetName)
      let changeValue = this.radioCondition(radioArr)
      fieldsets.forEach(el => el.disabled = !changeValue)
    }
  
    radioCondition(radioArr) {
      return radioArr.every(radio => {
        let radioName = radio[0]
        let selectedRadio = document.querySelector(`${this.formSelector} input[name="${radioName}"]:checked`)
        for (let i = 1; i < radio.length; i++) { if ( radio[i] === selectedRadio.value ) return true }
        return false
      })
    }
  
    toggleFieldsetsBySelect(inputName, selectName, enabledValue, inputValue) {
      let elements = this.fieldsetSelectorAll(inputName, inputValue)
      let select = this.inputSelectorAll(selectName)[0]
      let changeValue = select.options[select.selectedIndex].value === enabledValue
      elements.forEach(el => el.disabled = !changeValue)
    }
  
    checkInputs(inputName, inputValue, value) {
      let inputs = this.inputSelectorAll(inputName, inputValue)
      inputs.forEach(input => input.checked = value)
    }
  
    rangeSelectChangeHandler() {
      let from = document.querySelector('.format-from')
      let to =  document.querySelector('.format-to')
      Array.from(from.options).forEach((opt, i) => opt.disabled = (i <= to.selectedIndex))
      Array.from(to.options).forEach((opt, i) => opt.disabled = (i >= from.selectedIndex))
    }
  
    copyShortcode() {
      let shortcode = this.shortcodeElement.value
      navigator.clipboard.writeText(shortcode);
    }
    
    stickResultsToSidebar() {
      let sidebar = document.querySelector('#hebrew-sticky-aside')
      let results = document.querySelector('.shortcode-generator-results')
      if ( !sidebar || !results) return
      sidebar.innerHTML = ''
      sidebar.appendChild(results)
    }
  }
  