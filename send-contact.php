<?php
/**
 * Grupo Automotivo Prost — Formulário de Contato
 * Endpoint: POST /api/send-contact.php
 */

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

// ═══ CONFIGURAÇÃO ═══
require_once __DIR__ . '/config.php';
define('SMTP_FROM_NAME',  'Grupo Prost — Contato');
define('DEST_EMAIL',      'contato@grupoprost.com.br');

define('MIN_SUBMIT_TIME',  3);

define('LOG_FILE',         __DIR__ . '/../logs/contact-submissions.log');
define('RATE_LIMIT_FILE',  sys_get_temp_dir() . '/prost_contact_rate_limit.json');
define('RATE_LIMIT_MAX',   10);
define('RATE_LIMIT_WINDOW', 3600);

// ═══ CORS ═══
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

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { respond(405, 'Método não permitido.'); }

// ═══ FUNÇÕES ═══
function respond(int $code, string $message): void {
    http_response_code($code);
    echo json_encode(['success' => $code === 200, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function sanitize(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function logEntry(string $status, array $data): void {
    $line = sprintf(
        "[%s] %s | IP: %s | Nome: %s | Email: %s | Unidade: %s | Serviço: %s\n",
        date('Y-m-d H:i:s'), $status,
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $data['nome'] ?? '-', $data['email'] ?? '-',
        $data['unidade'] ?? '-', $data['servico'] ?? '-'
    );
    $logDir = dirname(LOG_FILE);
    if (!is_dir($logDir)) @mkdir($logDir, 0750, true);
    @file_put_contents(LOG_FILE, $line, FILE_APPEND | LOCK_EX);
}

function checkRateLimit(): bool {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $now = time();
    $data = [];
    if (file_exists(RATE_LIMIT_FILE)) {
        $raw = @file_get_contents(RATE_LIMIT_FILE);
        $data = $raw ? (json_decode($raw, true) ?: []) : [];
    }
    foreach ($data as $key => $entries) {
        $data[$key] = array_values(array_filter($entries, fn($t) => ($now - $t) < RATE_LIMIT_WINDOW));
        if (empty($data[$key])) unset($data[$key]);
    }
    if (count($data[$ip] ?? []) >= RATE_LIMIT_MAX) return false;
    $data[$ip][] = $now;
    @file_put_contents(RATE_LIMIT_FILE, json_encode($data), LOCK_EX);
    return true;
}

// ═══ RATE LIMITING ═══
if (!checkRateLimit()) {
    respond(429, 'Muitas mensagens enviadas. Tente novamente em 1 hora.');
}

// ═══ ANTI-SPAM: HONEYPOT + TIMESTAMP ═══
$honeypot = $_POST['website'] ?? '';
if (!empty($honeypot)) {
    logEntry('HONEYPOT_BLOCKED', ['nome' => '-', 'email' => '-', 'unidade' => '-', 'servico' => '-']);
    respond(200, 'Mensagem enviada com sucesso!');
}

$ts = intval($_POST['_ts'] ?? 0);
if ($ts > 0) {
    $elapsed = (time() * 1000 - $ts) / 1000;
    if ($elapsed < MIN_SUBMIT_TIME) {
        respond(422, 'Envio muito rápido. Aguarde alguns segundos e tente novamente.');
    }
}

// ═══ VALIDAÇÃO ═══
$fields = [
    'nome'     => 'Nome',
    'telefone' => 'Telefone',
    'email'    => 'E-mail',
    'unidade'  => 'Unidade',
    'servico'  => 'Serviço',
    'mensagem' => 'Mensagem',
];

$data = [];
foreach ($fields as $key => $label) {
    $value = sanitize($_POST[$key] ?? '');
    if ($value === '') respond(422, "Campo obrigatório: {$label}");
    $data[$key] = $value;
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) respond(422, 'E-mail inválido.');

$tel = preg_replace('/\D/', '', $data['telefone']);
if (strlen($tel) < 10 || strlen($tel) > 11) respond(422, 'Telefone inválido.');

if (mb_strlen($data['mensagem']) > 5000) respond(422, 'Mensagem excede 5000 caracteres.');

// Whitelist de unidades
$unidadesValidas = [
    'Prost Blindados — Pinheiros',
    'Prost Mecânica — Pacaembú',
    'Prost Funilaria — Barra Funda',
    'Sem preferência'
];
if (!in_array($data['unidade'], $unidadesValidas, true)) respond(422, 'Unidade inválida.');

// ═══ MAPEAMENTO UNIDADE → WHATSAPP ═══
$whatsappMap = [
    'Prost Blindados — Pinheiros'    => '5511947477869',
    'Prost Mecânica — Pacaembú'      => '5511949214860',
    'Prost Funilaria — Barra Funda'  => '5511977280908',
    'Sem preferência'                => '5511947477869',
];

// ═══ ENVIO ═══
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) { require $autoload; }
else {
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

    $mail->Subject = "Contato — {$data['nome']} — {$data['servico']}";
    $mail->isHTML(true);

    $dataEnvio = date('d/m/Y \à\s H:i');
    $whatsapp = $whatsappMap[$data['unidade']] ?? '5511947477869';

    $mail->Body = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"></head>
<body style="margin:0; padding:0; font-family:Arial,sans-serif; background:#f4f4f4;">
<div style="max-width:600px; margin:20px auto; background:#fff; border-radius:4px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <div style="background:#0e0e0e; padding:28px 32px; text-align:center;">
        <h1 style="color:#c9a84c; margin:0; font-size:20px; font-weight:700; letter-spacing:1px;">NOVA MENSAGEM DE CONTATO</h1>
        <p style="color:#888; margin:8px 0 0; font-size:12px;">{$dataEnvio}</p>
    </div>
    <div style="padding:32px;">
        <table style="width:100%; border-collapse:collapse; font-size:14px; color:#333;">
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; width:140px; font-size:12px; text-transform:uppercase; letter-spacing:0.5px;">Nome</td>
                <td style="padding:14px 0; font-weight:600; font-size:15px;">{$data['nome']}</td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; font-size:12px; text-transform:uppercase;">Telefone</td>
                <td style="padding:14px 0;"><a href="https://wa.me/{$whatsapp}" style="color:#c9a84c; text-decoration:none;">{$data['telefone']}</a></td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; font-size:12px; text-transform:uppercase;">E-mail</td>
                <td style="padding:14px 0;"><a href="mailto:{$data['email']}" style="color:#c9a84c; text-decoration:none;">{$data['email']}</a></td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; font-size:12px; text-transform:uppercase;">Unidade</td>
                <td style="padding:14px 0;">{$data['unidade']}</td>
            </tr>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:14px 0; color:#888; font-size:12px; text-transform:uppercase;">Serviço</td>
                <td style="padding:14px 0; font-weight:600;">{$data['servico']}</td>
            </tr>
            <tr>
                <td style="padding:14px 0; color:#888; font-size:12px; text-transform:uppercase; vertical-align:top;">Mensagem</td>
                <td style="padding:14px 0; line-height:1.7;">{$data['mensagem']}</td>
            </tr>
        </table>
    </div>
    <div style="background:#f9f9f9; padding:16px 32px; font-size:11px; color:#aaa; text-align:center; border-top:1px solid #eee;">
        Enviado via <a href="https://grupoprost.com.br" style="color:#c9a84c; text-decoration:none;">grupoprost.com.br</a>
    </div>
</div>
</body>
</html>
HTML;

    $mail->AltBody = "CONTATO — GRUPO PROST\n{$dataEnvio}\n\nNome: {$data['nome']}\nTelefone: {$data['telefone']}\nE-mail: {$data['email']}\nUnidade: {$data['unidade']}\nServiço: {$data['servico']}\n\nMensagem:\n{$data['mensagem']}\n\nEnviado via grupoprost.com.br";

    $mail->send();
    logEntry('ENVIADO', $data);
    respond(200, 'Mensagem enviada com sucesso! Entraremos em contato em breve.');

} catch (Exception $e) {
    logEntry('ERRO_SMTP: ' . $mail->ErrorInfo, $data);
    respond(500, 'Erro ao enviar mensagem. Tente novamente.');
}