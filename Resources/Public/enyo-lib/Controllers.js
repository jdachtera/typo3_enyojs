
enyo.kind({
    name: 'Dachtera.Enyojs.Controller.LinkController',
    kind: 'Dachtera.Enyojs.Controller.ActionController',
    
    /**
        
    */
    generate: function() {
        return this.initiateRequest('generate', arguments);
    },
    
    /**
        Parses an url and returns the GET parameters
    */
    parse: function() {
        return this.initiateRequest('parse', arguments);
    },
    
    controller: 'Link',
    pluginName: 'Enyojs_Ajax',
    extensionName: 'Enyojs',
    actions: {
        
        generate: [
            'getVars'
        ],
        
        parse: [
            'url'
        ]
        
    }
});
