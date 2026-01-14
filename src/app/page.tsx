'use client'

import { useState, useEffect } from 'react'
import { useAuthStore } from '@/lib/auth-store'
import { DashboardLayout } from '@/components/dashboard-layout'
import { ArchiveList } from '@/components/archives/archive-list'
import { AddArchive } from '@/components/archives/add-archive'
import { WarehouseList } from '@/components/warehouses/warehouse-list'
import { AddWarehouse } from '@/components/warehouses/add-warehouse'
import { ShelfList } from '@/components/shelves/shelf-list'
import { AddShelf } from '@/components/shelves/add-shelf'
import { AccountList } from '@/components/accounts/account-list'
import { AddAccount } from '@/components/accounts/add-account'
import { MovementHistoryList } from '@/components/movements/movement-history'
import LoginPage from '@/components/login-page'

type ActiveMenu = string

export default function Home() {
  const { isAuthenticated, user } = useAuthStore()

  // Get active menu from localStorage or default to 'list-arsip'
  const [activeMenu, setActiveMenu] = useState<ActiveMenu>('list-arsip')
  const [mounted, setMounted] = useState(false)

  // Sync with localStorage only after component is mounted
  useEffect(() => {
    setMounted(true)
    try {
      const saved = localStorage.getItem('activeMenu')
      if (saved) {
        setActiveMenu(saved)
      }
    } catch (error) {
      console.error('Error accessing localStorage:', error)
    }
  }, [])

  const handleMenuChange = (menuId: string) => {
    setActiveMenu(menuId)
    try {
      localStorage.setItem('activeMenu', menuId)
    } catch (error) {
      console.error('Error setting localStorage:', error)
    }
  }

  // Show loading state while checking auth
  if (!mounted) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p className="text-muted-foreground">Memuat...</p>
      </div>
    )
  }

  if (!isAuthenticated) {
    return <LoginPage />
  }

  const renderContent = () => {
    switch (activeMenu) {
      case 'list-arsip':
        return <ArchiveList />
      case 'tambah-arsip':
        return <AddArchive />
      case 'list-gudang':
        return <WarehouseList />
      case 'tambah-gudang':
        return <AddWarehouse />
      case 'list-rak':
        return <ShelfList />
      case 'tambah-rak':
        return <AddShelf />
      case 'list-akun':
        return <AccountList />
      case 'tambah-akun':
        return <AddAccount />
      case 'list-riwayat':
      case 'pindah-arsip':
      case 'pinjam-arsip':
        return <MovementHistoryList />
      default:
        return <ArchiveList />
    }
  }

  return (
    <DashboardLayout activeMenu={activeMenu} onMenuChange={handleMenuChange}>
      <div className="space-y-6">
        {renderContent()}
      </div>
    </DashboardLayout>
  )
}
