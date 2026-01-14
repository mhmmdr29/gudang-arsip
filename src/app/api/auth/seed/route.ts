import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

export async function POST(request: NextRequest) {
  try {
    // Check if accounts already exist
    const existingAccounts = await db.account.count()

    if (existingAccounts > 0) {
      return NextResponse.json(
        { message: 'Akun sudah ada' },
        { status: 200 }
      )
    }

    // Create default accounts
    await db.account.createMany({
      data: [
        {
          username: 'admin',
          email: 'admin@example.com',
          password: 'admin123',
          role: 'ADMIN',
        },
        {
          username: 'kassubag',
          email: 'kassubag@example.com',
          password: 'kassubag123',
          role: 'KASSUBAG_KUL',
        },
        {
          username: 'staff',
          email: 'staff@example.com',
          password: 'staff123',
          role: 'STAFF',
        },
      ],
    })

    return NextResponse.json(
      { message: 'Akun default berhasil dibuat' },
      { status: 201 }
    )
  } catch (error) {
    console.error('Seed error:', error)
    return NextResponse.json(
      { error: 'Terjadi kesalahan saat membuat akun default' },
      { status: 500 }
    )
  }
}
