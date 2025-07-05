<?php
namespace App\Repositories;

use App\Models\News;
use Ramsey\Uuid\Uuid;
use App\Repositories\Interfaces\NewsInterface as NewsInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepository implements NewsInterface {
    protected $limit = 10;

    public function __construct(){
        $this->news = [];
    }

    public function create(array $news): News{
        return News::create([
            'id' => $news['id'] ?? Uuid::uuid4()->toString(),
            'title' => $news['title'],
            'content' => $news['content'],
            'author_id' => $news['author_id'],
            'thumbnail_id' => $news['thumbnail_id'] ?? null,
        ]);
    }

    public function findAll($filter, $select, $perPage): LengthAwarePaginator{
        $query = News::query();

        foreach ($filter as $column => $value) {
            if (!empty($value)) {
                $query->where($column, 'LIKE', "%{$value}%");
            }
        }

        $query->select($select)
              ->orderBy('updated_at', 'desc');

        return $query->paginate($perPage)->withQueryString();
    }

    public function find($id): News|null{
        return News::find($id);
    }

    public function update($id, array $news): News|null{
        $existingNews = News::find($id);
        if ($existingNews) {
            $existingNews->title = $news['title'];
            $existingNews->content = $news['content'];
            $existingNews->save();
            return $existingNews;
        }
        return null;
    }

    public function delete($id): bool{
        $news = News::find($id);
        if ($news) {
            $news->delete();
            return true;
        }
        return false;
    }
}

?>