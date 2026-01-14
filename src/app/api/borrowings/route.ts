import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

// GET all borrowing history
export async function GET(request: NextRequest) {
  try {
    const borrowings = await db.borrowHistory.findMany({
      orderBy: {
        borrowedAt: 'desc',
      },
    })

    return NextResponse.json(borrowings)
  } catch (error) {
    console.error('Get borrowings error:', error)
    return NextResponse.json(
      { error: 'Gagal mengambil riwayat peminjaman' },
      { status: 500 }
    )
  }
}

// POST create borrowing
export async function POST(request: NextRequest) {
  try {
    const { archiveId, borrowerName } = await request.json()

    if (!archiveId || !borrowerName) {
      return NextResponse.json(
        { error: 'Arsip dan nama peminjam diperlukan' },
        { status: 400 }
      )
    }

    // Get archive information
    const archive = await db.archive.findUnique({
      where: { id: archiveId },
    })

    if (!archive) {
      return NextResponse.json(
        { error: 'Arsip tidak ditemukan' },
        { status: 404 }
      )
    }

    // Create borrowing history
    await db.borrowHistory.create({
      data: {
        archiveId,
        archiveName: archive.name,
        archiveCode: archive.code,
        borrowerName,
      },
    })

    return NextResponse.json({ message: 'Arsip berhasil dipinjam' }, { status: 201 })
  } catch (error) {
    console.error('Create borrowing error:', error)
    return NextResponse.json(
      { error: 'Gagal meminjam arsip' },
      { status: 500 }
    )
  }
}
