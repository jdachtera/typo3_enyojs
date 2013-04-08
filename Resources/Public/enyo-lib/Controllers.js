
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

enyo.kind({
    name: 'Dachtera.EventsBielefeld.Controller.CronUpdaterController',
    kind: 'Dachtera.Enyojs.Controller.ActionController',
    
    /**
        
    */
    updateProductions: function() {
        return this.initiateRequest('updateProductions', arguments);
    },
    
    /**
        
    */
    updateLocations: function() {
        return this.initiateRequest('updateLocations', arguments);
    },
    
    controller: 'CronUpdater',
    pluginName: 'EventsBielefeld',
    extensionName: 'EventsBielefeld',
    actions: {
        
        updateProductions: [],
        
        updateLocations: []
        
    }
});

enyo.kind({
    name: 'Dachtera.EventsBielefeld.Controller.AjaxController',
    kind: 'Dachtera.Enyojs.Controller.ActionController',
    
    /**
        
    */
    getLocationCategories: function() {
        return this.initiateRequest('getLocationCategories', arguments);
    },
    
    /**
        
    */
    getProductionCategories: function() {
        return this.initiateRequest('getProductionCategories', arguments);
    },
    
    /**
        
    */
    getEvents: function() {
        return this.initiateRequest('getEvents', arguments);
    },
    
    /**
        
    */
    getProductions: function() {
        return this.initiateRequest('getProductions', arguments);
    },
    
    /**
        
    */
    getLocations: function() {
        return this.initiateRequest('getLocations', arguments);
    },
    
    controller: 'Ajax',
    pluginName: 'EventsBielefeld',
    extensionName: 'EventsBielefeld',
    actions: {
        
        getLocationCategories: [],
        
        getProductionCategories: [],
        
        getEvents: [
            'startTime',
        
            'endTime'
        ],
        
        getProductions: [
            'uids'
        ],
        
        getLocations: [
            'uids'
        ]
        
    }
});
