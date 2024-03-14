jQuery(document).ready(function () {
    tippy('.label-dash-widgets', {
        animation: 'scale',
        duration: 0,
        arrow: false,
        placement: 'auto',
        theme: 'wpms-widgets-tippy',
        onShow(instance) {
            instance.popper.hidden = instance.reference.dataset.tippy ? false : true;
            instance.setContent(instance.reference.dataset.tippy);
        }
    });

    tippy('.wpms_dash_widgets', {
        animation: 'scale',
        duration: 0,
        arrow: false,
        placement: 'auto',
        theme: 'wpms-widgets-tippy',
        onShow(instance) {
            instance.popper.hidden = instance.reference.dataset.tippy ? false : true;
            instance.setContent(instance.reference.dataset.tippy);
        }
    });
    tippy('.intro-topic-tooltip', {
        animation: 'scale',
        duration: 0,
        arrow: false,
        placement: 'auto',
        theme: 'wpms-widgets-tippy',
        onShow(instance) {
            instance.popper.hidden = instance.reference.dataset.tippy ? false : true;
            instance.setContent(instance.reference.dataset.tippy);
        }
    });
});

