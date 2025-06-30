import React, { useEffect, useState } from 'react'
import axios from 'axios'
import { Link } from 'react-router-dom'

export default function AccountManagement() {
    const [users, setUsers] = useState([])
    const [currentPage, setCurrentPage] = useState(1)
    const usersPerPage = 10

    useEffect(() => {
        axios.get('/api/accounts/getAll')
            .then(response => {
                setUsers(response.data)
            })
            .catch(error => {
                console.error('Error fetching users:', error)
            })
    }, [])

    const totalPages = Math.ceil(users.length / usersPerPage)
    const indexOfLast = currentPage * usersPerPage
    const indexOfFirst = indexOfLast - usersPerPage
    const currentUsers = users.slice(indexOfFirst, indexOfLast)

    const goToPage = page => {
        if (page < 1 || page > totalPages) return
        setCurrentPage(page)
    }

    function editRole(uid, newRole) {
        axios.put(`/api/accounts/get-user/change-role/${uid}`, { role: newRole })
            .then(response => {
                if (response.statusText === 'OK') {
                    alert('Role updated successfully')
                    setUsers(prev =>
                        prev.map(u =>
                        u.uid === uid
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
                            {currentUsers.map(u => (
                                <tr
                                    key={u.uid}
                                    className="odd:bg-white even:bg-gray-50 hover:bg-gray-100"
                                >
                                    <td className="px-4 py-3">{u.username}</td>
                                    <td className="px-4 py-3 text-center">{u.email}</td>
                                    <td className="px-4 py-3">
                                        <div className="inline-block py-1 text-sm transition-colors">
                                            <select
                                                value={u.role}
                                                onChange={e => editRole(u.uid, e.target.value)}
                                                className="ml-2 bg-white border border-gray-300 rounded">
                                                <option value="admin">Admin</option>
                                                <option value="journalist">Writer</option>
                                                <option value="reader">Reader</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td className="px-4 py-3 text-center space-x-2">
                                        <Link
                                            to={`/accounts/${u.uid}/delete`}
                                            className="inline-block px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                                            Delete
                                        </Link>
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
                        className="px-3 py-1 rounded border border-gray-300 disabled:opacity-50">
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
                            `}>
                            {i + 1}
                        </button>
                    ))}

                    <button
                        onClick={() => goToPage(currentPage + 1)}
                        disabled={currentPage === totalPages}
                        className="px-3 py-1 rounded border border-gray-300 disabled:opacity-50">
                        Next
                    </button>
                </div>
            </div>
        </div>
    );
}
