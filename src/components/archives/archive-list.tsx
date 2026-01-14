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
import { FileText, Edit, Trash2, Upload, Search, History } from 'lucide-react'
import { useAuthStore } from '@/lib/auth-store'

interface Archive {
  id: string
  name: string
  code: string
  subBagCode: string
  yearCreated: number
  retentionPeriod: number
  shelfCode: string
  warehouseCode: string
  warehouseName: string
  filePath: string
}

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

interface MovementHistory {
  id: string
  archiveName: string
  archiveCode: string
  previousShelf: string
  newShelf: string
  movedAt: string
  movedBy: string
}

export function ArchiveList() {
  const [archives, setArchives] = useState<Archive[]>([])
  const [warehouses, setWarehouses] = useState<Warehouse[]>([])
  const [shelves, setShelves] = useState<Shelf[]>([])
  const [searchTerm, setSearchTerm] = useState('')
  const [isLoading, setIsLoading] = useState(true)
  const [editDialog, setEditDialog] = useState(false)
  const [selectedArchive, setSelectedArchive] = useState<Archive | null>(null)
  const [historyDialog, setHistoryDialog] = useState(false)
  const [archiveHistory, setArchiveHistory] = useState<MovementHistory[]>([])
  const [selectedArchiveForHistory, setSelectedArchiveForHistory] = useState<Archive | null>(null)
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

  const user = useAuthStore((state) => state.user)

  const canEdit = user?.role === 'KASSUBAG_KUL' || user?.role === 'ADMIN'

  // Filter archives based on search term
  const filteredArchives = archives.filter((archive) => {
    const searchLower = searchTerm.toLowerCase()
    return (
      archive.name.toLowerCase().includes(searchLower) ||
      archive.code.toLowerCase().includes(searchLower) ||
      archive.subBagCode.toLowerCase().includes(searchLower) ||
      archive.shelfCode.toLowerCase().includes(searchLower) ||
      archive.warehouseCode.toLowerCase().includes(searchLower) ||
      archive.warehouseName.toLowerCase().includes(searchLower) ||
      archive.yearCreated.toString().includes(searchLower)
    )
  })

  useEffect(() => {
    fetchArchives()
    fetchWarehouses()
  }, [])

  const fetchArchives = async () => {
    try {
      const response = await fetch('/api/archives')
      const data = await response.json()
      if (response.ok) {
        setArchives(data)
      }
    } catch (error) {
      toast.error('Gagal memuat data arsip')
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

  const fetchArchiveHistory = async (archiveId: string) => {
    try {
      const response = await fetch(`/api/movements?archiveId=${archiveId}`)
      const data = await response.json()
      if (response.ok) {
        setArchiveHistory(data)
      }
    } catch (error) {
      toast.error('Gagal memuat riwayat arsip')
    }
  }

  const handleEdit = (archive: Archive) => {
    setSelectedArchive(archive)
    setFormData({
      name: archive.name,
      code: archive.code,
      subBagCode: archive.subBagCode,
      yearCreated: archive.yearCreated.toString(),
      retentionPeriod: archive.retentionPeriod.toString(),
      shelfId: '',
      warehouseId: '',
    })
    setEditDialog(true)
  }

  const handleSave = async () => {
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

      const response = await fetch(`/api/archives/${selectedArchive?.id}`, {
        method: 'PUT',
        body: formDataToSend,
      })

      if (!response.ok) {
        throw new Error('Gagal mengupdate arsip')
      }

      toast.success('Arsip berhasil diupdate')
      setEditDialog(false)
      fetchArchives()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal mengupdate arsip')
    }
  }

  const handleDelete = async (id: string) => {
    if (!confirm('Apakah Anda yakin ingin menghapus arsip ini?')) return

    try {
      const response = await fetch(`/api/archives/${id}`, {
        method: 'DELETE',
      })

      if (!response.ok) {
        throw new Error('Gagal menghapus arsip')
      }

      toast.success('Arsip berhasil dihapus')
      fetchArchives()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menghapus arsip')
    }
  }

  const handleViewHistory = (archive: Archive) => {
    setSelectedArchiveForHistory(archive)
    fetchArchiveHistory(archive.id)
    setHistoryDialog(true)
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleString('id-ID', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-3xl font-bold tracking-tight">Daftar Arsip</h2>
          <p className="text-muted-foreground">Kelola seluruh arsip yang tersimpan</p>
        </div>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>List Arsip</CardTitle>
        </CardHeader>
        <CardContent>
          {/* Search Bar */}
          <div className="mb-4">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input
                type="text"
                placeholder="Cari arsip berdasarkan nama, kode, sub bag, rak, gudang, atau tahun..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="pl-10"
              />
            </div>
            {searchTerm && (
              <p className="text-sm text-muted-foreground mt-2">
                Ditemukan {filteredArchives.length} dari {archives.length} arsip
              </p>
            )}
          </div>

          {isLoading ? (
            <p className="text-center py-8">Memuat data...</p>
          ) : filteredArchives.length === 0 ? (
            <p className="text-center py-8 text-muted-foreground">
              {searchTerm ? 'Tidak ada arsip yang cocok dengan pencarian' : 'Tidak ada arsip'}
            </p>
          ) : (
            <div className="overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Nama Arsip</TableHead>
                    <TableHead>Kode Arsip</TableHead>
                    <TableHead>Kode Sub Bag</TableHead>
                    <TableHead>Tahun</TableHead>
                    <TableHead>Retensi (Tahun)</TableHead>
                    <TableHead>Rak</TableHead>
                    <TableHead>Gudang</TableHead>
                    <TableHead>File</TableHead>
                    <TableHead>Aksi</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {filteredArchives.map((archive) => (
                    <TableRow key={archive.id}>
                      <TableCell className="font-medium">{archive.name}</TableCell>
                      <TableCell>{archive.code}</TableCell>
                      <TableCell>{archive.subBagCode}</TableCell>
                      <TableCell>{archive.yearCreated}</TableCell>
                      <TableCell>{archive.retentionPeriod}</TableCell>
                      <TableCell>{archive.shelfCode}</TableCell>
                      <TableCell>{archive.warehouseName}</TableCell>
                      <TableCell>
                        {archive.filePath ? (
                          <Button variant="ghost" size="sm" asChild>
                            <a href={archive.filePath} target="_blank" rel="noopener noreferrer">
                              <Upload className="h-4 w-4" />
                            </a>
                          </Button>
                        ) : (
                          '-'
                        )}
                      </TableCell>
                      <TableCell>
                        <div className="flex gap-2">
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleEdit(archive)}
                          >
                            <Edit className="h-4 w-4" />
                          </Button>
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleViewHistory(archive)}
                            title="Lihat Riwayat"
                          >
                            <History className="h-4 w-4" />
                          </Button>
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleDelete(archive.id)}
                          >
                            <Trash2 className="h-4 w-4" />
                          </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>
          )}
        </CardContent>
      </Card>

      {/* Edit Archive Dialog */}
      <Dialog open={editDialog} onOpenChange={setEditDialog}>
        <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>Edit Arsip</DialogTitle>
            <DialogDescription>Edit informasi arsip</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="name">Nama Arsip</Label>
              <Input
                id="name"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="code">Kode Arsip</Label>
              <Input
                id="code"
                value={formData.code}
                onChange={(e) => setFormData({ ...formData, code: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="subBagCode">Kode Sub Bag</Label>
              <Input
                id="subBagCode"
                value={formData.subBagCode}
                onChange={(e) => setFormData({ ...formData, subBagCode: e.target.value })}
              />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="grid gap-2">
                <Label htmlFor="yearCreated">Tahun Dibuat</Label>
                <Input
                  id="yearCreated"
                  type="number"
                  value={formData.yearCreated}
                  onChange={(e) => setFormData({ ...formData, yearCreated: e.target.value })}
                />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="retentionPeriod">Masa Retensi (Tahun)</Label>
                <Input
                  id="retentionPeriod"
                  type="number"
                  value={formData.retentionPeriod}
                  onChange={(e) => setFormData({ ...formData, retentionPeriod: e.target.value })}
                />
              </div>
            </div>
            <div className="grid gap-2">
              <Label htmlFor="warehouse">Gudang</Label>
              <Select
                value={formData.warehouseId}
                onValueChange={(value) => {
                  setFormData({ ...formData, warehouseId: value })
                  fetchShelves(value)
                }}
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
              <Label htmlFor="shelf">Rak</Label>
              <Select
                value={formData.shelfId}
                onValueChange={(value) => setFormData({ ...formData, shelfId: value })}
              >
                <SelectTrigger id="shelf">
                  <SelectValue placeholder="Pilih rak" />
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
              <Label htmlFor="file">Upload PDF (Opsional)</Label>
              <Input
                id="file"
                type="file"
                accept=".pdf"
                onChange={(e) => setFile(e.target.files?.[0] || null)}
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setEditDialog(false)}>
              Batal
            </Button>
            <Button onClick={handleSave}>
              Simpan
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* View History Dialog */}
      <Dialog open={historyDialog} onOpenChange={setHistoryDialog}>
        <DialogContent className="max-w-4xl max-h-[80vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>Riwayat Pemindahan Arsip</DialogTitle>
            <DialogDescription>
              {selectedArchiveForHistory && (
                <>Riwayat pemindahan untuk arsip: <strong>{selectedArchiveForHistory.name}</strong> ({selectedArchiveForHistory.code})</>
              )}
            </DialogDescription>
          </DialogHeader>
          <div className="overflow-x-auto">
            {archiveHistory.length === 0 ? (
              <p className="text-center py-8 text-muted-foreground">Belum ada riwayat pemindahan</p>
            ) : (
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Tanggal</TableHead>
                    <TableHead>Rak Sebelum</TableHead>
                    <TableHead>Rak Sesudah</TableHead>
                    <TableHead>Dipindahkan Oleh</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {archiveHistory.map((history) => (
                    <TableRow key={history.id}>
                      <TableCell>{formatDate(history.movedAt)}</TableCell>
                      <TableCell>{history.previousShelf}</TableCell>
                      <TableCell>{history.newShelf}</TableCell>
                      <TableCell>{history.movedBy}</TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setHistoryDialog(false)}>
              Tutup
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  )
}
