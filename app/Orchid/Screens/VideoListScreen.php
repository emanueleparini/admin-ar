<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\VideoListLayout;
use App\Models\Video;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class VideoListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Video $video): array
    {
        $video->load('attachment');
        

        return [
            'videos' => Video::filters()->defaultSort('id')->paginate()
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return 'Video list';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "All Video list";
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.video.edit')
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            VideoListLayout::class
        ];
    }

    /**
     * @param Video $video
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Video $video)
    {
        $video->delete();

        Alert::info('You have successfully deleted the video');

        return redirect()->route('platform.video.list');
    }
}