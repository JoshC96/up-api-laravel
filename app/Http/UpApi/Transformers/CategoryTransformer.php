<?php

namespace App\Http\UpApi\Transformers;

use App\Http\Resources\V1\AccountCollection;
use App\Http\Resources\V1\CategoryCollection;
use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CategoryTransformer
{

    public function __construct(public User $user)
    {
        $this->user = $user;
    }
    
    public function transform(array $data): CategoryCollection
    {
        $transformedData = [];

        foreach ($data['data'] as $categoryData) {
            $category = Category::firstOrNew([
                'id' => $categoryData['id'],
            ]);

            // if ($category->wasRecentlyCreated) {
                $this->populatecategoryData($category, $categoryData);
            // }

            $transformedData[] = $category;
        }

        return new CategoryCollection($transformedData);
    }

    private function populatecategoryData(Category $category, array $categoryData): Category
    {
        $category->id = Arr::get($categoryData, 'id');
        $category->remote_id = Arr::get($categoryData, 'id');
        $category->name = Arr::get($categoryData, 'attributes.name');
        $category->parent_id = Arr::get($categoryData, 'relationships.parent.data.id');

        $category->user_id = $this->user->id;

        $category->save();

        return $category;
    }
}
