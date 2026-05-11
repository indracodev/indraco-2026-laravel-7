// Optimized theme.js
document.addEventListener('DOMContentLoaded', function () {
   const html = document.documentElement;
   const toggles = document.querySelectorAll('.theme-toggle');
   
   // Cache DOM elements that need theme updates
   const themeImages = document.querySelectorAll('.theme-image');
   const carouselInverts = document.querySelectorAll('.carousel-invert');

   /* =========================
      THEME CORE
   ========================== */

   function detectThemeOnce() {
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
   }

   // Read theme forced by server (e.g. brand pages set data-bs-theme="dark" on <html>)
   // We read it BEFORE any JS modifies the attribute.
   const pageForcedTheme = html.getAttribute('data-bs-theme') || null;

   function getTheme() {
      // User's explicit saved preference always wins
      const saved = localStorage.getItem('theme');
      if (saved) return saved;
      // Server-forced page theme (e.g. dark brand page) takes priority over OS default
      if (pageForcedTheme) return pageForcedTheme;
      // Fallback: OS preference
      return detectThemeOnce();
   }

   function setTheme(theme, save = false) {
      html.setAttribute('data-bs-theme', theme);
      if (save) localStorage.setItem('theme', theme);
      updateThemeImages(theme);
      syncInvertedCarousels(theme);
   }

   /* =========================
      CAROUSEL SWITCHER
   ========================== */
   function syncInvertedCarousels(theme) {
      if (!carouselInverts.length) return;
      const inverted = theme === 'dark' ? 'light' : 'dark';
      carouselInverts.forEach(el => {
         if (el.getAttribute('data-bs-theme') !== inverted) {
             el.setAttribute('data-bs-theme', inverted);
         }
      });
   }

   /* =========================
      IMAGE SWITCHER
   ========================== */
   function updateThemeImages(theme) {
      if (!themeImages.length) return;
      themeImages.forEach(img => {
         const src = img.dataset[theme];
         if (src && img.getAttribute('src') !== src) {
            img.setAttribute('src', src);
         }
      });
   }

   /* =========================
      INIT — always run image swap on load
   ========================== */
   const resolvedTheme = getTheme();
   // Always update images even if data-bs-theme already matches (server-rendered pages)
   html.setAttribute('data-bs-theme', resolvedTheme);
   updateThemeImages(resolvedTheme);
   syncInvertedCarousels(resolvedTheme);

   /* =========================
      TOGGLE HANDLERS (MULTI)
   ========================== */
   if (toggles.length) {
      toggles.forEach(toggle => {
         toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const newTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTheme(newTheme, true);
         });
      });
   }
});