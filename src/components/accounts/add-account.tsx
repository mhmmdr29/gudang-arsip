'use client'

import { useState } from 'react'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { toast } from 'sonner'
import { UserPlus, ArrowLeft } from 'lucide-react'

export function AddAccount() {
  const [formData, setFormData] = useState({
    username: '',
    email: '',
    password: '',
    role: 'STAFF',
  })
  const [isLoading, setIsLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)

    try {
      const response = await fetch('/api/accounts', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        const error = await response.json()
        throw new Error(error.error || 'Gagal menambah akun')
      }

      toast.success('Akun berhasil ditambahkan')
      setFormData({ username: '', email: '', password: '', role: 'STAFF' })
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menambah akun')
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-4">
        <Button
          variant="ghost"
          size="icon"
          onClick={() => {
            window.location.reload()
          }}
        >
          <ArrowLeft className="h-5 w-5" />
        </Button>
        <div>
          <h2 className="text-3xl font-bold tracking-tight">Tambah Akun</h2>
          <p className="text-muted-foreground">Tambahkan akun pengguna baru</p>
        </div>
      </div>

      <Card className="max-w-2xl">
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <UserPlus className="h-5 w-5" />
            Form Tambah Akun
          </CardTitle>
          <CardDescription>
            Lengkapi formulir di bawah ini untuk menambahkan akun baru
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid gap-2">
              <Label htmlFor="username">Username *</Label>
              <Input
                id="username"
                value={formData.username}
                onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                placeholder="Contoh: johndoe"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="email">Email *</Label>
              <Input
                id="email"
                type="email"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                placeholder="Contoh: johndoe@example.com"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="password">Password *</Label>
              <Input
                id="password"
                type="password"
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                placeholder="Minimal 6 karakter"
                required
                minLength={6}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="role">Role *</Label>
              <Select
                value={formData.role}
                onValueChange={(value) => setFormData({ ...formData, role: value })}
                required
              >
                <SelectTrigger id="role">
                  <SelectValue placeholder="Pilih role" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="KASSUBAG_KUL">Kassubag KUL (Akses Penuh)</SelectItem>
                  <SelectItem value="ADMIN">Admin (Gudang & Rak)</SelectItem>
                  <SelectItem value="STAFF">Staff (Arsip & Riwayat)</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="flex gap-2">
              <Button type="submit" disabled={isLoading}>
                {isLoading ? 'Memproses...' : 'Tambah Akun'}
              </Button>
              <Button
                type="button"
                variant="outline"
                onClick={() => setFormData({ username: '', email: '', password: '', role: 'STAFF' })}
              >
                Reset
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <Card className="max-w-2xl">
        <CardHeader>
          <CardTitle>Informasi Role</CardTitle>
        </CardHeader>
        <CardContent className="space-y-2 text-sm">
          <div>
            <strong>Kassubag KUL:</strong> Mengakses semua menu dan sub menu
          </div>
          <div>
            <strong>Admin:</strong> Mengakses Daftar Rak, Daftar Gudang, Riwayat Pemindahan, dan Daftar Arsip
          </div>
          <div>
            <strong>Staff:</strong> Mengakses Riwayat Pemindahan dan Daftar Arsip
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
