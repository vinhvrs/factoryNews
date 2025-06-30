import React from 'react';
import NewsList from './NewsList';

export default function WriterPanel(){
    return (
        <div className="flex">
          <main className="flex-1 p-6 bg-gray-50 overflow-auto">
            <NewsList />
          </main>
        </div>
      )
}