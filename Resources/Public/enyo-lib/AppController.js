enyo.kind({
    name: 'Dachtera.Enyojs.Controller.AppController',
    state: {
        _GET: {},
        url: '',
        title: ''
    },
    events: {
        onStateChanged: ''
    },
    handlers: {
        onChangeState: 'changeState'
    },
    changeState: function(inSender, inEvent) {
        inEvent.state = inEvent.state || {};
        var changeUrl = inEvent.state.url && inEvent.state.url !== this.state.url;
        enyo.mixin(this.state, inEvent.state);
        console.log('App State changed: ', this.state);
        if (changeUrl) {
            window.history.pushState(this.state, this.state.title, this.state.url);
        }
        this.waterfall('onStateChanged', {state: this.state});
        if (inEvent.state._GET) {
            this.$.linkController.generate(this.state._GET || {})
                .response(this, function(inSender, inResponse) {
                    if (inResponse !== this.state.url) {
                        this.bubble('onChangeState', {state: {url: inResponse}});
                    }
                }).go();
        }

    },
    create: function() {
        this.inherited(arguments);
        this.createComponent({kind: 'Dachtera.Enyojs.Controller.LinkController'});
        this.bubble('onChangeState', {});
    }
});