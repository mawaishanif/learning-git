<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Feeds;

class FeedController extends Controller
{
    public function index($shop)
    {
        $collection = request('collection');
        $variant = request('variant');
        $category = htmlspecialchars(request('category'));

        $pages = ["https://{$shop}/pages/pixels-fox-feed?collection={$collection}&variant={$variant}&category={$category}"];

        for ($i = 2; $i < 100; $i++) {
            array_push($pages, "https://{$shop}/pages/pixels-fox-feed?page={$i}&collection={$collection}&variant={$variant}&category={$category}");
        }

        //  dd($pages);

        $obj_merged = [];

        foreach ($pages as $page) {
            //Calling a http URL and getting content of the page (https://{$shop}/pages/pixels-fox-feed?page={$i}&collection={$collection}&variant={$variant}&category={$category})
            $feed = Feeds::make($page, true);
            //dd($feed);
            if (count($feed->get_items()) > 0) //By default fection get_items ... it is checking either <item> is available or not
            {
                // dd($feed);
                $obj_merged = (object) array_merge( //casting array to object
                    (array) $feed->get_items(),
                    (array) $obj_merged
                );
                sleep(3); //3 second delay to http call  
            } else {
                break;
            }
        }

        $data = array(
            'title'     => $feed->get_title(),
            'permalink' => $feed->get_permalink(),
            'category' => $category,
            'items'     => $obj_merged,
        );

        return response()->view('feed', $data)->header('Content-Type', 'application/xml');
    }
}
