const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style'

new blockManager('gutenova/filter-orderby')
    .controls(json)
    .css(Style)
    .register()