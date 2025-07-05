import React, { useState, useRef, useEffect } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import { set } from 'lodash';

export default function NewsForm() {
  const [error, setError] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const [author, setAuthor] = useState(null)
  const navigate = useNavigate();

  const [form, setForm] = useState({
    title: '',
    content: '',
    author_id: localStorage.getItem('userId') || '',
    thumbnail_id: '',
  });

  const authorName = () => {
    const name = localStorage.getItem('username')
    return name ?? 'Anonymous Author'
  }

  const taRef = useRef(null)
  
  const insertAtCursor = text => {
    const ta = taRef.current
    if (!ta) return
    const { selectionStart: start, selectionEnd: end } = ta
    const before = form.content.slice(0, start)
    const after = form.content.slice(end)
    const next = before + text + after
    setForm(f => ({ ...f, content: next }))
    setTimeout(() => {
      ta.selectionStart = ta.selectionEnd = start + text.length
      ta.focus()
    }, 0)
  }

  function insertImage(imgData) {
    const imgPath = imgData.path.startsWith('http')
      ? imgData.path
      : `http://localhost:1111${imgData.path}`
    const imgTag = `<img src="${imgPath}" alt="${imgData.alt || imgData.name}" />`
    insertAtCursor(imgTag)
  }

  const handleImageUpload = async e => {
    const file = e.target.files[0]
    if (!file) return
    const data = new FormData()
    data.append('image', file)
    try {
      const res = await axios.post(`/api/images`, data, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      localStorage.setItem('path', res.data.path)
      localStorage.setItem('name', res.data.name)
      localStorage.setItem('alt', res.data.name || 'News image')
      insertImage(res.data)
    } catch (err) {
      console.error(err)
      setError('Image upload failed.')
    }
  }

  const handleSubmit = async e => {
    e.preventDefault()
    setError('')
    setSubmitting(true)

    const payload = { ...form }

    try {
      const imgLoaded = ({
        path: localStorage.getItem('path'),
        name: localStorage.getItem('name'),
        alt: localStorage.getItem('alt'),
      })

      if (imgLoaded.path !== null) {
      const $thumbnail = await axios.put(`/api/images`, imgLoaded)
        .then(resp => {
          localStorage.removeItem('path')
          localStorage.removeItem('name')
          localStorage.removeItem('alt')
          if (resp.statusText === 'OK') {
            navigate(-1);
          } else {
            alert('Image upload failed.')
          }
          return resp.data
        })

        payload.thumbnail_id = $thumbnail.image.id
      }

      await axios.post(`/api/news`, payload).then(
        res => {
          if (res.status === 201) {
            alert('News created successfully!')
            navigate(-1)
          } else {
            throw new Error('Failed to create news')
          }
        }
      )
    } catch (err) {
      console.error(err)
      setError(err.response?.data?.message || 'Save failed.')
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="max-w-2xl mx-auto p-6 bg-white rounded shadow">
      <h2 className="text-2xl font-bold mb-4">
        Create News
      </h2>

      {error && (
        <div className="mb-4 text-red-600">{error}</div>
      )}

      <form onSubmit={handleSubmit} className="space-y-6">
        <div>
          <label className="block text-sm font-medium mb-1">Title</label>
          <input
            type="text"
            value={form.title}
            onChange={e => setForm({ ...form, title: e.target.value })}
            className="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <div>
          <label className="block text-sm font-medium mb-1">Content</label>
          <textarea
            ref={taRef}
            rows={8}
            value={form.content}
            onChange={e => setForm({ ...form, content: e.target.value })}
            className="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 resize-y"
            placeholder="Write your news…"
            required
          />
          <label className="inline-block mt-2 px-4 py-2 bg-gray-200 rounded cursor-pointer hover:bg-gray-300">
            Upload Image
            <input
              type="file"
              accept="image/*"
              onChange={handleImageUpload}
              className="hidden"
            />
          </label>
        </div>

        <div>
          <label className="block text-sm font-medium mb-1">Author</label>
          <span className="block text-gray-700">
            {author || authorName()}
          </span>
        </div>

        <button
          type="submit"
          className={`w-full text-center px-6 py-2 text-white rounded ${submitting ? 'bg-gray-400' : 'bg-blue-600 hover:bg-blue-700'
            } transition-colors`}
        > Submit
        </button>
      </form>
    </div>
  );
}
