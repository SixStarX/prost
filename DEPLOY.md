# Deploy de Segurança — Grupo Prost

## Arquivos modificados / criados
- `*.html` — todos os 9 arquivos: `<link rel="canonical">` e `og:url` apontando para apex (`grupoprost.com.br` sem `www`)
- `.htaccess` — NOVO — redirects HTTPS, www→apex, headers de segurança, cache, compressão, URLs limpas
- `robots.txt` — NOVO
- `sitemap.xml` — NOVO

## Ordem de upload (cPanel/FTP)
1. Faça backup do site atual (download via cPanel → File Manager → Compress)
2. Suba **primeiro** os HTMLs corrigidos
3. Suba `robots.txt` e `sitemap.xml` na raiz
4. Suba o `.htaccess` por **último** — é o que ativa todas as regras
5. Limpe cache do navegador e teste

## Auditoria pré-encontrada
- ✅ Sem mixed content real (apenas `xmlns="http://www.w3.org/2000/svg"` que é namespace XML válido)
- ✅ Recursos externos já em HTTPS (Google Fonts, FontAwesome CDN, Maps, WhatsApp API)
- ❌ Canonical apontava para www → CORRIGIDO para apex
- ❌ Sem .htaccess de produção → CRIADO
