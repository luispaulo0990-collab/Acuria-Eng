/**
 * ACURIA - Navigation & Scrollspy Module
 * Gerencia a barra de navegação segmentada, indicador ativo por scroll e menu mobile.
 */

export function initNavigation() {
  const header = document.querySelector('.site-header');
  const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
  const sections = document.querySelectorAll('section[id]');
  const mobileBtn = document.querySelector('.mobile-menu-btn');
  const mobileDrawer = document.querySelector('.mobile-drawer');

  // 1. Header background on scroll
  function handleScroll() {
    if (window.scrollY > 40) {
      header?.classList.add('scrolled');
    } else {
      header?.classList.remove('scrolled');
    }
    updateScrollspy();
  }

  // 2. Scrollspy para segmentações da barra superior
  function updateScrollspy() {
    const scrollPos = window.scrollY + 120;

    sections.forEach(section => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      const id = section.getAttribute('id');

      if (scrollPos >= top && scrollPos < top + height) {
        navLinks.forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === `#${id}`) {
            link.classList.add('active');
          }
        });
      }
    });
  }

  // 3. Smooth Scroll com offset de compensação do header
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href');
      if (targetId && targetId.startsWith('#')) {
        const targetSection = document.querySelector(targetId);
        if (targetSection) {
          e.preventDefault();
          const headerOffset = 80;
          const elementPosition = targetSection.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });

          // Fecha menu mobile se aberto
          if (mobileDrawer?.classList.contains('open')) {
            toggleMobileMenu(false);
          }
        }
      }
    });
  });

  // 4. Toggle Menu Mobile
  function toggleMobileMenu(forceState) {
    const isOpen = typeof forceState === 'boolean' ? forceState : !mobileDrawer?.classList.contains('open');
    mobileBtn?.classList.toggle('open', isOpen);
    mobileDrawer?.classList.toggle('open', isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
  }

  mobileBtn?.addEventListener('click', () => toggleMobileMenu());

  window.addEventListener('scroll', handleScroll, { passive: true });
  updateScrollspy();
}
