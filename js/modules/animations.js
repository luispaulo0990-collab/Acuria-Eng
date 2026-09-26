/**
 * ACURIA - Subtle Animations & Scroll Reveal Module
 * Animações sutis e fluidas com IntersectionObserver respeitando prefers-reduced-motion.
 */

export function initAnimations() {
  const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (isReducedMotion) return;

  const revealElements = document.querySelectorAll(
    '.method-step-card, .product-card, .comparison-card, .timeline-step, .line-card, .model-card'
  );

  const observerOptions = {
    root: null,
    rootMargin: '0px 0px -60px 0px',
    threshold: 0.12
  };

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        obs.unobserve(entry.target);
      }
    });
  }, observerOptions);

  revealElements.forEach((el, index) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = `opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1) ${(index % 4) * 0.08}s, transform 0.5s cubic-bezier(0.16, 1, 0.3, 1) ${(index % 4) * 0.08}s`;
    observer.observe(el);
  });
}
