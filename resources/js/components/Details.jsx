import axios from 'axios'
import React, { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'

export default function NewsDetail() {
  const [news, setNews] = useState(null)
  const [author, setAuthor] = useState(null)
  const navigate = useNavigate()

  const authorName = async (author_id) => {
    const response = await axios.get(`/api/accounts/get`, {
      params: { id: author_id }
    })
    if (response.status !== 200) {
      throw new Error('Failed to fetch author name')
    }
    const data = response.data
    return data.name ?? 'Anonymous Author'
  }

  useEffect(() => {
    const fetchNews = async () => {
      try {
        const json = localStorage.getItem('currentNews')
        if (!json) throw new Error('No news in storage')
        const data = JSON.parse(json)
        setNews(data)
        if (data.author_id) {
          const name = await authorName(data.author_id)
          setAuthor(name)
        }
      } catch (error) {
        console.error('Error fetching news:', error)
        setNews(null)
      }
    }

    fetchNews()
  }, [])

  useEffect(() => {
    try {
      const json = localStorage.getItem('currentNews')
      if (!json) throw new Error('No news in storage')
      const data = JSON.parse(json)
      setNews(data)
    } catch {
      setNews(null)
    }
  }, [])

  if (news === null) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center bg-gray-50 p-6">
        <p className="mb-4 text-gray-600">There is no news available.</p>
        <button
          onClick={() => navigate(-1)}
          className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Go Back
        </button>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50 py-12 px-6">
      <div className="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
        <h1 className="text-3xl font-bold mb-4">{news.title}</h1>
        <div className="text-sm text-gray-500 mb-6 flex space-x-4">
          <span>Publish: {new Date(news.create_at).toLocaleDateString('VN', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' })}</span>
          {author ? <span className="space-x-4">Author: {author}</span> : <span className="space-x-4"> Author: Anonymous Author</span>}
        </div>
        {news.content && (
          <p className="prose mb-6 whitespace-pre-line" dangerouslySetInnerHTML={{ __html: news.content }} />
        )}

        {news.imageUrl && (
          <img
            src={news.imageUrl}
            alt={news.title}
            className="w-full rounded-lg mb-6"
          />
        )}

        <button
          onClick ={() => navigate(-1)}
          className="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Go Back to home
        </button>
      </div>
    </div>
  )
}
