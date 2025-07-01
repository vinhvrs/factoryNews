<?php
namespace App\Repositories\Interfaces;

use App\Models\News;

interface NewsInterface{
    public function addNews(array $news): News;
    public function getNewsDetails($id);
    public function getNewsByTitle($title);
    public function getNewsByAuthor($authorId);
    public function getNewsByDate($date);
    public function getAllNews();
    public function updateNews($id, array $news): News;
    public function deleteNews($id);
}





?>