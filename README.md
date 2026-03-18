# 🚗 Grupo Automotivo Prost — Website Corporativo

<div align="center">

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Font Awesome](https://img.shields.io/badge/Font_Awesome-339AF0?style=for-the-badge&logo=fontawesome&logoColor=white)
![Google Fonts](https://img.shields.io/badge/Google_Fonts-4285F4?style=for-the-badge&logo=google&logoColor=white)

**Site corporativo premium para o Grupo Automotivo Prost — referência em Mecânica, Funilaria e Blindagem em São Paulo.**

[🌐 Ver Site](#) · [📋 Proposta Técnica](#documentação) · [🐛 Reportar Bug](../../issues) · [✨ Sugerir Melhoria](../../issues)

</div>

---

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Páginas](#-páginas)
- [Design System](#-design-system)
- [Funcionalidades](#-funcionalidades)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Como Usar](#-como-usar)
- [SEO e Performance](#-seo-e-performance)
- [Responsividade](#-responsividade)
- [Tecnologias](#-tecnologias)
- [Contato](#-contato)

---

## 🏢 Sobre o Projeto

O **Grupo Automotivo Prost** é uma referência no segmento automotivo premium em São Paulo, com mais de **30 anos de experiência** e **3 unidades especializadas**. Este repositório contém o código-fonte completo do site corporativo, desenvolvido do zero com foco em:

- **Alta conversão** — CTAs estratégicos, WhatsApp direto por unidade
- **Design premium** — identidade visual luxury/industrial com paleta preto e dourado
- **Performance** — HTML/CSS/JS puro, sem frameworks pesados
- **SEO técnico** — Schema.org, Open Graph, meta tags otimizadas
- **Responsividade total** — mobile-first, testado em todos os breakpoints

> *"Showroom da blindagem profissional, tecnologia Secforce, equipe altamente qualificada e equipamentos de última geração, aliados a uma localização privilegiada — a combinação perfeita para manter seu veículo sempre em dia com total conveniência e tranquilidade."*

---

## 📄 Páginas

| Arquivo | Página | Descrição | Tamanho |
|---------|--------|-----------|---------|
| `index.html` | **Home** | Página principal com hero, serviços, unidades e depoimentos | ~70KB |
| `motor.html` | **Mecânica** | Serviços de motor: revisão, diagnóstico, injeção eletrônica | ~54KB |
| `funilaria.html` | **Funilaria & Pintura** | Lataria, pintura em cabine, restauração e estética | ~47KB |
| `blindagem.html` | **Blindagem** | Showroom Secforce, níveis III-A ao IV, equipe e equipamentos | ~74KB |
| `contato.html` | **Contato & Carreiras** | Formulário de contato, mapa e candidaturas de emprego | ~52KB |

### Mapa de Navegação

```
📁 Grupo Automotivo Prost
│
├── 🏠 Home (index.html)
│   ├── Hero Section
│   ├── Sobre o Grupo
│   ├── Nossos Serviços (9 cards)
│   ├── Nossas Unidades ──────────── → blindagem.html
│   │                  ──────────── → motor.html
│   │                  ──────────── → funilaria.html
│   ├── Depoimentos
│   ├── Formulário de Contato
│   └── Newsletter
│
├── ⚙️  Mecânica (motor.html)
│   ├── Revisão de Motor
│   ├── Troca de Óleo e Filtros
│   ├── Diagnóstico Eletrônico
│   ├── Sistema de Arrefecimento
│   └── Injeção Eletrônica
│
├── 🎨 Funilaria (funilaria.html)
│   ├── Funilaria e Pintura
│   ├── Restauração de Clássicos
│   ├── Estética Automotiva
│   └── Cabine de Pintura Profissional
│
├── 🛡️  Blindagem (blindagem.html)
│   ├── Tecnologia Secforce
│   ├── Showroom Pinheiros
│   ├── Níveis III-A, III, IV e VIP
│   ├── Equipe Especializada
│   └── Equipamentos de Última Geração
│
└── 📞 Contato (contato.html)
    ├── Formulário de Agendamento
    ├── Mapa das 3 Unidades
    └── Trabalhe Conosco (Vagas + Candidatura)
```

---

## 🎨 Design System

### Paleta de Cores

| Token | Hex | Uso |
|-------|-----|-----|
| `--gold` | `#C9A84C` | Destaque principal — CTAs, ícones, bordas |
| `--gold-light` | `#E8C97A` | Hover de botões dourados |
| `--black` | `#050505` | Background principal |
| `--dark` | `#0E0E0E` | Background secundário |
| `--dark2` | `#161616` | Cards e seções alternadas |
| `--dark3` | `#1F1F1F` | Hover de cards |
| `--gray` | `#888888` | Textos secundários e nav |
| `--light` | `#E8E8E8` | Textos sobre fundo escuro |
| `--white` | `#FFFFFF` | Títulos e destaques máximos |

### Tipografia

| Papel | Fonte | Características |
|-------|-------|----------------|
| Display / Títulos | **Bebas Neue** | Impacto automotivo, lettering bold |
| Corpo e Interface | **Barlow** (300–700) | Legibilidade e modernidade |
| Labels e Nav | **Barlow Condensed** (600–700) | `letter-spacing: 0.1–0.2em` |

### Estilo Visual

O projeto segue a estética **Luxury Industrial** — escuro, metálico e premium, sem exageros. Elementos característicos:

- Noise texture overlay sutil via SVG inline
- Bordas douradas com animação de expansão no hover
- Cards com `translateY` e borda lateral dourada
- Animações `fadeUp` staggeradas no carregamento
- Scroll reveal com `IntersectionObserver`
- Grid de 2px entre cards (efeito "serralheria visual")

---

## ✨ Funcionalidades

### Header
- [x] Fixo com `backdrop-filter: blur` — efeito glassmorphism
- [x] Reduz de 80px → 64px ao fazer scroll
- [x] **Dropdown "Unidades"** com hover — direciona para cada página de serviço
- [x] Menu mobile com animação e overlay
- [x] Botão "Agendar" dourado com hover elevado

### Cards de Unidades (Home)
- [x] Clicáveis — click no card navega para o site da unidade
- [x] Links internos (tel, WhatsApp) funcionam independentemente via `stopPropagation`
- [x] Ícone `↗` sinaliza que o card é navegável

### Formulários
- [x] Formulário de contato com validação nativa HTML5
- [x] Select com `optgroup` organizados por categoria de serviço
- [x] Formulário de candidatura com upload de CV (PDF/DOC)
- [x] Feedback visual de sucesso após envio
- [x] Integração pronta para Formspree ou EmailJS

### Conversão
- [x] WhatsApp flutuante fixo em todas as páginas
- [x] Botões de WhatsApp individuais por unidade (número correto por página)
- [x] Newsletter com captura de e-mail
- [x] CTAs dourados estrategicamente posicionados

### Scroll & Animações
- [x] Scroll reveal com `IntersectionObserver` (delay staggerado)
- [x] Animações `fadeUp` no hero com delays progressivos
- [x] Botão "voltar ao topo" com aparição automática
- [x] Smooth scroll em todos os links âncora

---

## 📁 Estrutura do Projeto

```
grupo-automotivo-prost/
│
├── 📄 index.html              # Home — página principal
├── 📄 motor.html              # Mecânica Especializada
├── 📄 funilaria.html          # Funilaria e Pintura
├── 📄 blindagem.html          # Blindagem Automotiva
├── 📄 contato.html            # Contato e Trabalhe Conosco
│
├── 🖼️  prost-logo-removebg-preview.png   # Logo PROST (branco)
├── 🖼️  gap-logo.jpg                       # Logo Grupo Automotivo Prost
├── 🖼️  prost-logo.jpg                     # Logo alternativo
│
└── 📖 README.md
```

> **Nota:** O projeto foi desenvolvido em HTML/CSS/JS puro, sem dependência de frameworks ou bundlers. Basta abrir os arquivos `.html` em qualquer servidor estático.

---

## 🚀 Como Usar

### Requisitos

Nenhuma dependência local necessária. O projeto usa CDNs para:
- [Font Awesome 6.4](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css)
- [Google Fonts](https://fonts.googleapis.com) — Bebas Neue, Barlow, Barlow Condensed

### Rodando Localmente

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/grupo-automotivo-prost.git

# Entre na pasta
cd grupo-automotivo-prost

# Abra com Live Server (VS Code) ou qualquer servidor local
# Opção 1: VS Code + extensão Live Server → clique em "Go Live"

# Opção 2: Python
python -m http.server 8000
# Acesse: http://localhost:8000

# Opção 3: Node.js
npx serve .
# Acesse: http://localhost:3000
```

### Deploy

O projeto é compatível com qualquer hospedagem estática:

| Plataforma | Comando / Método |
|-----------|-----------------|
| **GitHub Pages** | Settings → Pages → Branch `main` → `/root` |
| **Vercel** | `vercel --prod` ou drag & drop na dashboard |
| **Netlify** | Drag & drop da pasta no dashboard |
| **Hostinger / cPanel** | Upload via FTP para `public_html/` |

### Personalizações Essenciais

Antes de publicar, substitua os seguintes valores:

```html
<!-- Links de WhatsApp — substitua pelos números reais -->
https://api.whatsapp.com/send?phone=5511943478100  ← Blindados
https://api.whatsapp.com/send?phone=5511976410925  ← Mecânica
https://api.whatsapp.com/send?phone=5511977280908  ← Funilaria

<!-- Meta canonical — substitua pelo domínio real -->
<link rel="canonical" href="https://www.grupoprost.com.br/">

<!-- Imagem OG — adicione uma imagem real do site -->
<meta property="og:image" content="https://www.grupoprost.com.br/og-image.jpg">

<!-- Google Maps — substitua pelo embed real das unidades -->
<iframe src="https://www.google.com/maps/embed?pb=...">
```

---

## 📈 SEO e Performance

### SEO Técnico Implementado

- [x] **Schema.org** `AutoRepair` com `@graph` para as 3 unidades (`LocalBusiness`)
- [x] **Schema.org** `Service` em cada página interna
- [x] **Schema.org** `BreadcrumbList` nas páginas de serviço
- [x] **Open Graph** completo (Facebook, LinkedIn, WhatsApp preview)
- [x] **Twitter Card** `summary_large_image`
- [x] Meta `description` única por página (150–160 chars)
- [x] `<link rel="canonical">` por página
- [x] Headings hierárquicos: H1 único → H2 → H3
- [x] `alt` descritivo em todas as imagens
- [x] `loading="lazy"` em imagens below-the-fold
- [x] `loading="eager"` apenas no hero

### Performance

| Técnica | Implementação |
|---------|--------------|
| Zero dependências locais | CDN para Font Awesome e Google Fonts |
| CSS inline crítico | Estilos no `<head>`, sem arquivos externos |
| Sem JavaScript pesado | Vanilla JS < 100 linhas por página |
| `font-display: swap` | Via parâmetro `&display=swap` no Google Fonts |
| Lazy loading nativo | `loading="lazy"` em todas as imagens não-hero |
| `IntersectionObserver` | Scroll reveal sem bibliotecas externas |

---

## 📱 Responsividade

O layout é **mobile-first** com 3 breakpoints principais:

| Breakpoint | Largura | Ajustes |
|-----------|---------|---------|
| **Desktop** | > 1024px | Layout completo, grid 3–4 colunas |
| **Tablet** | 768px – 1024px | Grid 2 colunas, nav reduzida |
| **Mobile** | < 768px | Coluna única, menu hambúrguer, hero simplificado |

---

## 🛠️ Tecnologias

| Tecnologia | Versão | Uso |
|-----------|--------|-----|
| HTML5 | — | Estrutura semântica |
| CSS3 | — | Layout, animações, design system |
| JavaScript ES6+ | — | Interatividade, scroll reveal, forms |
| Font Awesome | 6.4.0 | Ícones |
| Bebas Neue | — | Tipografia display |
| Barlow / Barlow Condensed | — | Tipografia de corpo |
| Google Maps Embed | — | Mapa das unidades |
| Schema.org | — | Dados estruturados para SEO |

---

## 🏭 Unidades

| Unidade | Endereço | Telefone | Especialidade |
|---------|----------|----------|--------------|
| **Prost Blindados** | Rua Henrique Schaumann, 555 — Pinheiros | (11) 94347-8100 | Blindagem & Showroom |
| **Prost Mecânica** | Av. Pacaembú, 1306 — Pacaembú | (11) 97641-0925 | Mecânica Geral |
| **Prost Funilaria** | Av. Dr. Abraão Ribeiro, 109 — Bom Retiro | (11) 97728-0908 | Funilaria & Estética |

**Horário:** Segunda a Sexta: 08:00 – 18:00 · Sábado: 08:00 – 13:00

---

## 📞 Contato

**Grupo Automotivo Prost**

- 🌐 Site: [grupoprost.com.br](https://www.grupoprost.com.br)
- 📸 Instagram: [@prost.autos](https://www.instagram.com/prost.autos/)
- 💬 WhatsApp Blindados: [(11) 94347-8100](https://api.whatsapp.com/send?phone=5511943478100)
- 💬 WhatsApp Mecânica: [(11) 97641-0925](https://api.whatsapp.com/send?phone=5511976410925)
- 💬 WhatsApp Funilaria: [(11) 97728-0908](https://api.whatsapp.com/send?phone=5511977280908)

---

## 📝 Licença

Este projeto é de uso exclusivo do **Grupo Automotivo Prost**. Todos os direitos reservados.

---

<div align="center">

Desenvolvido com precisão para o **Grupo Automotivo Prost** 🏎️

*© 2025 Grupo Automotivo Prost. Todos os direitos reservados.*

</div>
