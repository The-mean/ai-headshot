(function () {
    'use strict';

    var STYLE_ID = 'deswu-widget-style';

    function injectStyles() {
        if (document.getElementById(STYLE_ID)) return;

        var style = document.createElement('style');
        style.id = STYLE_ID;
        style.textContent = '' +
            '.deswu-hidden{display:none!important}' +
            '.deswu-bubble{position:fixed;bottom:20px;z-index:9999;width:84px;height:84px;border-radius:999px;overflow:hidden;background:#111;box-shadow:0 10px 40px rgba(0,0,0,.25);cursor:pointer;transition:transform .2s ease}' +
            '.deswu-bubble:hover{transform:translateY(-2px)}' +
            '.deswu-bubble--right{right:20px}.deswu-bubble--left{left:20px}' +
            '.deswu-thumb{width:100%;height:100%;object-fit:cover;display:block}' +
            '.deswu-play{position:absolute;inset:auto auto 8px 8px;border:none;background:var(--deswu-primary,#111827);color:#fff;border-radius:999px;padding:6px 9px;font-size:12px;line-height:1;cursor:pointer}' +
            '.deswu-modal{position:fixed;inset:0;background:rgba(0,0,0,.72);display:flex;align-items:center;justify-content:center;z-index:10000;padding:16px;backdrop-filter:blur(5px)}' +
            '.deswu-modal-card{width:min(900px,100%);background:#0f0f10;border-radius:18px;padding:12px;box-sizing:border-box;border:1px solid rgba(255,255,255,.08)}' +
            '.deswu-video{width:100%;max-height:70vh;border-radius:12px;background:#000}' +
            '.deswu-modal-actions{display:flex;justify-content:space-between;gap:10px;margin-top:10px}' +
            '.deswu-cta{display:inline-flex;align-items:center;justify-content:center;background:var(--deswu-primary,#111827);color:#fff;border-radius:10px;padding:9px 14px;font-size:13px;font-weight:600;text-decoration:none}' +
            '.deswu-close{border:1px solid #3f3f46;background:transparent;color:#fff;border-radius:10px;padding:8px 12px;font-size:12px;cursor:pointer}' +
            '.deswu-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px}' +
            '.deswu-grid-item{position:relative;border-radius:var(--deswu-radius,14px);overflow:hidden;background:#111;min-height:240px;box-shadow:0 10px 24px rgba(0,0,0,.14);transition:transform .2s ease}' +
            '.deswu-grid-item:hover{transform:translateY(-2px)}' +
            '.deswu-grid-item .deswu-thumb{height:100%}' +
            '.deswu-grid-item .deswu-play{inset:auto 8px 8px auto}' +
            '.deswu-grid-name{position:absolute;left:8px;bottom:8px;background:rgba(0,0,0,.55);color:#fff;font-size:12px;padding:4px 8px;border-radius:999px;max-width:70%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}' +
            '.deswu-branding{margin-top:10px;font-size:12px;color:#71717a;text-align:right}' +
            '.deswu-branding a{color:#111;text-decoration:none;font-weight:600}';

        document.head.appendChild(style);
    }

    function createLazyVideoModal(items, startIndex, campaignUrl, theme) {
        if (!items.length) return;

        var modal = document.createElement('div');
        modal.className = 'deswu-modal';
        modal.style.setProperty('--deswu-primary', theme.primaryColor);

        var card = document.createElement('div');
        card.className = 'deswu-modal-card';

        var video = document.createElement('video');
        video.className = 'deswu-video';
        video.controls = true;
        video.autoplay = true;
        video.preload = 'none';
        video.loading = 'lazy';

        var actions = document.createElement('div');
        actions.className = 'deswu-modal-actions';

        var cta = document.createElement('a');
        cta.className = 'deswu-cta';
        cta.href = campaignUrl;
        cta.target = '_blank';
        cta.rel = 'noopener noreferrer';
        cta.textContent = 'Ürünü İncele';

        var close = document.createElement('button');
        close.className = 'deswu-close';
        close.textContent = 'Close';

        var currentIndex = startIndex;

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

        function setVideo(index) {
            cleanupVideo();

            var selected = items[index];
            if (!selected) return;

            var source = document.createElement('source');
            source.src = selected.video_url;
            source.type = selected.video_url.indexOf('.webm') > -1 ? 'video/webm' : 'video/mp4';
            source.loading = 'lazy';
            video.appendChild(source);

            video.play().catch(function () {
                // user gesture policies may block autoplay
            });
        }

        video.addEventListener('ended', function () {
            if (!items.length) return;
            currentIndex = (currentIndex + 1) % items.length;
            setVideo(currentIndex);
        });

        close.addEventListener('click', function () {
            cleanupVideo();
            modal.remove();
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) close.click();
        });

        setVideo(currentIndex);

        actions.appendChild(cta);
        actions.appendChild(close);
        card.appendChild(video);
        card.appendChild(actions);
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

    function renderBubble(container, items, position, campaignUrl, theme) {
        if (!items.length) return;

        var active = items[0];
        var bubble = document.createElement('div');
        var left = position === 'bottom_left' || position === 'left';

        bubble.className = 'deswu-bubble ' + (left ? 'deswu-bubble--left' : 'deswu-bubble--right');
        bubble.style.setProperty('--deswu-primary', theme.primaryColor);

        bubble.appendChild(createThumb(active.thumbnail_url, active.author_name));
        bubble.appendChild(createPlayButton(function () { createLazyVideoModal(items, 0, campaignUrl, theme); }));
        bubble.addEventListener('click', function () { createLazyVideoModal(items, 0, campaignUrl, theme); });

        document.body.appendChild(bubble);
    }

    function renderGrid(container, items, campaignUrl, theme) {
        var grid = document.createElement('div');
        grid.className = 'deswu-grid';
        grid.style.setProperty('--deswu-primary', theme.primaryColor);
        grid.style.setProperty('--deswu-radius', theme.borderRadius + 'px');

        items.forEach(function (item, index) {
            var card = document.createElement('div');
            card.className = 'deswu-grid-item';
            card.appendChild(createThumb(item.thumbnail_url, item.author_name));
            card.appendChild(createPlayButton(function () { createLazyVideoModal(items, index, campaignUrl, theme); }));

            if (item.author_name) {
                var name = document.createElement('span');
                name.className = 'deswu-grid-name';
                name.textContent = item.author_name;
                card.appendChild(name);
            }

            card.addEventListener('click', function () { createLazyVideoModal(items, index, campaignUrl, theme); });
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
        var position = container.getAttribute('data-position') || 'bottom_right';
        var apiBase = container.getAttribute('data-api-base') || window.location.origin + '/api/v1';
        var campaignUrl = container.getAttribute('data-campaign-url') || window.location.href;
        var theme = {
            primaryColor: container.getAttribute('data-primary-color') || '#111827',
            borderRadius: Number(container.getAttribute('data-border-radius') || 14),
        };

        fetch(apiBase.replace(/\/$/, '') + '/widgets/' + encodeURIComponent(campaignId))
            .then(function (response) { return response.json(); })
            .then(function (payload) {
                var data = (payload && payload.data) || {};
                var items = data.testimonials || [];
                var brandingRequired = !!data.branding_required;
                if (!items.length) return;

                if (mode === 'bubble') {
                    renderBubble(container, items, position, campaignUrl, theme);
                    if (brandingRequired) appendBranding(container);
                    return;
                }

                renderGrid(container, items, campaignUrl, theme);

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
