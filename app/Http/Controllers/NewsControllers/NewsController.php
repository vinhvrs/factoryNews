<?php
namespace App\Http\Controllers\NewsControllers;

use App\Http\Controllers\Controller;
use App\Repositories\AccountRepository;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;
use App\Models\News;
use Symfony\Component\HttpFoundation\JsonResponse;

class NewsController extends Controller{
    protected NewsRepository $newsRepository;
    protected AccountRepository $accountRepository;

    public function __construct(NewsRepository $newsRepository){
        $this->newsRepository = $newsRepository;
    }

    //=============== GET NEWS ================

    public function getNewsDetails(Request $request): JsonResponse{
        $id = $request->input('id');
        if (!$id) {
            return response()->json(['message' => 'News ID is required'], 400);
        }

        $news = $this->newsRepository->getNewsDetails($id);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $author = $this->accountRepository->getAccountById($news->author_id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $newsDetails = [
            'title' => $news->title,
            'date' => $news->date,
            'content' => $news->content,
            'author' => $author->name,
        ];

        return response()->json($newsDetails, 200);
    }

    public function getAllNews(Request $request): JsonResponse{
        $title = $request->query('title');
        if ($title) {
            $paginator = $this->getNewsByTitle($title);
            if ($paginator->isEmpty()) {
                return response()->json(['message' => 'No news found with that title'], 404);
            }
            return $paginator;
        }

        $author_id = $request->query('author_id');
        if ($author_id) {
            $paginator = $this->getNewsByAuthor($author_id);
            if ($paginator->isEmpty()) {
                return response()->json(['message' => 'No news found for this author'], 404);
            }
            return $paginator;
        }

        $date = $request->query('date');
        if ($date) {
            $paginator = $this->getNewsByDate($date);
            if ($paginator->isEmpty()) {
                return response()->json(['message' => 'No news found for this date'], 404);
            }
            return $paginator;
        }

        $paginator = $this->newsRepository->getAllNews();

        if ($paginator->isEmpty()) {
            return response()->json(['message' => 'No news found'], 404);
        }

        return response()->json($paginator, 200);
    }

    public function getNewsByTitle($title){
        $existingNews = $this->newsRepository->getNewsByTitle($title);
        if ($existingNews->isEmpty()) {
            return response()->json(['message' => 'No news found with that title'], 404);
        }

        return response()->json($existingNews, 200);
    }

    public function getNewsByAuthor($author_id){
        $existingNews = $this->newsRepository->getNewsByAuthor($author_id);
        if ($existingNews->isEmpty()) {
            return response()->json(['message' => 'No news found for this author'], 404);
        }

        return response()->json($existingNews, 200);
    }

    public function getNewsByDate($date){
        $existingNews = $this->newsRepository->getNewsByDate($date);
        if ($existingNews->isEmpty()) {
            return response()->json(['message' => 'No news found for this date'], 404);
        }

        return response()->json($existingNews, 200);
    }

    //=============== CREATE NEWS ================

    public function addNews(Request $request){
        $data = $request->validate(
            [
                'title' => 'required|string|max:255',
                'create_at' => 'required|date_format:Y-m-d H:i:s',
                'content' => 'required|string',
                'author_id' => 'required|string|exists:accounts,id',
                'thumbnail_id' => 'nullable|string|exists:images,id'
            ]);

        $news = $this->newsRepository->addNews($data);
        $news = [
            'title' => $news->title,
            'create_at' => $news->create_at,
            'content' => $news->content,
            'author_id' => $news->author_id,
            'thumbnail_id' => $news->thumbnail_id
        ];
        return response()->json($news, 201);
    }

    //================ UPDATE NEWS ================

    public function updateNews(Request $request): JsonResponse{
        $id = $request->input('id');
        $existingNews = $this->newsRepository->getNewsDetails($id);

        if (!$existingNews) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $newsData = $request->validate([
            'title' => 'required|string|max:255',
            'create_at' => 'required|date format:Y-m-d H:i:s',
            'content' => 'required|string',
        ]);
        $updatedNews = $this->newsRepository->updateNews($id, $newsData);
        return response()->json($updatedNews, 200);
    }

    //================ DELETE NEWS ================

    public function deleteNews(Request $request): JsonResponse{
        $id = $request->input('id');
        $deleted = $this->newsRepository->deleteNews($id);
        if (!$deleted) {
            return response()->json(['message' => 'News deletion failed'], 500);
        }
        return response()->json(['message' => 'News deleted successfully'], 200);
    }
}
?>