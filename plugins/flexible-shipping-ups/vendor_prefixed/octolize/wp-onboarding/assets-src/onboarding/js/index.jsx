import React from 'react';
import Onboarding from "./components/onboarding";
import {render} from "react-dom";

const version = 2;

document.addEventListener( 'DOMContentLoaded', function () {
    let container_selector = '.onboarding-container-' + version;
    document.querySelectorAll(container_selector ).forEach(function( container ) {
        let onboarding_settings = JSON.parse( container.getAttribute( 'onboarding_settings' ) );
        render( <Onboarding settings={onboarding_settings}/>, container );
    });
}, false );
