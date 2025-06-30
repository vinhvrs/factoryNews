import React, {useEffect, useState} from 'react';
import axios from 'axios';
import NewsList from './NewsList';

export default function AdminPanel() {
  return (
    <div className="flex">
      <main className="flex-1 p-6 bg-gray-50 overflow-auto">
        <NewsList />
      </main>
    </div>
  )
}