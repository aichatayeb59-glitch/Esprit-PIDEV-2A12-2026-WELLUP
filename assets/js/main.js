// Scripts communs WELLUP
(function () {
  // Preloader : disparaît quand la page est chargée
  window.addEventListener('load', function () {
    var p = document.getElementById('preloader');
    if (p) { p.classList.add('hide'); }
  });

  // Menu mobile (front)
  var navBtn = document.querySelector('[data-nav-toggle]');
  var navLinks = document.getElementById('navLinks');
  if (navBtn && navLinks) {
    navBtn.addEventListener('click', function () { navLinks.classList.toggle('open'); });
  }

  // Sidebar (back) : ouvrir / fermer
  var sbBtn = document.querySelector('[data-sidebar-toggle]');
  var overlay = document.querySelector('[data-overlay]');
  if (sbBtn) {
    sbBtn.addEventListener('click', function () {
      if (window.innerWidth <= 900) {
        document.body.classList.toggle('sidebar-open');
      } else {
        document.body.classList.toggle('sidebar-collapsed');
      }
    });
  }
  if (overlay) {
    overlay.addEventListener('click', function () { document.body.classList.remove('sidebar-open'); });
  }

  // Confirmation avant suppression
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!window.confirm(form.getAttribute('data-confirm'))) { e.preventDefault(); }
    });
  });
})();
