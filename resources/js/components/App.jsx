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
import ImageUpload from './ImageUpLoad';
import DisplayImage from './DisplayImage';


import MainLayout from './MainLayout';
import Index from './Index';
import Details from './Details';
import NewsList from './NewsList';
import NewsListofAuthor from './NewsListAuthor';


export default function App() {
  return (
    <div onLoad={() => {
      if (localStorage.getItem('isAdmin')){
        <Navigate to="/admin-panel" />
      }
      else if (localStorage.getItem('isWriter')) {
        <Navigate to="/writer-panel" />
      } else {
        <Navigate to="/" />
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
          <Route path="/upload/:newsId" element={<ImageUpload />} />
          <Route path="/test" element={<ImageUpload />} />
          <Route path="/test-image" element={<DisplayImage />} />
        </Route>
      </Routes>
    </div>

  );
}