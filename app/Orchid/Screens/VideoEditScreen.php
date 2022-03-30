<?php

namespace App\Orchid\Screens;

use App\Models\Video;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Attachment\File;

class VideoEditScreen extends Screen
{
    /**
     * @var Video
     */
    public $video;

    /**
     * Query data.
     *
     * @param Video $video
     *
     * @return array
     */
    public function query(Video $video): array
    {
        $video->load('attachment');

        return [
            'video' => $video
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return $this->video->exists ? 'Edit video' : 'Upload new video';
    }
    
    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Video upload for AR";
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Save')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->video->exists),

            Button::make('Save')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->video->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->video->exists),
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
            Layout::rows([
                Input::make('video.title')
                    ->title('Title')
                    ->placeholder('Set the name of the video'),

                Upload::make('attach')
                    ->groups('video')
                    ->maxFiles(1)
                    ->targetRelativeUrl()
                    //->targetId()
                    //->media()
                    ->acceptedFiles('video/mp4'),

            ])
        ];
    }

    /**
     * @param Video    $video
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Video $video, Request $request)
    {

        $video->fill($request->get('video'))->save();

        $video->attachment()->syncWithoutDetaching(
            $request->input('attach', [])
        );
        
        Alert::info('You have successfully upload the video');

        return redirect()->route('platform.video.list');
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
