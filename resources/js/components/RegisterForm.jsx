// resources/js/components/RegisterForm.jsx
import React, { useState } from 'react'
import axios from 'axios'
import { useNavigate, Link } from 'react-router-dom'

export default function RegisterForm() {
  const [form, setForm] = useState({
    username: '',
    password: '',
    email: '',
    name: '',
    role: 'reader',
  })
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()

  const handleChange = (e) => {
    setForm(prev => ({ ...prev, [e.target.name]: e.target.value }))
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError('')
    setLoading(true)

    try {
      const res = await axios.post('/api/auth/register', form)
      if (res.status === 201 || res.status === 200) {
        navigate('/login')
      } else {
        throw new Error('Registration failed')
      }
    } catch (err) {
      setError(
        err.response?.data?.message ||
          err.message ||
          'Đăng ký thất bại. Vui lòng thử lại!'
      )
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="flex items-center justify-center bg-white">
      <div className="w-full max-w-lg bg-white p-10 rounded-lg shadow-lg">
        <h2 className="text-2xl font-bold text-center text-gray-800 mb-6">
          Đăng ký tài khoản
        </h2>

        {error && (
          <div className="mb-4 text-red-600 text-sm text-center">{error}</div>
        )}

        <form onSubmit={handleSubmit} className="space-y-6">
          {/** Username **/}
          <div className="grid grid-cols-3 gap-4 items-center">
            <label
              htmlFor="username"
              className="col-span-1 text-sm font-medium text-gray-700"
            >
              Tên đăng nhập
            </label>
            <input
              id="username"
              name="username"
              type="text"
              required
              value={form.username}
              onChange={handleChange}
              className="col-span-2 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Tên đăng nhập"
            />
          </div>

          {/** Name **/}
          <div className="grid grid-cols-3 gap-4 items-center">
            <label
              htmlFor="name"
              className="col-span-1 text-sm font-medium text-gray-700"
            >
              Họ và tên
            </label>
            <input
              id="name"
              name="name"
              type="text"
              required
              value={form.name}
              onChange={handleChange}
              className="col-span-2 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Họ và tên"
            />
          </div>

          {/** Email **/}
          <div className="grid grid-cols-3 gap-4 items-center">
            <label
              htmlFor="email"
              className="col-span-1 text-sm font-medium text-gray-700"
            >
              Email
            </label>
            <input
              id="email"
              name="email"
              type="email"
              required
              value={form.email}
              onChange={handleChange}
              className="col-span-2 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="you@example.com"
            />
          </div>

          {/** Password **/}
          <div className="grid grid-cols-3 gap-4 items-center">
            <label
              htmlFor="password"
              className="col-span-1 text-sm font-medium text-gray-700"
            >
              Mật khẩu
            </label>
            <input
              id="password"
              name="password"
              type="password"
              required
              value={form.password}
              onChange={handleChange}
              className="col-span-2 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="••••••••"
            />
          </div>

          {/** Submit **/}
          <button
            type="submit"
            disabled={loading}
            className={`w-full py-2 font-medium text-white rounded transition-colors ${
              loading ? 'bg-gray-400' : 'bg-blue-600 hover:bg-blue-700'
            }`}
          >
            {loading ? 'Đang đăng ký...' : 'Đăng ký'}
          </button>
        </form>

        <p className="mt-6 text-center text-sm text-gray-600">
          Đã có tài khoản?{' '}
          <Link to="/login" className="text-blue-600 hover:underline">
            Đăng nhập
          </Link>
        </p>
      </div>
    </div>
  )
}
