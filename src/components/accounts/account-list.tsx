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
import { Users, Edit, Trash2, Plus, UserPlus } from 'lucide-react'
import { useAuthStore } from '@/lib/auth-store'

interface Account {
  id: string
  username: string
  email: string
  role: string
}

export function AccountList() {
  const [accounts, setAccounts] = useState<Account[]>([])
  const [isLoading, setIsLoading] = useState(true)
  const [editDialog, setEditDialog] = useState(false)
  const [addDialog, setAddDialog] = useState(false)
  const [selectedAccount, setSelectedAccount] = useState<Account | null>(null)
  const [formData, setFormData] = useState({
    username: '',
    email: '',
    password: '',
    role: 'STAFF',
  })

  const user = useAuthStore((state) => state.user)

  const canManage = user?.role === 'KASSUBAG_KUL'

  useEffect(() => {
    fetchAccounts()
  }, [])

  const fetchAccounts = async () => {
    try {
      const response = await fetch('/api/accounts')
      const data = await response.json()
      if (response.ok) {
        setAccounts(data)
      }
    } catch (error) {
      toast.error('Gagal memuat data akun')
    } finally {
      setIsLoading(false)
    }
  }

  const handleAdd = () => {
    setSelectedAccount(null)
    setFormData({ username: '', email: '', password: '', role: 'STAFF' })
    setAddDialog(true)
  }

  const handleEdit = (account: Account) => {
    setSelectedAccount(account)
    setFormData({
      username: account.username,
      email: account.email,
      password: '',
      role: account.role,
    })
    setEditDialog(true)
  }

  const handleSaveEdit = async () => {
    try {
      const response = await fetch(`/api/accounts/${selectedAccount?.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        throw new Error('Gagal mengupdate akun')
      }

      toast.success('Akun berhasil diupdate')
      setEditDialog(false)
      fetchAccounts()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal mengupdate akun')
    }
  }

  const handleSaveAdd = async () => {
    try {
      const response = await fetch('/api/accounts', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        throw new Error('Gagal menambah akun')
      }

      toast.success('Akun berhasil ditambahkan')
      setAddDialog(false)
      fetchAccounts()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menambah akun')
    }
  }

  const handleDelete = async (id: string) => {
    if (!confirm('Apakah Anda yakin ingin menghapus akun ini?')) return

    try {
      const response = await fetch(`/api/accounts/${id}`, {
        method: 'DELETE',
      })

      if (!response.ok) {
        throw new Error('Gagal menghapus akun')
      }

      toast.success('Akun berhasil dihapus')
      fetchAccounts()
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Gagal menghapus akun')
    }
  }

  const getRoleBadge = (role: string) => {
    switch (role) {
      case 'KASSUBAG_KUL':
        return <span className="px-2 py-1 rounded-full text-xs font-semibold bg-primary text-primary-foreground">Kassubag</span>
      case 'ADMIN':
        return <span className="px-2 py-1 rounded-full text-xs font-semibold bg-secondary text-secondary-foreground">Admin</span>
      case 'STAFF':
        return <span className="px-2 py-1 rounded-full text-xs font-semibold bg-muted text-muted-foreground">Staff</span>
      default:
        return <span className="px-2 py-1 rounded-full text-xs font-semibold bg-muted">{role}</span>
    }
  }

  if (!canManage) {
    return (
      <div className="flex items-center justify-center h-96">
        <div className="text-center space-y-4">
          <Users className="h-16 w-16 mx-auto text-muted-foreground" />
          <h2 className="text-2xl font-bold">Akses Ditolak</h2>
          <p className="text-muted-foreground">
            Anda tidak memiliki izin untuk mengakses menu ini
          </p>
        </div>
      </div>
    )
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-3xl font-bold tracking-tight">Daftar Akun</h2>
          <p className="text-muted-foreground">Kelola seluruh akun pengguna sistem</p>
        </div>
        <Button onClick={handleAdd}>
          <UserPlus className="mr-2 h-4 w-4" />
          Tambah Akun
        </Button>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>List Akun</CardTitle>
        </CardHeader>
        <CardContent>
          {isLoading ? (
            <p className="text-center py-8">Memuat data...</p>
          ) : accounts.length === 0 ? (
            <p className="text-center py-8 text-muted-foreground">Tidak ada akun</p>
          ) : (
            <div className="overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Username</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Role</TableHead>
                    <TableHead>Aksi</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {accounts.map((account) => (
                    <TableRow key={account.id}>
                      <TableCell className="font-medium">{account.username}</TableCell>
                      <TableCell>{account.email}</TableCell>
                      <TableCell>{getRoleBadge(account.role)}</TableCell>
                      <TableCell>
                        <div className="flex gap-2">
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleEdit(account)}
                          >
                            <Edit className="h-4 w-4" />
                          </Button>
                          {account.id !== user?.id && (
                            <Button
                              variant="ghost"
                              size="sm"
                              onClick={() => handleDelete(account.id)}
                            >
                              <Trash2 className="h-4 w-4" />
                            </Button>
                          )}
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

      <Dialog open={addDialog} onOpenChange={setAddDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Tambah Akun</DialogTitle>
            <DialogDescription>Tambahkan akun pengguna baru</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="add-username">Username *</Label>
              <Input
                id="add-username"
                value={formData.username}
                onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                placeholder="Masukkan username"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="add-email">Email *</Label>
              <Input
                id="add-email"
                type="email"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                placeholder="Masukkan email"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="add-password">Password *</Label>
              <Input
                id="add-password"
                type="password"
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                placeholder="Masukkan password"
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="add-role">Role *</Label>
              <Select
                value={formData.role}
                onValueChange={(value) => setFormData({ ...formData, role: value })}
                required
              >
                <SelectTrigger id="add-role">
                  <SelectValue placeholder="Pilih role" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="KASSUBAG_KUL">Kassubag KUL</SelectItem>
                  <SelectItem value="ADMIN">Admin</SelectItem>
                  <SelectItem value="STAFF">Staff</SelectItem>
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
            <DialogTitle>Edit Akun</DialogTitle>
            <DialogDescription>Edit informasi akun</DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid gap-2">
              <Label htmlFor="username">Username *</Label>
              <Input
                id="username"
                value={formData.username}
                onChange={(e) => setFormData({ ...formData, username: e.target.value })}
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
                required
              />
            </div>
            <div className="grid gap-2">
              <Label htmlFor="password">Password</Label>
              <Input
                id="password"
                type="password"
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                placeholder="Kosongkan jika tidak ingin mengubah"
              />
              <p className="text-xs text-muted-foreground">
                Kosongkan password jika tidak ingin mengubahnya
              </p>
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
                  <SelectItem value="KASSUBAG_KUL">Kassubag KUL</SelectItem>
                  <SelectItem value="ADMIN">Admin</SelectItem>
                  <SelectItem value="STAFF">Staff</SelectItem>
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
