<?php
namespace App\Repositories;

use App\Models\News;
use App\Repositories\Interfaces\NewsInterface as NewsInterface;

class NewsRepository implements NewsInterface {

    public function __construct(){
        $this->news = [];
    }

    public function addNews(array $news): News{
        return News::create([
            'newsId' => uniqid(),
            'title' => $news['title'],
            'date' => $news['date'],
            'content' => $news['content'],
            'author' => $news['author'],
            'uid' => $news['uid']
        ]);
    }

    public function getNewsById($newsId){
        return News::find($newsId);
    }

    public function getNewsByAuthor($uid){
        return News::where('uid', 'LIKE', "%{$uid}%")->get();
    }

    public function getNewsByTitle($title){
        return News::where('title', 'LIKE', "%{$title}%")->get();
    }

    public function getNewsByUid($uid){
        return News::where('uid', 'LIKE', "%{$uid}%")->get();
    }

    public function getNewsByDate($date){
        return News::where('date', 'LIKE', "%{$date}%")->get();
    }

    public function getAllNews(){
        return News::all();
    }

    public function updateNews($newsId, array $news): News{
        $existingNews = News::find($newsId);
        if ($existingNews !== null) {
            $existingNews->title = $news['title'];
            $existingNews->date = $news['date'];
            $existingNews->content = $news['content'];
            $existingNews->author = $news['author'];
            $existingNews->save();
            return $existingNews;
        }
        return null;
    }

    public function deleteNews($newsId){
        $news = News::find($newsId);
        if ($news) {
            $news->delete();
            return true;
        }
        return false;
    }


}



?>