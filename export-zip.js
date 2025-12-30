#!/usr/bin/env node

/**
 * Script SEDERHANA untuk mengexport project Next.js ke ZIP
 * Cara pakai:
 * 1. Jalankan: bun run export-zip
 * 2. File ZIP akan dibuat di: gudang-arsip.zip
 */

const fs = require('fs')
const path = require('path')
const archiver = require('archiver')

const projectDir = __dirname
const outputZip = path.join(projectDir, 'gudang-arsip.zip')

console.log('🚀 Memulai proses export ZIP...')
console.log(`📁 Project directory: ${projectDir}`)
console.log(`📦 Output file: ${outputZip}`)

// Hapus file ZIP yang sudah ada
if (fs.existsSync(outputZip)) {
  console.log('⚠️  File ZIP sudah ada, menghapus...')
  fs.unlinkSync(outputZip)
}

try {
  console.log('📂 Scanning project files...')

  // Buat output stream
  const output = fs.createWriteStream(outputZip)
  const archive = archiver('zip', { zlib: { level: 9 } })

  output.on('close', () => {
    try {
      const stats = fs.statSync(outputZip)
      const sizeInMB = (stats.size / 1024 / 1024).toFixed(2)
      console.log('\n✅ Export ZIP selesai!')
      console.log(`📦 File ZIP: ${outputZip}`)
      console.log(`📊 Ukuran file: ${sizeInMB} MB`)
      console.log('\n' + '='.repeat(60))
      console.log('📝 CARA MENJALANKAN PROJECT:')
      console.log('='.repeat(60))
      console.log('1. Ekstrak file ZIP ke folder')
      console.log('2. Install dependencies: bun install')
      console.log('3. Jalankan development server: bun run dev')
      console.log('4. Buka browser: http://localhost:3000')
      console.log('\n' + '='.repeat(60))
      console.log('⚠️  PENTING - CATATAN:')
      console.log('='.repeat(60))
      console.log('📌 Ini adalah project NEXT.JS dengan teknologi:')
      console.log('   - React 19')
      console.log('   - TypeScript')
      console.log('   - Next.js 15')
      console.log('   - Prisma ORM')
      console.log('   - SQLite Database')
      console.log('\n📌 XAMPP / APACHE / MYSQL:')
      console.log('   - XAMPP hanya untuk menjalankan aplikasi PHP')
      console.log('   - XAMPP TIDAK bisa menjalankan Next.js')
      console.log('   - JANGAN upload file ZIP ke folder htdocs XAMPP')
      console.log('   - JANGAN ubah konfigurasi Apache untuk Next.js')
      console.log('\n📌 CARA MENJALANKAN YANG BENAR:')
      console.log('   - Pastikan Node.js atau Bun sudah terinstall')
      console.log('   - Pastikan bun install sudah dijalankan sekali')
      console.log('   - Gunakan terminal (Command Prompt / PowerShell)')
      console.log('   - Masuk ke folder project: cd /path/to/project')
      console.log('   - Jalankan: bun run dev')
      console.log('\n' + '='.repeat(60))
    } catch (err) {
      console.error('Error saat cek file ZIP:', err)
    }
  })

  archive.on('error', (err) => {
    console.error('❌ Error saat membuat ZIP:', err)
    process.exit(1)
  })

  // Pipe archive ke output
  archive.pipe(output)

  // Directories yang tidak perlu di-zip
  const excludeDirs = ['node_modules', '.next', '.git', 'prisma/migrations']

  // Files yang tidak perlu di-zip
  const excludeFiles = ['.env', 'dev.log', 'server.log', 'export-zip.js', 'gudang-arsip.zip', '.DS_Store', 'db/custom.db']

  let fileCount = 0

  // Fungsi rekursif untuk menambahkan file
  function addFilesToArchive(dir, baseDir = '') {
    const items = fs.readdirSync(dir)

    items.forEach(item => {
      const fullPath = path.join(dir, item)
      const relativePath = path.relative(projectDir, fullPath)
      const stat = fs.statSync(fullPath)

      if (stat.isDirectory()) {
        // Skip directory yang di-exclude
        if (excludeDirs.includes(relativePath)) {
          return
        }
        // Skip hidden directories (dimulai dengan .)
        if (path.basename(item).startsWith('.')) {
          return
        }
        // Skip __MACOSX directories
        if (item === '__MACOSX') {
          return
        }
        addFilesToArchive(fullPath, relativePath)
      } else {
        // Skip file yang di-exclude
        if (excludeFiles.some(pattern => relativePath.endsWith(pattern))) {
          return
        }
        // Skip hidden files
        if (path.basename(item).startsWith('.')) {
          return
        }
        // Skip lock files
        if (item.endsWith('.lock')) {
          return
        }

        archive.file(fullPath, { name: relativePath })
        fileCount++

        if (fileCount % 50 === 0) {
          process.stdout.write(`\r  Menambahkan files: ${fileCount}...`)
        }
      }
    })
  }

  // Mulai scanning
  addFilesToArchive(projectDir)

  // Selesaikan archive
  archive.finalize().catch(err => {
    console.error('\n❌ Error saat finalisasi archive:', err)
    process.exit(1)
  })

} catch (error) {
  console.error('\n❌ Error saat export ZIP:', error)
  process.exit(1)
}
