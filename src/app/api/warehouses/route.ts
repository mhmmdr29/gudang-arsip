import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

// GET all warehouses
export async function GET(request: NextRequest) {
  try {
    const warehouses = await db.warehouse.findMany({
      orderBy: {
        createdAt: 'desc',
      },
    })

    return NextResponse.json(warehouses)
  } catch (error) {
    console.error('Get warehouses error:', error)
    return NextResponse.json(
      { error: 'Gagal mengambil data gudang' },
      { status: 500 }
    )
  }
}

// POST create warehouse
export async function POST(request: NextRequest) {
  try {
    const { code, name, location } = await request.json()

    if (!code || !name || !location) {
      return NextResponse.json(
        { error: 'Semua field diperlukan' },
        { status: 400 }
      )
    }

    // Check if code already exists
    const existingCode = await db.warehouse.findUnique({
      where: { code },
    })

    if (existingCode) {
      return NextResponse.json(
        { error: 'Kode gudang sudah digunakan' },
        { status: 400 }
      )
    }

    const warehouse = await db.warehouse.create({
      data: {
        code,
        name,
        location,
      },
    })

    return NextResponse.json(warehouse, { status: 201 })
  } catch (error) {
    console.error('Create warehouse error:', error)
    return NextResponse.json(
      { error: 'Gagal membuat gudang' },
      { status: 500 }
    )
  }
}
