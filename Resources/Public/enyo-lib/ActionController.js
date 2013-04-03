enyo.kind({
    name: 'Dachtera.Enyojs.Controller.ActionController',
    kind: 'Component',
    initiateRequest: function(actionName, args) {
        var params = {
            eID: 'enyojs',
            extensionName: this.extensionName,
            pluginName: this.pluginName
        };
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
