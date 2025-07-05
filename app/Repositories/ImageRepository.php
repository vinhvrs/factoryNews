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

    public function get($id, $name, $path)
    {
        return Images::where('id', "LIKE", "%$id%")
            ->where('name', 'LIKE', "%$name%")
            ->where('path', 'LIKE', "%$path%")
            ->paginate($this->limit);
    }

    public function delete($id)
    {
        return Images::destroy($id);
    }
}


?>