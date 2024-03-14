const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/account-form-register')
    .controls(json)
    .css(Style)
    .register()