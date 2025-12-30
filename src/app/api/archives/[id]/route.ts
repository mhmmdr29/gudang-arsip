import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'
import { writeFile, unlink } from 'fs/promises'
import { join } from 'path'

interface Params {
  params: {
    id: string
  }
}

// PUT update archive
export async function PUT(request: NextRequest, { params }: Params) {
  try {
    const id = params.id
    const formData = await request.formData()

    const name = formData.get('name') as string
    const code = formData.get('code') as string
    const subBagCode = formData.get('subBagCode') as string
    const yearCreated = parseInt(formData.get('yearCreated') as string)
    const retentionPeriod = parseInt(formData.get('retentionPeriod') as string)
    const shelfId = formData.get('shelfId') as string
    const file = formData.get('file') as File | null

    if (!name || !code || !subBagCode || !yearCreated || !retentionPeriod || !shelfId) {
      return NextResponse.json(
        { error: 'Semua field diperlukan' },
        { status: 400 }
      )
    }

    // Get existing archive
    const existingArchive = await db.archive.findUnique({
      where: { id },
    })

    if (!existingArchive) {
      return NextResponse.json(
        { error: 'Arsip tidak ditemukan' },
        { status: 404 }
      )
    }

    // Get shelf information
    const shelf = await db.shelf.findUnique({
      where: { id: shelfId },
      include: {
        warehouse: true,
      },
    })

    if (!shelf) {
      return NextResponse.json(
        { error: 'Rak tidak ditemukan' },
        { status: 404 }
      )
    }

    // Handle file upload
    let filePath = existingArchive.filePath
    if (file && file.size > 0) {
      // Delete old file if exists
      if (existingArchive.filePath) {
        try {
          const oldFilePath = join(process.cwd(), 'public', existingArchive.filePath)
          await unlink(oldFilePath)
        } catch (error) {
          console.error('Error deleting old file:', error)
        }
      }

      // Upload new file
      const bytes = await file.arrayBuffer()
      const buffer = Buffer.from(bytes)
      const timestamp = Date.now()
      const filename = `${timestamp}-${file.name}`
      const uploadDir = join(process.cwd(), 'public', 'uploads')
      const filepath = join(uploadDir, filename)

      await writeFile(filepath, buffer)
      filePath = `/uploads/${filename}`
    }

    // Update archive
    const archive = await db.archive.update({
      where: { id },
      data: {
        name,
        code,
        subBagCode,
        yearCreated,
        retentionPeriod,
        shelfId,
        warehouseId: shelf.warehouseId,
        filePath,
      },
    })

    return NextResponse.json(archive)
  } catch (error) {
    console.error('Update archive error:', error)
    return NextResponse.json(
      { error: 'Gagal mengupdate arsip' },
      { status: 500 }
    )
  }
}

// DELETE archive
export async function DELETE(request: NextRequest, { params }: Params) {
  try {
    const id = params.id

    const archive = await db.archive.findUnique({
      where: { id },
    })

    if (!archive) {
      return NextResponse.json(
        { error: 'Arsip tidak ditemukan' },
        { status: 404 }
      )
    }

    // Delete file if exists
    if (archive.filePath) {
      try {
        const filePath = join(process.cwd(), 'public', archive.filePath)
        await unlink(filePath)
      } catch (error) {
        console.error('Error deleting file:', error)
      }
    }

    await db.archive.delete({
      where: { id },
    })

    return NextResponse.json({ message: 'Arsip berhasil dihapus' })
  } catch (error) {
    console.error('Delete archive error:', error)
    return NextResponse.json(
      { error: 'Gagal menghapus arsip' },
      { status: 500 }
    )
  }
}
