<?php
function generateRandomString($length = 10) {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

function encryptFile($filePath, $key) {
    $data = file_get_contents($filePath);

    // Generate IV (16 bytes)
    $iv = openssl_random_pseudo_bytes(16);

    // Encrypt data
    $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

    // mengenerate nama file random dan menyimpan enkripsi
    $randomFilename = generateRandomString() . '.enc';
    $encryptedFilePath = 'uploads/' . $randomFilename;
    file_put_contents($encryptedFilePath, $iv . $encryptedData);

    // mengembalikan nama file dan menghapus file asli
    unlink($filePath);
    return $randomFilename;
}

if (isset($_GET['file'])) {
    $filePath = urldecode($_GET['file']);
    $key = 'your-encryption-key-32-bytes-long'; // 32 bytes key for AES-256
    $randomFilename = encryptFile($filePath, $key);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>File Encrypted</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-success" role="alert">
                File telah dienkripsi. Kode acak untuk dekripsi: <strong><?php echo htmlspecialchars($randomFilename); ?></strong>
            </div>
            <a href="index.php" class="btn btn-primary">Kembali ke halaman utama</a>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
}
?>
