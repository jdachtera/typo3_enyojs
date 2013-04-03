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
                    name: 'content',
                    allowHtml: true
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
        this.$.content.setContent(inResponse);
        this.applyLinkListeners();
    },
    applyLinkListeners: function() {
        var links = this.$.content.hasNode().getElementsByTagName('a');
        enyo.forEach(links, function(link) {
            link.addEventListener('click', enyo.bind(this, 'linkClickHandler', link));
        }, this);
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
    linkClickHandler: function(inSender, inEvent) {
        inEvent.preventDefault();
        if (this.isInternalLink(inEvent.target)) {
            this.setUrl(inSender.href);
        } else {
            //window.open(inSender.href);
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