import React, { useEffect, useState } from 'react'
import axios from 'axios'
import { Link, useNavigate  } from 'react-router-dom'

export default function NewsListofAuthor() {
    const [news, setNews] = useState([])
    const [currentPage, setCurrentPage] = useState(1)
    const navigate = useNavigate()
    const newsPerPage = 7

    useEffect(() => {
        const userId = localStorage.getItem('userId')
        axios.get('/api/news/get-by-author', {params: {uid:userId}})
            .then(res => setNews(res.data))
            .catch(console.error)
    }, [])

    const totalPages = Math.ceil(news.length / newsPerPage)
    const indexOfLast = currentPage * newsPerPage
    const indexOfFirst = indexOfLast - newsPerPage
    const currentNews = news.slice(indexOfFirst, indexOfLast)

    const goToPage = page => {
        if (page < 1 || page > totalPages) return
        setCurrentPage(page)
    }

    function deleteNews(newsId) {
        if (window.confirm('Are you sure you want to delete this news?')) {
            axios.delete(`/api/news/delete/${newsId}`)
                .then(() => {
                    setNews(prevNews => prevNews.filter(n => n.newsId !== newsId))
                    alert('News deleted successfully')
                })
                .catch(error => {
                    console.error('Error deleting news:', error)
                    alert('Failed to delete news')
                })
        }
    }

    function checkAuthor(newsItem) {
        const currentUser = localStorage.getItem('userId')
        const author = newsItem.uid || '—'
        return currentUser === author ? true : false
    }

    function editNews(newsItem) {
        if (!checkAuthor(newsItem)) {
            alert('You can only edit your own news.')
            return
        }
        localStorage.setItem('currentNews', JSON.stringify(newsItem))
        navigate('/writer-panel/edit-news')
    }

    return (
        <div className="bg-gray-50 py-12">
            <div className="max-w-screen-2xl mx-auto px-8">
                <h1 className="text-4xl font-extrabold text-center text-gray-800 mb-8">
                    Breaking News
                </h1>

                <Link
                    to="/writer-panel/create-news"
                    className="inline-block px-3 py-1 my-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors"
                >
                    Create New News
                </Link>
                <div className="overflow-x-auto bg-white rounded-lg shadow">

                    <table className="min-w-full table-auto border-collapse">
                        <thead className="bg-gray-100">
                            <tr>
                                <th className="px-4 py-2 text-left">Title</th>
                                <th className="px-4 py-2 text-center">Date</th>
                                <th className="px-4 py-2 text-left">Author</th>
                                <th className="px-4 py-2 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200">
                            {currentNews.map(n => (
                                <tr
                                    key={n.newsId}
                                    className="odd:bg-white even:bg-gray-50 hover:bg-gray-100"
                                >
                                    <td className="px-4 py-3">{n.title}</td>
                                    <td className="px-4 py-3 text-center">
                                        {new Date(n.date).toLocaleDateString('VN')}
                                    </td>
                                    <td className="px-4 py-3">{n.author || '—'}</td>
                                    <td className="px-4 py-3 text-center space-x-2">
                                        <button
                                            onClick={() => editNews(n)}
                                            className="inline-block px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            onClick={() => deleteNews(n.newsId)}
                                            className="inline-block px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="flex items-center justify-center mt-6 space-x-1">
                    <button
                        onClick={() => goToPage(currentPage - 1)}
                        disabled={currentPage === 1}
                        className="px-3 py-1 rounded border border-gray-300 disabled:opacity-50"
                    >
                        Prev
                    </button>

                    {Array.from({ length: totalPages }, (_, i) => (
                        <button
                            key={i + 1}
                            onClick={() => goToPage(i + 1)}
                            className={`
                px-3 py-1 rounded border
                ${currentPage === i + 1
                                    ? 'bg-blue-600 text-white border-blue-600'
                                    : 'border-gray-300 hover:bg-gray-100'}
              `}
                        >
                            {i + 1}
                        </button>
                    ))}

                    <button
                        onClick={() => goToPage(currentPage + 1)}
                        disabled={currentPage === totalPages}
                        className="px-3 py-1 rounded border border-gray-300 disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    )
}
