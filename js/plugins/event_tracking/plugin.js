/**
 * @copyright A3020
 */
CKEDITOR.plugins.add('event_tracking', {
    init: function(editor) {
        var pluginName = 'event_tracking';
        var label = 'Event Tracking';
        var iconPath = CCM_REL + '/packages/event_tracking/js/plugins/event_tracking/images/event-tracking.png';

        // Add the command which is referenced in the toolbar for example.
        editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName));


        // Add button to the toolbar.
        editor.ui.addButton(pluginName, {
            label: label,
            command: pluginName,
            icon: iconPath
        });

        editor.on('selectionChange', function(evt) {
            if (editor.readOnly) return;
            
            var command = editor.getCommand(pluginName);
            var element = evt.data.path.lastElement && evt.data.path.lastElement.getAscendant('a', true);

            if (element && element.getName() === 'a' && element.getAttribute('href') && element.getChildCount()) {
                command.setState(CKEDITOR.TRISTATE_OFF);
            } else {
                command.setState(CKEDITOR.TRISTATE_DISABLED);
            }
        });
        
        if (editor.contextMenu) {
            var pluginNameGroup = pluginName + 'Group';
            var pluginNameItem = pluginName + 'Item';

            editor.addMenuGroup(pluginNameGroup, 10);

            editor.addMenuItem(pluginNameItem, {
                label: label,
                icon: iconPath,
                command: pluginName,
                group: pluginNameGroup,
                order: 1
            });
            
            editor.contextMenu.addListener(function(element, selection) {
                if (!element) {
                    return null;
                }

                if (element.is('a')) {
                    return { 'event_trackingItem': CKEDITOR.TRISTATE_OFF};
                }

                return null;
            });
        }

        // Create a dialog. The dialog is referenced in the command.
        CKEDITOR.dialog.add(pluginName, function(editor) {
            return {
                title : label,
                minWidth : "400",
                minHeight : "260",
                contents: [{
                    id: 'tab-settings',
                    label: 'Settings',
                    elements: [
                        {
                            type: 'select',
                            id: 'eventHandler',
                            label: 'Event Handler *',
                            style: 'min-width: 100px',
                            items: [
                                [
                                    'Click', 'click'
                                ],
                                [
                                    'None (remove)', ''
                                ]
                            ],
                            default: 'click'
                        },
                        {
                            type: 'text',
                            id: 'eventCategory',
                            label: 'Event Category *',
                            setup: function(element) {
                                this.setValue(element.data('event-tracking-category'));
                            }
                        },
                        {
                            type: 'text',
                            id: 'eventAction',
                            label: 'Event Action *',
                            setup: function(element) {
                                this.setValue(element.data('event-tracking-action'));
                            }
                        },
                        {
                            type: 'text',
                            id: 'eventLabel',
                            label: 'Event Label',
                            setup: function(element) {
                                this.setValue(element.data('event-tracking-label'));
                            }
                        },
                        {
                            type: 'text',
                            id: 'eventValue',
                            label: 'Event Value',
                            validate: CKEDITOR.dialog.validate.integer('Invalid event value! The value should be an integer or empty.'),
                            setup: function(element) {
                                this.setValue(element.data('event-tracking-value'));
                            }
                        },
                        {
                            type: 'html',
                            html: '<p style="color: #aaa;">See <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/events" ' +
                                'target="_blank" style="color: #aaa; text-decoration: underline; cursor: pointer;">Google Developers</a> for more information.</p>'
                        },
                    ]
                }],
                onOk: function() {
                    // The currently selected element.
                    var element = this.element;

                    // Remove existing data attributes.
                    element.removeAttribute('data-event-tracking-handler');
                    element.removeAttribute('data-event-tracking-category');
                    element.removeAttribute('data-event-tracking-action');
                    element.removeAttribute('data-event-tracking-label');
                    element.removeAttribute('data-event-tracking-value');

                    var dialog = this;

                    // This is the event that will trigger the tracker.
                    // If this is set to 'None', no data attributes will be set.
                    if (dialog.getValueOf('tab-settings', 'eventHandler')) {
                        element.setAttribute('data-event-tracking-handler', dialog.getValueOf('tab-settings', 'eventHandler'));

                        // Set required 'Category' attribute.
                        if (dialog.getValueOf('tab-settings', 'eventCategory')) {
                            element.setAttribute('data-event-tracking-category', dialog.getValueOf('tab-settings', 'eventCategory'));
                        }

                        // Set required 'Action' attribute.
                        if (dialog.getValueOf('tab-settings', 'eventAction')) {
                            element.setAttribute('data-event-tracking-action', dialog.getValueOf('tab-settings', 'eventAction'));
                        }

                        // Set optional 'Label' attribute.
                        if (dialog.getValueOf('tab-settings', 'eventLabel')) {
                            element.setAttribute('data-event-tracking-label', dialog.getValueOf('tab-settings', 'eventLabel'));
                        }

                        // Set optional 'Value' attribute.
                        if (dialog.getValueOf('tab-settings', 'eventValue')) {
                            element.setAttribute('data-event-tracking-value', dialog.getValueOf('tab-settings', 'eventValue'));
                        }
                    }

                    this.commitContent(element);
                },
                onShow: function() {
                    var element = editor.getSelection().getStartElement();
                    this.element = element;

                    this.setupContent(element);
                }
            }
        });
    }
});
