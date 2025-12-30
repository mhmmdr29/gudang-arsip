import { NextRequest, NextResponse } from 'next/server'
import { db } from '@/lib/db'

interface Params {
  params: {
    id: string
  }
}

// PUT update account
export async function PUT(request: NextRequest, { params }: Params) {
  try {
    const id = params.id
    const { username, email, password, role } = await request.json()

    if (!username || !email || !role) {
      return NextResponse.json(
        { error: 'Username, email, dan role diperlukan' },
        { status: 400 }
      )
    }

    // Check if username already exists (excluding current account)
    const existingUsername = await db.account.findFirst({
      where: {
        username,
        id: { not: id },
      },
    })

    if (existingUsername) {
      return NextResponse.json(
        { error: 'Username sudah digunakan' },
        { status: 400 }
      )
    }

    // Check if email already exists (excluding current account)
    const existingEmail = await db.account.findFirst({
      where: {
        email,
        id: { not: id },
      },
    })

    if (existingEmail) {
      return NextResponse.json(
        { error: 'Email sudah digunakan' },
        { status: 400 }
      )
    }

    const updateData: any = {
      username,
      email,
      role,
    }

    // Only update password if provided
    if (password && password.trim() !== '') {
      updateData.password = password
    }

    const account = await db.account.update({
      where: { id },
      data: updateData,
    })

    return NextResponse.json(account)
  } catch (error) {
    console.error('Update account error:', error)
    return NextResponse.json(
      { error: 'Gagal mengupdate akun' },
      { status: 500 }
    )
  }
}

// DELETE account
export async function DELETE(request: NextRequest, { params }: Params) {
  try {
    const id = params.id

    await db.account.delete({
      where: { id },
    })

    return NextResponse.json({ message: 'Akun berhasil dihapus' })
  } catch (error) {
    console.error('Delete account error:', error)
    return NextResponse.json(
      { error: 'Gagal menghapus akun' },
      { status: 500 }
    )
  }
}
