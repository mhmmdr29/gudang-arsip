'use client'

import { useState, useEffect } from 'react'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { toast } from 'sonner'
import { Archive, Edit, Trash2, Plus } from 'lucide-react'
import { useAuthStore } from '@/lib/auth-store'

interface Shelf {
  id: string
  code: string
  warehouseCode: string
  warehouseName: string
}

interface Warehouse {
  id: string
  code: string
  name: string
}

export function ShelfList() {
  const [shelves, setShelves] = useState<Shelf[]>([])
  const [warehouses, setWarehouses] = useState<Warehouse[]>([])
  const [isLoading, setIsLoading] = useState(true)
  const [editDialog, setEditDialog] = useState(false)
  const [addDialog, setAddDialog] = useState(false)
  const [selectedShelf, setSelectedShelf] = useState<Shelf | null>(null)
  const [formData, setFormData] = useState({
    code: '',
    warehouseId: '',
  })

  const user = useAuthStore((state) => state.user)

  const canEdit = user?.role === 'KASSUBAG_KUL' || user?.role === 'ADMIN'

  useEffect(() => {
    fetchShelves()
    fetchWarehouses()
  }, [])

  const fetchShelves = async () => {
    try {
      const response = await fetch('/api/shelves')
      const data = await response.json()
      if (response.ok) {
        setShelves(data)
      }
    } catch (error) {
      toast.error('Gagal memuat data rak')
    } finally {
      setIsLoading(false)
    }
  }

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

  const handleAdd = () => {
    setSelectedShelf(null)
    setFormData({ code: '', warehouseId: '' })
    setAddDialog(true)
  }

  const handleEdit = (shelf: Shelf) => {
    setSelectedShelf(shelf)
    setFormData({
      code: shelf.code,
      warehouseId: '',
    })
    setEditDialog(true)
  }

  const handleSaveEdit = async () => {
    try {
      const response = await fetch(`/api/shelves/${selectedShelf?.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        throw new Error('Gagal mengupdate rak')
      }

      toast.success('Rak berhasil diupdate')
      setEditDialog(false)
      fetchShelves()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal mengupdate rak')
    }
  }

  const handleSaveAdd = async () => {
    try {
      const response = await fetch('/api/shelves', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        throw new Error('Gagal menambah rak')
      }

      toast.success('Rak berhasil ditambahkan')
      setAddDialog(false)
      fetchShelves()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menambah rak')
    }
  }

  const handleDelete = async (id: string) => {
    if (!confirm('Apakah Anda yakin ingin menghapus rak ini?')) return

    try {
      const response = await fetch(`/api/shelves/${id}`, {
        method: 'DELETE',
      })

      if (!response.ok) {
        throw new Error('Gagal menghapus rak')
      }

      toast.success('Rak berhasil dihapus')
      fetchShelves()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menghapus rak')
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-3xl font-bold tracking-tight">Daftar Rak</h2>
          <p className="text-muted-foreground">Kelola seluruh rak penyimpanan arsip</p>
        </div>
        {canEdit && (
          <Button onClick={handleAdd}>
            <Plus className="mr-2 h-4 w-4" />
            Tambah Rak
          </Button>
        )}
      </div>

      <Card>
        <CardHeader>
          <CardTitle>List Rak</CardTitle>
        </CardHeader>
        <CardContent>
          {isLoading ? (
            <p className="text-center py-8">Memuat data...</p>
          ) : shelves.length === 0 ? (
            <p className="text-center py-8 text-muted-foreground">Tidak ada rak</p>
          ) : (
            <div className="overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Kode Rak</TableHead>
                    <TableHead>Gudang</TableHead>
                    <TableHead>Kode Gudang</TableHead>
                    {canEdit && <TableHead>Aksi</TableHead>}
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {shelves.map((shelf) => (
                    <TableRow key={shelf.id}>
                      <TableCell className="font-medium">{shelf.code}</TableCell>
                      <TableCell>{shelf.warehouseName}</TableCell>
                      <TableCell>{shelf.warehouseCode}</TableCell>
                      {canEdit && (
                        <TableCell>
                          <div className="flex gap-2">
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleEdit(shelf)}
                            >
                              <Edit className="h-4 w-4" />
                            </Button>
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleDelete(shelf.id)}
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
            <DialogTitle>Tambah Rak</DialogTitle>
            <DialogDescription>Tambahkan rak baru ke sistem</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="add-code">Kode Rak *</Label>
              <Input
                id="add-code"
                value={formData.code}
                onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                placeholder="RAK-001"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="add-warehouse">Gudang *</Label>
              <Select
                value={formData.warehouseId}
                onValueChange={(value) => setFormData({ ...formData, warehouseId: value })}
                required
              >
                <SelectTrigger id="add-warehouse">
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
            <DialogTitle>Edit Rak</DialogTitle>
            <DialogDescription>Edit informasi rak</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="code">Kode Rak *</Label>
              <Input
                id="code"
                value={formData.code}
                onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="warehouse">Gudang *</Label>
              <Select
                value={formData.warehouseId}
                onValueChange={(value) => setFormData({ ...formData, warehouseId: value })}
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
