document.querySelectorAll('[data-event-tracking-handler="click"]').forEach(function (link) {
    var onClickHandler = function (e) {
        if (typeof ga === "undefined") {
            return;
        }

        e.preventDefault();

        console.log('Tracking event via GA analytics.js');

        function submit() {
            if (e.target.href) {
                window.location.href = e.target.href;
            }
        }

        // In case Analytics doesn't trigger the callback
        setTimeout(submit, 1000);

        ga('send', 'event', {
            hitType: 'event',
            transport: 'beacon',
            eventCategory: link.getAttribute('data-event-tracking-category'),
            eventAction: link.getAttribute('data-event-tracking-action'),
            eventLabel: link.getAttribute('data-event-tracking-label'),
            eventValue: link.getAttribute('data-event-tracking-value'),
            'hitCallback': function () {
                submit()
            }
        });
    };

    link.addEventListener('click', onClickHandler);
});
