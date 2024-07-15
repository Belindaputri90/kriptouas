<?php
function decryptFile($filePath, $key) {
    $data = file_get_contents($filePath);

    // Extract IV (16 bytes) dan dekripsi data
    $iv = substr($data, 0, 16);
    $encryptedData = substr($data, 16);

    // dekripsi data
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);

    // menyimpan file dengan nama asli
    $decryptedFilePath = str_replace('.enc', '.jpg', $filePath);
    file_put_contents($decryptedFilePath, $decryptedData);

    // menjalankan file dekripsi untuk di download
    header('Content-Disposition: attachment; filename="' . basename($decryptedFilePath) . '"');
    readfile($decryptedFilePath);

    // hapus file dekripsi setelah di download dari folders uploads
    unlink($decryptedFilePath);
}

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $key = 'your-encryption-key-32-bytes-long'; // 32 bytes key for AES-256
    $filePath = 'uploads/' . $code;

    if (file_exists($filePath)) {
        decryptFile($filePath, $key);
    } else {
        $errorMessage = "File tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decrypt Image</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Decrypt Image</h1>

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="decrypt.php" method="post">
            <div class="form-group">
                <label for="code">Masukkan kode acak untuk dekripsi (nama file terenkripsi):</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <button type="submit" class="btn btn-primary">Decrypt</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
