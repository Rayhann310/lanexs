<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Halaman Tidak Ditemukan - LANEXS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Outfit:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl p-10 md:p-14 text-center border border-slate-100">
        <div class="text-[8rem] leading-none font-bold text-slate-200 mb-4" style="font-family: 'Outfit', sans-serif;">
            404
        </div>
        <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center text-4xl mx-auto -mt-16 mb-6 relative z-10 shadow-sm border-4 border-white">
            <i class="bi bi-sign-turn-right-fill"></i>
        </div>
        
        <h1 class="text-3xl font-extrabold text-slate-900 mb-3" style="font-family: 'Outfit', sans-serif;">Halaman Tidak Ditemukan</h1>
        <p class="text-slate-500 mb-8 text-lg">Maaf, halaman yang Anda cari mungkin telah dihapus, diubah namanya, atau tidak pernah ada.</p>
        
        <a href="<?= BASE_URL ?>/" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5">
            <i class="bi bi-house-door-fill mr-2"></i> Kembali ke Beranda
        </a>
    </div>
</body>
</html>
