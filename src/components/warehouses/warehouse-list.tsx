'use client'

import { useState, useEffect } from 'react'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { toast } from 'sonner'
import { Building2, Edit, Trash2, Plus } from 'lucide-react'
import { useAuthStore } from '@/lib/auth-store'

interface Warehouse {
  id: string
  code: string
  name: string
  location: string
}

export function WarehouseList() {
  const [warehouses, setWarehouses] = useState<Warehouse[]>([])
  const [isLoading, setIsLoading] = useState(true)
  const [editDialog, setEditDialog] = useState(false)
  const [addDialog, setAddDialog] = useState(false)
  const [selectedWarehouse, setSelectedWarehouse] = useState<Warehouse | null>(null)
  const [formData, setFormData] = useState({
    code: '',
    name: '',
    location: '',
  })

  const user = useAuthStore((state) => state.user)

  const canEdit = user?.role === 'KASSUBAG_KUL' || user?.role === 'ADMIN'

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
    } finally {
      setIsLoading(false)
    }
  }

  const handleAdd = () => {
    setSelectedWarehouse(null)
    setFormData({ code: '', name: '', location: '' })
    setAddDialog(true)
  }

  const handleEdit = (warehouse: Warehouse) => {
    setSelectedWarehouse(warehouse)
    setFormData({
      code: warehouse.code,
      name: warehouse.name,
      location: warehouse.location,
    })
    setEditDialog(true)
  }

  const handleSaveEdit = async () => {
    try {
      const response = await fetch(`/api/warehouses/${selectedWarehouse?.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        throw new Error('Gagal mengupdate gudang')
      }

      toast.success('Gudang berhasil diupdate')
      setEditDialog(false)
      fetchWarehouses()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal mengupdate gudang')
    }
  }

  const handleSaveAdd = async () => {
    try {
      const response = await fetch('/api/warehouses', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        throw new Error('Gagal menambah gudang')
      }

      toast.success('Gudang berhasil ditambahkan')
      setAddDialog(false)
      fetchWarehouses()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menambah gudang')
    }
  }

  const handleDelete = async (id: string) => {
    if (!confirm('Apakah Anda yakin ingin menghapus gudang ini?')) return

    try {
      const response = await fetch(`/api/warehouses/${id}`, {
        method: 'DELETE',
      })

      if (!response.ok) {
        throw new Error('Gagal menghapus gudang')
      }

      toast.success('Gudang berhasil dihapus')
      fetchWarehouses()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menghapus gudang')
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-3xl font-bold tracking-tight">Daftar Gudang</h2>
          <p className="text-muted-foreground">Kelola seluruh gudang penyimpanan arsip</p>
        </div>
        {canEdit && (
          <Button onClick={handleAdd}>
            <Plus className="mr-2 h-4 w-4" />
            Tambah Gudang
          </Button>
        )}
      </div>

      <Card>
        <CardHeader>
          <CardTitle>List Gudang</CardTitle>
        </CardHeader>
        <CardContent>
          {isLoading ? (
            <p className="text-center py-8">Memuat data...</p>
          ) : warehouses.length === 0 ? (
            <p className="text-center py-8 text-muted-foreground">Tidak ada gudang</p>
          ) : (
            <div className="overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Kode</TableHead>
                    <TableHead>Nama Gudang</TableHead>
                    <TableHead>Lokasi</TableHead>
                    {canEdit && <TableHead>Aksi</TableHead>}
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {warehouses.map((warehouse) => (
                    <TableRow key={warehouse.id}>
                      <TableCell className="font-medium">{warehouse.code}</TableCell>
                      <TableCell>{warehouse.name}</TableCell>
                      <TableCell>{warehouse.location}</TableCell>
                      {canEdit && (
                        <TableCell>
                          <div className="flex gap-2">
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleEdit(warehouse)}
                            >
                              <Edit className="h-4 w-4" />
                            </Button>
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleDelete(warehouse.id)}
                            >
                              <Trash2 className="h-4 w-4" />
                            </Button>
                          </div>
                        </TableCell>
                      )}
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>
          )}
        </CardContent>
      </Card>

      <Dialog open={addDialog} onOpenChange={setAddDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Tambah Gudang</DialogTitle>
            <DialogDescription>Tambahkan gudang baru ke sistem</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="add-code">Kode Gudang *</Label>
              <Input
                id="add-code"
                value={formData.code}
                onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                placeholder="GUD-001"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="add-name">Nama Gudang *</Label>
              <Input
                id="add-name"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                placeholder="Gudang Utama"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="add-location">Lokasi *</Label>
              <Input
                id="add-location"
                value={formData.location}
                onChange={(e) => setFormData({ ...formData, location: e.target.value })}
                placeholder="Lantai 1, Gedung A"
                required
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setAddDialog(false)}>
              Batal
            </Button>
            <Button onClick={handleSaveAdd}>
              Simpan
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      <Dialog open={editDialog} onOpenChange={setEditDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Edit Gudang</DialogTitle>
            <DialogDescription>Edit informasi gudang</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="code">Kode Gudang *</Label>
              <Input
                id="code"
                value={formData.code}
                onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="name">Nama Gudang *</Label>
              <Input
                id="name"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="location">Lokasi *</Label>
              <Input
                id="location"
                value={formData.location}
                onChange={(e) => setFormData({ ...formData, location: e.target.value })}
                required
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setEditDialog(false)}>
              Batal
            </Button>
            <Button onClick={handleSaveEdit}>
              Simpan
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  )
}
