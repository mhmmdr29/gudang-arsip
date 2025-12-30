'use client'

import { useState } from 'react'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { toast } from 'sonner'
import { Plus, ArrowLeft } from 'lucide-react'

export function AddWarehouse() {
  const [formData, setFormData] = useState({
    code: '',
    name: '',
    location: '',
  })
  const [isLoading, setIsLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)

    try {
      const response = await fetch('/api/warehouses', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        const error = await response.json()
        throw new Error(error.error || 'Gagal menambah gudang')
      }

      toast.success('Gudang berhasil ditambahkan')
      setFormData({ code: '', name: '', location: '' })
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menambah gudang')
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
          <h2 className="text-3xl font-bold tracking-tight">Tambah Gudang</h2>
          <p className="text-muted-foreground">Tambahkan gudang baru ke sistem</p>
        </div>
      </div>

      <Card className="max-w-2xl">
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Plus className="h-5 w-5" />
            Form Tambah Gudang
          </CardTitle>
          <CardDescription>
            Lengkapi formulir di bawah ini untuk menambahkan gudang baru
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid gap-2">
              <Label htmlFor="code">Kode Gudang *</Label>
              <Input
                id="code"
                value={formData.code}
                onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                placeholder="Contoh: GUD-001"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="name">Nama Gudang *</Label>
              <Input
                id="name"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                placeholder="Contoh: Gudang Utama"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="location">Lokasi *</Label>
              <Input
                id="location"
                value={formData.location}
                onChange={(e) => setFormData({ ...formData, location: e.target.value })}
                placeholder="Contoh: Lantai 1, Gedung A"
                required
              />
            </div>
            <div className="flex gap-2">
              <Button type="submit" disabled={isLoading}>
                {isLoading ? 'Memproses...' : 'Tambah Gudang'}
              </Button>
              <Button
                type="button"
                variant="outline"
                onClick={() => setFormData({ code: '', name: '', location: '' })}
              >
                Reset
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}
