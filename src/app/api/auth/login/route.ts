import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

export async function POST(request: NextRequest) {
  // Pastikan selalu mengembalikan JSON response
  const headers = {
    'Content-Type': 'application/json',
  }

  try {
    // Cek request body
    let body
    try {
      const text = await request.text()
      if (!text) {
        return NextResponse.json(
          { error: 'Request body kosong' },
          { status: 400, headers }
        )
      }
      body = JSON.parse(text)
    } catch (parseError) {
      return NextResponse.json(
        { error: 'Format request tidak valid' },
        { status: 400, headers }
      )
    }

    const { username, password } = body

    if (!username || !password) {
      return NextResponse.json(
        { error: 'Username dan password diperlukan' },
        { status: 400, headers }
      )
    }

    const account = await db.account.findUnique({
      where: { username },
    })

    if (!account) {
      return NextResponse.json(
        { error: 'Username atau password salah' },
        { status: 401, headers }
      )
    }

    // Simple password comparison (in production, use bcrypt)
    const isValidPassword = password === account.password

    if (!isValidPassword) {
      return NextResponse.json(
        { error: 'Username atau password salah' },
        { status: 401, headers }
      )
    }

    const user = {
      id: account.id,
      username: account.username,
      email: account.email,
      role: account.role,
    }

    return NextResponse.json({ user }, { headers })
  } catch (error) {
    console.error('Login error:', error)

    // Pastikan error juga mengembalikan JSON
    return NextResponse.json(
      { error: 'Terjadi kesalahan saat login' },
      { status: 500, headers }
    )
  }
}
