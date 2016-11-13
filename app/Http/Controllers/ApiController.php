<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use App\Http\Requests\ShortenUrlRequest;
use App\Models\ShortUrl;
use App\Models\DeviceUrl;

class ApiController extends Controller
{
    /**
     * @var ShortUrl
     */
    protected $shortUrlModel;

    /**
     * ApiController constructor.
     * @param ShortUrl $shortUrlModel
     */
    public function __construct(ShortUrl $shortUrlModel)
    {
        $this->shortUrlModel = $shortUrlModel;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUrls()
    {
        $urls = $this->shortUrlModel->with('urls')->paginate(10);

        return response()->json([
            'code' => 200,
            'data' => $urls
        ]);
    }


    /**
     * @param ShortenUrlRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shorten(ShortenUrlRequest $request)
    {
        if (!$request->get('url') && !$request->get('urls')) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'data' => ['errors' => 'url/urls field missing or empty']
            ], 422);
        }

        //generate hash
        $hash = $this->uniqueHash();
        $shortUrl = new ShortUrl();

        DB::beginTransaction();
        try {
            $shortUrl->short_code = $hash;
            $shortUrl->save();

            $deviceUrls = [];

            if($request->exists('url')) {
                $deviceUrl = new DeviceUrl();
                $deviceUrl->long_url = $request->get('url');
                $deviceUrl->device_type = 'desktop';
                $deviceUrls[] = $deviceUrl;
            }

            if($request->exists('urls')) {

                $deviceUrls = [];
                $urls = $request->get('urls');

                foreach ($urls as $deviceType => $url) {
                    if(in_array($deviceType, ['desktop', 'mobile', 'tablet'])) {
                        $deviceUrl = new DeviceUrl();
                        $deviceUrl->long_url = $url;
                        $deviceUrl->device_type = $deviceType;
                        $deviceUrls[] = $deviceUrl;
                    }
                }
            }

            if(empty($deviceUrl)) {
                throw new Exception;
            }

            $shortUrl->urls()->saveMany($deviceUrls);
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'code' => 500,
                'message' => 'Error in generating short url'
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'data' => $shortUrl
        ]);
    }

    /**
     * @return mixed|string
     */
    protected function uniqueHash()
    {
        //generate random hash
        $hash = random_hash();

        //check if hash exists in db, generate again if it does
        $hashExists = $this->shortUrlModel->where('short_code', $hash)->first();

        return $hashExists ? $this->uniqueHash(): $hash;
    }

}
