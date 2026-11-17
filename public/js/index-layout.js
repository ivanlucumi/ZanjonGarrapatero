(() => {
  document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    try {
      localStorage.removeItem('cczg-mode');
      delete body.dataset.mode;
    } catch (_) {
      // Limpieza silenciosa de modo oscuro anterior.
    }

    // Sincronización dinámica de los 3 colores principales desde theme-colors.css
    try {
      const rootStyles = getComputedStyle(document.documentElement);
      const primaryHex = rootStyles.getPropertyValue('--brand-primary').trim();
      const secondaryHex = rootStyles.getPropertyValue('--brand-secondary').trim();
      const accentHex = rootStyles.getPropertyValue('--brand-accent').trim();

      if (primaryHex) {
        document.querySelectorAll('.triad-val-primary').forEach((el) => {
          el.textContent = primaryHex.toUpperCase();
        });
      }
      if (secondaryHex) {
        document.querySelectorAll('.triad-val-secondary').forEach((el) => {
          el.textContent = secondaryHex.toUpperCase();
        });
      }
      if (accentHex) {
        document.querySelectorAll('.triad-val-accent').forEach((el) => {
          el.textContent = accentHex.toUpperCase();
        });
      }
    } catch (_) {
      // Fallback silencioso si no se computan estilos.
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

    // Coordinación de reproducción de videos de fondo y ciclo continuo suave
    const heroCarouselElement = document.getElementById('inicioCarousel');
    if (heroCarouselElement) {
      if (window.bootstrap?.Carousel) {
        bootstrap.Carousel.getOrCreateInstance(heroCarouselElement, {
          interval: 6500,
          ride: 'carousel',
          pause: 'hover',
          wrap: true,
          touch: true,
        });
      }

      const playActiveVideo = () => {
        const activeItem = heroCarouselElement.querySelector('.carousel-item.active');
        const video = activeItem?.querySelector('video.carousel-media');
        if (video) {
          video.play().catch(() => {});
        }
      };

      heroCarouselElement.addEventListener('slide.bs.carousel', (e) => {
        // Al comenzar la transición suave, iniciar reproducción del video entrante
        const enteringVideo = e.relatedTarget?.querySelector('video.carousel-media');
        if (enteringVideo) {
          try {
            enteringVideo.currentTime = 0;
          } catch (_) {}
          enteringVideo.play().catch(() => {});
        }
      });

      heroCarouselElement.addEventListener('slid.bs.carousel', () => {
        // Al terminar el fundido, pausar los videos de slides inactivos para liberar recursos
        heroCarouselElement.querySelectorAll('.carousel-item:not(.active) video.carousel-media').forEach((video) => {
          video.pause();
        });
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

    // ========================================================
    // Transición y Animación Suave de Cards al Scroll (Scroll Reveal)
    // ========================================================
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && 'IntersectionObserver' in window) {
      const cardSelectors = [
        '.glass-card',
        '.territory',
        '.activity-card',
        '.news-card',
        '.pillar',
        '.gallery-item',
        '.login-hero-card',
        '.video-card',
        '.stat',
        '.timeline-card'
      ];

      const cards = document.querySelectorAll(cardSelectors.join(', '));

      if (cards.length > 0) {
        const cardObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('scroll-card-in');
              observer.unobserve(entry.target);
            }
          });
        }, {
          threshold: 0.08,
          rootMargin: '0px 0px -40px 0px'
        });

        cards.forEach((card) => {
          card.classList.add('scroll-card-reveal');

          // Escalonamiento suave (stagger) para tarjetas dentro del mismo contenedor
          if (card.parentNode && card.parentNode.children.length > 1) {
            const index = Array.from(card.parentNode.children).indexOf(card);
            if (index > 0 && index < 8) {
              card.style.transitionDelay = `${index * 0.07}s`;
            }
          }

          // Si el elemento ya está dentro del viewport inicial, mostrarlo inmediatamente
          const rect = card.getBoundingClientRect();
          if (rect.top < window.innerHeight && rect.bottom > 0) {
            card.classList.add('scroll-card-in');
          } else {
            cardObserver.observe(card);
          }
        });
      }
    }

    // Efecto sutil de elevación y blur en navbar al hacer scroll
    const navbar = document.querySelector('.navbar');
    if (navbar) {
      const checkNavbarScroll = () => {
        if (window.scrollY > 25) {
          navbar.classList.add('navbar-scrolled');
        } else {
          navbar.classList.remove('navbar-scrolled');
        }
      };
      window.addEventListener('scroll', checkNavbarScroll, { passive: true });
      checkNavbarScroll();
    }

    // Efecto sutil de inclinación 3D táctil en la tarjeta orbital (Desktop)
    const orbitCard = document.querySelector('.orbit-card');
    if (orbitCard && window.innerWidth >= 992 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      const orbitalWrap = orbitCard.closest('.orbital');
      orbitalWrap?.addEventListener('mousemove', (e) => {
        const rect = orbitalWrap.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;
        orbitCard.style.transform = `rotateY(${x * 18 - 9}deg) rotateX(${-y * 18 + 5}deg) scale(1.02)`;
      });
      orbitalWrap?.addEventListener('mouseleave', () => {
        orbitCard.style.transform = 'rotateY(-9deg) rotateX(5deg) scale(1)';
      });
    }
  });
})();
