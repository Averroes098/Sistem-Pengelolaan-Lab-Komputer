# Implementation Guide - Database Changes

## 1. Sign-Up Process Implementation

### Routes (routes/web.php atau routes/auth.php)

```php
// Register routes
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::get('/profile/complete', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
```

### Controller - RegisteredUserController

```php
public function store(StoreUserRequest $request)
{
    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'is_profile_complete' => false, // Flag untuk routing ke profile completion
    ]);

    Auth::login($user);

    return redirect()->route('profile.edit')
        ->with('info', 'Silakan lengkapi data profil Anda untuk melanjutkan');
}
```

### Controller - ProfileController

```php
public function edit()
{
    return view('profile.complete-profile', [
        'user' => auth()->user()
    ]);
}

public function update(UpdateUserProfileRequest $request, User $user)
{
    $user->update($request->validated());
    $user->update(['is_profile_complete' => true]);

    return redirect()->route('dashboard')
        ->with('success', 'Profil berhasil dilengkapi!');
}
```

### Middleware - Register di app/Http/Kernel.php

```php
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware
        \App\Http\Middleware\CheckProfileComplete::class, // ADD THIS
    ],
];
```

---

## 2. Lab Availability Feature

### Model Methods - Laboratorium.php

```php
// Get only available labs
public static function available()
{
    return self::where('status', 'tersedia')->get();
}

// Update availability
public function updateStatus($status)
{
    $this->status = $status;
    $this->save();
}

// Get lab photo URL
public function getPhotoUrl()
{
    return $this->photo_lab ? asset('storage/' . $this->photo_lab) : asset('images/placeholder.jpg');
}
```

### Controller - LaboratoriumController

```php
public function index()
{
    $labs = Laboratorium::available()->with('alats')->get();
    return view('laboratorium.index', compact('labs'));
}

public function updateStatus(Request $request, Laboratorium $lab)
{
    $lab->updateStatus($request->status);
    return response()->json(['message' => 'Status lab berhasil diupdate']);
}

public function uploadPhoto(Request $request, Laboratorium $lab)
{
    $request->validate(['photo' => 'required|image|max:2048']);
    
    $path = $request->file('photo')->store('labs', 'public');
    $lab->photo_lab = $path;
    $lab->save();
    
    return back()->with('success', 'Foto lab berhasil diupload');
}
```

---

## 3. Equipment/Tool Management

### Model Methods - Alat.php

```php
// Get available tools
public static function available()
{
    return self::where('status_peminjaman', 'tersedia')->get();
}

// Get tools by lab
public static function byLab($labId)
{
    return self::where('lab_id', $labId)->get();
}

// Update status
public function updateStatus($status)
{
    $this->status_peminjaman = $status;
    $this->save();
}

// Update condition
public function updateKondisi($kondisi)
{
    $this->kondisi = $kondisi;
    $this->save();
}
```

### Controller - AlatController

```php
public function index($lab_id)
{
    $lab = Laboratorium::findOrFail($lab_id);
    $alats = Alat::byLab($lab_id)->get();
    
    return view('alat.index', compact('lab', 'alats'));
}

public function updateStatus(Request $request, Alat $alat)
{
    $alat->updateStatus($request->status);
    return response()->json(['message' => 'Status alat diupdate']);
}

public function updateKondisi(Request $request, Alat $alat)
{
    $alat->updateKondisi($request->kondisi);
    return response()->json(['message' => 'Kondisi alat diupdate']);
}
```

---

## 4. Schedule Management

### Model Methods - Jadwal.php

```php
// Get upcoming schedules
public static function upcoming()
{
    return self::where('tgl_jadwal', '>=', now()->date)
        ->orderBy('tgl_jadwal', 'asc')
        ->get();
}

// Get conflicts
public static function hasConflict($lab_id, $tgl_jadwal, $waktu_mulai, $waktu_selesai, $exclude_id = null)
{
    $query = self::where('lab_id', $lab_id)
        ->where('tgl_jadwal', $tgl_jadwal)
        ->whereIn('status', ['terjadwal', 'sedang berlangsung']);

    if ($exclude_id) {
        $query->where('id', '!=', $exclude_id);
    }

    // Check time overlap
    return $query->where(function($q) use ($waktu_mulai, $waktu_selesai) {
        $q->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
          ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai]);
    })->exists();
}
```

### Controller - JadwalController

```php
public function create()
{
    $labs = Laboratorium::available()->get();
    return view('jadwal.create', compact('labs'));
}

public function store(Request $request)
{
    $request->validate([
        'lab_id' => 'required|exists:laboratorium,id',
        'tgl_jadwal' => 'required|date|after:today',
        'waktu_mulai' => 'required|date_format:H:i',
        'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        'keterangan' => 'nullable|string',
    ]);

    if (Jadwal::hasConflict($request->lab_id, $request->tgl_jadwal, $request->waktu_mulai, $request->waktu_selesai)) {
        return back()->with('error', 'Jadwal bentrok dengan peminjaman lain');
    }

    Jadwal::create([
        'user_id' => auth()->id(),
        'lab_id' => $request->lab_id,
        'tgl_jadwal' => $request->tgl_jadwal,
        'waktu_mulai' => $request->waktu_mulai,
        'waktu_selesai' => $request->waktu_selesai,
        'status' => 'terjadwal',
        'keterangan' => $request->keterangan,
    ]);

    return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dibuat');
}
```

---

## 5. Document Management

### Controller - DocumentController

```php
public function store(Request $request, Laboratorium $lab)
{
    $request->validate([
        'tipe_dokumen' => 'required|in:SOP,Panduan Peminjaman,Laporan Kerusakan',
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'file' => 'required|file|max:10240', // Max 10MB
    ]);

    $path = $request->file('file')->store("documents/{$lab->id}", 'public');

    Document::create([
        'lab_id' => $lab->id,
        'tipe_dokumen' => $request->tipe_dokumen,
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'file_path' => $path,
        'uploaded_by' => auth()->id(),
    ]);

    return back()->with('success', 'Dokumen berhasil diupload');
}

public function destroy(Document $document)
{
    Storage::disk('public')->delete($document->file_path);
    $document->delete();
    
    return back()->with('success', 'Dokumen berhasil dihapus');
}
```

---

## 6. Loan Management with Condition Tracking

### Model Methods - Peminjaman.php

```php
public function logDamage()
{
    if ($this->kondisi_pengembalian === 'Rusak') {
        // Create damage report document
        Document::create([
            'lab_id' => $this->lab_id,
            'tipe_dokumen' => 'Laporan Kerusakan',
            'judul' => "Kerusakan - {$this->alat->nama_alat}",
            'deskripsi' => $this->catatan_kerusakan,
            'file_path' => null, // atau bisa ditambah file upload
            'uploaded_by' => auth()->id(),
        ]);
    }
}
```

### Controller - PeminjamanController

```php
public function store(Request $request)
{
    $request->validate([
        'lab_id' => 'required|exists:laboratorium,id',
        'alat_id' => 'required|exists:alat,id',
        'tgl_pinjam' => 'required|date|after_or_equal:today',
        'durasi_jam' => 'required|integer|min:1|max:24',
    ]);

    $alat = Alat::findOrFail($request->alat_id);
    if ($alat->status_peminjaman !== 'tersedia') {
        return back()->with('error', 'Alat tidak tersedia untuk dipinjam');
    }

    $tgl_kembali = Carbon::parse($request->tgl_pinjam)->addHours($request->durasi_jam);

    Peminjaman::create([
        'user_id' => auth()->id(),
        'lab_id' => $request->lab_id,
        'alat_id' => $request->alat_id,
        'tgl_pinjam' => $request->tgl_pinjam,
        'tgl_kembali' => $tgl_kembali,
        'durasi_jam' => $request->durasi_jam,
        'status_peminjaman' => 'pending', // Tunggu approval
    ]);

    return back()->with('success', 'Permintaan peminjaman dikirim');
}

public function returnItem(Request $request, Peminjaman $peminjaman)
{
    $request->validate([
        'kondisi_pengembalian' => 'required|in:Baik,Rusak,Hilang',
        'catatan_kerusakan' => 'nullable|string',
    ]);

    $peminjaman->update([
        'status_pengembalian' => 'sudah dikembalikan',
        'kondisi_pengembalian' => $request->kondisi_pengembalian,
        'catatan_kerusakan' => $request->catatan_kerusakan,
    ]);

    // Log jika ada kerusakan
    if ($request->kondisi_pengembalian !== 'Baik') {
        $peminjaman->logDamage();
    }

    return back()->with('success', 'Item berhasil dikembalikan');
}
```

---

## 7. MSI Dashboard Implementation

### Model - Laboratorium.php (Add Query Methods)

```php
// Get borrowing statistics
public static function borrowingStats($period = 'month')
{
    // Query based on period
    $dates = match($period) {
        'week' => [now()->subWeek(), now()],
        'month' => [now()->subMonth(), now()],
        'year' => [now()->subYear(), now()],
    };

    return Laboratorium::withCount([
        'peminjamans' => function($q) use ($dates) {
            $q->whereBetween('created_at', $dates);
        }
    ])->orderBy('peminjamans_count', 'desc')->get();
}

// Get damaged equipment
public static function damagedEquipment($period = 'month')
{
    $dates = match($period) {
        'week' => [now()->subWeek(), now()],
        'month' => [now()->subMonth(), now()],
        'year' => [now()->subYear(), now()],
    };

    return Peminjaman::where('kondisi_pengembalian', 'Rusak')
        ->whereBetween('created_at', $dates)
        ->with(['alat', 'laboratorium'])
        ->get();
}
```

### Controller - DashboardController (MSI)

```php
public function msiIndex()
{
    if (auth()->user()->level !== 'admin') {
        abort(403);
    }

    $data = [
        'borrowing_stats_month' => Laboratorium::borrowingStats('month'),
        'borrowing_stats_year' => Laboratorium::borrowingStats('year'),
        'damaged_equipment' => Laboratorium::damagedEquipment('month'),
        'most_borrowed_tools' => $this->getMostBorrowedTools(),
        'usage_trend' => $this->getUsageTrend(),
    ];

    return view('dashboard.msi', $data);
}

private function getMostBorrowedTools()
{
    return Alat::withCount('peminjamans')
        ->orderBy('peminjamans_count', 'desc')
        ->limit(10)
        ->get();
}

private function getUsageTrend($days = 30)
{
    $dates = collect();
    for ($i = $days; $i >= 0; $i--) {
        $dates->push(now()->subDays($i)->format('Y-m-d'));
    }

    return Peminjaman::whereDate('created_at', '>=', now()->subDays($days))
        ->get()
        ->groupBy(fn($p) => $p->created_at->format('Y-m-d'))
        ->map(fn($group) => $group->count());
}
```

---

## Testing Endpoints

```bash
# Test sign-up
POST /register
{
  "nama": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

# Test profile completion
PUT /profile/1
{
  "nim": "123456",
  "no_telp": "081234567890",
  "jenis_kelamin": "L",
  "program_studi": "Teknik Informatika",
  "angkatan": "2021"
}

# Test update lab status
POST /laboratorium/1/status
{
  "status": "tidak_tersedia"
}

# Test schedule creation
POST /jadwal
{
  "lab_id": 1,
  "tgl_jadwal": "2025-11-25",
  "waktu_mulai": "09:00",
  "waktu_selesai": "11:00",
  "keterangan": "Praktikum Pemrograman"
}

# Test borrow item
POST /peminjaman
{
  "lab_id": 1,
  "alat_id": 1,
  "tgl_pinjam": "2025-11-25",
  "durasi_jam": 2
}

# Test return item
PUT /peminjaman/1/return
{
  "kondisi_pengembalian": "Baik"
}

# Test return with damage
PUT /peminjaman/1/return
{
  "kondisi_pengembalian": "Rusak",
  "catatan_kerusakan": "Keyboard tidak berfungsi"
}
```

---

## Next Steps

1. ✅ Run migrations
2. ⏳ Create views for sign-up and profile completion
3. ⏳ Create views for lab browsing with photos
4. ⏳ Create views for equipment management
5. ⏳ Create views for scheduling
6. ⏳ Create views for document management
7. ⏳ Create MSI dashboard with charts
8. ⏳ Setup file upload storage
9. ⏳ Setup notifications for profile completion
10. ⏳ Setup email verification

---

Generated: November 20, 2025
