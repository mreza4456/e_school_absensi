<!-- index.html -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absensi Kartu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-b from-teal-600 to-teal-800">
  <div class="bg-white p-10 rounded-2xl shadow-2xl w-[750px] text-center">
     <div class="flex justify-between mb-10">
<h1 class="text-xl">E-School Absensi</h1>
<h1 class="items-center item-center text-xl">SMKN 1 Pasuruan</h1>
     </div>
    <div class="grid grid-cols-2">

   <dotlottie-wc
  src="https://lottie.host/64e55d48-45bf-459c-96aa-b22ce8b11995/E7avVDqXBe.lottie"
  style="width: 300px;height: 300px"
  speed="3"
  autoplay
  loop
></dotlottie-wc>
    <div class="flex flex-col justify-center">
        <h2 class="text-gray-900"> - Time Now - </h2>
    <div id="clock" class="text-5xl font-mono font-semibold  mb-6"></div>
    
</div>
 </div>
 <p class="text-gray-500 mb-10">Tempelkan kartu Anda di mesin pembaca</p>
    <!-- Jam real-time -->
   
 <button onclick="tapCard()" 
      class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition">
      Simulasikan Tap
    </button> 
    <div id="status" class="mt-4 font-semibold"></div>
  </div>
  <script
  src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js"
  type="module"
></script>


  <script>
    // Update jam setiap detik
    function updateClock() {
      const now = new Date();
      const timeString = now.toLocaleTimeString("id-ID", { hour12: false });
      document.getElementById("clock").textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function tapCard() {
      const statusEl = document.getElementById("status");

      // simulasi response (acak berhasil/gagal)
      const success = Math.random() > 0.5;

      if (success) {
        statusEl.textContent = "✅ Absen berhasil, mengalihkan...";
        statusEl.className = "mt-4 font-semibold text-green-600";
        setTimeout(() => {
          window.location.href = "profile";
        }, 1500);
      } else {
        statusEl.textContent = "❌ Absen gagal";
        statusEl.className = "mt-4 font-semibold text-red-600";
      }
    }
  </script>
</body>
</html>
