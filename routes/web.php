<?php

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LanguageController;
// Route::get('/', function () {
//     $siswa = Siswa::with(['uidType' => function($query) {
//         $query->where('type', 'face_id');
//     }])
//     ->whereHas('uidType', function($query) {
//         $query->where('type', 'face_id');
//     })
//     ->get()
//     ->map(function ($student) {
//         return [
//             'id' => $student->id,
//             'sekolah_id' => $student->sekolah_id,
//             'kelas_id' => $student->kelas_id,
//             'nama' => $student->nama,
//             'nis' => $student->nis,
//             'panggilan' => $student->panggilan,
//             'uid_type' => $student->uidType->map(function ($item) {
//                 try {
//                     if (json_decode($item->value)) {
//                         $item->value = json_decode($item->value, true);
//                     }
//                 } catch (\Exception $e) {
//                     // Keep original value if not valid JSON
//                 }
//                 return $item;
//             })
//         ];
//     });

//     return response()->json([
//         "Siswa" => $siswa
//     ]);
// });

Route::get('/', function () {
    return view('welcome');
});
Route::get('/profile', function () {
    return view('profile');
});

Route::get('/login', function () {
    return redirect('/admin');
})->name('login');



Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
