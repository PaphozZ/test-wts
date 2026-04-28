<?php

namespace App\Orchid\Screens\Post;

use App\Models\Post;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PostListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'posts' => Post::with('user')->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Публикации';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Создать')
                ->icon('plus')
                ->route('platform.post.edit'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('posts', [
                TD::make('id', 'ID')->sort(),
                TD::make('title', 'Заголовок')->sort()->filter(TD::FILTER_TEXT),
                TD::make('user.name', 'Автор')->sort(),
                TD::make('created_at', 'Дата')->sort(),
                TD::make('actions', 'Действия')
                    ->render(fn ($post) => Link::make('Редактировать')
                        ->route('platform.post.edit', $post->id)),
            ]),
        ];
    }
}