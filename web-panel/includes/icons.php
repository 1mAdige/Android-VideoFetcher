<?php
/**
 * CDN bagimliligi olmadan gosterilecek inline SVG ikonlar
 */
function panel_icon_user() {
    return '<svg class="panel-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.87 0-7 2.13-7 4.76V21h14v-2.24C19 16.13 15.87 14 12 14z"/></svg>';
}

function panel_icon_lock() {
    return '<svg class="panel-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2zm-3 0H10V6a2 2 0 1 1 4 0v2z"/></svg>';
}

function panel_icon_logout() {
    return '<svg class="panel-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M10 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h5v-2H5V5h5V3zm11 7-4-4v3H9v2h8v3l4-4z"/></svg>';
}

function panel_icon_login() {
    return '<svg class="panel-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 10H12v2h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-8v2h8v12z"/></svg>';
}

function panel_icon_globe() {
    return '<svg class="panel-svg-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm7.93 9h-3.18a15.7 15.7 0 0 0-1.1-4.02A8.03 8.03 0 0 1 19.93 11zM12 4c.95 1.6 1.63 3.4 1.93 5.36H10.07C10.37 7.4 11.05 5.6 12 4zM8.35 6.98A15.7 15.7 0 0 0 7.25 11H4.07a8.03 8.03 0 0 1 4.28-4.02zM4.07 13h3.18c.22 1.45.62 2.8 1.1 4.02A8.03 8.03 0 0 1 4.07 13zm7.93 7c-.95-1.6-1.63-3.4-1.93-5.36h3.86C13.63 16.6 12.95 18.4 12 20zm3.58-2.02c.48-1.22.88-2.57 1.1-4.02h3.18a8.03 8.03 0 0 1-4.28 4.02z"/></svg>';
}

function panel_icon_inline($svg) {
    return '<span class="panel-inline-icon">' . $svg . '</span>';
}
