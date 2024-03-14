import React from 'react'
import ReactDOM from 'react-dom'
import App from './App'

const modalContainer = document.getElementById('mm-voltax-vms-modal')

if (modalContainer) {
    ReactDOM.render(<App />, modalContainer)
}
