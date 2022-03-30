<?php

namespace App\Orchid\Layouts;

use App\Models\Video;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Button;
use Orchid\Filters\Filterable;

class VideoListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'videos';

    /**
     * @return string
     */
    protected function textNotFound(): string
    {
        return __('There are no records in this view :(');
    }

    /**
     * @return bool
     */
    protected function striped(): bool
    {
        return true;
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', 'Title')
                ->sort()
                ->filter(Input::make()),

            TD::make('attachment', 'Video')
                ->render(function (Video $video) {
                    foreach ($video->attachment as $video_attach) {
                        return '<video width="350" height="250">
                            <source src="'.$video_attach->url.'" type="video/mp4">
                            </video>';
                    }
                }),

            TD::make('created_at', 'Created')
                ->sort()
                ->align(TD::ALIGN_CENTER)
                ->filter(Input::make()->mask('A-999999'))
                ,
            
            TD::make('updated_at', 'Last edit')->sort()->defaultHidden(),

                TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Video $video) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                          Link::make(__('Edit'))
                            ->route('platform.video.edit', $video->id)
                            ->icon('pencil'),
              
                          Button::make(__('Delete'))
                            ->icon('trash')
                            ->method('remove') // remove method defined in Screen
                            ->confirm(__('Please confirm record delete'))
                            ->parameters([
                                'id' => $video->id,
                            ]),
                        ]);
                }),
        ];
    }


}
