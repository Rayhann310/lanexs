<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Tidak Ditemukan - LANEX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#4e73df', secondary: '#224abe' }, fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-3xl shadow-xl max-w-md w-full text-center">
        <div class="w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <h2 class="text-2xl font-bold mb-2">Resi Tidak Ditemukan</h2>
        <p class="text-gray-500 mb-8">Maaf, paket dengan nomor resi <strong class="text-gray-800"><?= htmlspecialchars($resi) ?></strong> tidak dapat kami temukan di dalam sistem.</p>
        <a href="<?= BASE_URL ?>/" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-xl font-bold transition flex items-center justify-center">
            <i class="bi bi-arrow-left mr-2"></i> Kembali ke Beranda
        </a>
    </div>
</body>
</html>
