enyo.kind({
    name: 'Dachtera.Enyojs.PageBrowser',
    layoutKind: 'FittableRowsLayout',
    published: {
        url: '',
        targetName: null,
        preloadLinks: true
    },
    statics: {
        cache: {}
    },
    events: {
        onUrlChanged: ''
    },
    components: [
        {
            kind: 'Dachtera.Enyojs.LoadingScreen'
        },
        {

            kind: 'Scroller',
            classes: 'nice-padding',
            components: [
                {
                    name: 'content'
                }
            ],
            fit: true
        }
    ],
    urlChanged: function() {
        this.bubble('onUrlChanged');
        var url = this.generateAjaxUrl(this.url);
        if (this.ctor.cache[url]) {
            this.changeContent(this, this.ctor.cache[url]);
        } else {
            this.$.loadingScreen.show();
            return new enyo.Ajax({
                url: url,
                cacheBust: false,
                handleAs: 'text'
            })
                .response(this, 'putIntoCache')
                .response(this, 'changeContent')
                .response(this, 'preload')
                .response(this.$.loadingScreen, 'hide')
                .error(this, 'loadingFailed')
                .go();
        }
    },
    parseResponse: function(response) {
        var parsed = enyo.json.parse(response);

        return parsed;
    },
    generateAjaxUrl: function(url) {
        var link = document.createElement('a');
        link.href = url;
        link.search += (link.search == '' ? '?' : '&') + 'type=2';
        return link.href;
    },
    putIntoCache: function(inSender, inResponse) {
        this.ctor.cache[inSender.url] = inResponse;
    },
    changeContent: function(inSender, inResponse) {
        var parsed = this.parseResponse(inResponse);
        //this.bubble('onChangeState', {state: parsed.state});
        this.$.content.destroyClientControls();
        this.$.content.createClientComponents(parsed.content);
        this.$.content.render();
        //this.applyLinkListeners();
    },
    preload: function() {
        var links = this.$.content.hasNode().getElementsByTagName('a');
        if (this.preloadLinks) {
            enyo.forEach(links, function(link) {
                var url = this.generateAjaxUrl(link.href);
                if (!this.ctor.cache.hasOwnProperty(url)) {
                    new enyo.Ajax({
                        url: url,
                        cacheBust: false,
                        handleAs: 'text'
                    }).response(this, 'putIntoCache').go();
                }
            }, this);
        }
    },
    linkTap: function(inSender, inEvent) {
        inEvent.preventDefault();
        if (this.isInternalLink(inSender.hasNode())) {
            this.setUrl(inSender.attributes.href);
        } else {
            //window.open(inSender.attributes.href);
        }
        return false;
    },
    isInternalLink: function(link) {
        return link.hostname === location.hostname &&
            (link.pathname === location.pathname || link.pathname.substr(-9) !== 'index.php' || location.pathname.substr(-9) !== 'index.php');
    },
    loadingFailed: function() {
        this.$.loadingScreen.hide();
    }
});