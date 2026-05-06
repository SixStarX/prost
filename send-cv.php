<?php
/**
 * Grupo Automotivo Prost — Envio de Currículo
 * Endpoint: POST /api/send-cv.php
 * 
 * Dependência: PHPMailer (vendor/phpmailer/)
 */

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

// ═══════════════════════════════════════════════════
// CONFIGURAÇÃO
// ═══════════════════════════════════════════════════
require_once __DIR__ . '/config.php';
define('SMTP_FROM_NAME',  'Grupo Prost — Trabalhe Conosco');
define('DEST_EMAIL',      'contato@grupoprost.com.br');

define('MIN_SUBMIT_TIME',  3);    // Mínimo 3 segundos entre carregamento e envio (anti-bot)

define('MAX_FILE_SIZE',    5 * 1024 * 1024); // 5MB
define('ALLOWED_MIME',     'application/pdf');
define('LOG_FILE',         __DIR__ . '/../logs/cv-submissions.log');

define('RATE_LIMIT_FILE',   sys_get_temp_dir() . '/prost_cv_rate_limit.json');
define('RATE_LIMIT_MAX',    5);
define('RATE_LIMIT_WINDOW', 3600);

// ═══════════════════════════════════════════════════
// CORS & METHOD CHECK
// ═══════════════════════════════════════════════════

$allowedOrigins = [
    'https://grupoprost.com.br',
    'https://www.grupoprost.com.br',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: {$origin}");
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, 'Método não permitido.');
}

// ═══════════════════════════════════════════════════
// FUNÇÕES UTILITÁRIAS
// ═══════════════════════════════════════════════════

function respond(int $code, string $message, array $extra = []): void
{
    http_response_code($code);
    echo json_encode(
        array_merge(['success' => $code === 200, 'message' => $message], $extra),
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

function sanitize(string $input): string
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function logEntry(string $status, array $data): void
{
    $line = sprintf(
        "[%s] %s | IP: %s | Nome: %s | Email: %s | Cargo: %s | Unidade: %s\n",
        date('Y-m-d H:i:s'),
        $status,
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $data['nome'] ?? '-',
        $data['email'] ?? '-',
        $data['cargo'] ?? '-',
        $data['unidade'] ?? '-'
    );
    
    $logDir = dirname(LOG_FILE);
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    @file_put_contents(LOG_FILE, $line, FILE_APPEND | LOCK_EX);
}

function checkRateLimit(): bool
{
    $ip  = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $now = time();
    $data = [];

    if (file_exists(RATE_LIMIT_FILE)) {
        $raw = @file_get_contents(RATE_LIMIT_FILE);
        $data = $raw ? (json_decode($raw, true) ?: []) : [];
    }

    // Limpar entradas expiradas
    foreach ($data as $key => $entries) {
        $data[$key] = array_values(array_filter($entries, fn($t) => ($now - $t) < RATE_LIMIT_WINDOW));
        if (empty($data[$key])) unset($data[$key]);
    }

    if (count($data[$ip] ?? []) >= RATE_LIMIT_MAX) {
        return false;
    }

    $data[$ip][] = $now;
    @file_put_contents(RATE_LIMIT_FILE, json_encode($data), LOCK_EX);
    return true;
}

// ═══════════════════════════════════════════════════
// RATE LIMITING
// ═══════════════════════════════════════════════════

if (!checkRateLimit()) {
    logEntry('RATE_LIMIT', ['nome' => '-', 'email' => '-', 'cargo' => '-', 'unidade' => '-']);
    respond(429, 'Muitas submissões. Tente novamente em 1 hora.');
}

// ═══════════════════════════════════════════════════
// ANTI-SPAM: HONEYPOT + TIMESTAMP
// ═══════════════════════════════════════════════════

// Honeypot: campo invisível que só bots preenchem
$honeypot = $_POST['website'] ?? '';
if (!empty($honeypot)) {
    logEntry('HONEYPOT_BLOCKED', ['nome' => '-', 'email' => '-', 'cargo' => '-', 'unidade' => '-']);
    respond(200, 'Candidatura enviada com sucesso!'); // Resposta falsa para o bot
}

// Timestamp: rejeita envios < 3 segundos após carregamento da página
$ts = intval($_POST['_ts'] ?? 0);
if ($ts > 0) {
    $elapsed = (time() * 1000 - $ts) / 1000; // diferença em segundos
    if ($elapsed < MIN_SUBMIT_TIME) {
        respond(422, 'Envio muito rápido. Aguarde alguns segundos e tente novamente.');
    }
}

// ═══════════════════════════════════════════════════
// VALIDAÇÃO DE CAMPOS
// ═══════════════════════════════════════════════════

$fields = [
    'nome'        => 'Nome',
    'telefone'    => 'Telefone',
    'email'       => 'E-mail',
    'cargo'       => 'Cargo de Interesse',
    'unidade'     => 'Unidade de Preferência',
    'experiencia' => 'Experiência Profissional',
];

$data = [];
foreach ($fields as $key => $label) {
    $value = sanitize($_POST[$key] ?? '');
    if ($value === '') {
        respond(422, "Campo obrigatório: {$label}");
    }
    $data[$key] = $value;
}

// Email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    respond(422, 'E-mail inválido.');
}

// Telefone (formato BR)
$tel = preg_replace('/\D/', '', $data['telefone']);
if (strlen($tel) < 10 || strlen($tel) > 11) {
    respond(422, 'Telefone inválido. Use formato (11) 99999-9999.');
}

// Limite de texto
if (mb_strlen($data['experiencia']) > 5000) {
    respond(422, 'Texto de experiência excede 5000 caracteres.');
}

// Validação de valores do select (evita manipulação)
$cargosValidos = [
    'Técnico em Blindagem',
    'Mecânico de Automóveis',
    'Funileiro / Pintor Automotivo',
    'Técnico em Diagnóstico Eletrônico',
    'Consultor de Atendimento',
    'Outro'
];
if (!in_array($data['cargo'], $cargosValidos, true)) {
    respond(422, 'Cargo inválido.');
}

$unidadesValidas = [
    'Prost Blindados — Pinheiros',
    'Prost Mecânica — Pacaembú',
    'Prost Funilaria — Barra Funda',
    'Sem preferência'
];
if (!in_array($data['unidade'], $unidadesValidas, true)) {
    respond(422, 'Unidade inválida.');
}

// ═══════════════════════════════════════════════════
// VALIDAÇÃO DO ARQUIVO
// ═══════════════════════════════════════════════════

if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
    $errorMap = [
        UPLOAD_ERR_INI_SIZE   => 'Arquivo excede o limite do servidor.',
        UPLOAD_ERR_FORM_SIZE  => 'Arquivo excede o limite do formulário.',
        UPLOAD_ERR_PARTIAL    => 'Upload interrompido. Tente novamente.',
        UPLOAD_ERR_NO_FILE    => 'Nenhum arquivo enviado. Anexe seu currículo em PDF.',
        UPLOAD_ERR_NO_TMP_DIR => 'Erro interno do servidor.',
        UPLOAD_ERR_CANT_WRITE => 'Erro ao processar arquivo.',
    ];
    $code = $_FILES['cv']['error'] ?? UPLOAD_ERR_NO_FILE;
    respond(422, $errorMap[$code] ?? 'Erro no upload.');
}

$file = $_FILES['cv'];

// Tamanho
if ($file['size'] > MAX_FILE_SIZE) {
    respond(422, 'Arquivo excede o limite de 5MB.');
}

if ($file['size'] === 0) {
    respond(422, 'Arquivo vazio.');
}

// MIME real via finfo (não confiar no browser)
$finfo = new finfo(FILEINFO_MIME_TYPE);
$realMime = $finfo->file($file['tmp_name']);
if ($realMime !== ALLOWED_MIME) {
    respond(422, 'Apenas arquivos PDF são aceitos. Tipo detectado: ' . $realMime);
}

// Magic bytes
$handle = fopen($file['tmp_name'], 'rb');
$header = fread($handle, 5);
fclose($handle);
if ($header !== '%PDF-') {
    respond(422, 'Arquivo PDF inválido ou corrompido.');
}

// ═══════════════════════════════════════════════════
// ENVIO DE EMAIL
// ═══════════════════════════════════════════════════

// Tentar autoload do Composer primeiro, fallback para includes manuais
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
} else {
    require __DIR__ . '/vendor/phpmailer/PHPMailer.php';
    require __DIR__ . '/vendor/phpmailer/SMTP.php';
    require __DIR__ . '/vendor/phpmailer/Exception.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';
    $mail->Timeout    = 30;

    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress(DEST_EMAIL);
    $mail->addReplyTo($data['email'], $data['nome']);

    $mail->Subject = "Novo Currículo — {$data['nome']} — {$data['cargo']}";

    $mail->isHTML(true);
    $mail->Body    = buildEmailBody($data);
    $mail->AltBody = buildEmailPlainText($data);

    // Anexo direto do tmp (sem persistir no disco)
    $safeFilename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $data['nome']) . '_CV.pdf';
    $mail->addAttachment($file['tmp_name'], $safeFilename, 'base64', 'application/pdf');

    $mail->send();

    logEntry('ENVIADO', $data);
    respond(200, 'Candidatura enviada com sucesso! Entraremos em contato em breve.');

} catch (Exception $e) {
    logEntry('ERRO_SMTP: ' . $mail->ErrorInfo, $data);
    respond(500, 'Erro ao enviar candidatura. Tente novamente em alguns minutos.');
}

// ═══════════════════════════════════════════════════
// TEMPLATES DE EMAIL
// ═══════════════════════════════════════════════════

function buildEmailBody(array $d): string
{
    $exp = nl2br($d['experiencia']);
    $dataEnvio = date('d/m/Y \à\s H:i');
    
    return <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"></head>
<body style="margin:0; padding:0; font-family: Arial, Helvetica, sans-serif; background:#f4f4f4;">
<div style="max-width:600px; margin:20px auto; background:#ffffff; border-radius:4px; overflow:hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    
    <!-- Header -->
    <div style="background:#0e0e0e; padding:28px 32px; text-align:center;">
        <h1 style="color:#c9a84c; margin:0; font-size:20px; font-weight:700; letter-spacing:1px;">
            NOVA CANDIDATURA RECEBIDA
        </h1>
        <p style="color:#888; margin:8px 0 0; font-size:12px;">{$dataEnvio}</p>
    </div>
    
    <!-- Body -->
    <div style="padding:32px;">
        <table style="width:100%; border-collapse:collapse; font-size:14px; color:#333;">
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; width:160px; vertical-align:top; font-size:12px; text-transform:uppercase; letter-spacing:0.5px;">Nome</td>
                <td style="padding:14px 0; font-weight:600; font-size:15px;">{$d['nome']}</td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; vertical-align:top; font-size:12px; text-transform:uppercase; letter-spacing:0.5px;">Telefone</td>
                <td style="padding:14px 0;">{$d['telefone']}</td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; vertical-align:top; font-size:12px; text-transform:uppercase; letter-spacing:0.5px;">E-mail</td>
                <td style="padding:14px 0;"><a href="mailto:{$d['email']}" style="color:#c9a84c; text-decoration:none;">{$d['email']}</a></td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; vertical-align:top; font-size:12px; text-transform:uppercase; letter-spacing:0.5px;">Cargo</td>
                <td style="padding:14px 0; font-weight:600;">{$d['cargo']}</td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; vertical-align:top; font-size:12px; text-transform:uppercase; letter-spacing:0.5px;">Unidade</td>
                <td style="padding:14px 0;">{$d['unidade']}</td>
            </tr>
            <tr>
                <td style="padding:14px 0; color:#888; vertical-align:top; font-size:12px; text-transform:uppercase; letter-spacing:0.5px;">Experiência</td>
                <td style="padding:14px 0; line-height:1.7;">{$exp}</td>
            </tr>
        </table>
    </div>
    
    <!-- Footer -->
    <div style="background:#f9f9f9; padding:16px 32px; font-size:11px; color:#aaa; text-align:center; border-top:1px solid #eee;">
        📎 Currículo em anexo (PDF) · Enviado via <a href="https://grupoprost.com.br" style="color:#c9a84c; text-decoration:none;">grupoprost.com.br</a>
    </div>
</div>
</body>
</html>
HTML;
}

function buildEmailPlainText(array $d): string
{
    $dataEnvio = date('d/m/Y \à\s H:i');
    
    return <<<TEXT
════════════════════════════════════════
NOVA CANDIDATURA — GRUPO AUTOMOTIVO PROST
{$dataEnvio}
════════════════════════════════════════

Nome: {$d['nome']}
Telefone: {$d['telefone']}
E-mail: {$d['email']}
Cargo de Interesse: {$d['cargo']}
Unidade de Preferência: {$d['unidade']}

── Experiência Profissional ──
{$d['experiencia']}

────────────────────────────────────────
Currículo em anexo (PDF)
Enviado via grupoprost.com.br
TEXT;
}