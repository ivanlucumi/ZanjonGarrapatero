(() => {
  document.addEventListener('DOMContentLoaded', () => {
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
          // Si el navegador bloquea almacenamiento local, no detiene la navegación.
        }
      });
    });

    try {
      const saved = localStorage.getItem('cczg-mode');
      if (saved) {
        body.dataset.mode = saved;
        buttons.forEach((button) => button.classList.toggle('active', button.dataset.modeButton === saved));
      }
    } catch (_) {
      // Estado inicial luminoso por defecto.
    }

    const mediaModalElement = document.getElementById('mediaModal');
    let mediaModal = null;
    const getMediaModal = () => {
      if (!mediaModal && mediaModalElement && window.bootstrap?.Modal) {
        mediaModal = new bootstrap.Modal(mediaModalElement);
      }
      return mediaModal;
    };

    const mediaImage = document.getElementById('mediaImage');
    const mediaVideo = document.getElementById('mediaVideo');
    const mediaTitle = document.getElementById('mediaModalLabel');

    document.querySelectorAll('.gallery-item img').forEach((image) => {
      image.addEventListener('click', () => {
        if (image.closest('[data-video-src]')) return;
        const modal = getMediaModal();
        if (!modal) return;
        if (mediaVideo) {
          mediaVideo.hidden = true;
          mediaVideo.pause();
        }
        if (mediaImage) {
          mediaImage.hidden = false;
          mediaImage.src = image.currentSrc || image.src;
          mediaImage.alt = image.alt;
        }
        if (mediaTitle) {
          mediaTitle.textContent = image.alt || 'Galería del Consejo';
        }
        modal.show();
      });
    });

    document.querySelectorAll('[data-video-src]').forEach((trigger) => {
      trigger.addEventListener('click', () => {
        const modal = getMediaModal();
        if (!modal) return;
        if (mediaImage) {
          mediaImage.hidden = true;
          mediaImage.removeAttribute('src');
        }
        if (mediaVideo) {
          mediaVideo.hidden = false;
          mediaVideo.src = trigger.dataset.videoSrc;
          mediaVideo.load();
          mediaVideo.play().catch(() => {});
        }
        if (mediaTitle) {
          mediaTitle.textContent = trigger.dataset.mediaTitle || 'Video del Consejo';
        }
        modal.show();
      });
    });

    // Control del Menú Lateral para Móviles (Diseño 2 / Index2)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar-glass');
    const backdrop = document.querySelector('.sidebar-backdrop');
    const closeSidebar = () => {
      sidebar?.classList.remove('show');
      backdrop?.classList.remove('show');
    };

    sidebarToggle?.addEventListener('click', () => {
      sidebar?.classList.toggle('show');
      backdrop?.classList.toggle('show');
    });

    backdrop?.addEventListener('click', closeSidebar);

    // Cerrar sidebar al hacer clic en un enlace (en móvil)
    document.querySelectorAll('.sidebar-link').forEach((link) => {
      link.addEventListener('click', () => {
        if (window.innerWidth < 992) {
          closeSidebar();
        }
      });
    });

    // Proyección de Multimedia (Imágenes y Videos desde carrusel o botones de proyección)
    document.querySelectorAll('.btn-projection, [data-projection-src]').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const modal = getMediaModal();
        if (!modal) return;

        const type = btn.dataset.mediaType || 'image';
        const src = btn.dataset.mediaSrc || btn.dataset.projectionSrc;
        const title = btn.dataset.mediaTitle || 'Proyección Comunitaria';

        if (type === 'video') {
          if (mediaImage) {
            mediaImage.hidden = true;
            mediaImage.removeAttribute('src');
          }
          if (mediaVideo) {
            mediaVideo.hidden = false;
            mediaVideo.src = src;
            mediaVideo.load();
            mediaVideo.play().catch(() => {});
          }
        } else {
          if (mediaVideo) {
            mediaVideo.hidden = true;
            mediaVideo.pause();
            mediaVideo.removeAttribute('src');
          }
          if (mediaImage) {
            mediaImage.hidden = false;
            mediaImage.src = src;
            mediaImage.alt = title;
          }
        }

        if (mediaTitle) {
          mediaTitle.textContent = title;
        }

        modal.show();
      });
    });

    // Coordinación de reproducción de videos de fondo en el carrusel
    const heroCarousel = document.getElementById('inicioCarousel');
    if (heroCarousel) {
      const playActiveVideo = () => {
        const activeItem = heroCarousel.querySelector('.carousel-item.active');
        const video = activeItem?.querySelector('video.carousel-media');
        if (video) {
          video.play().catch(() => {});
        }
      };

      heroCarousel.addEventListener('slide.bs.carousel', (e) => {
        const leavingItem = heroCarousel.querySelectorAll('.carousel-item')[e.from];
        const leavingVideo = leavingItem?.querySelector('video.carousel-media');
        if (leavingVideo) {
          leavingVideo.pause();
        }

        const enteringVideo = e.relatedTarget?.querySelector('video.carousel-media');
        if (enteringVideo) {
          enteringVideo.play().catch(() => {});
        }
      });

      // Asegurar reproducción inicial si el primer slide es un video
      playActiveVideo();
    }

    mediaModalElement?.addEventListener('hidden.bs.modal', () => {
      if (mediaVideo) {
        mediaVideo.pause();
        mediaVideo.removeAttribute('src');
      }
      if (mediaImage) {
        mediaImage.removeAttribute('src');
      }
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
  });
})();
