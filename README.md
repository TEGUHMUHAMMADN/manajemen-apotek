# 💊 Aplikasi Manajemen Apotek

Panduan lengkap untuk melakukan *clone* dan menjalankan proyek ini di komputer lokal (*local development*).

---

## 🛠️ Stack & Teknologi Utama

* **Backend Framework:** Laravel  
* **Authentication:** Laravel Fortify (Headless Authentication Backend)  
* **Authorization & Roles:** Spatie Laravel Permission
* **Frontend:** Vue.js \+ Inertia.js (Single Page Application experience)  
* **Styling:** Tailwind CSS

---

## 📋 Prasyarat

Sebelum memulai, pastikan komputer Anda sudah menginstal:

* **PHP** (Versi 8.3.16, sesuai dengan yang digunakan pada Laragon)  
* **Composer**  
* **Node.js** & **NPM**  
* **Git**

---

## ⚙️ Langkah-langkah Instalasi

### 1\. Clone Repository

Buka terminal di komputer Anda, lalu jalankan perintah berikut:

git clone https://github.com/alif032/manajemen-apotek.git

cd manajemen-apotek

### 2\. Install Dependensi PHP (Backend)

Jalankan perintah ini untuk menginstal *package* Laravel/PHP:

composer install

### 3\. Install Dependensi Node.js (Frontend / Inertia & Vue)

Jalankan perintah ini untuk menginstal *package* JavaScript:

npm install

### 4\. Konfigurasi Environment (`.env`)

Salin file contoh konfigurasi dan *generate* kunci aplikasi:

cp .env.example .env

php artisan key:generate

Buka file `.env` menggunakan teks editor (VS Code), lalu sesuaikan konfigurasi database Anda:

DB\_CONNECTION=mysql

DB\_HOST=127.0.0.1

DB\_PORT=3306

DB\_DATABASE=nama\_database\_lokal

DB\_USERNAME=root

DB\_PASSWORD=

### 5\. Catatan Khusus untuk Laravel Fortify & Vue

Karena proyek ini menggunakan Laravel Fortify sebagai *backend-driven authentication* yang digabungkan dengan Vue.js dan Inertia.js, pastikan hal berikut terpenuhi:

* Fortify akan otomatis menangani *routing* untuk *login*, *register*, dan *password reset* yang terhubung langsung ke komponen Vue di sisi *frontend*.  
* Pastikan tabel `users` sudah terbentuk dengan benar melalui migrasi database.

### 6\. Migrasi & Seeder Database

Jalankan perintah berikut untuk membuat tabel database dan mengisi data awal:

php artisan migrate \--seed

### 7\. Menjalankan Server Lokal

Anda perlu membuka **dua tab terminal** yang berbeda untuk menjalankan *backend* dan *frontend* secara bersamaan:

* **Terminal 1 (Menjalankan Laravel Server):**  
    
  php artisan serve  
    
* **Terminal 2 (Menjalankan Vite untuk Asset Vue/Inertia):**  
    
  npm run dev

Buka browser dan akses tautan lokal yang muncul di terminal (biasanya `http://127.0.0.1:8000`).