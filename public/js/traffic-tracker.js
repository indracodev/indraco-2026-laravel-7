(function() {
    const CONFIG = {
        endpoint: '/admin/traffic/event',
        csrf: document.querySelector('meta[name="csrf-token"]')?.content
    };

    if (!CONFIG.csrf) return;

    // Track Clicks
    document.addEventListener('click', (e) => {
        const target = e.target.closest('a, button, input[type="submit"]');
        if (!target) return;

        const data = {
            event_type: target.dataset.track || 'click',
            element_tag: target.tagName,
            element_id: target.id || null,
            element_text: target.dataset.name || target.innerText?.trim().substring(0, 50) || target.value || null,
            page_path: window.location.pathname,
            _token: CONFIG.csrf
        };

        sendData(data);
    });

    // Track Scroll
    let maxScroll = 0;
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const h = document.documentElement;
            const b = document.body;
            const st = 'scrollTop';
            const sh = 'scrollHeight';
            const percent = Math.round((h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100);
            
            // Only send milestones to reduce requests
            const milestone = [25, 50, 75, 100].find(m => percent >= m && maxScroll < m);
            if (milestone) {
                maxScroll = milestone;
                sendData({
                    event_type: 'scroll',
                    scroll_depth: milestone,
                    page_path: window.location.pathname,
                    _token: CONFIG.csrf
                });
            }
        }, 500);
    });

    function sendData(data) {
        if (navigator.sendBeacon) {
            const formData = new FormData();
            for (const key in data) formData.append(key, data[key]);
            navigator.sendBeacon(CONFIG.endpoint, formData);
        } else {
            // Fallback for older browsers
            fetch(CONFIG.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
                keepalive: true
            });
        }
    }
})();
