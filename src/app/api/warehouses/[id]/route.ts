import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

interface Params {
  params: {
    id: string
  }
}

// PUT update warehouse
export async function PUT(request: NextRequest, { params }: Params) {
  try {
    const id = params.id
    const { code, name, location } = await request.json()

    if (!code || !name || !location) {
      return NextResponse.json(
        { error: 'Semua field diperlukan' },
        { status: 400 }
      )
    }

    // Check if code already exists (excluding current warehouse)
    const existingCode = await db.warehouse.findFirst({
      where: {
        code,
        id: { not: id },
      },
    })

    if (existingCode) {
      return NextResponse.json(
        { error: 'Kode gudang sudah digunakan' },
        { status: 400 }
      )
    }

    const warehouse = await db.warehouse.update({
      where: { id },
      data: {
        code,
        name,
        location,
      },
    })

    return NextResponse.json(warehouse)
  } catch (error) {
    console.error('Update warehouse error:', error)
    return NextResponse.json(
      { error: 'Gagal mengupdate gudang' },
      { status: 500 }
    )
  }
}

// DELETE warehouse
export async function DELETE(request: NextRequest, { params }: Params) {
  try {
    const id = params.id

    await db.warehouse.delete({
      where: { id },
    })

    return NextResponse.json({ message: 'Gudang berhasil dihapus' })
  } catch (error) {
    console.error('Delete warehouse error:', error)
    return NextResponse.json(
      { error: 'Gagal menghapus gudang. Pastikan tidak ada arsip yang menggunakan gudang ini.' },
      { status: 500 }
    )
  }
}
