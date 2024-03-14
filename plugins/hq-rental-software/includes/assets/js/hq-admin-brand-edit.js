document.querySelectorAll('#hq-snippet-reservation-button').forEach(item => {
    item.addEventListener('click', event => {
        //handle click
        var brand = item.dataset.brand;
        var snippet = item.dataset.snippet;
        var code = hqBrandSnippets[brand][snippet];
        navigator.clipboard.writeText(code).then(function () {
            alert('Snippet copied.');
        }, function () {
            alert("There was an issue copying the snippet. Please get in touch with our support team.");
        });
        try {
            navigator.permissions.query({name: 'clipboard-write'}).then(result => {
                if (result.state == 'granted' || result.state == 'prompt') {
                    navigator.clipboard.writeText(code).then(function () {
                        alert('Snippet copied.');
                    }, function () {
                        alert("There was an issue copying the snippet. Please get in touch with our support team.");
                    });
                } else {
                    alert("There was an issue copying the snippet. Please get in touch with our support team.");
                }
            });
        } catch (e) {
            alert("There was an issue copying the snippet. Please get in touch with our support team.");
        }
    });
});
(function ($) {
    tippy('#hq-snippet-reservation-button');
})(jQuery);
