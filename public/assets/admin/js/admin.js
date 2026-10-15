(function () {
  'use strict';

  var root = document.documentElement;

  /* ---------- theme toggle (light / glass dark) ---------- */
  var themeBtn = document.getElementById('themeBtn');

  function paintThemeIcon() {
    if (!themeBtn) { return; }
    var icon = root.dataset.theme === 'glass' ? 'bi-sun' : 'bi-moon-stars';
    themeBtn.innerHTML = '<i class="bi ' + icon + '" aria-hidden="true"></i>';
  }

  if (themeBtn) {
    paintThemeIcon();
    themeBtn.addEventListener('click', function () {
      root.dataset.theme = root.dataset.theme === 'glass' ? 'light' : 'glass';
      try { localStorage.setItem('theme', root.dataset.theme); } catch (e) { /* storage blocked */ }
      paintThemeIcon();
    });
  }

  /* ---------- toast ---------- */
  function toast(message, type) {
    var box = document.getElementById('toasts');
    if (!box || !message) { return; }

    var el = document.createElement('div');
    el.className = 'toast' + (type === 'err' ? ' err' : '');

    var icon = document.createElement('i');
    icon.className = 'bi bi-' + (type === 'err' ? 'exclamation-circle' : 'check-circle');
    icon.setAttribute('aria-hidden', 'true');

    var text = document.createElement('span');
    text.textContent = message;

    el.appendChild(icon);
    el.appendChild(text);
    box.appendChild(el);
    setTimeout(function () { el.remove(); }, 3600);
  }

  window.PromonAdmin = { toast: toast };

  // Works with Livewire 3: $this->dispatch('toast', message: 'Saved', type: 'ok');
  window.addEventListener('toast', function (event) {
    var detail = event.detail || {};
    if (Array.isArray(detail)) { detail = detail[0] || {}; }
    toast(detail.message, detail.type);
  });

  // Server flash message: <div data-flash="Saved" hidden></div>
  var flash = document.querySelector('[data-flash]');
  if (flash) { toast(flash.getAttribute('data-flash'), flash.getAttribute('data-flash-type') || 'ok'); }

  /* ---------- show / hide password ---------- */
  Array.prototype.forEach.call(document.querySelectorAll('[data-toggle-password]'), function (button) {
    button.addEventListener('click', function () {
      var input = document.getElementById(button.getAttribute('data-toggle-password'));
      if (!input) { return; }
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.setAttribute('aria-pressed', show ? 'true' : 'false');
      button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      button.innerHTML = '<i class="bi ' + (show ? 'bi-eye-slash' : 'bi-eye') + '" aria-hidden="true"></i>';
    });
  });

  /* ---------- stop double submit ---------- */
  Array.prototype.forEach.call(document.querySelectorAll('form[data-lock]'), function (form) {
    form.addEventListener('submit', function () {
      setTimeout(function () {
        Array.prototype.forEach.call(form.querySelectorAll('button[type=submit]'), function (b) { b.disabled = true; });
      }, 0);
    });
  });

  // Browser back button restores the page from cache: make buttons usable again.
  window.addEventListener('pageshow', function (event) {
    if (!event.persisted) { return; }
    Array.prototype.forEach.call(document.querySelectorAll('form[data-lock] button[type=submit]'), function (b) { b.disabled = false; });
  });
})();
