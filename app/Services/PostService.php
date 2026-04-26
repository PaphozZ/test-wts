<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PostService
{
    public function createPost(User $user, array $data): Post
    {
        return $user->posts()->create([
            'title' => $data['title'],
            'text'  => $data['text'],
        ]);
    }

    public function getAllPosts(array $filters, int $limit = 15, int $offset = 0): LengthAwarePaginator
    {
        $query = Post::with('user');
        $query = $this->applyFiltersAndSorting($query, $filters);
        return $this->paginate($query, $limit, $offset);
    }

    public function getUserPosts(User $user, array $filters, int $limit = 15, int $offset = 0): LengthAwarePaginator
    {
        $query = Post::where('user_id', $user->id);
        $query = $this->applyFiltersAndSorting($query, $filters);
        return $this->paginate($query, $limit, $offset);
    }

    /**
     * Применяет фильтрацию по дате и сортировку к запросу
     */
    private function applyFiltersAndSorting(Builder $query, array $filters): Builder
    {
        // Фильтрация по дате (от, до)
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Сортировка
        $sortBy    = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';

        if (in_array($sortBy, ['title', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    /**
     * Применяет пагинацию к запросу с учетом offset и limit
     */
    private function paginate(Builder $query, int $limit, int $offset): LengthAwarePaginator
    {
        $page = floor($offset / $limit) + 1;
        return $query->paginate($limit, ['*'], 'page', $page);
    }
}