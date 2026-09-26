# ACURIA | Inteligência de Custos para Decisões Imobiliárias

Plataforma institucional e boutique de inteligência de custos, engenharia de valor e planejamento estratégico para incorporadoras, desenvolvedores e investidores imobiliários na praça de São Paulo.

---

## 🏛️ Sobre a Marca & Conceito

> **ACURIA** *(do latim accurātus → acurácia)*: Grau de conformidade matemática e exatidão absoluta. No desenvolvimento imobiliário, representa a eliminação de desvios orçamentários através de quantificação cirúrgica, banco paramétrico próprio e leitura de mercado antes do primeiro metro cúbico concretado na obra.

---

## 🎨 Versões Disponíveis para Comparação

O projeto conta com duas direções visuais completas, independentes e comparáveis através de alternador rápido no cabeçalho:

1. **Versão Dark / Blueprint Técnico (`index.html`)**:
   - Estética inspirada em plantas técnicas, precisão cartesiana e grid estrutural escuro (`#0A111E`).
   - Acentos em Verde Esmeralda Técnico (`#0D9488` / `#5EEAD4`) e tipografia de engenharia.
   - Folhas de estilo: pasta `css/`.

2. **Versão Light / Soft Modern & Editorial Luxury (`index-light.html`)**:
   - Estética suave, moderna e arejada com fundo porcelana (`#F8FAFC`), superfícies brancas com sombras ambientes difusas e alta legibilidade.
   - Cartões com arquitetura *double-bezel*, tipografia nobre e contraste com *Deep Slate Navy* (`#0F172A`).
   - Folhas de estilo: pasta `css-light/`.

---

## 📐 Estrutura de Seções

1. **Sobre Nós (Origem & Acurácia)** — Definição etimológica, manifesto técnico, 3 pilares de acurácia e selos de governança.
2. **Posicionamento** — A pergunta do incorporador (*"Esse empreendimento fecha e onde está o risco?"*) e comparativo espelhado com o mercado convencional.
3. **Método Proprietário CUSTO 360°** — 7 etapas sequenciais (Projeto, Quantitativo, Banco de Custos, Mercado, Benchmarking, Cenários, Decisão).
4. **Portfólio de Soluções** — Grid balanceado 2 + 1 + 2 com destaque central para `ACURIA | DECIDE` (Carro-Chefe) e soluções de ciclo de vida (`ESTIMA`, `ORÇA`, `CONTROL`, `DATA`).
5. **Engenharia de Valor & Simulador Interativo** — Calculadora dinâmica de cenários alternativos (Base, Alvenaria Racional, Otimizado ACURIA, Misto Acelerado) e estudo de caso consolidado.
6. **Linhas Atendidas & Formatos Comerciais** — Residencial, HIS/HMP, Médio/Alto Padrão, Loteamentos e modelos *Spot*, *Pipeline Retainer* e *Success Fee*.
7. **Como Trabalhamos** — Linha do tempo de engajamento em 5 semanas (Imersão, Quantificação, Cenários, Decisão) e entregáveis sob NDA.
8. **Diagnóstico Piloto & Contato** — Formulário seguro com validação e retorno em até 5 dias úteis.

---

## 🛠️ Tecnologias & Arquitetura

- **HTML5 Semântico**: Acessível, estruturado e otimizado para SEO & Open Graph.
- **CSS3 Modular**:
  - `variables.css` — Tokens de cor, tipografia e espaçamentos.
  - `base.css` — Reset moderno e padrões de fundo.
  - `layout.css` — Header flutuante, grid container e footer.
  - `components.css` — Cards, simulador, tabelas e formulários.
  - `responsive.css` — Breakpoints fluidos para mobile, tablet e desktop.
- **JavaScript Vanilla ES6+**:
  - `navigation.js` — Scrollspy com indicador ativo e gaveta mobile.
  - `simulator.js` — Motor de cálculo de cenários e atualização reativa do gráfico.
  - `form.js` — Validação e sanitização segura de entradas.
  - `animations.js` — Intersection Observer para revelação suave de elementos.

---

## 🚀 Como Executar Localmente

Como o projeto é construído em padrões nativos (Vanilla HTML/CSS/JS), basta abrir qualquer um dos arquivos HTML diretamente no navegador:

- **Versão Dark**: Abra `index.html`
- **Versão Light**: Abra `index-light.html`

Ou utilize qualquer servidor local:
```bash
# Com Python 3
python -m http.server 8000

# Com Node / npx
npx serve .
```
Acesse `http://localhost:8000` no seu navegador.
