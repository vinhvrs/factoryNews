import React, { useState } from 'react'
import { Outlet, Link, useNavigate } from 'react-router-dom'


export default function MainLayout() {

  const [open, setOpen] = useState(false)
  const username = localStorage.getItem('username') || 'Guest'

  const handleLogout = () => {
    localStorage.clear()
    useNavigate('/login')
  }

  return (
    <div className="min-h-screen bg-gray-80">
      <nav className="sticky top-0 z-10 space-x-4 max-w-screen mb-6 bg-gray-800 p-4 text-white">
        <div className="container mx-auto flex items-center justify-between space-x-4">
          <div className="flex space-x-6">
            <Link to="/writer-panel/dashboard" className="">Dashboard</Link>
          </div>

          <div className="relative">
            <button
              onClick={() => setOpen(o => !o)}
              className="flex items-center space-x-2 focus:outline-none"
            >
              <span>Welcome back,</span>
              <span className="font-semibold">{username}</span>
              <svg
                className="w-4 h-4 transform transition-transform"
                style={{ transform: open ? 'rotate(180deg)' : 'rotate(0)' }}
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
              >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                  d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            {open && (
              <div className="absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden">
                <Link
                  to="/settings"
                  className="block px-4 py-2 hover:bg-gray-100"
                  onClick={() => setOpen(false)}>
                  Settings
                </Link>
                <button
                  onClick={handleLogout}
                  className="w-full text-left px-4 py-2 hover:bg-gray-100">
                  Log out
                </button>
              </div>
            )}
          </div>
        </div>
      </nav>
      <main className="container mx-auto p-6">
        <Outlet />
      </main>
    </div>
  )
}
