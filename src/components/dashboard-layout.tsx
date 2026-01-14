'use client'

import { useAuthStore } from '@/lib/auth-store'
import { Button } from '@/components/ui/button'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet'
import { Separator } from '@/components/ui/separator'
import {
  LayoutDashboard,
  FileText,
  ArrowRightLeft,
  Building2,
  Archive,
  Users,
  LogOut,
  Menu,
  FilePlus,
  Edit,
  Trash2,
  Package,
  UserPlus,
  FileEdit
} from 'lucide-react'

interface MenuItem {
  id: string
  label: string
  icon: React.ElementType
  subMenus?: SubMenuItem[]
}

interface SubMenuItem {
  id: string
  label: string
  icon: React.ElementType
}

const menuItems: MenuItem[] = [
  {
    id: 'daftar-arsip',
    label: 'Daftar Arsip',
    icon: FileText,
    subMenus: [
      { id: 'list-arsip', label: 'List Arsip', icon: LayoutDashboard },
      { id: 'tambah-arsip', label: 'Tambah Arsip', icon: FilePlus },
    ]
  },
  {
    id: 'riwayat-pemindahan',
    label: 'Riwayat Pemindahan',
    icon: ArrowRightLeft,
    subMenus: [
      { id: 'pindah-arsip', label: 'Pindah Arsip', icon: Package },
      { id: 'pinjam-arsip', label: 'Pinjam Arsip', icon: FileEdit },
      { id: 'list-riwayat', label: 'List Riwayat', icon: LayoutDashboard },
    ]
  },
  {
    id: 'daftar-gudang',
    label: 'Daftar Gudang',
    icon: Building2,
    subMenus: [
      { id: 'list-gudang', label: 'List Gudang', icon: LayoutDashboard },
      { id: 'tambah-gudang', label: 'Tambah Gudang', icon: FilePlus },
    ]
  },
  {
    id: 'daftar-rak',
    label: 'Daftar Rak',
    icon: Archive,
    subMenus: [
      { id: 'list-rak', label: 'List Rak', icon: LayoutDashboard },
      { id: 'tambah-rak', label: 'Tambah Rak', icon: FilePlus },
    ]
  },
  {
    id: 'daftar-akun',
    label: 'Daftar Akun',
    icon: Users,
    subMenus: [
      { id: 'list-akun', label: 'List Akun', icon: LayoutDashboard },
      { id: 'tambah-akun', label: 'Tambah Akun', icon: UserPlus },
    ]
  }
]

export function DashboardLayout({
  children,
  activeMenu,
  onMenuChange,
}: {
  children: React.ReactNode
  activeMenu: string
  onMenuChange: (menuId: string) => void
}) {
  const user = useAuthStore((state) => state.user)
  const logout = useAuthStore((state) => state.logout)

  const handleLogout = () => {
    logout()
    window.location.reload()
  }

  const canAccessMenu = (menuId: string): boolean => {
    if (!user) return false

    const rolePermissions: Record<string, string[]> = {
      'KASSUBAG_KUL': ['daftar-arsip', 'riwayat-pemindahan', 'daftar-gudang', 'daftar-rak', 'daftar-akun'],
      'ADMIN': ['daftar-arsip', 'riwayat-pemindahan', 'daftar-gudang', 'daftar-rak'],
      'STAFF': ['daftar-arsip', 'riwayat-pemindahan'],
    }

    return rolePermissions[user.role]?.includes(menuId) ?? false
  }

  const Sidebar = () => (
    <div className="flex h-full w-64 flex-col bg-card">
      <div className="p-6">
        <h1 className="text-xl font-bold">Gudang Arsip</h1>
        <p className="text-sm text-muted-foreground mt-1">
          Role: {user?.role}
        </p>
      </div>
      <Separator />
      <ScrollArea className="flex-1 px-4 py-4">
        <nav className="space-y-2">
          {menuItems.map((menu) => {
            if (!canAccessMenu(menu.id)) return null

            const Icon = menu.icon
            return (
              <div key={menu.id} className="space-y-1">
                <div className="flex items-center gap-2 px-3 py-2 text-sm font-medium text-muted-foreground">
                  <Icon className="h-4 w-4" />
                  <span>{menu.label}</span>
                </div>
                {menu.subMenus && (
                  <div className="ml-4 space-y-1">
                    {menu.subMenus.map((subMenu) => {
                      const SubIcon = subMenu.icon
                      return (
                        <button
                          key={subMenu.id}
                          onClick={() => onMenuChange(subMenu.id)}
                          className={`w-full flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-colors ${
                            activeMenu === subMenu.id
                              ? 'bg-primary text-primary-foreground'
                              : 'hover:bg-muted'
                          }`}
                        >
                          <SubIcon className="h-4 w-4" />
                          <span>{subMenu.label}</span>
                        </button>
                      )
                    })}
                  </div>
                )}
              </div>
            )
          })}
        </nav>
      </ScrollArea>
      <Separator />
      <div className="p-4">
        <Button
          variant="ghost"
          className="w-full justify-start"
          onClick={handleLogout}
        >
          <LogOut className="mr-2 h-4 w-4" />
          Keluar
        </Button>
      </div>
    </div>
  )

  return (
    <div className="min-h-screen flex flex-col bg-background">
      <div className="flex-1 flex">
        {/* Desktop Sidebar */}
        <div className="hidden md:block">
          <Sidebar />
        </div>

        {/* Mobile Sidebar */}
        <Sheet>
          <SheetTrigger asChild className="md:hidden absolute top-4 left-4 z-50">
            <Button variant="outline" size="icon">
              <Menu className="h-5 w-5" />
            </Button>
          </SheetTrigger>
          <SheetContent side="left" className="p-0">
            <Sidebar />
          </SheetContent>
        </Sheet>

        {/* Main Content */}
        <main className="flex-1 overflow-auto">
          <div className="container mx-auto p-6 md:p-8">
            {children}
          </div>
        </main>
      </div>
    </div>
  )
}
