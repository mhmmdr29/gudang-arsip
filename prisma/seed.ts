import { db } from '@/lib/db'

async function main() {
  console.log('Checking for existing accounts...')

  const existingAccounts = await db.account.count()

  if (existingAccounts > 0) {
    console.log('Accounts already exist. Skipping seed.')
    return
  }

  console.log('Creating default accounts...')

  await db.account.createMany({
    data: [
      {
        username: 'admin',
        email: 'admin@example.com',
        password: 'admin123',
        role: 'ADMIN',
      },
      {
        username: 'kassubag',
        email: 'kassubag@example.com',
        password: 'kassubag123',
        role: 'KASSUBAG_KUL',
      },
      {
        username: 'staff',
        email: 'staff@example.com',
        password: 'staff123',
        role: 'STAFF',
      },
    ],
  })

  console.log('✅ Default accounts created successfully!')
  console.log('Default credentials:')
  console.log('  Admin: admin / admin123')
  console.log('  Kassubag: kassubag / kassubag123')
  console.log('  Staff: staff / staff123')
}

main()
  .catch((e) => {
    console.error('Error seeding database:', e)
    process.exit(1)
  })
  .finally(async () => {
    await db.$disconnect()
  })
