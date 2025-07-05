import React, { useEffect, useState } from 'react'
import axios from 'axios'

export default function AccountManagement() {
    const [users, setUsers] = useState([])          
    const [currentPage, setCurrentPage] = useState(1)
    const [totalPages, setTotalPages] = useState(0)
    const usersPerPage = 10

    function editRole(id, newRole) {
        axios.put(`/api/accounts/${id}/role`, { role: newRole })
            .then(response => {
                if (response.statusText === 'OK') {
                    alert('Role updated successfully')
                    setUsers(prev =>
                        prev.map(u =>
                            u.id === id
                                ? { ...u, role: newRole }
                                : u
                        )
                    )
                }
                else {
                    alert('Failed to update role')
                }
            })
            .catch(error => {
                console.error('Error updating role:', error)
                alert('An error occurred while updating the role')
            })
    }

    function deleteAccount(id) {
        if (!window.confirm('Are you sure you want to delete this account?')) {
            return
        }

        axios.delete(`/api/accounts/${id}`)
            .then(response => {
                if (response.statusText === 'OK') {
                    const isOnlyRowOnPage = users.length === 1;
                    const nextPage = (isOnlyRowOnPage && currentPage > 1)
                        ? currentPage - 1
                        : currentPage;
                    fetchPage(nextPage);
                } else {
                    alert('Failed to delete account')
                }
            })
            .catch(error => {
                console.error('Error deleting account:', error)
                alert('An error occurred while deleting the account')
            })
    }

    const fetchPage = page => {
        axios.get('/api/accounts', {
            params: {
                page,
                per_page: usersPerPage
            }
        })
            .then(res => {
                const { data, current_page, last_page } = res.data
                setUsers(data)
                setCurrentPage(current_page)
                setTotalPages(last_page)
            })
            .catch(console.error)
    }

    useEffect(() => {
        fetchPage(1)
    }, [])

    const goToPage = page => {
        if (page < 1 || page > totalPages) return
        fetchPage(page)
    }

    return (
        <div className="bg-gray-50 py-12">
            <div className="max-w-screen-2xl mx-auto px-8">
                <h1 className="text-4xl font-extrabold text-center text-gray-800 mb-8">
                    Account Management
                </h1>

                <div className="overflow-x-auto bg-white rounded-lg shadow">
                    <table className="min-w-full table-auto border-collapse">
                        <thead className="bg-gray-100">
                            <tr>
                                <th className="px-4 py-2 text-left">Username</th>
                                <th className="px-4 py-2 text-center">Email</th>
                                <th className="px-4 py-2 text-left">Role</th>
                                <th className="px-4 py-2 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200">
                            {users.map(u => (
                                <tr key={u.id} className="odd:bg-white even:bg-gray-50 hover:bg-gray-100">
                                    <td className="px-4 py-3">{u.username}</td>
                                    <td className="px-4 py-3 text-center">{u.email}</td>
                                    <td className="px-4 py-3">
                                        <select
                                            value={u.role}
                                            onChange={e => editRole(u.id, e.target.value)}
                                            className="bg-white border border-gray-300 rounded p-1"
                                        >
                                            <option value="admin">Admin</option>
                                            <option value="journalist">Writer</option>
                                            <option value="reader">Reader</option>
                                        </select>
                                    </td>
                                    <td className="px-4 py-3 text-center">
                                        <button
                                            onClick={() => deleteAccount(u.id)}
                                            className="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="flex justify-center space-x-1 mt-6">
                    <button
                        onClick={() => goToPage(currentPage - 1)}
                        disabled={currentPage === 1}
                        className="px-3 py-1 border rounded disabled:opacity-50"
                    >
                        Prev
                    </button>
                    {Array.from({ length: totalPages }, (_, i) => (
                        <button
                            key={`page-${i + 1}`}
                            onClick={() => goToPage(i + 1)}
                            className={`px-3 py-1 border rounded ${currentPage === i + 1
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'hover:bg-gray-100'
                                }`}
                        >
                            {i + 1}
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
        </div>
    )
}
