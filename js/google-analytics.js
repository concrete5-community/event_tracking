document.querySelectorAll('[data-event-tracking-handler="click"]').forEach(function (link) {
    var onClickHandler = function () {
        if (typeof ga === "undefined") {
            return;
        }

        ga('send', 'event', {
            hitType: 'event',
            transport: 'beacon',
            eventCategory: link.getAttribute('data-event-tracking-category'),
            eventAction: link.getAttribute('data-event-tracking-action'),
            eventLabel: link.getAttribute('data-event-tracking-label'),
            eventValue: link.getAttribute('data-event-tracking-value'),
        });
    };

    link.addEventListener('click', onClickHandler);
});
