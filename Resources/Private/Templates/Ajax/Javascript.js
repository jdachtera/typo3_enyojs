enyo.kind({
    name: 'TYPO3.Enyojs.AjaxBridge',
    kind: 'Object',
    constructor: function() {
        this.inherited(arguments);
        for (var action in this.actions) {
            if (this.actions.hasOwnProperty(action)) {
                this[action] = this._getActionShortcut(action);
            }
        }
    },
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
        return new enyo.Ajax({
            method: 'POST',
            url: 'index.php'
        }).go(this.serializeObject(params));
    },
    _getActionShortcut: function(action) {
        return enyo.bind(this, function() {
            return this.initiateRequest(action, arguments);
        });
    },
    serializeObject: function(obj, prefix) {
        var str = [];
        for(var p in obj) {
            var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
            str.push(typeof v == "object" ?
                this.serializeObject(v, k) :
                encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
        return str.join("&");
    }
});

<f:for each="{kindConfigs}" as="kindConfig">
enyo.singleton({kindConfig});
</f:for>