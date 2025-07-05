import React from 'react';
import { Routes, Route, Link, Navigate } from 'react-router-dom';


import AuthLayout from './AuthLayout';
import LoginForm from './LoginForm';
import RegisterForm from './RegisterForm'; 

import AdminLayout from './AdminLayout';
import AdminPanel from './AdminPanel';
import AccountManagement from './AccountManagement';

import WriterLayout from './WriterLayout';
import WriterPanel from './WriterPanel';
import NewsForm from './NewsForm';
import EditForm from './EditForm';


import MainLayout from './MainLayout';
import Index from './Index';
import Details from './Details';
import NewsList from './NewsList';
import NewsListofAuthor from './NewsListAuthor';


export default function App() {
  return (
    <div onLoad={() => {
      switch (localStorage.getItem('access')) {
        case 'admin':
          document.title = 'Admin Panel';
          break;
        case 'writer':
          document.title = 'Writer Panel';
          break;
        case 'reader':
          document.title = 'Reader Panel';
          break;
        default:
          document.title = 'Factory News';
          break;
      }
    }}>
      <Routes>
        {/* Auth Routes */}
        <Route element={<AuthLayout />}>
          <Route path="/login" element={<LoginForm />} />
          <Route path="/register" element={<RegisterForm />} />
        </Route>
        { /* Admin Routes */ }
        <Route path="/admin-panel" element={<AdminLayout />}>
          <Route index element={<Index />} />
          <Route path="dashboard" element={<Index />} />
          <Route path="news-management" element={<NewsList />} />
          <Route path="news-management/edit-news" element={<EditForm />} />
          <Route path="account-management" element={<AccountManagement />} />
        </Route>
        {/* Writer Routes */}
        <Route path="/writer-panel" element={<WriterLayout />}>
          <Route index element={<Index />} />
          <Route path="dashboard" element={<Index />} />
          <Route path="news-management" element={<NewsListofAuthor />} />
          <Route path="create-news" element={<NewsForm />} />
          <Route path="edit-news" element={<EditForm />} />
        </Route>
        {/* Guest Routes */}
        <Route element={<MainLayout />}>
          <Route path="/" element={<Index />} />
          <Route path="/news/:title" element={<Details />} />
        </Route>
      </Routes>
    </div>

  );
}