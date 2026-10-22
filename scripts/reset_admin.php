<?php
/**
 * PlantHR Lite - Emergency Administrator Password Reset CLI Tool
 * Digunakan oleh Tim IT / Database Administrator ketika HR Admin lupa kata sandi.
 * 
 * Penggunaan via Terminal / PowerShell:
 *   php scripts/reset_admin.php [email] [password_baru]
 * 
 * Contoh:
 *   php scripts/reset_admin.php hr@plant.local admin123
 */

if (php_sapi_name() !== 'cli') {
    die("Akses ditolak. Skrip ini hanya dapat dijalankan melalui CLI Terminal.\n");
}

echo "========================================================\n";
echo " PlantHR Lite - Emergency Admin Password Reset Tool\n";
echo "========================================================\n";

// Muat konfigurasi database
$db_host = getenv('DB_HOST') ?: '127.0.0.1';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db_name = getenv('DB_NAME') ?: 'planthr_db';
$db_port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

try {
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("[ERROR] Gagal terhubung ke basis data MySQL: " . $e->getMessage() . "\n");
}

$target_email = isset($argv[1]) ? trim($argv[1]) : 'hr@plant.local';
$new_password = isset($argv[2]) ? trim($argv[2]) : 'admin123';

// Cari user berdasarkan email atau role HR_ADMIN
$stmt = $pdo->prepare("SELECT id, nip, full_name, email, role FROM employees WHERE email = ? LIMIT 1");
$stmt->execute([$target_email]);
$user = $stmt->fetch();

if (!$user) {
    // Fallback: cari HR_ADMIN pertama
    $stmt = $pdo->query("SELECT id, nip, full_name, email, role FROM employees WHERE role = 'HR_ADMIN' LIMIT 1");
    $user = $stmt->fetch();
    if (!$user) {
        die("[ERROR] Tidak ditemukan akun dengan email '{$target_email}' maupun role HR_ADMIN di database.\n");
    }
}

$hash = password_hash($new_password, PASSWORD_BCRYPT);
$update_stmt = $pdo->prepare("UPDATE employees SET password = ?, updated_at = NOW() WHERE id = ?");
$update_stmt->execute([$hash, $user['id']]);

echo "[BERHASIL] Kata sandi berhasil direset!\n";
echo "--------------------------------------------------------\n";
echo " NIP         : " . $user['nip'] . "\n";
echo " Nama Lengkap: " . $user['full_name'] . "\n";
echo " Peran (Role): " . $user['role'] . "\n";
echo " Email Login : " . $user['email'] . "\n";
echo " Sandi Baru  : " . $new_password . "\n";
echo "--------------------------------------------------------\n";
echo " Silakan login kembali di portal web: /auth/login\n";
echo "========================================================\n";
