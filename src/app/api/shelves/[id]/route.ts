import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

interface Params {
  params: {
    id: string
  }
}

// PUT update shelf
export async function PUT(request: NextRequest, { params }: Params) {
  try {
    const id = params.id
    const { code, warehouseId } = await request.json()

    if (!code || !warehouseId) {
      return NextResponse.json(
        { error: 'Kode rak dan gudang diperlukan' },
        { status: 400 }
      )
    }

    // Check if warehouse exists
    const warehouse = await db.warehouse.findUnique({
      where: { id: warehouseId },
    })

    if (!warehouse) {
      return NextResponse.json(
        { error: 'Gudang tidak ditemukan' },
        { status: 404 }
      )
    }

    // Check if code already exists for this warehouse (excluding current shelf)
    const existingCode = await db.shelf.findFirst({
      where: {
        code,
        warehouseId,
        id: { not: id },
      },
    })

    if (existingCode) {
      return NextResponse.json(
        { error: 'Kode rak sudah digunakan di gudang ini' },
        { status: 400 }
      )
    }

    const shelf = await db.shelf.update({
      where: { id },
      data: {
        code,
        warehouseId,
      },
    })

    return NextResponse.json(shelf)
  } catch (error) {
    console.error('Update shelf error:', error)
    return NextResponse.json(
      { error: 'Gagal mengupdate rak' },
      { status: 500 }
    )
  }
}

// DELETE shelf
export async function DELETE(request: NextRequest, { params }: Params) {
  try {
    const id = params.id

    await db.shelf.delete({
      where: { id },
    })

    return NextResponse.json({ message: 'Rak berhasil dihapus' })
  } catch (error) {
    console.error('Delete shelf error:', error)
    return NextResponse.json(
      { error: 'Gagal menghapus rak. Pastikan tidak ada arsip yang menggunakan rak ini.' },
      { status: 500 }
    )
  }
}
