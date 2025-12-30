import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

// GET all accounts
export async function GET(request: NextRequest) {
  try {
    const accounts = await db.account.findMany({
      select: {
        id: true,
        username: true,
        email: true,
        role: true,
        createdAt: true,
      },
      orderBy: {
        createdAt: 'desc',
      },
    })

    return NextResponse.json(accounts)
  } catch (error) {
    console.error('Get accounts error:', error)
    return NextResponse.json(
      { error: 'Gagal mengambil data akun' },
      { status: 500 }
    )
  }
}

// POST create account
export async function POST(request: NextRequest) {
  try {
    const { username, email, password, role } = await request.json()

    if (!username || !email || !password || !role) {
      return NextResponse.json(
        { error: 'Semua field diperlukan' },
        { status: 400 }
      )
    }

    // Check if username already exists
    const existingUsername = await db.account.findUnique({
      where: { username },
    })

    if (existingUsername) {
      return NextResponse.json(
        { error: 'Username sudah digunakan' },
        { status: 400 }
      )
    }

    // Check if email already exists
    const existingEmail = await db.account.findUnique({
      where: { email },
    })

    if (existingEmail) {
      return NextResponse.json(
        { error: 'Email sudah digunakan' },
        { status: 400 }
      )
    }

    const account = await db.account.create({
      data: {
        username,
        email,
        password,
        role,
      },
    })

    return NextResponse.json(account, { status: 201 })
  } catch (error) {
    console.error('Create account error:', error)
    return NextResponse.json(
      { error: 'Gagal membuat akun' },
      { status: 500 }
    )
  }
}
