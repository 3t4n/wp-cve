var LISTED_FORMS = []

function getParameterByName(name, scriptId) {
    if(!scriptId) scriptId = 'leadlovers-webcomponent-script-js'
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(document.querySelector(`#${scriptId}`).src);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function observe(fn) {
    const body = document.body
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'childList') {
          for (let i = 0; i < mutation.addedNodes.length; i++) {
            const node = mutation.addedNodes[i]
            if (
              node.nodeName === 'FORM' ||
              (node.nodeType === 1 && node.querySelector('form'))
            ) {
              fn()
            }
          }
        }
      })
    })
    observer.observe(body, {
      childList: true,
      attributes: false,
      characterData: false,
      subtree: true,
      attributeOldValue: false,
      characterDataOldValue: false,
    })
}

function handleSubmitForm(event, integrations, srcId) {
    const form = event.target
    const hasEmptyRequiredFields = [...form.querySelectorAll('input,textarea')]?.some((x) => { 
      const isRequired = x.getAttribute('required') || x.getAttribute('aria-required') ||  x.getAttribute('aria-invalid')
      return isRequired && !x.value 
    })
    if(hasEmptyRequiredFields) return
    event.preventDefault()
    const formData = [...new FormData(form)]
    const integration = integrations.find((int) => int.form_id === form.id)
    const postData = {
        action:  'leadlovers-save-lead',
        nonce: getParameterByName('leadlovers-save-lead_nonce', srcId),
        machineCode: integration.machine_id,
        emailSequenceCode: integration.funnel_id,
        sequenceLevelCode: integration.sequence_id,
        dynamicFields: [],
        tags: integration.tags ? JSON.stringify(integration.tags.split(',')) : '[]',
    }
    const fixedFields = [
        'name',
        'email',
        'phone',
        'city',
        'company',
        'state',
        'sex',
        'birthday',
        'message',
        'note',
        'score',
        'photo'   
    ]
    const maps = integration?.mapped_fields?.split(',').map((val) => {
        const split = val.split(':')
        return {
        key: split[0],
        value: split[1],
        }
    }, {})
    if (maps) {
        maps.forEach((map) => {
            const isFixed = fixedFields.some((ff) => ff === map.value)
            const fieldValue = formData.find((d) => d[0] === map.key)[1]
            if(isFixed)
                postData[map.value] = fieldValue
            else
                postData.dynamicFields.push({ id: map.value, value: fieldValue})
        })
    }

    postData.dynamicFields = JSON.stringify(postData.dynamicFields)
  
    const saveCaptureLog = () => {
      const logData = {
          action:  'leadlovers-save-capture-log',
          nonce: getParameterByName('leadlovers-save-capture-log_nonce', srcId),
          page: window.location.href.split('#')[0],
          machine_name: integration.machine_name,
          funnel_name: integration.funnel_name,
          sequence_name: integration.sequence_name,
          form_id: integration.form_id,
          json: JSON.stringify(postData), 
      }
      const params = new URLSearchParams()
      for(let key in logData)
          params.append(key, logData[key])
  
      return fetch(getParameterByName('xhr_url', srcId), {
          method: 'POST',
          body: params
      })
  }

  const saveErrorLog = (error) => {
      const logData = {
          action:  'leadlovers-save-error-log',
          nonce: getParameterByName('leadlovers-save-error-log_nonce', srcId),
          page: window.location.href.split('#')[0],
          machine_name: integration.machine_name,
          funnel_name: integration.funnel_name,
          sequence_name: integration.sequence_name,
          form_id: integration.form_id,
          json: JSON.stringify(postData), 
          error,
      }
      const params = new URLSearchParams()
      for(let key in logData)
          params.append(key, logData[key])
  
     return fetch(getParameterByName('xhr_url', srcId), {
          method: 'POST',
          body: params
      })
  }

  const postParams = new URLSearchParams()
  for(let key in postData)
      postParams.append(key, postData[key])

    fetch(getParameterByName('xhr_url', srcId),  {
        method: 'POST',
        body: postParams
    })
    .then(res => res.json())
    .catch(error => {
           saveErrorLog(JSON.stringify(error))   
          .then(res => res.json())
          .catch(error => {
                  console.error(error)
          })
          .then(response => {    
          })
    })
    .then(response => {
      const redirect = () => {
        const action = form.action
        if(!action) return
        const target = form.target
        if(target) window.open(form.action, form.target)
        else window.location.href = form.action
      }
     if(response?.data?.response?.code === 200) {
        saveCaptureLog()
        .then(res => res.json())
        .catch(error => {
                console.error(error)
        })
        .then(response => {    
          redirect()
        })
      } else {
        saveErrorLog(JSON.stringify(response))
        .then(res => res.json())
        .catch(error => {
                console.error(error)
        })
        .then(response => {    
          redirect()
        })
      }
    })
}