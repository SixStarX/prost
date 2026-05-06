# Grupo Automotivo Prost — Website Corporativo

<div align="center">

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)

**Site corporativo premium para o Grupo Automotivo Prost — referência em Mecânica, Funilaria e Blindagem em São Paulo.**

[Ver Site](https://www.grupoprost.com.br) · [Reportar Bug](../../issues) · [Sugerir Melhoria](../../issues)

</div>

---

## Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Páginas](#páginas)
- [Design System](#design-system)
- [Funcionalidades](#funcionalidades)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Backend](#backend)
- [Segurança](#segurança)
- [SEO e Performance](#seo-e-performance)
- [Responsividade](#responsividade)
- [Como Usar](#como-usar)
- [Deploy](#deploy)
- [Tecnologias](#tecnologias)
- [Unidades](#unidades)

---

## Sobre o Projeto

O **Grupo Automotivo Prost** é uma referência no segmento automotivo premium em São Paulo, com mais de **30 anos de experiência** e **3 unidades especializadas**. Este repositório contém o código-fonte completo do site corporativo, desenvolvido do zero com foco em:

- **Alta conversão** — CTAs estratégicos e integração direta com WhatsApp por unidade
- **Design premium** — identidade visual luxury/industrial com paleta preto e dourado
- **Performance** — HTML/CSS/JS puro, sem frameworks pesados
- **SEO técnico** — Schema.org, Open Graph, meta tags otimizadas
- **Responsividade total** — mobile-first, testado em todos os breakpoints
- **Backend seguro** — PHP com rate limiting, honeypot, validação MIME e logs

---

## Páginas

| Arquivo | Página | Descrição |
|---------|--------|-----------|
| `index.html` | **Home** | Hero, 9 serviços, 3 unidades e depoimentos |
| `motor.html` | **Mecânica** | Revisão, diagnóstico eletrônico, injeção |
| `cambio.html` | **Câmbio** | Manual, automático, CVT/DSG |
| `freios.html` | **Freios** | Diagnóstico, discos, pastilhas, ABS/ESP |
| `suspensao.html` | **Suspensão** | Alinhamento 3D, amortecedores, balanceamento |
| `escapamento.html` | **Escapamento** | Silencioso, catalisador, diagnóstico |
| `funilaria.html` | **Funilaria & Pintura** | Lataria, cabine, restauração de clássicos |
| `blindagem.html` | **Blindagem** | Showroom Secforce, níveis III-A ao IV |
| `contato.html` | **Contato & Carreiras** | Formulário, mapa e candidaturas |

### Mapa de Navegação

```
Grupo Automotivo Prost
│
├── Home (index.html)
│   ├── Hero Section
│   ├── Sobre o Grupo
│   ├── Nossos Serviços (9 cards)
│   ├── Nossas Unidades ──→ blindagem.html / motor.html / funilaria.html
│   ├── Depoimentos
│   └── Formulário de Contato + Newsletter
│
├── Mecânica (motor.html) ── Câmbio ── Freios ── Suspensão ── Escapamento
│
├── Funilaria (funilaria.html)
│
├── Blindagem (blindagem.html)
│   ├── Tecnologia Secforce
│   ├── Showroom Pinheiros
│   └── Níveis III-A, III, IV e VIP
│
└── Contato (contato.html)
    ├── Formulário de Agendamento (AJAX → send-contact.php)
    ├── Mapa das 3 Unidades
    └── Trabalhe Conosco (Upload de CV → send-cv.php)
```

---

## Design System

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

Estética **Luxury Industrial** — escuro, metálico e premium. Elementos característicos:

- Noise texture overlay sutil via SVG inline
- Bordas douradas com animação de expansão no hover
- Cards com `translateY` e borda lateral dourada
- Animações `fadeUp` staggeradas no carregamento
- Scroll reveal com `IntersectionObserver`
- Header glassmorphism com `backdrop-filter: blur`

---

## Funcionalidades

### Header
- [x] Fixo com glassmorphism (`backdrop-filter: blur`)
- [x] Reduz de 80px → 64px ao fazer scroll
- [x] Dropdown "Unidades" com hover
- [x] Menu mobile com animação e overlay
- [x] Botão "Agendar" dourado com hover elevado

### Formulários
- [x] Formulário de contato com validação HTML5 + envio AJAX
- [x] Select com `optgroup` organizados por categoria
- [x] Upload de CV com validação de tipo, tamanho e magic bytes
- [x] Feedback visual de sucesso/erro após envio
- [x] Anti-bot: honeypot + timestamp mínimo de 3s

### Conversão
- [x] WhatsApp flutuante fixo em todas as páginas
- [x] Botões de WhatsApp individuais por unidade (número correto)
- [x] Newsletter com captura de e-mail
- [x] CTAs dourados estrategicamente posicionados

### Scroll & Animações
- [x] Scroll reveal com `IntersectionObserver` (delay staggerado)
- [x] Animações `fadeUp` no hero com delays progressivos
- [x] Botão "voltar ao topo" com aparição automática
- [x] Smooth scroll em todos os links âncora

---

## Estrutura do Projeto

```
grupo-automotivo-prost/
│
├── index.html               # Home
├── motor.html               # Mecânica
├── cambio.html              # Câmbio
├── freios.html              # Freios
├── suspensao.html           # Suspensão
├── escapamento.html         # Escapamento
├── funilaria.html           # Funilaria & Pintura
├── blindagem.html           # Blindagem
├── contato.html             # Contato & Carreiras
│
├── send-contact.php         # Backend: formulário de contato
├── send-cv.php              # Backend: upload de currículo
├── config.example.php       # Template de configuração SMTP
│
├── .htaccess                # Apache: HTTPS, clean URLs, segurança
├── robots.txt               # SEO: regras de indexação
├── sitemap.xml              # SEO: mapa do site
├── style.css                # CSS auxiliar
├── setup.sh                 # Script de setup inicial
│
├── images/
│   ├── blindadora/          # Fotos do showroom e equipe
│   ├── motor/               # Fotos de mecânica
│   ├── cambio/              # Fotos de câmbio
│   ├── freios/              # Fotos de freios
│   ├── suspensao/           # Fotos de suspensão
│   ├── escapamento/         # Fotos de escapamento
│   ├── funilaria/           # Fotos de funilaria
│   └── index/               # Fotos da home
│
├── prost-logo-removebg-preview.png
├── gap-logo.jpg
├── prost-logo.jpg
│
└── README.md
```

---

## Backend

O backend é composto por dois endpoints PHP com validação completa e envio via SMTP.

### Dependências

O projeto usa [PHPMailer](https://github.com/PHPMailer/PHPMailer) para envio de e-mails. Os arquivos devem estar em:

```
vendor/
└── phpmailer/
    ├── PHPMailer.php
    ├── SMTP.php
    └── Exception.php
```

### Configuração SMTP

Copie `config.example.php` para `config.php` e preencha com os dados reais:

```bash
cp config.example.php config.php
```

```php
// config.php
define('SMTP_HOST',       'seu-servidor-smtp.com.br');
define('SMTP_PORT',       587);
define('SMTP_USER',       'contato@seudominio.com.br');
define('SMTP_PASS',       'SUA_SENHA_AQUI');
define('SMTP_FROM_EMAIL', 'contato@seudominio.com.br');
```

> **Importante:** `config.php` está no `.gitignore` e nunca deve ser commitado.

### Endpoints

| Arquivo | Endpoint | Função |
|---------|----------|--------|
| `send-contact.php` | `POST /send-contact.php` | Processa o formulário de contato |
| `send-cv.php` | `POST /send-cv.php` | Recebe e valida upload de currículo (PDF) |

### Fluxo de Validação — Formulário de Contato

1. Verificação de CORS (apenas domínio autorizado)
2. Rate limiting por IP (10 envios/hora)
3. Validação do campo honeypot (anti-bot)
4. Verificação de timestamp mínimo (3 segundos)
5. Validação de campos obrigatórios e formatos
6. Envio via SMTP com fallback em texto simples
7. Log em `/logs/contact-submissions.log`

### Fluxo de Validação — Upload de CV

1. Rate limiting por IP (5 uploads/hora)
2. Verificação de tamanho (máx. 5MB)
3. Verificação de MIME type via `finfo`
4. Validação de magic bytes (`%PDF-`)
5. Envio via SMTP com o PDF como anexo
6. Log em `/logs/cv-submissions.log`

---

## Segurança

### Headers HTTP (`.htaccess`)

| Header | Valor |
|--------|-------|
| `Strict-Transport-Security` | `max-age=63072000; includeSubDomains; preload` |
| `X-Frame-Options` | `SAMEORIGIN` |
| `X-Content-Type-Options` | `nosniff` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Permissions-Policy` | Bloqueia geolocation, microphone, camera, payment |
| `Content-Security-Policy` | Permite apenas GTM/GA4/FontAwesome/Google |

### Bloqueios de Arquivo

Acesso bloqueado via `.htaccess` para: `.bak`, `.sql`, `.log`, `.sh`, `.ini`, `.config`, `.md`, `.docx`

### Anti-Spam nos Formulários

- Campo honeypot invisível (bots preenchem, humanos não)
- Tempo mínimo de 3s entre carregamento e envio
- Rate limiting por IP com janela de 1 hora
- Validação server-side independente do frontend

---

## SEO e Performance

### SEO Técnico

- [x] Schema.org `AutoRepair` com `@graph` para as 3 unidades
- [x] Schema.org `Service` em cada página interna
- [x] Schema.org `BreadcrumbList` nas páginas de serviço
- [x] Open Graph completo (Facebook, LinkedIn, WhatsApp preview)
- [x] Twitter Card `summary_large_image`
- [x] Meta `description` única por página (150–160 chars)
- [x] `<link rel="canonical">` por página
- [x] Headings hierárquicos: H1 único → H2 → H3
- [x] `alt` descritivo em todas as imagens
- [x] `loading="lazy"` em imagens below-the-fold

### Performance

| Técnica | Implementação |
|---------|--------------|
| Zero dependências locais | CDN para Font Awesome e Google Fonts |
| CSS inline crítico | Estilos no `<head>`, sem arquivos externos |
| Sem JavaScript pesado | Vanilla JS < 100 linhas por página |
| `font-display: swap` | Via `&display=swap` no Google Fonts |
| Lazy loading nativo | `loading="lazy"` em todas as imagens não-hero |
| Compressão gzip | Via `mod_deflate` no `.htaccess` |
| Cache de assets | 1 mês para imagens/CSS/JS |

---

## Responsividade

Layout **mobile-first** com 3 breakpoints principais:

| Breakpoint | Largura | Ajustes |
|-----------|---------|---------|
| **Desktop** | > 1024px | Grid completo, 3–4 colunas |
| **Tablet** | 768px – 1024px | Grid 2 colunas, nav reduzida |
| **Mobile** | < 768px | Coluna única, menu hambúrguer |

---

## Como Usar

### Pré-requisitos

- PHP 7.4+ com extensões: `fileinfo`, `openssl`, `mbstring`
- PHPMailer (copiar para `vendor/phpmailer/`)
- Servidor Apache com `mod_rewrite` e `mod_headers`

### Instalação Local

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/grupo-automotivo-prost.git
cd grupo-automotivo-prost

# Configure o SMTP
cp config.example.php config.php
# Edite config.php com suas credenciais

# Adicione o PHPMailer
mkdir -p vendor/phpmailer
# Copie PHPMailer.php, SMTP.php, Exception.php para vendor/phpmailer/

# Inicie um servidor local (para frontend apenas)
python -m http.server 8000
# ou
npx serve .
```

> Para testar o backend (formulários), use XAMPP, Laragon ou um servidor PHP real.

### Personalizações Necessárias

```html
<!-- WhatsApp — substitua pelos números reais -->
https://api.whatsapp.com/send?phone=5511943478100  ← Blindados
https://api.whatsapp.com/send?phone=5511976410925  ← Mecânica
https://api.whatsapp.com/send?phone=5511977280908  ← Funilaria

<!-- Canonical — substitua pelo domínio real -->
<link rel="canonical" href="https://www.grupoprost.com.br/">

<!-- Google Maps — substitua pelos embeds reais das unidades -->
<iframe src="https://www.google.com/maps/embed?pb=...">
```

---

## Deploy

| Plataforma | Método |
|-----------|--------|
| **Hostinger / cPanel** | Upload via FTP para `public_html/` |
| **GitHub Pages** | Settings → Pages → Branch `main` → `/root` (somente frontend) |
| **Vercel** | `vercel --prod` (somente frontend) |
| **Netlify** | Drag & drop da pasta (somente frontend) |

> Para o backend PHP funcionar (formulários e upload de CV), é necessário hospedagem com suporte a PHP. GitHub Pages, Vercel e Netlify suportam apenas arquivos estáticos.

### Checklist de Deploy

- [ ] `config.php` configurado com credenciais SMTP reais
- [ ] PHPMailer instalado em `vendor/phpmailer/`
- [ ] `diagnostico.php` removido (se existir)
- [ ] Pasta `logs/` criada e com permissão de escrita (`chmod 750`)
- [ ] HTTPS ativo no servidor
- [ ] `.htaccess` habilitado (`AllowOverride All`)

---

## Tecnologias

| Tecnologia | Versão | Uso |
|-----------|--------|-----|
| HTML5 | — | Estrutura semântica |
| CSS3 | — | Layout, animações, design system |
| JavaScript ES6+ | — | Interatividade, scroll reveal, forms |
| PHP | 7.4+ | Backend: formulários e upload |
| PHPMailer | 6.x | Envio de e-mail via SMTP |
| Font Awesome | 6.4.0 | Ícones |
| Bebas Neue | — | Tipografia display |
| Barlow / Barlow Condensed | — | Tipografia de corpo |
| Google Maps Embed | — | Mapa das unidades |
| Schema.org | — | Dados estruturados para SEO |
| Apache mod_rewrite | — | Clean URLs e segurança |

---

## Unidades

| Unidade | Endereço | Telefone | Especialidade |
|---------|----------|----------|--------------|
| **Prost Blindados** | Rua Henrique Schaumann, 555 — Pinheiros | (11) 94347-8100 | Blindagem & Showroom |
| **Prost Mecânica** | Av. Pacaembú, 1306 — Pacaembú | (11) 97641-0925 | Mecânica Geral |
| **Prost Funilaria** | Av. Dr. Abraão Ribeiro, 109 — Bom Retiro | (11) 97728-0908 | Funilaria & Estética |

**Horário:** Segunda a Sexta: 08:00 – 18:00 · Sábado: 08:00 – 13:00

---

## Contato

**Grupo Automotivo Prost**

- Site: [grupoprost.com.br](https://www.grupoprost.com.br)
- Instagram: [@prost.autos](https://www.instagram.com/prost.autos/)
- WhatsApp Blindados: [(11) 94347-8100](https://api.whatsapp.com/send?phone=5511943478100)
- WhatsApp Mecânica: [(11) 97641-0925](https://api.whatsapp.com/send?phone=5511976410925)
- WhatsApp Funilaria: [(11) 97728-0908](https://api.whatsapp.com/send?phone=5511977280908)

---

## Licença

Este projeto é de uso exclusivo do **Grupo Automotivo Prost**. Todos os direitos reservados.

---

<div align="center">

Desenvolvido para o **Grupo Automotivo Prost**

*© 2025 Grupo Automotivo Prost. Todos os direitos reservados.*

</div>
