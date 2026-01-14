import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

// GET all movement history
export async function GET(request: NextRequest) {
  try {
    const searchParams = request.nextUrl.searchParams
    const archiveId = searchParams.get('archiveId')

    const whereClause = archiveId ? { archiveId } : {}

    const movements = await db.movementHistory.findMany({
      where: whereClause,
      orderBy: {
        movedAt: 'desc',
      },
    })

    return NextResponse.json(movements)
  } catch (error) {
    console.error('Get movements error:', error)
    return NextResponse.json(
      { error: 'Gagal mengambil riwayat pemindahan' },
      { status: 500 }
    )
  }
}

// POST create movement
export async function POST(request: NextRequest) {
  try {
    const { archiveId, newShelfId, movedBy } = await request.json()

    if (!archiveId || !newShelfId || !movedBy) {
      return NextResponse.json(
        { error: 'Arsip, rak tujuan, dan nama yang memindahkan diperlukan' },
        { status: 400 }
      )
    }

    // Get archive and current shelf information
    const archive = await db.archive.findUnique({
      where: { id: archiveId },
      include: {
        shelf: true,
      },
    })

    if (!archive) {
      return NextResponse.json(
        { error: 'Arsip tidak ditemukan' },
        { status: 404 }
      )
    }

    // Get new shelf information
    const newShelf = await db.shelf.findUnique({
      where: { id: newShelfId },
      include: {
        warehouse: true,
      },
    })

    if (!newShelf) {
      return NextResponse.json(
        { error: 'Rak tujuan tidak ditemukan' },
        { status: 404 }
      )
    }

    // Create movement history
    await db.movementHistory.create({
      data: {
        archiveId,
        archiveName: archive.name,
        archiveCode: archive.code,
        previousShelf: archive.shelf.code,
        newShelf: newShelf.code,
        movedBy: movedBy,
      },
    })

    // Update archive shelf and warehouse
    await db.archive.update({
      where: { id: archiveId },
      data: {
        shelfId: newShelfId,
        warehouseId: newShelf.warehouseId,
      },
    })

    return NextResponse.json({ message: 'Arsip berhasil dipindahkan' }, { status: 201 })
  } catch (error) {
    console.error('Create movement error:', error)
    return NextResponse.json(
      { error: 'Gagal memindahkan arsip' },
      { status: 500 }
    )
  }
}
