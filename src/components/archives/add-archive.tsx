'use client'

import { useState, useEffect } from 'react'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { toast } from 'sonner'
import { FilePlus, ArrowLeft } from 'lucide-react'

interface Warehouse {
  id: string
  code: string
  name: string
}

interface Shelf {
  id: string
  code: string
  warehouseId: string
}

export function AddArchive() {
  const [formData, setFormData] = useState({
    name: '',
    code: '',
    subBagCode: '',
    yearCreated: '',
    retentionPeriod: '',
    shelfId: '',
    warehouseId: '',
  })
  const [file, setFile] = useState<File | null>(null)
  const [warehouses, setWarehouses] = useState<Warehouse[]>([])
  const [shelves, setShelves] = useState<Shelf[]>([])
  const [isLoading, setIsLoading] = useState(false)

  useEffect(() => {
    fetchWarehouses()
  }, [])

  const fetchWarehouses = async () => {
    try {
      const response = await fetch('/api/warehouses')
      const data = await response.json()
      if (response.ok) {
        setWarehouses(data)
      }
    } catch (error) {
      toast.error('Gagal memuat data gudang')
    }
  }

  const fetchShelves = async (warehouseId: string) => {
    try {
      const response = await fetch(`/api/shelves?warehouseId=${warehouseId}`)
      const data = await response.json()
      if (response.ok) {
        setShelves(data)
      }
    } catch (error) {
      toast.error('Gagal memuat data rak')
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)

    try {
      const formDataToSend = new FormData()
      formDataToSend.append('name', formData.name)
      formDataToSend.append('code', formData.code)
      formDataToSend.append('subBagCode', formData.subBagCode)
      formDataToSend.append('yearCreated', formData.yearCreated)
      formDataToSend.append('retentionPeriod', formData.retentionPeriod)
      formDataToSend.append('shelfId', formData.shelfId)
      if (file) {
        formDataToSend.append('file', file)
      }

      const response = await fetch('/api/archives', {
        method: 'POST',
        body: formDataToSend,
      })

      if (!response.ok) {
        const error = await response.json()
        throw new Error(error.error || 'Gagal menambah arsip')
      }

      toast.success('Arsip berhasil ditambahkan')
      setFormData({
        name: '',
        code: '',
        subBagCode: '',
        yearCreated: '',
        retentionPeriod: '',
        shelfId: '',
        warehouseId: '',
      })
      setFile(null)
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menambah arsip')
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
          <h2 className="text-3xl font-bold tracking-tight">Tambah Arsip</h2>
          <p className="text-muted-foreground">Tambahkan arsip baru ke sistem</p>
        </div>
      </div>

      <Card className="max-w-2xl">
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <FilePlus className="h-5 w-5" />
            Form Tambah Arsip
          </CardTitle>
          <CardDescription>
            Lengkapi formulir di bawah ini untuk menambahkan arsip baru
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid gap-2">
              <Label htmlFor="name">Nama Arsip *</Label>
              <Input
                id="name"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                placeholder="Masukkan nama arsip"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="code">Kode Arsip *</Label>
              <Input
                id="code"
                value={formData.code}
                onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                placeholder="Masukkan kode arsip"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="subBagCode">Kode Sub Bag *</Label>
              <Input
                id="subBagCode"
                value={formData.subBagCode}
                onChange={(e) => setFormData({ ...formData, subBagCode: e.target.value })}
                placeholder="Masukkan kode sub bagian"
                required
              />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="grid gap-2">
                <Label htmlFor="yearCreated">Tahun Dibuat *</Label>
                <Input
                  id="yearCreated"
                  type="number"
                  value={formData.yearCreated}
                  onChange={(e) => setFormData({ ...formData, yearCreated: e.target.value })}
                  placeholder="2024"
                  required
                />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="retentionPeriod">Masa Retensi (Tahun) *</Label>
                <Input
                  id="retentionPeriod"
                  type="number"
                  value={formData.retentionPeriod}
                  onChange={(e) => setFormData({ ...formData, retentionPeriod: e.target.value })}
                  placeholder="10"
                  required
                />
              </div>
            </div>
            <div className="grid gap-2">
              <Label htmlFor="warehouse">Gudang *</Label>
              <Select
                value={formData.warehouseId}
                onValueChange={(value) => {
                  setFormData({ ...formData, warehouseId: value, shelfId: '' })
                  fetchShelves(value)
                }}
                required
              >
                <SelectTrigger id="warehouse">
                  <SelectValue placeholder="Pilih gudang" />
                </SelectTrigger>
                <SelectContent>
                  {warehouses.map((warehouse) => (
                    <SelectItem key={warehouse.id} value={warehouse.id}>
                      {warehouse.name} ({warehouse.code})
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="grid gap-2">
              <Label htmlFor="shelf">Rak *</Label>
              <Select
                value={formData.shelfId}
                onValueChange={(value) => setFormData({ ...formData, shelfId: value })}
                required
                disabled={!formData.warehouseId}
              >
                <SelectTrigger id="shelf">
                  <SelectValue placeholder={
                    formData.warehouseId ? 'Pilih rak' : 'Pilih gudang terlebih dahulu'
                  } />
                </SelectTrigger>
                <SelectContent>
                  {shelves.map((shelf) => (
                    <SelectItem key={shelf.id} value={shelf.id}>
                      {shelf.code}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="grid gap-2">
              <Label htmlFor="file">Upload File PDF</Label>
              <Input
                id="file"
                type="file"
                accept=".pdf"
                onChange={(e) => setFile(e.target.files?.[0] || null)}
              />
              <p className="text-sm text-muted-foreground">
                Format file harus PDF. Maksimal 10MB.
              </p>
            </div>
            <div className="flex gap-2">
              <Button type="submit" disabled={isLoading}>
                {isLoading ? 'Memproses...' : 'Tambah Arsip'}
              </Button>
              <Button
                type="button"
                variant="outline"
                onClick={() => {
                  setFormData({
                    name: '',
                    code: '',
                    subBagCode: '',
                    yearCreated: '',
                    retentionPeriod: '',
                    shelfId: '',
                    warehouseId: '',
                  })
                  setFile(null)
                }}
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
