<?php
namespace App\Http\Controllers\NewsControllers;

use App\Http\Controllers\Controller;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller{
    protected NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository){
        $this->newsRepository = $newsRepository;
    }

    public function addNews(Request $request){
        $data = $request->validate(
            [
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'content' => 'required|string',
                'author' => 'required|string|max:255',
                'uid' => 'required|exists:accounts,uid'
            ]);

        $news = $this->newsRepository->addNews($data);
        $news = [
            'newsId' => $news->newsId,
            'title' => $news->title,
            'date' => $news->date,
            'content' => $news->content,
            'author' => $news->author,
            'uid' => $news->uid
        ];
        return response()->json($news, 201);
    }

    public function getNews($newsId){
        return $this->newsRepository->getNewsById($newsId);
    }

    public function getAllNews(){
        return $this->newsRepository->getAllNews();
    }

    public function updateNews($newsId, Request $request){
        $existingNews = $this->newsRepository->getNewsById($newsId);
        if (!$existingNews) {
            return response()->json(['message' => 'News not found'], 404);
        }
        $newsData = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'content' => 'required|string',
            'author' => 'required|string|max:255'
        ]);
        $updatedNews = $this->newsRepository->updateNews($newsId, $newsData);
        return response()->json($updatedNews, 200);
    }

    public function deleteNews($newsId){
        return $this->newsRepository->deleteNews($newsId);
    }

    public function getNewsByAuthor(Request $request){
        $author = $request->validate([
            'uid' => 'required|exists:accounts,uid',
        ]);
        $existingNews = $this->newsRepository->getNewsByAuthor($author['uid']);
        if ($existingNews->isEmpty()) {
            return response()->json(['message' => 'No news found for this author'], 404);
        }

        return response()->json($existingNews, 200);
    }

    public function getNewsByTitle(Request $request){
        $title = $request->validate([
            'title' => 'required|string|max:255'
        ]);
        $existingNews = $this->newsRepository->getNewsByTitle($title['title']);
        if ($existingNews->isEmpty()) {
            return response()->json(['message' => 'No news found with that title'], 404);
        }

        return response()->json($existingNews, 200);
    }

    public function getNewsByUid(Request $Request){
        $uid = $Request->validate([
            'uid' => 'required|exists:accounts,uid'
        ]);
        $uid = $uid['uid'];
        $existingNews = $this->newsRepository->getNewsByUid($uid);
        if ($existingNews->isEmpty()) {
            return response()->json(['message' => 'No news found for this user'], 404);
        }

        return response()->json($existingNews, 200);
    }

    public function getNewsByDate(Request $request){
        $date = $request->validate([
            'date' => 'required|date'
        ]);
        $date = $date['date'];
        $existingNews = $this->newsRepository->getNewsByDate($date);
        if ($existingNews->isEmpty()) {
            return response()->json(['message' => 'No news found for this date'], 404);
        }

        return response()->json($existingNews, 200);
    }

}


?>