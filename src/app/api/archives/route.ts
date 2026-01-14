import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'
import { writeFile } from 'fs/promises'
import { join } from 'path'

// GET all archives
export async function GET(request: NextRequest) {
  try {
    const archives = await db.archive.findMany({
      include: {
        shelf: {
          include: {
            warehouse: true,
          },
        },
      },
      orderBy: {
        createdAt: 'desc',
      },
    })

    // Transform to include shelf and warehouse codes
    const transformedArchives = archives.map((archive) => ({
      id: archive.id,
      name: archive.name,
      code: archive.code,
      subBagCode: archive.subBagCode,
      yearCreated: archive.yearCreated,
      retentionPeriod: archive.retentionPeriod,
      shelfId: archive.shelfId,
      warehouseId: archive.warehouseId,
      shelfCode: archive.shelf.code,
      warehouseCode: archive.shelf.warehouse.code,
      warehouseName: archive.shelf.warehouse.name,
      filePath: archive.filePath,
    }))

    return NextResponse.json(transformedArchives)
  } catch (error) {
    console.error('Get archives error:', error)
    return NextResponse.json(
      { error: 'Gagal mengambil data arsip' },
      { status: 500 }
    )
  }
}

// POST create archive
export async function POST(request: NextRequest) {
  try {
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
    let filePath = null
    if (file) {
      const bytes = await file.arrayBuffer()
      const buffer = Buffer.from(bytes)
      const timestamp = Date.now()
      const filename = `${timestamp}-${file.name}`
      const uploadDir = join(process.cwd(), 'public', 'uploads')
      const filepath = join(uploadDir, filename)

      await writeFile(filepath, buffer)
      filePath = `/uploads/${filename}`
    }

    const archive = await db.archive.create({
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

    return NextResponse.json(archive, { status: 201 })
  } catch (error) {
    console.error('Create archive error:', error)
    return NextResponse.json(
      { error: 'Gagal membuat arsip' },
      { status: 500 }
    )
  }
}
