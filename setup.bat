@echo off
echo ========================================
echo   SETUP APLIKASI GUDANG ARSIP
echo ========================================
echo.

REM Cek apakah di folder project
if not exist "package.json" (
    echo ❌ ERROR: package.json tidak ditemukan!
    echo.
    echo Pastikan Command Prompt sudah di folder project:
    echo   cd path\ke\gudang-arsip
    echo.
    pause
    exit /b
)

echo ✅ Folder project benar!
echo.

REM Step 1: Cek schema.prisma
echo [Step 1/5] Cek file schema.prisma...
if not exist "prisma\schema.prisma" (
    echo ❌ ERROR: prisma\schema.prisma tidak ditemukan!
    pause
    exit /b
)

echo ✅ File schema.prisma ditemukan!
echo.

REM Step 2: Cek line datasource di schema.prisma
echo [Step 2/5] Cek datasource di schema.prisma...
findstr /C:"datasource db" "prisma\schema.prisma" >nul
if %errorlevel% neq 0 (
    echo ⚠️  WARNING: "datasource db" tidak ditemukan di schema.prisma
    echo.
    echo Mencoba mencari typo "datasource db" tanpa 'a'...
    findstr /C:"datasource db" "prisma\schema.prisma" >nul
    if %errorlevel% equ 0 (
        echo ❌ ERROR: Typo ditemukan! "datasource db" seharusnya "datasource db"
        echo.
        echo Silakan perbaiki secara manual:
        echo   1. Buka prisma\schema.prisma
        echo   2. Cari line 8
        echo   3. Ganti "datasource db" menjadi "datasource db"
        echo   4. Save dan jalankan script ini lagi
        echo.
        pause
        exit /b
    )
) else (
    echo ✅ "datasource db" ditemukan dengan benar!
)
echo.

REM Step 3: Clean Prisma cache
echo [Step 3/5] Clean Prisma cache...
if exist "node_modules\.prisma" (
    rmdir /s /q "node_modules\.prisma" 2>nul
    echo ✅ Prisma cache berhasil dihapus!
) else (
    echo ℹ️  Prisma cache tidak ada, tidak perlu dihapus
)
echo.

REM Step 4: Generate Prisma Client
echo [Step 4/5] Generating Prisma Client...
call npx prisma generate

if errorlevel 1 (
    echo.
    echo ❌ ERROR: Gagal generate Prisma Client!
    echo.
    echo Solusi:
    echo   1. Pastikan Node.js terinstall: node --version
    echo   2. Pastikan file prisma\schema.prisma ada dan benar
    echo   3. Cek line 8 di prisma\schema.prisma harus: datasource db {
    echo   4. Jika masih gagal, cek file .env dan pastikan DATABASE_URL ada
    echo.
    pause
    exit /b
)

echo ✅ Prisma Client berhasil digenerate!
echo.

REM Step 5: Selesai
echo [Step 5/5] Setup selesai!
echo.
echo ========================================
echo   SIAP JALANKAN APLIKASI!
echo ========================================
echo.
echo Jalankan perintah ini:
echo    npm run dev
echo.
echo Lalu buka browser:
echo    http://localhost:3000
echo.
echo Login dengan salah satu akun:
echo    admin / admin123
echo    kassubag / kassubag123
echo    staff / staff123
echo    hanif / hanif123
echo    susi / susi123
echo.
echo ========================================
echo.

pause
