import React, { useState } from 'react'
import axios from 'axios'
import { useNavigate, Link } from 'react-router-dom'

export default function LoginForm() {
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const navigate = useNavigate()

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError('')

    try {
      const res = await axios.post('/api/auth/login', { username, password })
      if (res.statusText !== 'OK') {
        throw new Error('Login failed. Please try again!')
      }
      alert('Login successful!')
      localStorage.setItem('username', res.data.user.username)
      localStorage.setItem('userId', res.data.user.uid)
      
      if (res.data.user.role === 'admin') {
        localStorage.setItem('isAdmin', true)
        navigate('/admin-panel')
      } else {
        localStorage.setItem('isAdmin', false)
        if (res.data.user.role === 'journalist') {
          localStorage.setItem('isWriter', true)
          navigate('/writer-panel')
        } else {
          localStorage.setItem('isWriter', false)
          navigate('/')
        }
      }
    } catch (err) {
      setError(
        err.response?.data?.message ||
        'Login failed. Please try again!'
      )
    }
  }

  return (
    <div className="flex items-center justify-center py-8 px-6">
      <div className="w-full max-w-lg w-full bg-white p-10 rounded-lg shadow">
        <h2 className="text-2xl font-bold text-center text-gray-800 mb-6">
          Login
        </h2>

        {error && (
          <div className="mb-4 text-red-600 text-sm text-center">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-5">
          <div className="flex items-center space-x-4">
            <label htmlFor="username" className="flex-none w-32 block text-sm font-medium text-gray-700">
              username
            </label>
            <input
              id="username"
              type="text"
              required
              value={username}
              onChange={e => setUsername(e.target.value)}
              className="mt-1 w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Tên đăng nhập"
            />
          </div>

          <div className="flex items-center space-x-4">
            <label htmlFor="password" className="flex-none w-32 block text-sm font-medium text-gray-700">
              password
            </label>
            <input
              id="password"
              type="password"
              required
              value={password}
              onChange={e => setPassword(e.target.value)}
              className="mt-1 w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="•••••••••••••••••••••••"
            />
          </div>

          <button
            type="submit"
            className="w-full py-2 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition-colors"
          >
            Login
          </button>
        </form>

        <p className="mt-4 text-center text-sm text-gray-600">
          Don't have an account?{' '}
          <Link to="/register" className="text-blue-600 hover:underline">
            Register
          </Link>
        </p>
      </div>
    </div>
  )
}
