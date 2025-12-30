# Sistem Gudang Arsip - Deployment Guide

## 📦 Deployment Options

### Option 1: Vercel (Rekomendasi - GRATIS)
**Cocok untuk:** Next.js, Static hosting, Serverless functions

#### Langkah-langkah:
1. Push project ke GitHub/GitLab
2. Login ke [vercel.com](https://vercel.com)
3. Klik "Add New Project"
4. Import dari GitHub
5. Deploy - akan otomatis dalam 2-3 menit
6. Download dari URL: `https://your-project-name.vercel.app/gudang-arsip.zip`

---

### Option 2: Netlify (GRATIS)
**Cocok untuk:** Static sites, Next.js

#### Langkah-langkah:
1. Build project terlebih dahulu:
   ```bash
   npm run build
   ```
2. Login ke [netlify.com](https://app.netlify.com)
3. Drag & drop folder `.next` ke Netlify
4. Download dari URL yang diberikan

---

### Option 3: Railway / Render (GRATIS)
**Cocok untuk:** Full-stack apps dengan backend

#### Langkah-langkah:
1. Push project ke GitHub
2. Connect GitHub ke Railway/Render
3. Auto-deploy saat ada push baru
4. Download ZIP dari production URL

---

## ⚠️ CATATAN PENTING

### Database SQLite di Production:
- SQLite di Vercel adalah **read-only** (hanya bisa membaca, tidak bisa write/modify)
- Untuk fitur full CRUD (Create, Read, Update, Delete) di production, perlu:
  - Migrate ke PostgreSQL / MySQL (Railway, Render, Supabase)
  - ATAU gunakan service file storage khusus

### Database Alternatif (Gratis):
1. **Supabase** - PostgreSQL gratis, 500MB
   - Website: https://supabase.com
   - Ideal untuk production

2. **Neon** - PostgreSQL serverless gratis
   - Website: https://neon.tech

3. **PlanetScale** - MySQL serverless gratis
   - Website: https://planetscale.com

---

## 🎯 Solusi Cepat untuk Download ZIP

### Jika HANYA butuh download ZIP (bukan deploy):

#### Solusi A: Transfer langsung
1. Gunakan **FileZilla** atau **WinSCP** (SFTP client)
2. Connect ke server dengan kredensial SSH/SFTP
3. Download file: `/home/z/my-project/gudang-arsip.zip`

#### Solusi B: SCP Command
```bash
# Dari laptop Anda (terminal/PowerShell):
scp user@server-ip:/home/z/my-project/gudang-arsip.zip .
```

#### Solusi C: Download via Cloud
1. Upload ke Google Drive / Dropbox / OneDrive
2. Download dari laptop Anda

---

## 📝 Konfigurasi untuk Deployment

### Update `.env` untuk Production:
```env
# Database connection (gunakan PostgreSQL untuk production)
DATABASE_URL="postgresql://user:password@host:port/database"

# APP URL (akan di-set otomatis oleh deployment platform)
NEXT_PUBLIC_APP_URL="https://your-app.vercel.app"
```

### Update `package.json` scripts:
```json
{
  "scripts": {
    "dev": "next dev",
    "build": "next build",
    "start": "next start",
    "lint": "next lint",
    "export-zip": "node export-zip.js"
  }
}
```

---

## 🚀 Deployment ke Vercel (Langkah Detail)

### 1. Install Vercel CLI
```bash
bun i -g vercel
```

### 2. Login Vercel
```bash
vercel login
```

### 3. Deploy Project
```bash
vercel
```

### 4. Set Environment Variables
Di Vercel Dashboard:
- Settings → Environment Variables
- Tambahkan:
  - `DATABASE_URL` (dari database provider Anda)
  - `NEXT_PUBLIC_APP_URL`

### 5. Download ZIP
Setelah deploy:
1. Buka: `https://your-app.vercel.app/gudang-arsip.zip`
2. Download ke laptop

---

## 📊 Perbandingan Platform Hosting

| Platform | Gratis? | Next.js Support | Database | Custom Domain |
|----------|----------|---------------|----------|---------------|
| **Vercel** | ✅ Ya | ✅ Native | Postgres/MySQL | ✅ Ya |
| **Netlify** | ✅ Ya | ✅ Native | External | ✅ Ya |
| **Railway** | ✅ Ya | ✅ Ya | Postgres/MySQL | ✅ Ya |
| **Render** | ✅ Ya | ✅ Ya | Postgres/MySQL | ✅ Ya |
| **Heroku** | ❌ Tidak | ✅ Ya | Postgres/MySQL | ✅ Ya |

---

## ❓ Pertanyaan Umum

### Q: Apakah SQLite bisa dipakai di production?
**A:** Vercel/Netlify tidak mendukung file system write. Gunakan PostgreSQL/MySQL di production.

### Q: Bagaimana cara migrasi dari SQLite ke PostgreSQL?
**A:**
1. Buat database di Supabase/Neon
2. Update `.env` dengan connection string baru
3. Jalankan migration: `bunx prisma migrate deploy`
4. Jalan `bun run dev` untuk test

### Q: Apakah bisa auto-deploy saat push ke GitHub?
**A:** Ya! Connect GitHub repo ke Vercel, setiap push akan otomatis deploy.

---

## 💡 Tips Tambahan

1. **Test Production Build** sebelum deploy:
   ```bash
   bun run build
   ```

2. **Gunakan Production Database** untuk testing:
   - Create separate database untuk production
   - Jangan gunakan local database di production URL

3. **Backup Data** sebelum migrasi:
   - Export data dari SQLite
   - Import ke PostgreSQL

---

## 🎉 Ringkasan

**Untuk download ZIP:**
- Vercel: Deploy → `https://app-name.vercel.app/gudang-arsip.zip`
- Netlify: Deploy → `https://app-name.netlify.app/gudang-arsip.zip`

**Untuk aplikasi full production:**
- Database: Supabase / Neon / PlanetScale
- Hosting: Vercel / Railway / Render
- Domain: Bisa gunakan custom domain

---

**Silakan pilih deployment option yang cocok dengan kebutuhan Anda!**
