enyo.kind({
    name: 'Dachtera.Enyojs.Request',
    kind: 'enyo.Ajax',
    defaultParams: {

    },
    statics: {
        serializeObject: function(obj, prefix) {
            var str = [];
            for(var p in obj) {
                var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                str.push(typeof v == "object" ?
                    this.serializeObject(v, k) :
                    encodeURIComponent(k) + "=" + encodeURIComponent(v));
            }
            return str.join("&");
        },
        parseQueryString: function(query) {
            query = query[0] == '?' ? query.slice(1) : query;
            var parts = query.split("&");
            var conf = {};
            enyo.forEach(parts, function(part) {
                var p = part.split("=");
                conf[p[0]] = p[1];
            })
            return conf;
        }
    },
    constructor: function(conf, params) {
        this.inherited(arguments, [conf]);
        this.params = enyo.mixin(enyo.clone(this.defaultParams), params || {});
    },
    go: function() {
        return this.inherited(arguments, [this.ctor.serializeObject(this.params)]);
    }
});