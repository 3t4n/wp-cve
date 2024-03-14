const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style'

new blockManager('gutenova/archive-result-count')
    .controls(json)
    .css(Style)
    .register()