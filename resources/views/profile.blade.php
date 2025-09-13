<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengguna</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-2xl shadow-2xl w-[400px] text-center">
    <img src="https://via.placeholder.com/120" alt="Foto Profil" 
         class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-teal-600">
    <h1 class="text-2xl font-bold text-gray-800">Rizky Mahasiswa</h1>
    <p class="text-gray-500 mb-4">ID: 123456</p>
    
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-semibold inline-block mb-4">
      ✅ Absen Berhasil
    </div>

    <div class="text-left space-y-2">
      <p><span class="font-semibold">Program Studi:</span> Informatika</p>
      <p><span class="font-semibold">Tanggal:</span> <span id="date"></span></p>
      <p><span class="font-semibold">Jam:</span> <span id="time"></span></p>
    </div>

    <button onclick="window.location.href='index.html'" 
      class="mt-6 w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition">
      Kembali
    </button>
  </div>

  <script>
    const date = new Date();
    document.getElementById("date").textContent = date.toLocaleDateString("id-ID");
    document.getElementById("time").textContent = date.toLocaleTimeString("id-ID");
  </script>
</body>
</html>
