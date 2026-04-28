<?php

namespace App\Orchid\Screens\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class PostEditScreen extends Screen
{
    public $post;

    public function query(Post $post): iterable
    {
        return ['post' => $post];
    }

    public function name(): ?string
    {
        return $this->post->exists ? 'Редактировать пост' : 'Создать пост';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить')
                ->icon('save')
                ->method('save'),
            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->post->exists),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('post.title')->title('Заголовок')->required(),
                TextArea::make('post.text')->title('Текст')->rows(10)->required(),
                Relation::make('post.user_id')
                    ->title('Автор')
                    ->fromModel(User::class, 'name')
                    ->required(),
            ]),
        ];
    }

    public function save(Request $request, Post $post)
    {
        $post->fill($request->get('post'))->save();
        Alert::info('Пост сохранён');
        return redirect()->route('platform.post.list');
    }

    public function remove(Post $post)
    {
        $post->delete();
        Alert::info('Пост удалён');
        return redirect()->route('platform.post.list');
    }
}