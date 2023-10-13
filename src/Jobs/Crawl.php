<?php

namespace App\Jobs;

use App\Models\Stock;
use App\Models\StockDetail;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Crawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $url = $this->url;

            $stock = DB::table('stocks')->where('uri', $url)->first();
            if (isset($stock->id)) {
                return true;
            }


            DB::beginTransaction();
            $client = new Client();
            $crawler = $client->request('GET', $url);

            $title = $crawler->filter('h1.post-title')->text();
            $create_date = $crawler->filter('time.post-date')->text();

            $olderLink = $crawler->filter('a.blog-pager-older-link')->link()->getUri();
            $images = [];
            $crawler->filter('div.entry-content > div.separator')->each(function ($node) use(&$images) {
                try {
                    $link = $node->filter('a')->link()->getUri();
                    array_push($images, $link);
                } catch (\Exception $exception) {}
            });

            $stock = new Stock();
            $stock->fill([
                'title' => $title,
                'created_at' => Carbon::parse($create_date),
                'image' => $images[0] ?? '',
                'uri' => $url
            ]);

            $stock->save();


            $dataDetail = [];
            if (!empty($images)) {
                foreach ($images as $image) {
                    array_push($dataDetail, [
                        'url' => $image,
                        'stock_id' => $stock->id
                    ]);
                }
            }

            StockDetail::insert($dataDetail);
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(). '------' . $exception->getTraceAsString());
            Log::error('URL ERROR ' . $this->url);
        }
    }
}
