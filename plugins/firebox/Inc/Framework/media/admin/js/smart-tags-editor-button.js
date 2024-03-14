document.addEventListener("DOMContentLoaded", function(e) {
	if (!window.MutationObserver)
	{
		return;
	}

	let observer = new MutationObserver((mutations) => {
		mutations.forEach(function(mutation) {
			if (!mutation.addedNodes) {
				return;
			}

			for (let i = 0; i < mutation.addedNodes.length; i++) {
				if (mutation.addedNodes[i] && typeof mutation.addedNodes[i].matches === 'function' && (mutation.addedNodes[i].innerHTML.includes('edit-post-header-toolbar__left"') || mutation.addedNodes[i].innerHTML.includes('editor-document-tools__left"')) && !mutation.addedNodes[i].innerHTML.includes('fpf-block-editor-top-button"')) {
					let toolbar = mutation.addedNodes[i].querySelector('.edit-post-header-toolbar__left');
					
					// WP 6.5
					if (!toolbar) {
						toolbar = mutation.addedNodes[i].querySelector('.editor-document-tools__left');
					}

					let buttonNode = document.createElement('div');
					buttonNode.innerHTML = '<button class="components-button fpf-modal-opener fpf-block-editor-top-button" data-fpf-modal="#fpf-smart-tags-list-modal"><i class="dashicons dashicons-tag"></i>' + fpf_js_object.SMART_TAGS_TITLE + '</button>';
					toolbar.appendChild(buttonNode);
				}
			}
		});
	});

    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: false,
        characterData: false,
    });
});