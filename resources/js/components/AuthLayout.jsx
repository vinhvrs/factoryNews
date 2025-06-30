import React from 'react'
import { Outlet } from 'react-router-dom'

export default function AuthLayout() {
    return (
        <div className="w-screen-md h-screen flex items-center justify-center bg-white">
            <div className="w-full max-w bg-white p-10 rounded-lg">
                <Outlet />
            </div>
        </div>
    )
}
