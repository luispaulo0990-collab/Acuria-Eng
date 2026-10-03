<?php
/**
 * ACURIA - Processador Seguro de Contatos e Leads (Hostinger Ready)
 * 1. Sanitização e proteção anti-spam (Honeypot)
 * 2. Armazenamento em Banco de Dados Relacional SQLite (leads.sqlite)
 * 3. Envio de notificação imediata para acuria.engenharia@gmail.com
 */

header('Content-Type: application/json; charset=utf-8');

// Permite apenas método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

// 1. Verificação Anti-Spam (Honeypot)
if (!empty($_POST['website_hp'])) {
    // Resposta falsa de sucesso para bots
    echo json_encode(['success' => true, 'message' => 'Recebido']);
    exit;
}

// 2. Sanitização e Validação dos Campos
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$empresa = filter_input(INPUT_POST, 'empresa', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$linha = filter_input(INPUT_POST, 'linha', FILTER_SANITIZE_SPECIAL_CHARS);
$fase = filter_input(INPUT_POST, 'fase', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$nome || mb_strlen(trim($nome)) < 3) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Por favor, informe um nome completo válido.']);
    exit;
}

if (!$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Por favor, informe um e-mail corporativo válido.']);
    exit;
}

if (!$empresa || mb_strlen(trim($empresa)) < 2) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Por favor, informe o nome da sua empresa / incorporadora.']);
    exit;
}

$linha = $linha ?: 'Não especificada';
$fase = $fase ?: 'Não informada';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido';
$data_envio = date('Y-m-d H:i:s');

// 3. Gravação no Banco de Dados SQLite
$dbDir = __DIR__ . '/database';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

$dbPath = $dbDir . '/leads.sqlite';
$dbSaved = false;

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cria tabela com índices se não existir
    $db->exec("CREATE TABLE IF NOT EXISTS leads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        empresa TEXT NOT NULL,
        email TEXT NOT NULL,
        linha TEXT,
        fase TEXT,
        ip TEXT,
        data_hora DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $db->prepare("INSERT INTO leads (nome, empresa, email, linha, fase, ip, data_hora) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $empresa, $email, $linha, $fase, $ip, $data_envio]);
    $dbSaved = true;
} catch (Exception $e) {
    error_log("ACURIA DB Error: " . $e->getMessage());
}

// 4. Envio de Notificação por E-mail para acuria.engenharia@gmail.com
$destinatario = 'acuria.engenharia@gmail.com';
$assunto = "=?UTF-8?B?" . base64_encode("Novo Lead ACURIA: {$nome} ({$empresa})") . "?=";

$mensagemHtml = "
<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0A111E; color: #E7ECF3; padding: 24px; margin: 0; }
    .card { background: #0E1626; border: 1px solid #1E293B; border-radius: 8px; max-width: 600px; margin: 0 auto; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .header { background: #0A111E; padding: 20px 24px; border-bottom: 2px solid #0D9488; }
    .header h2 { margin: 0; font-size: 18px; color: #FFFFFF; font-weight: 500; letter-spacing: 0.12em; }
    .header h2 span { color: #5EEAD4; }
    .body { padding: 24px; }
    .field { margin-bottom: 16px; border-bottom: 1px solid rgba(71,85,105,0.25); padding-bottom: 12px; }
    .field:last-child { border-bottom: none; }
    .label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.12em; color: #94A3B8; margin-bottom: 4px; font-weight: 600; }
    .value { font-size: 15px; color: #FFFFFF; line-height: 1.4; }
    .footer { background: #070D18; padding: 14px 24px; font-size: 12px; color: #64748B; text-align: center; }
  </style>
</head>
<body>
  <div class='card'>
    <div class='header'>
      <h2>ACURIA <span>| NOVO LEAD RECEBIDO</span></h2>
    </div>
    <div class='body'>
      <div class='field'>
        <div class='label'>Nome Completo</div>
        <div class='value'><strong>" . htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') . "</strong></div>
      </div>
      <div class='field'>
        <div class='label'>Empresa / Incorporadora</div>
        <div class='value'>" . htmlspecialchars($empresa, ENT_QUOTES, 'UTF-8') . "</div>
      </div>
      <div class='field'>
        <div class='label'>E-mail Corporativo</div>
        <div class='value'><a href='mailto:" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "' style='color: #2DD4BF; text-decoration: none; font-weight: 500;'>" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</a></div>
      </div>
      <div class='field'>
        <div class='label'>Linha do Empreendimento</div>
        <div class='value'>" . htmlspecialchars($linha, ENT_QUOTES, 'UTF-8') . "</div>
      </div>
      <div class='field'>
        <div class='label'>Fase Atual do Projeto / Observações</div>
        <div class='value'>" . nl2br(htmlspecialchars($fase, ENT_QUOTES, 'UTF-8')) . "</div>
      </div>
      <div class='field'>
        <div class='label'>Data e Horário do Envio</div>
        <div class='value'>" . date('d/m/Y H:i:s') . "</div>
      </div>
    </div>
    <div class='footer'>
      Enviado automaticamente pelo formulário do site www.acuria.eng.br
    </div>
  </div>
</body>
</html>
";

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=utf-8';
$headers[] = 'From: ACURIA Notificações <contato@acuria.eng.br>';
$headers[] = 'Reply-To: ' . $nome . ' <' . $email . '>';
$headers[] = 'X-Mailer: PHP/' . phpversion();

$mailSent = @mail($destinatario, $assunto, $mensagemHtml, implode("\r\n", $headers));

// Retorna resposta de sucesso para o frontend
echo json_encode([
    'success' => true,
    'message' => 'Solicitação enviada com sucesso.',
    'db_saved' => $dbSaved,
    'mail_sent' => $mailSent
]);
