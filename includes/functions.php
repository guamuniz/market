<?php
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

function json_response(array $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function require_admin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function csrf_check(): void {
    $token = $_POST['csrf'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(419); exit('Sessão expirada. Atualize a página.');
    }
}

function track_event(string $event, ?string $target = null): void {
    try {
        $allowed = ['page_view','click','search','asset_open','chart_interaction','alert_view'];
        if (!in_array($event, $allowed, true)) $event = 'click';
        $sid = session_id() ?: bin2hex(random_bytes(16));
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
        $hash = hash('sha256', $ip . '|' . $ua . '|' . ($GLOBALS['config']['app']['key'] ?? ''));
        $path = substr($_SERVER['REQUEST_URI'] ?? '/', 0, 500);
        $stmt = db()->prepare('INSERT INTO analytics_events (session_key, visitor_hash, event_name, target, page_path, user_agent, created_at) VALUES (?,?,?,?,?,?,NOW())');
        $stmt->execute([$sid, $hash, $event, $target ? substr($target,0,190) : null, $path, $ua]);
    } catch (Throwable $e) {
        // Analytics não deve interromper a navegação pública.
    }
}

function http_get_json(string $url, array $headers = []): ?array {
    if (!function_exists('curl_init')) return null;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_USERAGENT => 'XYZ-Market/1.0',
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $body = curl_exec($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (!$body || $status < 200 || $status >= 300) return null;
    $data = json_decode($body, true);
    return is_array($data) ? $data : null;
}
