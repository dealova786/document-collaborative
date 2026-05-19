Document Collaborative

Sistem collaborative document editor ini berbasis laravel yang memungkinkan beberapa pengguna mengedit dokumen secara real-time.

Fitur yang digunakan:
1. Login & Register
2. CRUD Dokumen
3. Realtime Collaborative Editing
4. Realtime Cursor
5. Realtime Typing Indicator
6. Riwayat Perubahan Dokumen (version historys)
7. Dashboard User

Teknologi yang Digunakan
- Laravel
- Laravel Reverb
- Bootstrap 
- MySQL
- JavaScript

Cara Menjalankan Project:
1. Clone Repository
   git clone https://github.com/dealova786/document-collaborative.git
2. Masuk ke Folder Project
3. Install Dependency Laravel
   composer Install
4. Install Dependency JavaScript
   npm install
5. Copy File Environment
   copy .env.example .env
6. Atur Database
   Buka file .env lalu ubah bagian database sesuai database MySQL yang digunakan
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=document_collaborative
   DB_USERNAME=root
   DB_PASSWORD=
7. Jalankan Migrasi Database
   php artisan migrate
8. Jalankan Laravel Serve
   php artisan serve
10. Jalankan Vite
    npm rub dev
11. Jalankan Laravel Reverb
    php artisan reverb:start


Author
Dea Lova Asmara
