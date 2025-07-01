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
        <Route element={<AuthLayout />}>
          <Route path="/login" element={<LoginForm />} />
          <Route path="/register" element={<RegisterForm />} />
        </Route>
      {/* </Routes>

      <Routes> */}
        <Route element={<AdminLayout />}>
          <Route path="/admin-panel" element={<Index />} />
          <Route path="/admin-panel/dashboard" element={<Index />} />
          <Route path="/admin-panel/news-management" element={<NewsList />} />
          <Route path="/admin-panel/news-management/edit-news" element={<EditForm />} />
          <Route path="/admin-panel/account-management" element={<AccountManagement />} />
        </Route>
      {/* </Routes>

      <Routes> */}
        <Route element={<WriterLayout />}>
          <Route path="/writer-panel" element={<Index />} />
          <Route path="/writer-panel/dashboard" element={<Index />} />
          <Route path="/writer-panel/news-management" element={<NewsListofAuthor />} />
          <Route path="/writer-panel/create-news" element={<NewsForm />} />
          <Route path="/writer-panel/edit-news" element={<EditForm />} />
        </Route>
      {/* </Routes>

      <Routes> */}
        <Route element={<MainLayout />}>
          <Route path="/" element={<Index />} />
          <Route path="/news/:title" element={<Details />} />
        </Route>
      </Routes>
    </div>

  );
}