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
import { ArrowRightLeft, Package, FileEdit, ArrowLeft, Search, Edit, Trash2 } from 'lucide-react'
import { useAuthStore } from '@/lib/auth-store'

interface Archive {
  id: string
  name: string
  code: string
  shelfCode: string
}

interface Shelf {
  id: string
  code: string
  warehouseName: string
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

interface BorrowHistory {
  id: string
  archiveName: string
  archiveCode: string
  borrowerName: string
  borrowedAt: string
  returnedAt: string | null
}

export function MovementHistoryList() {
  const user = useAuthStore((state) => state.user)
  const [activeTab, setActiveTab] = useState<'history' | 'move' | 'borrow'>('history')
  const [archives, setArchives] = useState<Archive[]>([])
  const [shelves, setShelves] = useState<Shelf[]>([])
  const [movementHistory, setMovementHistory] = useState<MovementHistory[]>([])
  const [borrowHistory, setBorrowHistory] = useState<BorrowHistory[]>([])
  const [isLoading, setIsLoading] = useState(true)

  // Move archive form
  const [moveDialog, setMoveDialog] = useState(false)
  const [moveArchiveSearch, setMoveArchiveSearch] = useState('')
  const [moveShelfSearch, setMoveShelfSearch] = useState('')
  const [moveFormData, setMoveFormData] = useState({
    archiveId: '',
    newShelfId: '',
    movedBy: '',
  })

  // Borrow archive form
  const [borrowDialog, setBorrowDialog] = useState(false)
  const [borrowArchiveSearch, setBorrowArchiveSearch] = useState('')
  const [borrowFormData, setBorrowFormData] = useState({
    archiveId: '',
    borrowerName: '',
  })

  // Edit movement form
  const [editMovementDialog, setEditMovementDialog] = useState(false)
  const [editMovementFormData, setEditMovementFormData] = useState({
    archiveName: '',
    archiveCode: '',
    previousShelf: '',
    newShelf: '',
    movedBy: '',
  })
  const [selectedMovement, setSelectedMovement] = useState<MovementHistory | null>(null)

  // Check if user can edit/delete movement history
  const canEditMovement = user?.role === 'KASSUBAG_KUL' || user?.role === 'ADMIN'

  useEffect(() => {
    fetchArchives()
    fetchShelves()
    fetchMovementHistory()
    fetchBorrowHistory()
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

  const fetchShelves = async () => {
    try {
      const response = await fetch('/api/shelves')
      const data = await response.json()
      if (response.ok) {
        setShelves(data)
      }
    } catch (error) {
      toast.error('Gagal memuat data rak')
    }
  }

  const fetchMovementHistory = async () => {
    try {
      const response = await fetch('/api/movements')
      const data = await response.json()
      if (response.ok) {
        setMovementHistory(data)
      }
    } catch (error) {
      toast.error('Gagal memuat riwayat pemindahan')
    }
  }

  const fetchBorrowHistory = async () => {
    try {
      const response = await fetch('/api/borrowings')
      const data = await response.json()
      if (response.ok) {
        setBorrowHistory(data)
      }
    } catch (error) {
      toast.error('Gagal memuat riwayat peminjaman')
    }
  }

  const handleMoveArchive = async () => {
    try {
      const response = await fetch('/api/movements', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(moveFormData),
      })

      if (!response.ok) {
        throw new Error('Gagal memindahkan arsip')
      }

      toast.success('Arsip berhasil dipindahkan')
      setMoveDialog(false)
      setMoveFormData({ archiveId: '', newShelfId: '', movedBy: '' })
      setMoveArchiveSearch('')
      setMoveShelfSearch('')
      fetchMovementHistory()
      fetchArchives()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal memindahkan arsip')
    }
  }

  const handleBorrowArchive = async () => {
    try {
      const response = await fetch('/api/borrowings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(borrowFormData),
      })

      if (!response.ok) {
        throw new Error('Gagal meminjam arsip')
      }

      toast.success('Arsip berhasil dipinjam')
      setBorrowDialog(false)
      setBorrowFormData({ archiveId: '', borrowerName: '' })
      setBorrowArchiveSearch('')
      fetchBorrowHistory()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal meminjam arsip')
    }
  }

  const handleReturnArchive = async (id: string) => {
    try {
      const response = await fetch(`/api/borrowings/${id}/return`, {
        method: 'POST',
      })

      if (!response.ok) {
        throw new Error('Gagal mengembalikan arsip')
      }

      toast.success('Arsip berhasil dikembalikan')
      fetchBorrowHistory()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal mengembalikan arsip')
    }
  }

  const handleEditMovement = (movement: MovementHistory) => {
    setSelectedMovement(movement)
    setEditMovementFormData({
      archiveName: movement.archiveName,
      archiveCode: movement.archiveCode,
      previousShelf: movement.previousShelf,
      newShelf: movement.newShelf,
      movedBy: movement.movedBy,
    })
    setEditMovementDialog(true)
  }

  const handleSaveMovement = async () => {
    if (!selectedMovement) return

    try {
      const response = await fetch(`/api/movements/${selectedMovement.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(editMovementFormData),
      })

      if (!response.ok) {
        throw new Error('Gagal mengupdate riwayat pemindahan')
      }

      toast.success('Riwayat pemindahan berhasil diupdate')
      setEditMovementDialog(false)
      fetchMovementHistory()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal mengupdate riwayat pemindahan')
    }
  }

  const handleDeleteMovement = async (id: string) => {
    if (!confirm('Apakah Anda yakin ingin menghapus riwayat pemindahan ini?')) return

    try {
      const response = await fetch(`/api/movements/${id}`, {
        method: 'DELETE',
      })

      if (!response.ok) {
        throw new Error('Gagal menghapus riwayat pemindahan')
      }

      toast.success('Riwayat pemindahan berhasil dihapus')
      fetchMovementHistory()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menghapus riwayat pemindahan')
    }
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

  // Filter archives for move form
  const filteredMoveArchives = archives.filter((archive) => {
    const searchLower = moveArchiveSearch.toLowerCase()
    return (
      archive.name.toLowerCase().includes(searchLower) ||
      archive.code.toLowerCase().includes(searchLower) ||
      archive.shelfCode.toLowerCase().includes(searchLower)
    )
  })

  // Filter shelves for move form
  const filteredMoveShelves = shelves.filter((shelf) => {
    const searchLower = moveShelfSearch.toLowerCase()
    return (
      shelf.code.toLowerCase().includes(searchLower) ||
      shelf.warehouseName.toLowerCase().includes(searchLower)
    )
  })

  // Filter archives for borrow form
  const filteredBorrowArchives = archives.filter((archive) => {
    const searchLower = borrowArchiveSearch.toLowerCase()
    return (
      archive.name.toLowerCase().includes(searchLower) ||
      archive.code.toLowerCase().includes(searchLower) ||
      archive.shelfCode.toLowerCase().includes(searchLower)
    )
  })

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-4">
        <div className="flex-1">
          <h2 className="text-3xl font-bold tracking-tight">Riwayat Pemindahan</h2>
          <p className="text-muted-foreground">Kelola riwayat pemindahan dan peminjaman arsip</p>
        </div>
      </div>

      <div className="flex gap-2">
        <Button
          variant={activeTab === 'history' ? 'default' : 'outline'}
          onClick={() => setActiveTab('history')}
        >
          List Riwayat
        </Button>
        <Button
          variant={activeTab === 'move' ? 'default' : 'outline'}
          onClick={() => setActiveTab('move')}
        >
          <Package className="mr-2 h-4 w-4" />
          Pindah Arsip
        </Button>
        <Button
          variant={activeTab === 'borrow' ? 'default' : 'outline'}
          onClick={() => setActiveTab('borrow')}
        >
          <FileEdit className="mr-2 h-4 w-4" />
          Pinjam Arsip
        </Button>
      </div>

      {activeTab === 'history' && (
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Riwayat Pemindahan</CardTitle>
            </CardHeader>
            <CardContent>
              {movementHistory.length === 0 ? (
                <p className="text-center py-8 text-muted-foreground">Tidak ada riwayat pemindahan</p>
              ) : (
                <div className="overflow-x-auto">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Nama Arsip</TableHead>
                        <TableHead>Kode Arsip</TableHead>
                        <TableHead>Rak Sebelum</TableHead>
                        <TableHead>Rak Sesudah</TableHead>
                        <TableHead>Tanggal</TableHead>
                        <TableHead>Dipindahkan Oleh</TableHead>
                        {canEditMovement && <TableHead>Aksi</TableHead>}
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {movementHistory.map((item) => (
                        <TableRow key={item.id}>
                          <TableCell className="font-medium">{item.archiveName}</TableCell>
                          <TableCell>{item.archiveCode}</TableCell>
                          <TableCell>{item.previousShelf}</TableCell>
                          <TableCell>{item.newShelf}</TableCell>
                          <TableCell>{formatDate(item.movedAt)}</TableCell>
                          <TableCell>{item.movedBy}</TableCell>
                          {canEditMovement && (
                            <TableCell>
                              <div className="flex gap-2">
                                <Button
                                  variant="ghost"
                                  size="sm"
                                  onClick={() => handleEditMovement(item)}
                                >
                                  <Edit className="h-4 w-4" />
                                </Button>
                                <Button
                                  variant="ghost"
                                  size="sm"
                                  onClick={() => handleDeleteMovement(item.id)}
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

          <Card>
            <CardHeader>
              <CardTitle>Riwayat Peminjaman</CardTitle>
            </CardHeader>
            <CardContent>
              {borrowHistory.length === 0 ? (
                <p className="text-center py-8 text-muted-foreground">Tidak ada riwayat peminjaman</p>
              ) : (
                <div className="overflow-x-auto">
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Nama Arsip</TableHead>
                        <TableHead>Kode Arsip</TableHead>
                        <TableHead>Peminjam</TableHead>
                        <TableHead>Tanggal Pinjam</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Aksi</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {borrowHistory.map((item) => (
                        <TableRow key={item.id}>
                          <TableCell className="font-medium">{item.archiveName}</TableCell>
                          <TableCell>{item.archiveCode}</TableCell>
                          <TableCell>{item.borrowerName}</TableCell>
                          <TableCell>{formatDate(item.borrowedAt)}</TableCell>
                          <TableCell>
                            {item.returnedAt ? (
                              <span className="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Dikembalikan
                              </span>
                            ) : (
                              <span className="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                Dipinjam
                              </span>
                            )}
                          </TableCell>
                          <TableCell>
                            {!item.returnedAt && (
                              <Button
                                size="sm"
                                onClick={() => handleReturnArchive(item.id)}
                              >
                                Kembalikan
                              </Button>
                            )}
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              )}
            </CardContent>
          </Card>
        </div>
      )}

      {activeTab === 'move' && (
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Package className="h-5 w-5" />
                Pindah Arsip
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid gap-2">
                <Label htmlFor="move-archive-search">Cari Arsip *</Label>
                <div className="relative">
                  <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input
                    id="move-archive-search"
                    type="text"
                    placeholder="Cari berdasarkan nama, kode, atau rak..."
                    value={moveArchiveSearch}
                    onChange={(e) => setMoveArchiveSearch(e.target.value)}
                    className="pl-10"
                  />
                </div>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="archive">Pilih Arsip *</Label>
                <Select
                  value={moveFormData.archiveId}
                  onValueChange={(value) => setMoveFormData({ ...moveFormData, archiveId: value })}
                >
                  <SelectTrigger id="archive">
                    <SelectValue placeholder="Pilih arsip" />
                  </SelectTrigger>
                  <SelectContent>
                    {filteredMoveArchives.length === 0 ? (
                      <div className="p-2 text-sm text-muted-foreground text-center">
                        {moveArchiveSearch ? 'Tidak ada arsip yang cocok' : 'Pilih arsip dari daftar'}
                      </div>
                    ) : (
                      filteredMoveArchives.map((archive) => (
                        <SelectItem key={archive.id} value={archive.id}>
                          {archive.name} ({archive.code}) - Rak: {archive.shelfCode}
                        </SelectItem>
                      ))
                    )}
                  </SelectContent>
                </Select>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="move-shelf-search">Cari Rak Tujuan *</Label>
                <div className="relative">
                  <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input
                    id="move-shelf-search"
                    type="text"
                    placeholder="Cari berdasarkan kode rak atau nama gudang..."
                    value={moveShelfSearch}
                    onChange={(e) => setMoveShelfSearch(e.target.value)}
                    className="pl-10"
                  />
                </div>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="newShelf">Pilih Rak Tujuan *</Label>
                <Select
                  value={moveFormData.newShelfId}
                  onValueChange={(value) => setMoveFormData({ ...moveFormData, newShelfId: value })}
                >
                  <SelectTrigger id="newShelf">
                    <SelectValue placeholder="Pilih rak tujuan" />
                  </SelectTrigger>
                  <SelectContent>
                    {filteredMoveShelves.length === 0 ? (
                      <div className="p-2 text-sm text-muted-foreground text-center">
                        {moveShelfSearch ? 'Tidak ada rak yang cocok' : 'Pilih rak dari daftar'}
                      </div>
                    ) : (
                      filteredMoveShelves.map((shelf) => (
                        <SelectItem key={shelf.id} value={shelf.id}>
                          {shelf.code} - {shelf.warehouseName}
                        </SelectItem>
                      ))
                    )}
                  </SelectContent>
                </Select>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="movedBy">Dipindahkan Oleh *</Label>
                <Input
                  id="movedBy"
                  type="text"
                  value={moveFormData.movedBy}
                  onChange={(e) => setMoveFormData({ ...moveFormData, movedBy: e.target.value })}
                  placeholder="Masukkan nama yang memindahkan arsip"
                />
              </div>
              <Button onClick={handleMoveArchive} disabled={!moveFormData.archiveId || !moveFormData.newShelfId || !moveFormData.movedBy}>
                Pindahkan Arsip
              </Button>
            </CardContent>
          </Card>
        </div>
      )}

      {activeTab === 'borrow' && (
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <FileEdit className="h-5 w-5" />
                Pinjam Arsip
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid gap-2">
                <Label htmlFor="borrow-archive-search">Cari Arsip *</Label>
                <div className="relative">
                  <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input
                    id="borrow-archive-search"
                    type="text"
                    placeholder="Cari berdasarkan nama, kode, atau rak..."
                    value={borrowArchiveSearch}
                    onChange={(e) => setBorrowArchiveSearch(e.target.value)}
                    className="pl-10"
                  />
                </div>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="borrowArchive">Pilih Arsip *</Label>
                <Select
                  value={borrowFormData.archiveId}
                  onValueChange={(value) => setBorrowFormData({ ...borrowFormData, archiveId: value })}
                >
                  <SelectTrigger id="borrowArchive">
                    <SelectValue placeholder="Pilih arsip" />
                  </SelectTrigger>
                  <SelectContent>
                    {filteredBorrowArchives.length === 0 ? (
                      <div className="p-2 text-sm text-muted-foreground text-center">
                        {borrowArchiveSearch ? 'Tidak ada arsip yang cocok' : 'Pilih arsip dari daftar'}
                      </div>
                    ) : (
                      filteredBorrowArchives.map((archive) => (
                        <SelectItem key={archive.id} value={archive.id}>
                          {archive.name} ({archive.code}) - Rak: {archive.shelfCode}
                        </SelectItem>
                      ))
                    )}
                  </SelectContent>
                </Select>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="borrowerName">Nama Peminjam *</Label>
                <Input
                  id="borrowerName"
                  value={borrowFormData.borrowerName}
                  onChange={(e) => setBorrowFormData({ ...borrowFormData, borrowerName: e.target.value })}
                  placeholder="Masukkan nama peminjam"
                />
              </div>
              <Button onClick={handleBorrowArchive} disabled={!borrowFormData.archiveId || !borrowFormData.borrowerName}>
                Pinjam Arsip
              </Button>
            </CardContent>
          </Card>
        </div>
      )}

      <Dialog open={editMovementDialog} onOpenChange={setEditMovementDialog}>
        <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>Edit Riwayat Pemindahan</DialogTitle>
            <DialogDescription>Edit informasi riwayat pemindahan arsip</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="edit-archiveName">Nama Arsip</Label>
              <Input
                id="edit-archiveName"
                value={editMovementFormData.archiveName}
                onChange={(e) => setEditMovementFormData({ ...editMovementFormData, archiveName: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="edit-archiveCode">Kode Arsip</Label>
              <Input
                id="edit-archiveCode"
                value={editMovementFormData.archiveCode}
                onChange={(e) => setEditMovementFormData({ ...editMovementFormData, archiveCode: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="edit-previousShelf">Rak Sebelum</Label>
              <Input
                id="edit-previousShelf"
                value={editMovementFormData.previousShelf}
                onChange={(e) => setEditMovementFormData({ ...editMovementFormData, previousShelf: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="edit-newShelf">Rak Sesudah</Label>
              <Input
                id="edit-newShelf"
                value={editMovementFormData.newShelf}
                onChange={(e) => setEditMovementFormData({ ...editMovementFormData, newShelf: e.target.value })}
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="edit-movedBy">Dipindahkan Oleh</Label>
              <Input
                id="edit-movedBy"
                value={editMovementFormData.movedBy}
                onChange={(e) => setEditMovementFormData({ ...editMovementFormData, movedBy: e.target.value })}
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setEditMovementDialog(false)}>
              Batal
            </Button>
            <Button onClick={handleSaveMovement}>
              Simpan
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  )
}
