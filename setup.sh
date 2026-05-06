#!/bin/bash
# ═══════════════════════════════════════════════════
# Grupo Automotivo Prost — Setup Script
# Executar no Terminal do cPanel
# ═══════════════════════════════════════════════════

set -e

echo "═══ PROST — Setup de Formulários ═══"
echo ""

# Detectar public_html
PUBLIC_HTML="$HOME/public_html"
if [ ! -d "$PUBLIC_HTML" ]; then
    echo "❌ Diretório $PUBLIC_HTML não encontrado."
    exit 1
fi

echo "📂 Public HTML: $PUBLIC_HTML"

# 1. Criar estrutura de diretórios
echo ""
echo "1/5 — Criando diretórios..."
mkdir -p "$PUBLIC_HTML/api/vendor/phpmailer"
mkdir -p "$PUBLIC_HTML/logs"
echo "   ✅ /api/ criado"
echo "   ✅ /logs/ criado"

# 2. Download PHPMailer (direto do GitHub)
echo ""
echo "2/5 — Baixando PHPMailer..."
cd "$PUBLIC_HTML/api/vendor/phpmailer"

PHPMAILER_VERSION="6.9.3"
BASE_URL="https://raw.githubusercontent.com/PHPMailer/PHPMailer/v${PHPMAILER_VERSION}/src"

curl -sO "$BASE_URL/PHPMailer.php"
curl -sO "$BASE_URL/SMTP.php"
curl -sO "$BASE_URL/Exception.php"

if [ -f "PHPMailer.php" ] && [ -f "SMTP.php" ] && [ -f "Exception.php" ]; then
    echo "   ✅ PHPMailer v${PHPMAILER_VERSION} instalado"
else
    echo "   ❌ Falha no download. Verifique conectividade."
    echo "   Manual: https://github.com/PHPMailer/PHPMailer/releases"
    exit 1
fi

# 3. Proteger diretório de logs
echo ""
echo "3/5 — Protegendo /logs/..."
echo "Deny from all" > "$PUBLIC_HTML/logs/.htaccess"
echo "   ✅ .htaccess de bloqueio criado"

# 4. Verificar PHP
echo ""
echo "4/5 — Verificando PHP..."
PHP_VERSION=$(php -v 2>/dev/null | head -1 | awk '{print $2}')
echo "   PHP: $PHP_VERSION"

# Verificar extensões necessárias
for ext in curl fileinfo mbstring openssl; do
    if php -m 2>/dev/null | grep -qi "^$ext$"; then
        echo "   ✅ ext-$ext"
    else
        echo "   ⚠️  ext-$ext NÃO encontrada — ativar no cPanel > PHP Selector"
    fi
done

# Verificar upload_max_filesize
UPLOAD_MAX=$(php -r "echo ini_get('upload_max_filesize');" 2>/dev/null)
POST_MAX=$(php -r "echo ini_get('post_max_size');" 2>/dev/null)
echo "   upload_max_filesize: $UPLOAD_MAX (precisa ser >= 8M)"
echo "   post_max_size: $POST_MAX (precisa ser >= 10M)"

# 5. Permissões
echo ""
echo "5/5 — Ajustando permissões..."
chmod 750 "$PUBLIC_HTML/api"
chmod 640 "$PUBLIC_HTML/api/send-cv.php" 2>/dev/null || true
chmod 640 "$PUBLIC_HTML/api/send-contact.php" 2>/dev/null || true
chmod 750 "$PUBLIC_HTML/logs"
echo "   ✅ Permissões ajustadas"

# Resumo
echo ""
echo "═══════════════════════════════════════════════════"
echo "✅ Setup concluído!"
echo ""
echo "PRÓXIMOS PASSOS:"
echo "  1. Upload dos arquivos PHP para /api/"
echo "  2. Upload do contato.html atualizado"
echo "  3. Trocar placeholders nos arquivos:"
echo "     - TROCAR_SITE_KEY_RECAPTCHA  (contato.html)"
echo "     - TROCAR_CHAVE_SECRETA_RECAPTCHA  (send-cv.php + send-contact.php)"
echo "     - TROCAR_SENHA_SMTP  (send-cv.php + send-contact.php)"
echo "  4. Criar reCAPTCHA v3: https://www.google.com/recaptcha/admin"
echo "  5. Criar email rh@grupoprost.com.br no cPanel"
echo "  6. Testar envio"
echo "═══════════════════════════════════════════════════"