import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

export default function NewsListofAuthor() {
  const [news, setNews] = useState([]);                  
  const [currentPage, setCurrentPage] = useState(1);    
  const [totalPages, setTotalPages] = useState(0);       
  const newsPerPage = 7;                              
  const navigate = useNavigate();

  const fetchPage = page => {
    axios.get('/api/news/gets', {
      params: { 
        author_id: localStorage.getItem('userId'),
        page, 
        per_page: newsPerPage 
      }
    })
    .then(res => {
      const { data, current_page, last_page } = res.data;
      setNews(data);                
      setCurrentPage(current_page);
      setTotalPages(last_page);
    })
    .catch(console.error);
  };

  useEffect(() => {
    fetchPage(1);
  }, []);

  if (news.length === 0) {
    <div className="text-center text-gray-500">There is no news</div>;
  }

  const goToPage = page => {
    if (page < 1 || page > totalPages) return;
    fetchPage(page);
  };

  const editNews = news => {
    localStorage.setItem('currentNews', JSON.stringify(news));
    navigate('/writer-panel/edit-news');
  }

  const deleteNews = news => {
    if (window.confirm(`Are you sure you want to delete the news: ${news.title}?`)) {
      axios.delete(`/api/news/delete`,{
        data: { id: news.id }
      })
        .then(() => {
          setNews(prevNews => prevNews.filter(n => n.id !== news.id));
        })
        .catch(err => {
          console.error('Error deleting news:', err);
          alert('Failed to delete news');
        });
    }
  }

  return (
    <div className="max-w-screen-2xl mx-auto px-8">
      <h1 className="text-4xl font-extrabold text-center mb-8">
        Your News
      </h1>
      <button
          onClick={() => navigate('/writer-panel/create-news')}
          className="px-2 py-1 m-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors"
        >
          + Create News
        </button>
      <div className="overflow-x-auto bg-white rounded-lg shadow">
        <table className="min-w-full table-auto border-collapse">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-4 py-2 text-left">Title</th>
              <th className="px-4 py-2 text-center">Date</th>
              <th className="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {news.map(n => (
              <tr key={n.id} className="hover:bg-gray-100">
                <td className="px-4 py-3">{n.title}</td>
                <td className="px-4 py-3 text-center">
                  {new Date(n.create_at).toLocaleDateString('VN')}
                </td>
                <td className="px-4 py-3 text-center">
                    <button 
                        onClick={() => editNews(n)}
                        className="px-3 py-1 mx-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Edit
                    </button>
                    <button 
                        onClick={() => deleteNews(n)}
                        className="px-3 py-1 mx-2 bg-red-600 text-white rounded hover:bg-red-700"
                    >
                        Delete
                    </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Pagination Controls */}
      <div className="flex justify-center space-x-1 mt-4">
        <button 
          onClick={() => goToPage(currentPage - 1)}
          disabled={currentPage === 1}
          className="px-3 py-1 border rounded disabled:opacity-50"
        >
          Prev
        </button>

        {Array.from({ length: totalPages }, (_, i) => (
          <button
            key={i+1}
            onClick={() => goToPage(i+1)}
            className={`px-3 py-1 border rounded ${
              currentPage === i+1 
                ? 'bg-blue-600 text-white border-blue-600' 
                : 'hover:bg-gray-100'
            }`}
          >
            {i+1}
          </button>
        ))}

        <button 
          onClick={() => goToPage(currentPage + 1)}
          disabled={currentPage === totalPages}
          className="px-3 py-1 border rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  );
}
