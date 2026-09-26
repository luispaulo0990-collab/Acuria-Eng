/**
 * ACURIA - Secure Form Handler Module (Security-First)
 * Tratamento seguro de dados, sanitização anti-XSS, validação client-side e feedback ao usuário.
 */

export function initFormHandler() {
  const form = document.getElementById('contact-form');
  const modalBackdrop = document.getElementById('secure-modal');
  const modalCloseBtn = document.getElementById('modal-close-btn');
  const modalSummary = document.getElementById('modal-lead-summary');

  if (!form) return;

  /**
   * Sanitizador básico de caracteres perigosos para prevenção de injeção
   */
  function sanitizeInput(str) {
    if (typeof str !== 'string') return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;')
      .trim();
  }

  /**
   * Validação de e-mail corporativo
   */
  function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email);
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const nameInput = form.querySelector('#lead-name');
    const companyInput = form.querySelector('#lead-company');
    const emailInput = form.querySelector('#lead-email');
    const lineSelect = form.querySelector('#lead-line');
    const notesInput = form.querySelector('#lead-notes');
    const submitBtn = form.querySelector('button[type="submit"]');

    const cleanName = sanitizeInput(nameInput?.value);
    const cleanCompany = sanitizeInput(companyInput?.value);
    const cleanEmail = sanitizeInput(emailInput?.value);
    const cleanLine = sanitizeInput(lineSelect?.value);
    const cleanNotes = sanitizeInput(notesInput?.value);

    // Validações
    if (!cleanName || cleanName.length < 3) {
      alert('Por favor, informe um nome válido com ao menos 3 caracteres.');
      nameInput?.focus();
      return;
    }

    if (!cleanEmail || !isValidEmail(cleanEmail)) {
      alert('Por favor, informe um e-mail corporativo válido.');
      emailInput?.focus();
      return;
    }

    if (!cleanCompany || cleanCompany.length < 2) {
      alert('Por favor, informe o nome da sua empresa / incorporadora.');
      companyInput?.focus();
      return;
    }

    // Feedback de carregamento no botão
    if (submitBtn) {
      const originalText = submitBtn.textContent;
      submitBtn.textContent = 'Processando com Segurança...';
      submitBtn.disabled = true;

      setTimeout(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;

        // Renderiza mensagem no modal sem innerHTML perigoso
        if (modalSummary) {
          modalSummary.textContent = `Recebemos a solicitação de diagnóstico para a empresa ${cleanCompany} (${cleanLine}). Nossa equipe de inteligência de custos em São Paulo entrará em contato pelo e-mail ${cleanEmail} em até 5 dias úteis.`;
        }

        // Abre modal de confirmação
        modalBackdrop?.classList.add('active');
        form.reset();
      }, 600);
    }
  });

  // Fechamento do Modal
  modalCloseBtn?.addEventListener('click', () => {
    modalBackdrop?.classList.remove('active');
  });

  modalBackdrop?.addEventListener('click', (e) => {
    if (e.target === modalBackdrop) {
      modalBackdrop.classList.remove('active');
    }
  });
}
