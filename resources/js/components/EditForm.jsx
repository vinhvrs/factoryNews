import React, { useState, useRef } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

export default function EditForm() {
  const [error, setError] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const currentNews = JSON.parse(localStorage.getItem('currentNews')) || {};
  const navigate = useNavigate();

  const [form, setForm] = useState({
    title: currentNews.title || '',
    date: '',
    content: currentNews.content || '',
    author: currentNews.author || '',
    uid: localStorage.getItem('userId') || ''
  });

  const taRef = useRef(null)

  function handleTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
  }

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
    const imgPath = imgData.imagePath.startsWith('http')
      ? imgData.imagePath
      : `http://localhost:1111${imgData.imagePath}`
    const imgTag = `<img src="${imgPath}" alt="${imgData.imageAlt || imgData.imageName}" />`
    insertAtCursor(imgTag)
  }

  const handleImageUpload = async e => {
    const file = e.target.files[0]
    if (!file) return
    const data = new FormData()
    data.append('image', file)
    try {
      const res = await axios.post(`/api/images/upload-temp-image`,
        data, { headers: { 'Content-Type': 'multipart/form-data' } }
      )
      localStorage.setItem('tempImagePath', res.data.imagePath)
      localStorage.setItem('tempImageName', res.data.imageName)
      localStorage.setItem('tempImageAlt', res.data.imageName || 'News image')
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

    const now = handleTime()
    const payload = { ...form, date: now }

    try {
      const newId = await axios.put(`/api/news/update/${currentNews.newsId}`, payload).then(
        res => {
          return res.data.newsId
        }
      )

      const imgLoaded = ({
        newsId: newId,
        imagePath: localStorage.getItem('tempImagePath'),
        imageName: localStorage.getItem('tempImageName'),
        imageAlt: localStorage.getItem('tempImageAlt')
      })

      await axios.post(`/api/images/temp-image-handle`, imgLoaded)
        .then(resp => {
          localStorage.removeItem('tempImagePath')
          localStorage.removeItem('tempImageName')
          localStorage.removeItem('tempImageAlt')
          if (resp.statusText === 'OK') {
            alert('Image uploaded successfully!')
            navigate(-1);
          } else {
            alert('Image upload failed.')
          }
        })
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
          <input
            type="text"
            value={form.author}
            onChange={e => setForm({ ...form, author: e.target.value })}
            className="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500"
          />
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
