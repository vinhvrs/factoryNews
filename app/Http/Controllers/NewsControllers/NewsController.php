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
        $this->accountRepository = new AccountRepository();
    }

    //=============== GET NEWS ================

    public function show(Request $request): JsonResponse{
        $id = $request->route('id');
        
        $news = $this->newsRepository->find($id);

        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $author = $this->accountRepository->find($news->author_id, ['name']);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $newsDetails = [
            'title' => $news->title,
            'content' => $news->content,
            'author' => $author->name,
            'created_at' => $news->created_at->format('Y-m-d H:i:s'),
        ];

        return response()->json($newsDetails, 200);
    }

    public function index(Request $request): JsonResponse{
        $data = $request->validate([
            'title' => 'sometimes|string',
            'author_id' => 'sometimes|string|exists:accounts,id',
            'created_at' => 'sometimes|date_format:Y-m-d',
            'updated_at' => 'sometimes|date_format:Y-m-d',
            'fields' => 'sometimes|string',
            'order' => 'sometimes|string|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'perPage' => 'sometimes|integer|min:1|max:100',
        ]);

        $filters = [
            'title' => $data['title'] ?? null,
            'author_id' => $data['author_id'] ?? null,
            'created_at' => $data['created_at'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
        ];

        $rawFields = $data['fields'] ?? '';
        $parts = array_filter(explode(',', $rawFields), fn($f) => !empty($f));
        $allowed = ['id', 'title', 'content', 'author_id', 'created_at'];
        $select = array_intersect($allowed, $parts);

        if (empty($select)) {
            $select = ['*'];
        }

        $perPage = $data['perPage'] ?? 10;

        $paginator = $this->newsRepository->findAll(
            $filters, 
            $select, 
            $perPage
        );

        if ($paginator->isEmpty()) {
            return response()->json(['message' => 'No news found'], 404);
        }

        return response()->json($paginator, 200);
    }

    //=============== CREATE NEWS ================

    public function store(Request $request): JsonResponse{
        $data = $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'author_id' => 'required|string|exists:accounts,id',
                'thumbnail_id' => 'nullable|string|exists:images,id'
            ]);

        $news = $this->newsRepository->create($data);
        $news = [
            'title' => $news->title,
            'content' => $news->content,
            'author_id' => $news->author_id,
            'thumbnail_id' => $news->thumbnail_id
        ];
        return response()->json($news, 201);
    }

    //================ UPDATE NEWS ================

    public function update(Request $request): JsonResponse{
        $id = $request->route('id');
        $existingNews = $this->newsRepository->find($id);

        if (!$existingNews) {
            return response()->json(['message' => 'News not found'], 404);
        }

        $newsData = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);
        $updatedNews = $this->newsRepository->update($id, $newsData);
        return response()->json($updatedNews, 200);
    }

    //================ DELETE NEWS ================

    public function delete(Request $request): JsonResponse{
        $id = $request->route('id');
        $existingNews = $this->newsRepository->find($id);
        if (!$existingNews) {
            return response()->json(['message' => 'News not found'], 204);
        }

        $deleted = $this->newsRepository->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'News deletion failed'], 500);
        }
        return response()->json(['message' => 'News deleted successfully'], 200);
    }
}
?>