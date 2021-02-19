document.querySelectorAll('[data-event-tracking-handler="click"]').forEach(function (link) {
    var onClickHandler = function (e) {
        if (typeof gtag === "undefined") {
            return;
        }

        e.preventDefault();

        console.log('Tracking event via GA gtag.js');

        function submit() {
            if (e.target.href) {
                window.location.href = e.target.href;
            }
        }

        // In case Analytics doesn't trigger the callback
        setTimeout(submit, 1000);

        gtag('event', link.getAttribute('data-event-tracking-action'), {
            'event_category': link.getAttribute('data-event-tracking-category'),
            'event_label': link.getAttribute('data-event-tracking-label'),
            'event_value': link.getAttribute('data-event-tracking-value'),
            'event_callback': submit()
        });
    };

    link.addEventListener('click', onClickHandler);
});
