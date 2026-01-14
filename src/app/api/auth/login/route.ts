import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

export async function POST(request: NextRequest) {
  console.log('=== LOGIN API START ===')

  try {
    // 1. Baca request body sebagai text dulu
    let bodyText = ''
    try {
      bodyText = await request.text()
      console.log('Request body length:', bodyText.length)
    } catch (readError) {
      console.error('Error reading request body:', readError)
      return NextResponse.json(
        { error: 'Gagal membaca request body' },
        { status: 500 }
      )
    }

    // 2. Cek apakah body kosong
    if (!bodyText || bodyText.trim() === '') {
      console.error('Request body kosong')
      return NextResponse.json(
        { error: 'Request body kosong' },
        { status: 400 }
      )
    }

    // 3. Parse JSON dari text
    let body
    try {
      body = JSON.parse(bodyText)
      console.log('Request body parsed successfully')
      console.log('Username:', body.username)
    } catch (parseError) {
      console.error('Error parsing JSON:', parseError)
      console.error('Raw bodyText (first 500 chars):', bodyText.substring(0, 500))
      return NextResponse.json(
        { error: 'Format request tidak valid. Pastikan mengirim JSON yang benar.' },
        { status: 500 }
      )
    }

    // 4. Validasi input
    const { username, password } = body

    if (!username || !password) {
      console.error('Username atau password kosong')
      return NextResponse.json(
        { error: 'Username dan password diperlukan' },
        { status: 400 }
      )
    }

    console.log('Mencoba login untuk user:', username)

    // 5. Query database
    console.log('Querying database...')
    let account
    try {
      account = await db.account.findUnique({
        where: { username },
      })
      console.log('Database query completed successfully')
    } catch (queryError) {
      console.error('Error querying database:', queryError)
      console.error('Error details:', queryError instanceof Error ? queryError.stack : String(queryError))
      return NextResponse.json(
        { error: 'Terjadi kesalahan saat mengakses database. Error: ' + (queryError instanceof Error ? queryError.message : String(queryError)) },
        { status: 500 }
      )
    }

    // 6. Cek user
    if (!account) {
      console.error('User tidak ditemukan:', username)
      return NextResponse.json(
        { error: 'Username atau password salah' },
        { status: 401 }
      )
    }

    console.log('User ditemukan:', account.username)

    // 7. Validasi password (simple comparison, production gunakan bcrypt)
    const isValidPassword = password === account.password

    if (!isValidPassword) {
      console.error('Password salah untuk user:', username)
      return NextResponse.json(
        { error: 'Username atau password salah' },
        { status: 401 }
      )
    }

    console.log('Password valid, login berhasil!')

    // 8. Buat user object untuk response
    const user = {
      id: account.id,
      username: account.username,
      email: account.email,
      role: account.role,
    }

    // 9. Return sukses dengan JSON
    console.log('=== LOGIN API SUCCESS ===')
    return NextResponse.json({ user })

  } catch (error) {
    console.error('=== LOGIN API ERROR ===')
    console.error('Error type:', error instanceof Error ? error.constructor.name : typeof error)
    console.error('Error message:', error instanceof Error ? error.message : String(error))
    console.error('Error stack:', error instanceof Error ? error.stack : 'No stack available')

    return NextResponse.json(
      {
        error: 'Terjadi kesalahan saat login',
        details: error instanceof Error ? error.message : String(error)
      },
      { status: 500 }
    )
  }
}
