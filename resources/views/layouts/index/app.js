(() => {
  const body = document.body;
  const buttons = document.querySelectorAll('[data-mode-button]');

  buttons.forEach((button) => {
    button.addEventListener('click', () => {
      const mode = button.dataset.modeButton;
      body.dataset.mode = mode;
      buttons.forEach((item) => item.classList.toggle('active', item === button));
      try {
        localStorage.setItem('cczg-mode', mode);
      } catch (_) {
        // La experiencia sigue funcionando si el navegador bloquea almacenamiento local.
      }
    });
  });

  try {
    const saved = localStorage.getItem('cczg-mode');
    if (saved === 'origin' || saved === 'editorial') {
      body.dataset.mode = saved;
      buttons.forEach((button) => button.classList.toggle('active', button.dataset.modeButton === saved));
    }
  } catch (_) {
    // Estado inicial seguro: diseño Origen.
  }

  const mediaModalElement = document.getElementById('mediaModal');
  const mediaModal = mediaModalElement && window.bootstrap ? new bootstrap.Modal(mediaModalElement) : null;
  const mediaImage = document.getElementById('mediaImage');
  const mediaVideo = document.getElementById('mediaVideo');
  const mediaTitle = document.getElementById('mediaModalLabel');

  document.querySelectorAll('.gallery-item img').forEach((image) => {
    image.addEventListener('click', () => {
      if (image.closest('[data-video-src]')) return;
      if (!mediaModal) return;
      mediaVideo.hidden = true;
      mediaVideo.pause();
      mediaImage.hidden = false;
      mediaImage.src = image.currentSrc || image.src;
      mediaImage.alt = image.alt;
      mediaTitle.textContent = image.alt || 'Galería del Consejo';
      mediaModal.show();
    });
  });

  document.querySelectorAll('[data-video-src]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      if (!mediaModal) return;
      mediaImage.hidden = true;
      mediaImage.removeAttribute('src');
      mediaVideo.hidden = false;
      mediaVideo.src = trigger.dataset.videoSrc;
      mediaVideo.load();
      mediaTitle.textContent = trigger.dataset.mediaTitle || 'Video del Consejo';
      mediaModal.show();
      mediaVideo.play().catch(() => {});
    });
  });

  mediaModalElement?.addEventListener('hidden.bs.modal', () => {
    mediaVideo.pause();
    mediaVideo.removeAttribute('src');
    mediaImage.removeAttribute('src');
  });

  const parallax = document.querySelector('[data-parallax]');
  if (parallax && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const updateParallax = () => {
      const rect = parallax.getBoundingClientRect();
      const offset = (window.innerHeight / 2 - (rect.top + rect.height / 2)) * Number(parallax.dataset.parallax || 0.12);
      parallax.style.setProperty('--parallax-y', `${offset}px`);
    };
    window.addEventListener('scroll', updateParallax, { passive: true });
    updateParallax();
  }
})();
