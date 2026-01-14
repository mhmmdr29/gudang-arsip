import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

// GET all shelves
export async function GET(request: NextRequest) {
  try {
    const searchParams = request.nextUrl.searchParams
    const warehouseId = searchParams.get('warehouseId')

    const shelves = await db.shelf.findMany({
      where: warehouseId ? { warehouseId } : undefined,
      include: {
        warehouse: true,
      },
      orderBy: {
        createdAt: 'desc',
      },
    })

    // Transform to include warehouse code and name
    const transformedShelves = shelves.map((shelf) => ({
      id: shelf.id,
      code: shelf.code,
      warehouseId: shelf.warehouseId,
      warehouseCode: shelf.warehouse.code,
      warehouseName: shelf.warehouse.name,
    }))

    return NextResponse.json(transformedShelves)
  } catch (error) {
    console.error('Get shelves error:', error)
    return NextResponse.json(
      { error: 'Gagal mengambil data rak' },
      { status: 500 }
    )
  }
}

// POST create shelf
export async function POST(request: NextRequest) {
  try {
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

    // Check if code already exists for this warehouse
    const existingCode = await db.shelf.findUnique({
      where: {
        code_warehouseId: {
          code,
          warehouseId,
        },
      },
    })

    if (existingCode) {
      return NextResponse.json(
        { error: 'Kode rak sudah digunakan di gudang ini' },
        { status: 400 }
      )
    }

    const shelf = await db.shelf.create({
      data: {
        code,
        warehouseId,
      },
    })

    return NextResponse.json(shelf, { status: 201 })
  } catch (error) {
    console.error('Create shelf error:', error)
    return NextResponse.json(
      { error: 'Gagal membuat rak' },
      { status: 500 }
    )
  }
}
