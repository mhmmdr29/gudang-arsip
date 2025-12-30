import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

interface Params {
  params: {
    id: string
  }
}

// PUT update movement history
export async function PUT(request: NextRequest, { params }: Params) {
  try {
    const id = params.id
    const { archiveName, archiveCode, previousShelf, newShelf, movedBy } = await request.json()

    if (!archiveName || !archiveCode || !previousShelf || !newShelf || !movedBy) {
      return NextResponse.json(
        { error: 'Semua field diperlukan' },
        { status: 400 }
      )
    }

    const movement = await db.movementHistory.update({
      where: { id },
      data: {
        archiveName,
        archiveCode,
        previousShelf,
        newShelf,
        movedBy,
      },
    })

    return NextResponse.json(movement)
  } catch (error) {
    console.error('Update movement error:', error)
    return NextResponse.json(
      { error: 'Gagal mengupdate riwayat pemindahan' },
      { status: 500 }
    )
  }
}

// DELETE movement history
export async function DELETE(request: NextRequest, { params }: Params) {
  try {
    const id = params.id

    await db.movementHistory.delete({
      where: { id },
    })

    return NextResponse.json({ message: 'Riwayat pemindahan berhasil dihapus' })
  } catch (error) {
    console.error('Delete movement error:', error)
    return NextResponse.json(
      { error: 'Gagal menghapus riwayat pemindahan' },
      { status: 500 }
    )
  }
}
