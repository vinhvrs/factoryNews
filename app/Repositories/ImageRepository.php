<?php
namespace App\Repositories;

use App\Models\Images;
use Ramsey\Uuid\Uuid;
use App\Repositories\Interfaces\ImageInterface as ImageInterface;

class ImageRepository implements ImageInterface {
    protected $limit = 10;

    public function __construct(){
        $this->image = new Images();
    }

    public function create(array $image): Images
    {
        return Images::create([
            'id' => $image['id'] ?? Uuid::uuid4()->toString(),
            'path' => $image['path'],
            'name' => $image['name'],
            'alt' => $image['alt'] ?? $image['name'] ?? null
        ]);
    }

    public function get($id, array $select)
    {
        $query = Images::query();

        $query->select($select)
              ->where('id', $id);
        return $query->first();
    }

    public function getAll(array $filters, array $select, int $perPage)
    {
        $query = Images::query();

        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $query->where($column, 'LIKE', "%{$value}%");
            }
        }

        $query->select($select)
              ->orderBy('created_at', 'desc');

        return $query->paginate($perPage)->withQueryString();
    }

    public function delete($id)
    {
        return Images::destroy($id);
    }
}


?>