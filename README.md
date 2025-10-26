# 🪶 Kawruh Linuwih  
> Ilmu yang lebih, unggul, dan memberi penerang bagi kehidupan.

---

## 🧭 Ringkasan
**Kawruh Linuwih** adalah platform pembelajaran daring gratis berbasis **Laravel 12** dan **Filament 4**,  
dilengkapi dengan integrasi **AI Groq** untuk menghadirkan pengalaman belajar yang lebih interaktif, adaptif, dan cerdas.  

Proyek ini bertujuan menyebarkan ilmu yang bermanfaat (*kawruh migunani*) kepada semua orang,  
dengan semangat *ngudi kawruh* — mencari pengetahuan yang luhur dan berguna bagi sesama.

---

## ⚙️ Teknologi Utama
| Komponen | Deskripsi |
|-----------|------------|
| **Laravel 12** | Framework backend utama |
| **Filament 4** | Admin panel, dashboard, dan halaman pembelajaran |
| **Alpine.js & TailwindCSS** | Interaktivitas ringan & tampilan modern |
| **MySQL / PostgreSQL** | Basis data utama |
| **Groq AI API** | Generasi otomatis quiz, ringkasan materi, dan insight pembelajaran |
| **Laravel Pennant / Breeze** | Autentikasi & manajemen fitur |
| **Spatie Permission** | Role & hak akses pengguna |

---

## 🧩 Fitur Utama
- 🔐 **Autentikasi Pengguna**  
  Login, registrasi, dan reset password.

- 📚 **Manajemen Course & Lesson (Filament Resource)**  
  Admin dapat membuat course, mengunggah materi, dan mengatur urutan pelajaran.

- 🤖 **AI Groq Integration**  
  - Membuat soal/quiz otomatis dari materi.  
  - Merangkum materi panjang menjadi poin penting.  
  - Memberikan rekomendasi course berdasarkan pola belajar pengguna.  

- ▶️ **Halaman Belajar (Course Player)**  
  - Tampilan ringkas untuk video, teks, atau PDF.  
  - Navigasi next/previous antar lesson.  

- ✅ **Progress Tracking**  
  - Menandai lesson yang sudah diselesaikan.  
  - Statistik belajar pengguna.

---

## 🗂️ Struktur Halaman
| Path | Deskripsi |
|------|------------|
| `/login` | Halaman login |
| `/dashboard-student` | Dashboard student & detail course |
| `/stage-content/{slug}` | Halaman Belajar |
| `/admin` | Panel Filament (manajemen course, user, AI setting) |

---

## 🧱 Struktur Database (Rencana Awal)
- **users** — bawaan Laravel (id, name, email, password, role)  
- **projects** — (id, title, description, difficulty, created_by)  
- **project_stages** — (id, project_id, title, instructions, order)  
- **student_projects** — (id, user_id, project_id, status)  
- **student_project_stages** — (id, student_project_id, user_id, status, created_at)
- **quizzes** — (id, student_project_stage_id, score, total_question)
- **quiz_answer** — (id, quiz_id, question, options_json, selected_answer, is_correct)

---

## 🚀 Instalasi
```bash
# 1. Clone repo
git clone https://github.com/username/kawruh-linuwih.git
cd kawruh-linuwih

# 2. Install dependency
composer install
npm install && npm run build

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 4. Migrasi database
php artisan migrate --seed

# 5. Jalankan server
php artisan serve
```

---

## 🤖 Integrasi AI Groq
Tambahkan kredensial Groq di file `.env`:

```
GROQ_API_KEY=your_api_key_here
```

Contoh penggunaan di Laravel service:

```php
use App\Services\GroqService;

$response = app(GroqService::class)->generateQuizFromLesson($lessonContent);
```

---

## 🌸 Filosofi
> *“Ngudi kawruh sing linuwih, ora mung kanggo pinter, nanging kanggo migunani.”*  
> — Mencari ilmu yang unggul, bukan hanya untuk kepintaran, tetapi untuk memberi manfaat.

---

## 🧑‍💻 Pengembang
Proyek ini dikembangkan oleh **Ahmad**,  
sebagai bagian dari inisiatif membangun ekosistem pembelajaran teknologi berbasis budaya lokal.

---

## 🕊️ Lisensi
Proyek ini dirilis di bawah **MIT License**.  
Silakan gunakan, modifikasi, dan kembangkan untuk tujuan pembelajaran dan riset.

---

> 🪷 *Kawruh Linuwih — Ilmu yang membawa keunggulan dan kebermanfaatan.*
