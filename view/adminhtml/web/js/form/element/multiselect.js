define([
    'jquery',
    'rjsResolver',
    'Magento_Ui/js/form/element/multiselect'
], function ($, resolver, Multiselect) {
    'use strict';

    return Multiselect.extend({
        defaults: {
            multiselectSelector: '.redepygdprcookie-multiselect',
        },

        initialize: function () {
            this._super();
            resolver(this.disableOptions, this);

            return this;
        },

        disableOptions: function () {
            var disabledOptionIds = this.source.disabled_cookies ?? [];

            $.each($(this.multiselectSelector + ' option'), function (optionId, option) {
                if (disabledOptionIds.indexOf(option.value) !== -1) {
                    option.disabled = true;
                }
            });

            return this;
        },
    });
});
