(function () {
    'use strict';

    var STYLE_ID = 'deswu-widget-style';

    function injectStyles() {
        if (document.getElementById(STYLE_ID)) return;

        var style = document.createElement('style');
        style.id = STYLE_ID;
        style.textContent = '' +
            '.deswu-hidden{display:none!important}' +
            '.deswu-bubble{position:fixed;bottom:20px;z-index:9999;width:84px;height:84px;border-radius:999px;overflow:hidden;background:#111;box-shadow:0 10px 40px rgba(0,0,0,.25);cursor:pointer}' +
            '.deswu-bubble--right{right:20px}.deswu-bubble--left{left:20px}' +
            '.deswu-thumb{width:100%;height:100%;object-fit:cover;display:block}' +
            '.deswu-play{position:absolute;inset:auto auto 8px 8px;border:none;background:rgba(0,0,0,.65);color:#fff;border-radius:999px;padding:6px 9px;font-size:12px;line-height:1}' +
            '.deswu-modal{position:fixed;inset:0;background:rgba(0,0,0,.72);display:flex;align-items:center;justify-content:center;z-index:10000;padding:16px}' +
            '.deswu-modal-card{width:min(900px,100%);background:#0f0f10;border-radius:18px;padding:12px;box-sizing:border-box}' +
            '.deswu-video{width:100%;max-height:80vh;border-radius:12px;background:#000}' +
            '.deswu-close{float:right;border:none;background:#fff;border-radius:999px;padding:6px 10px;font-size:12px;cursor:pointer}' +
            '.deswu-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px}' +
            '.deswu-grid-item{position:relative;border-radius:14px;overflow:hidden;background:#111;min-height:240px}' +
            '.deswu-grid-item .deswu-thumb{height:100%}' +
            '.deswu-grid-item .deswu-play{inset:auto 8px 8px auto}' +
            '.deswu-grid-name{position:absolute;left:8px;bottom:8px;background:rgba(0,0,0,.55);color:#fff;font-size:12px;padding:4px 8px;border-radius:999px;max-width:70%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}'+            '.deswu-branding{margin-top:10px;font-size:12px;color:#71717a;text-align:right}'+            '.deswu-branding a{color:#111;text-decoration:none;font-weight:600}';

        document.head.appendChild(style);
    }

    function createLazyVideoModal(videoUrl) {
        var modal = document.createElement('div');
        modal.className = 'deswu-modal';

        var card = document.createElement('div');
        card.className = 'deswu-modal-card';

        var close = document.createElement('button');
        close.className = 'deswu-close';
        close.textContent = 'Close';

        var video = document.createElement('video');
        video.className = 'deswu-video';
        video.controls = true;
        video.autoplay = true;
        video.preload = 'none';
        video.loading = 'lazy';

        function cleanupVideo() {
            var sources = video.querySelectorAll('source');
            for (var i = 0; i < sources.length; i += 1) {
                sources[i].src = '';
            }

            video.pause();
            video.removeAttribute('src');
            video.srcObject = null;
            video.load();
        }

        close.addEventListener('click', function () {
            cleanupVideo();
            modal.remove();
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) close.click();
        });

        var source = document.createElement('source');
        source.src = videoUrl;
        source.type = videoUrl.indexOf('.webm') > -1 ? 'video/webm' : 'video/mp4';
        source.loading = 'lazy';
        video.appendChild(source);

        card.appendChild(close);
        card.appendChild(video);
        modal.appendChild(card);
        document.body.appendChild(modal);
    }

    function createThumb(url, alt) {
        var img = document.createElement('img');
        img.className = 'deswu-thumb';
        img.loading = 'lazy';
        img.decoding = 'async';
        img.src = url;
        img.alt = alt || 'Customer video testimonial';

        return img;
    }

    function createPlayButton(onClick) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'deswu-play';
        btn.textContent = '▶';
        btn.addEventListener('click', function (event) {
            event.stopPropagation();
            onClick();
        });

        return btn;
    }

    function renderBubble(container, items, position) {
        if (!items.length) return;

        var active = items[0];
        var bubble = document.createElement('div');
        bubble.className = 'deswu-bubble ' + (position === 'left' ? 'deswu-bubble--left' : 'deswu-bubble--right');
        bubble.appendChild(createThumb(active.thumbnail_url, active.author_name));
        bubble.appendChild(createPlayButton(function () { createLazyVideoModal(active.video_url); }));
        bubble.addEventListener('click', function () { createLazyVideoModal(active.video_url); });

        document.body.appendChild(bubble);
    }

    function renderGrid(container, items) {
        var grid = document.createElement('div');
        grid.className = 'deswu-grid';

        items.forEach(function (item) {
            var card = document.createElement('div');
            card.className = 'deswu-grid-item';
            card.appendChild(createThumb(item.thumbnail_url, item.author_name));
            card.appendChild(createPlayButton(function () { createLazyVideoModal(item.video_url); }));

            if (item.author_name) {
                var name = document.createElement('span');
                name.className = 'deswu-grid-name';
                name.textContent = item.author_name;
                card.appendChild(name);
            }

            card.addEventListener('click', function () { createLazyVideoModal(item.video_url); });
            grid.appendChild(card);
        });

        container.innerHTML = '';
        container.appendChild(grid);
    }


    function appendBranding(container) {
        var brand = document.createElement('div');
        brand.className = 'deswu-branding';
        brand.innerHTML = 'Powered by <a href="https://deswu.co" target="_blank" rel="noopener noreferrer">Deswu</a>';
        container.appendChild(brand);
    }

    function mountWidget(container) {
        var campaignId = container.getAttribute('data-campaign-id');
        if (!campaignId) return;

        var mode = container.getAttribute('data-deswu-widget') || 'grid';
        var position = container.getAttribute('data-position') || 'right';
        var apiBase = container.getAttribute('data-api-base') || window.location.origin + '/api/v1';

        fetch(apiBase.replace(/\/$/, '') + '/widgets/' + encodeURIComponent(campaignId))
            .then(function (response) { return response.json(); })
            .then(function (payload) {
                var data = (payload && payload.data) || {};
                var items = data.testimonials || [];
                var brandingRequired = !!data.branding_required;
                if (!items.length) return;

                if (mode === 'bubble') {
                    renderBubble(container, items, position);
                    if (brandingRequired) appendBranding(container);
                    return;
                }

                renderGrid(container, items);

                if (brandingRequired) {
                    appendBranding(container);
                }
            })
            .catch(function () {
                // silently fail to avoid harming host page UX
            });
    }

    function init() {
        injectStyles();

        var nodes = document.querySelectorAll('[data-campaign-id][data-deswu-widget]');
        if (!nodes.length) return;

        for (var i = 0; i < nodes.length; i += 1) {
            mountWidget(nodes[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
        return;
    }

    init();
})();
