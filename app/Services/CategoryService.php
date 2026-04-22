<?php
namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService {

    public function paginate() : LengthAwarePaginator {
        return Category::paginate(10);
    }

    public function create(array $data) : Category {
        return Category::create($data);
    }

    public function find(string $id) : ?Category {
        return Category::find($id);
    }

    public function update(Category $category, array $data) : Category {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(string $id) : bool {
        return Category::where('id', $id)->delete() > 0;
    }

}