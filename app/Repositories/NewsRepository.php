<?php
namespace App\Repositories;

use App\Models\News;
use App\Repositories\Interfaces\NewsInterface as NewsInterface;

class NewsRepository implements NewsInterface {
    protected $limit = 10;

    public function __construct(){
        $this->news = [];
    }

    public function addNews(array $news): News{
        return News::create([
            'id' => uniqid(),
            'title' => $news['title'],
            'create_at' => $news['create_at'],
            'content' => $news['content'],
            'author_id' => $news['author_id'],
            'thumbnail_id' => $news['thumbnail_id'] ?? null,
        ]);
    }
    
    public function getAllNews(){
        return News::query()
            ->orderBy('create_at', 'desc')
            ->paginate($this->limit)
            ->withQueryString();
    }

    public function getNewsDetails($id){
        return News::query()
            ->find($id);
    }

    public function getNewsByAuthor($authorId){
        return News::query()
            ->where('author_id', 'LIKE', "%{$authorId}%")
            ->orderBy('create_at', 'desc')
            ->paginate($this->limit);
    }

    public function getNewsByTitle($title){
        return News::query()
            ->where('title', 'LIKE', "%{$title}%")
            ->orderBy('title', 'desc')
            ->paginate($this->limit);
    }

    public function getNewsByDate($createAt){
        return News::query()
            ->where('create_at', 'LIKE', "%{$createAt}%")
            ->orderBy('create_at', 'desc')
            ->paginate($this->limit);
    }


    public function updateNews($id, array $news): News{
        $existingNews = News::find($id);
        if ($existingNews !== null) {
            $existingNews->title = $news['title'];
            $existingNews->create_at = $news['create_at'];
            $existingNews->content = $news['content'];
            $existingNews->save();
            return $existingNews;
        }
        return null;
    }

    public function deleteNews($id){
        $news = News::find($id)
            ->first();
        if ($news) {
            $news->delete();
            return true;
        }
        return false;
    }


}



?>