enyo.kind({
    name: 'Dachtera.Enyojs.Controller.ActionController',
    kind: 'Component',
    defaultParams: {},
    handlers: {
        onStateChanged:'stateChanged'
    },
    stateChanged: function(inSender, inEvent) {
        this.defaultParams = enyo.clone(inEvent.state._GET);
    },
    initiateRequest: function(actionName, args) {
        var params = enyo.mixin(enyo.clone(this.defaultParams), enyo.mixin(args[this.actions[actionName].length], {
            eID: 'enyojs',
            extensionName: this.extensionName,
            pluginName: this.pluginName
        }));
        var ns = ('tx_' + this.extensionName + '_' + this.pluginName).toLowerCase();
        params[ns] = {
            controller: this.controller,
            action: actionName
        };
        enyo.forEach(this.actions[actionName], function(arg, index) {
            if (args.hasOwnProperty(index)) {
                params[ns][arg] = args[index];
            }
        }, this);

        return new Dachtera.Enyojs.Request({
            method: 'POST',
            url: 'index.php'
        }, params);
    }
});
