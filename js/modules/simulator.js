/**
 * ACURIA - Value Engineering Scenario Simulator Module
 * Interatividade do comparador de cenários construtivos e cálculo de impacto financeiro.
 */

export function initSimulator() {
  const scenarioData = {
    'base': {
      label: 'Cenário Base (Referência)',
      costPerM2: 'R$ 4.187',
      totalCost: 'R$ 54,2M',
      reductionPct: '0,0%',
      risk: 'MÉDIO-ALTO',
      systemDescription: 'Estrutura padrão com parede diafragma contínua e alvenaria pesada com argamassa armada tradicional.',
      barHeight: '82%',
      isOptimal: false
    },
    'c01': {
      label: 'Cenário 01 (Alvenaria Racionalizada)',
      costPerM2: 'R$ 4.052',
      totalCost: 'R$ 52,5M',
      reductionPct: '-3,2%',
      risk: 'MÉDIO',
      systemDescription: 'Racionalização de blocos e otimização pontual de contenção sem alteração de fechamentos externos.',
      barHeight: '74%',
      isOptimal: false
    },
    'c02': {
      label: 'Cenário 02 (Recomendado ACURIA)',
      costPerM2: 'R$ 3.877',
      totalCost: 'R$ 51,8M',
      reductionPct: '-7,4% (Economia de R$ 2,4M)',
      risk: 'BAIXO (Mapeado e Mitigado)',
      systemDescription: 'Estacas espaçadas com concreto projetado + Painéis pré-moldados leves integrados com acabamento texturizado.',
      barHeight: '62%',
      isOptimal: true
    },
    'c03': {
      label: 'Cenário 03 (Solução Alternativa Acelerada)',
      costPerM2: 'R$ 4.240',
      totalCost: 'R$ 54,9M',
      reductionPct: '+1,3%',
      risk: 'MÉDIO (Menor Prazo)',
      systemDescription: 'Estrutura metálica mista visando redução de 45 dias de cronograma com custo de material superior.',
      barHeight: '90%',
      isOptimal: false
    }
  };

  const scenarioButtons = document.querySelectorAll('.scenario-btn');
  const barCols = document.querySelectorAll('.bar-col');
  const descEl = document.getElementById('scenario-desc-text');
  const riskBadgeEl = document.getElementById('scenario-risk-badge');
  const resultTitleEl = document.getElementById('scenario-result-title');
  const resultMetricEl = document.getElementById('scenario-result-metric');

  function selectScenario(scenarioKey) {
    const data = scenarioData[scenarioKey];
    if (!data) return;

    // Atualiza botões
    scenarioButtons.forEach(btn => {
      btn.classList.toggle('active', btn.getAttribute('data-scenario') === scenarioKey);
    });

    // Atualiza colunas do gráfico
    barCols.forEach(col => {
      col.classList.toggle('active', col.getAttribute('data-scenario') === scenarioKey);
    });

    // Atualiza descrições de forma segura (textContent)
    if (descEl) descEl.textContent = data.systemDescription;
    if (riskBadgeEl) {
      riskBadgeEl.textContent = `Risco: ${data.risk}`;
      riskBadgeEl.className = data.isOptimal ? 'mono-tag active' : 'mono-tag';
    }
    if (resultTitleEl) resultTitleEl.textContent = data.label;
    if (resultMetricEl) resultMetricEl.textContent = data.reductionPct;
  }

  // Event Listeners nos Botões
  scenarioButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const scKey = btn.getAttribute('data-scenario');
      if (scKey) selectScenario(scKey);
    });
  });

  // Event Listeners nas Barras do Gráfico
  barCols.forEach(col => {
    col.addEventListener('click', () => {
      const scKey = col.getAttribute('data-scenario');
      if (scKey) selectScenario(scKey);
    });
  });

  // Inicializa com o cenário recomendado (c02)
  selectScenario('c02');
}
