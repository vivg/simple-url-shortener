<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Jenssegers\Agent\Agent;
use Redirect;
use Exception;


class RedirectController extends Controller
{
    public $shortUrlModel;
    public $agent;

    /**
     * RedirectController constructor.
     * @param ShortUrl $shortUrlModel
     * @param Agent $agent
     */
    public function __construct(
        ShortUrl $shortUrlModel,
        Agent $agent
    ) {
        $this->shortUrlModel = $shortUrlModel;
        $this->agent = $agent;
    }

    /**
     * @param $shortCode
     * @return mixed
     */
    public function redirect($shortCode)
    {
        $shortUrl = $this->shortUrlModel->where('short_code', $shortCode)->with('urls')->first();

        //check whether the short code exists , if not throw 404
        if(!$shortUrl) {
            abort(404);
        }

        $deviceType = $this->deviceType();

        //check the agent and see if corresponding url type exists, if not default to desktop url
        $urls = $shortUrl->urls->keyBy('device_type')->all();

        $redirectUrl = $urls['desktop'];

        if(key_exists($deviceType, $urls)) {
            $redirectUrl = $urls[$deviceType];
        }

        //increment redirect count for the device type, throw a 500 in case of db error
        try {
            $redirectUrl->redirect_count +=1;
            $redirectUrl->save();
        } catch (Exception $e) {
            //we should also log here to investigate later.
            abort(500, 'Something went wrong. Please try again later.');
        }


        //redirect to the appropriate url with a 301 status code
        return Redirect::to($redirectUrl->long_url, 301);

    }

    /**
     * @return string
     */
    protected function deviceType()
    {
        if($this->agent->isDesktop()) {
            return 'desktop';
        }

        if($this->agent->isMobile()) {
            return 'mobile';
        }

        if($this->agent->isTablet()) {
            return 'tablet';
        }

        return 'desktop';
    }



}
