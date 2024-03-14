function removeUrlErrorParameter() {
    let params = new URLSearchParams(location.search);
    params.delete('library-viewer-error-message');
    if( 0 === Array.from(params).length ){
        history.replaceState(null, '', location.pathname);
    } else {
        history.replaceState(null, '', '?' + params + location.hash);
    }
}

removeUrlErrorParameter();