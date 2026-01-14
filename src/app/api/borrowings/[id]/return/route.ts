import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

interface Params {
  params: {
    id: string
  }
}

// POST return archive
export async function POST(request: NextRequest, { params }: Params) {
  try {
    const id = params.id

    // Update borrowing history with return date
    await db.borrowHistory.update({
      where: { id },
      data: {
        returnedAt: new Date(),
      },
    })

    return NextResponse.json({ message: 'Arsip berhasil dikembalikan' })
  } catch (error) {
    console.error('Return archive error:', error)
    return NextResponse.json(
      { error: 'Gagal mengembalikan arsip' },
      { status: 500 }
    )
  }
}
