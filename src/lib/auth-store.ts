import { create } from 'zustand'
import { persist } from 'zustand/middleware'

export type Role = 'KASSUBAG_KUL' | 'ADMIN' | 'STAFF'

interface User {
  id: string
  username: string
  email: string
  role: Role
}

interface AuthState {
  user: User | null
  isAuthenticated: boolean
  login: (user: User) => void
  logout: () => void
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set) => ({
      user: null,
      isAuthenticated: false,
      login: (user) => set({ user, isAuthenticated: true }),
      logout: () => set({ user: null, isAuthenticated: false }),
    }),
    {
      name: 'auth-storage',
      storage: {
        getItem: (name) => {
          if (typeof window === 'undefined') return null
          try {
            const str = localStorage.getItem(name)
            return str ? JSON.parse(str) : null
          } catch (error) {
            console.error('Error getting item from localStorage:', error)
            return null
          }
        },
        setItem: (name, value) => {
          if (typeof window === 'undefined') return
          try {
            localStorage.setItem(name, JSON.stringify(value))
          } catch (error) {
            console.error('Error setting item in localStorage:', error)
          }
        },
        removeItem: (name) => {
          if (typeof window === 'undefined') return
          try {
            localStorage.removeItem(name)
          } catch (error) {
            console.error('Error removing item from localStorage:', error)
          }
        },
      },
    }
  )
)

// Helper function to check if user has access to specific menu
export const canAccessMenu = (role: Role, menu: string): boolean => {
  const rolePermissions: Record<Role, string[]> = {
    KASSUBAG_KUL: ['daftar-arsip', 'riwayat-pemindahan', 'daftar-gudang', 'daftar-rak', 'daftar-akun'],
    ADMIN: ['daftar-arsip', 'riwayat-pemindahan', 'daftar-gudang', 'daftar-rak'],
    STAFF: ['daftar-arsip', 'riwayat-pemindahan'],
  }

  return rolePermissions[role]?.includes(menu) ?? false
}
