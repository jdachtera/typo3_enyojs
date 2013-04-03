enyo.kind({
    name: 'Dachtera.Enyojs.LoadingScreen',
    tag: null,
    components: [
        {
            kind: 'onyx.Scrim',
            classes: 'onyx-scrim-translucent',
            components: [
                {
                    kind: 'onyx.Popup',
                    centered: true,
                    components: [
                        {kind: 'onyx.Spinner'}
                    ]
                }
            ]
        }

    ],
    show: function() {
        this.$.popup.show();
        this.$.scrim.show();
    },
    hide: function() {
        this.$.popup.hide();
        this.$.scrim.hide();
    }
});