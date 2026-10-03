<?php
/**
 * ACURIA - Painel Seguro de Gestão de Leads
 * Acesso protegido por chave/senha para visualização e exportação CSV
 */

// DEFINA SUA SENHA DE ACESSO AQUI:
$senhaMestra = 'acuria2026';

$autenticado = false;
session_start();

if (isset($_GET['sair'])) {
    session_destroy();
    header('Location: admin-leads.php');
    exit;
}

if (isset($_POST['senha']) && $_POST['senha'] === $senhaMestra) {
    $_SESSION['acuria_admin'] = true;
}

if (!empty($_SESSION['acuria_admin']) || (isset($_GET['token']) && $_GET['token'] === $senhaMestra)) {
    $autenticado = true;
}

$dbPath = __DIR__ . '/database/leads.sqlite';
$leads = [];

if ($autenticado && file_exists($dbPath)) {
    try {
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Exportação CSV
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=leads_acuria_' . date('Y-m-d') . '.csv');
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 para Excel abrir com acentuação correta
            fputcsv($output, ['ID', 'Data/Hora', 'Nome', 'Empresa', 'E-mail', 'Linha', 'Fase/Notas', 'IP'], ';');
            $stmt = $db->query("SELECT id, data_hora, nome, empresa, email, linha, fase, ip FROM leads ORDER BY id DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($output, $row, ';');
            }
            fclose($output);
            exit;
        }

        $stmt = $db->query("SELECT * FROM leads ORDER BY id DESC");
        $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $erroDb = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ACURIA | Gestão de Leads</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Roboto+Mono:wght@400;500&family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #0A111E; color: #E7ECF3; padding: 24px; min-height: 100vh; }
    .container { max-width: 1200px; margin: 0 auto; }
    .header { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #1E293B; padding-bottom: 20px; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
    .brand { font-family: 'Urbanist', sans-serif; font-size: 20px; letter-spacing: 0.14em; font-weight: 600; color: #FFFFFF; }
    .brand span { color: #0D9488; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 4px; font-size: 13px; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: 0.2s; }
    .btn-primary { background: #0D9488; color: #FFFFFF; }
    .btn-primary:hover { background: #14B8A6; }
    .btn-outline { background: transparent; border: 1px solid #334155; color: #94A3B8; }
    .btn-outline:hover { background: #1E293B; color: #FFFFFF; }
    .card { background: #0E1626; border: 1px solid #1E293B; border-radius: 8px; padding: 24px; margin-bottom: 24px; }
    .table-responsive { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    th { padding: 12px 16px; background: #070D18; color: #94A3B8; font-family: 'Roboto Mono', monospace; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 1px solid #1E293B; }
    td { padding: 14px 16px; border-bottom: 1px solid rgba(71,85,105,0.2); vertical-align: top; }
    tr:hover td { background: rgba(13, 148, 136, 0.04); }
    .mono { font-family: 'Roboto Mono', monospace; font-size: 12px; color: #94A3B8; }
    .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-family: 'Roboto Mono', monospace; background: rgba(13, 148, 136, 0.15); color: #5EEAD4; border: 1px solid rgba(45, 212, 191, 0.3); }
    .login-box { max-width: 400px; margin: 80px auto; background: #0E1626; border: 1px solid #1E293B; border-radius: 8px; padding: 32px; text-align: center; }
    .form-input { width: 100%; padding: 12px 16px; background: #070D18; border: 1px solid #334155; border-radius: 4px; color: #FFFFFF; font-size: 14px; margin: 16px 0; }
    .form-input:focus { outline: none; border-color: #0D9488; }
  </style>
</head>
<body>

  <div class="container">
    <?php if (!$autenticado): ?>
      <div class="login-box">
        <div class="brand" style="margin-bottom: 8px;">ACURIA <span>| LEADS</span></div>
        <p style="font-size: 13px; color: #94A3B8;">Acesso restrito à gestão de contatos</p>
        
        <form method="POST">
          <input type="password" name="senha" class="form-input" placeholder="Digite a senha mestra..." autofocus required>
          <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Acessar Painel</button>
        </form>
        <p style="margin-top: 14px; font-size: 11px; color: #64748B;">Senha padrão configurada: <code>acuria2026</code></p>
      </div>
    <?php else: ?>
      <div class="header">
        <div>
          <div class="brand">ACURIA <span>| GESTÃO DE LEADS</span></div>
          <p style="font-size: 12px; color: #94A3B8; margin-top: 4px;">Banco de Dados: <code>database/leads.sqlite</code> (Total: <?php echo count($leads); ?> leads)</p>
        </div>
        <div style="display: flex; gap: 10px;">
          <a href="?export=csv" class="btn btn-primary">⬇ Exportar para Excel (.CSV)</a>
          <a href="?sair=1" class="btn btn-outline">Sair</a>
        </div>
      </div>

      <div class="card">
        <?php if (empty($leads)): ?>
          <p style="text-align: center; padding: 40px; color: #94A3B8;">Nenhum lead registrado no banco de dados até o momento.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Data/Hora</th>
                  <th>Nome</th>
                  <th>Empresa</th>
                  <th>E-mail</th>
                  <th>Linha</th>
                  <th>Fase / Mensagem</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($leads as $l): ?>
                  <tr>
                    <td class="mono"><?php echo htmlspecialchars($l['data_hora']); ?></td>
                    <td><strong><?php echo htmlspecialchars($l['nome']); ?></strong></td>
                    <td><?php echo htmlspecialchars($l['empresa']); ?></td>
                    <td><a href="mailto:<?php echo htmlspecialchars($l['email']); ?>" style="color: #2DD4BF; text-decoration: none;"><?php echo htmlspecialchars($l['email']); ?></a></td>
                    <td><span class="badge"><?php echo htmlspecialchars($l['linha']); ?></span></td>
                    <td style="max-width: 320px; font-size: 13px; color: #CBD5E1;"><?php echo nl2br(htmlspecialchars($l['fase'])); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
