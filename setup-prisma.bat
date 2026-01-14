@echo off
echo ========================================
echo   SETUP PRISMA CLIENT
echo ========================================
echo.

REM Cek apakah ada node_modules
if not exist "node_modules" (
    echo [1/3] node_modules tidak ada, jalankan: npm install
    echo.
    pause
    exit /b
)

echo [1/3] Cleaning Prisma cache...
rmdir /s /q node_modules\.prisma 2>nul

echo [2/3] Generating Prisma Client...
call npx prisma generate

if errorlevel 1 (
    echo.
    echo ❌ ERROR: Gagal generate Prisma Client
    echo.
    echo Solusi:
    echo 1. Pastikan Node.js terinstall
    echo 2. Pastikan file prisma/schema.prisma ada dan benar
    echo 3. Cek line 9: harus "datasource db {" (bukan "datasource db {")
    echo.
    pause
    exit /b
)

echo [3/3] ✅ Prisma Client berhasil digenerate!
echo.
echo ========================================
echo   SELESAI! SILAKAN JALANKAN:
echo   npm run dev
echo ========================================
echo.
pause
