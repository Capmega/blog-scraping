<?php

namespace Sdkconsultoria\BlogScraping\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Sdkconsultoria\BlogScraping\Models\ScrapingUrl;
use Storage;

class SendBlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sdk:ScrapingSendPosts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client([
            'base_uri' => 'http://127.0.0.1:8001/admin/scraping/',
            'timeout'  => 60.0,
        ]);

        $urls = ScrapingUrl::all();

        foreach ($urls as $key => $url) {
            $category = $url->category;

            if ($category) {
                $data   = $url->data;
                if ($data) {
                    $images = $data->images()->limit(20)->get();
                    $array_images = [];
                    foreach ($images as $key => $image) {
                        $file_route = storage_path('app/scraping/') . $data->id . '/' . $image->id . '.' . $image->extension;
                        try {
                            $file_data = fopen($file_route, 'r');
                            $array_images[] = [
                                'name'     => 'images[]',
                                'contents' => $file_data
                            ];
                        } catch (\Exception $e) {

                        }
                    }

                    if ($data) {
                        if ($data->spin_lvl) {
                            $response = $client->request('POST', 'catch-post', [
                                'multipart' => array_merge([
                                    [
                                        'name'     => 'name',
                                        'contents' => $url->name
                                    ],
                                    [
                                        'name'     => 'category',
                                        'contents' => $category->name
                                    ],
                                    [
                                        'name'     => 'url',
                                        'contents' => $url->url
                                    ],
                                    [
                                        'name'     => 'description',
                                        'contents' => $data->spin
                                    ],
                                    [
                                        'name'     => 'spin_lvl',
                                        'contents' => $data->spin_lvl
                                    ],
                                ], $array_images)
                            ]);
                            dump($response->getBody()->getContents());
                        }
                    }
                }
            }
        }
    }
}
