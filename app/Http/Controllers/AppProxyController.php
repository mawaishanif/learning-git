<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pixel;
use App\Models\User;
use App\Models\History;
use App\Models\Event;
use Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class AppProxyController extends Controller
{
    public function index()
    {
        request()->merge(['user_ip' => 'null']);
        $shop = Auth::user();
        if($shop):
            $route = request('route');
            $tagCheck = false;
            $collectionCheck = false;
            if ($route == "init") {
                $dTags = Pixel::where('shop_id', $shop->id)->where('status', 1)->get();
                return response()->json($dTags);
            }
            if ($route == "status") {
                $user = User::where('name', $shop->name)->first();
                $collections = [];
                $tags = [];
                $masterPixel = Pixel::where(['shop_id' => $user->id, 'status' => 1])->get();
                $tagPixels = Pixel::where(['type' => 'tag', 'shop_id' => $user->id, 'status' => 1])->get();
                $tags = $tagPixels;
                $pixels = $user->pixels;
                $pageViewEventId = request('pageViewEventId');
                $searchEventId = request('searchEventId');
                $viewContentEventId = request('viewContentEventId');
                $pixels_browser = json_decode(request('pixels'), true);
                foreach ($pixels as $pixel) :
                    if ($pixel->access_token) :
                        $data = [];
                        if ($pixel->test_token) :
                            $data["test_event_code"] = $pixel->test_token;
                        endif;
                        $requestData = [
                            'event_name' => 'PageView',
                            "event_time" =>  time(),
                            "event_id" => $pageViewEventId,
                            "event_source_url" => rawurlencode(request('source_url')),
                            "action_source" => "website"
                        ];
                        $userData = [];
                        if (request('user_ip') && request('user_ip') != 'null') :
                            $userData["client_ip_address"] = request('user_ip');
                        endif;
                        if (request('user_agent')  && request('user_agent') != 'null') :
                            $userData["client_user_agent"] = request('user_agent');
                        endif;
                        if (request('fbp') && request('fbp') != 'null') :
                            $userData["fbp"] = request('fbp');
                        endif;
                        if (request('fbc') && request('fbc') != 'null') :
                            $userData["fbc"] = request('fbc');
                        endif;
                        if (request('c_user') && request('c_user') != 'null') :
                            $userData["fb_login_id"] = request('c_user');
                        endif;
                        $requestData['user_data'] = $userData;
                        $data['data'][] = $requestData;
                        if (request('searchString')) :
                            $data['data'][0]['event_name'] = 'Search';
                            $data['data'][0]['event_time'] = time();
                            $data['data'][0]['event_id'] = $searchEventId;
                            $data['data'][0]['content_ids'] = explode(',', request('content_ids'));
                            $data['data'][0]["content_type"] = "product_group";
                            $data['data'][0]['value'] = 1;
                            $data['data'][0]["search_string"] = request('searchString');
                            $data['data'][0]["currency"] = request('currency');
                            $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                            $data['data'][0]['event_name'] = 'PageView';
                            $data['data'][0]['event_id'] = $pageViewEventId;
                        endif;
                        if (request('productCollections')) :
                            if ($pixel->type == 'collection') :
                                $pItem = json_decode($pixel->collection);
                                // dd($pItem);
                                $collection = new Collection();
                                foreach ($pItem as $item) {
                                    $collection->push((object)['collection' => $item]);
                                }
                                // dd($collection);
                                $flag = $collection->contains(function ($r) {
                                    return in_array($r->collection, explode(',', request('productCollections')));
                                });
                                // dd($flag);
                                if ($flag) :
                                    $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                                    $data['data'][0]['event_name'] = 'ViewContent';
                                    $data['data'][0]['event_time'] = time();
                                    $data['data'][0]['event_id'] = $viewContentEventId;
                                    $data['data'][0]['content_ids'] = explode(',', request('content_ids'));
                                    $data['data'][0]["content_type"] = "product_group";
                                    $data['data'][0]['value'] = 1;
                                    $data['data'][0]["search_string"] = request('searchString');
                                    $data['data'][0]["currency"] = request('currency');
                                    $data['data'][0]["contents"] = [["id" => request('productId'), "quantity" => "1"]];
                                    $data['data'][0]["content_name"] = request('productTitle');
                                    $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                                // info($result->json());
                                endif;
                            endif;
                        endif;
                        if (request('productTags')) :
                            if ($pixel->type == 'tag') :
                                $flag = collect(explode(',', request('productTags')))->contains(function ($r) use ($pixel) {
                                    return $r == $pixel->tag;
                                });
                                if ($flag) :
                                    $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                                    $data['data'][0]['event_name'] = 'ViewContent';
                                    $data['data'][0]['event_time'] = time();
                                    $data['data'][0]['event_id'] = $viewContentEventId;
                                    $data['data'][0]['content_ids'] = explode(',', request('content_ids'));
                                    $data['data'][0]["content_type"] = "product_group";
                                    $data['data'][0]['value'] = 1;
                                    $data['data'][0]["search_string"] = request('searchString');
                                    $data['data'][0]["currency"] = request('currency');
                                    $data['data'][0]["contents"] = [["id" => request('productId'), "quantity" => "1"]];
                                    $data['data'][0]["content_name"] = request('productTitle');
                                    $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                                endif;
                            endif;
                        endif;
                        if ($pixel->type == 'master') :
                            $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                            $data['data'][0]['event_name'] = 'ViewContent';
                            $data['data'][0]['event_time'] = time();
                            $data['data'][0]['event_id'] = $viewContentEventId;
                            $data['data'][0]['content_ids'] = explode(',', request('content_ids'));
                            $data['data'][0]["content_type"] = "product_group";
                            $data['data'][0]['value'] = 1;
                            $data['data'][0]["search_string"] = request('searchString');
                            $data['data'][0]["currency"] = request('currency');
                            $data['data'][0]["contents"] = [["id" => request('productId'), "quantity" => "1"]];
                            $data['data'][0]["content_name"] = request('productTitle');
                            $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                        endif;
                    endif;
                    $single = [];
                    $single = [
                        "id" => $pixel->pixel_id
                    ];
                    $singlecoll = [];
                    foreach ($pixels as $pixel) {
                        if ($pixel->type == "collection") {
                            $singlecoll[] = $pixel;
                        }
                    }
                    // $single['collections'] = $singlecoll;
                    $collections = $singlecoll;
                endforeach;
                return response()->json([
                    'status' => true,
                    'pixels' => $masterPixel,
                    'collectionPixel' => $collections,
                    'tags' => $tags,
                    'preference' => true,
                    'snapchatid' => $user->snapchatid,
                    'pinterestid' => $user->pinterestid,
                    'pageviewId' => $pageViewEventId,
                    'searchId' => $searchEventId,
                    'viewContentId' => $viewContentEventId
                ]);
            }
            if ($route == "addToCart") {
                $user = User::where('name', $shop->name)->first();
                $pixels = $user->pixels;
                $pixels = $pixels->filter(function ($element) {
                    return in_array($element->pixel_id, explode(',', request('pixelIds')));
                });
                if (request('requestType') == 'addToCart') :
                    $aTCIC = request('addToCartEventId');
                elseif (request('requestType') == 'InitiateCheckout') :
                    $aTCIC = request('InitiateCheckoutEventId');
                else :
                    $aTCIC = request('cartEventId');
                endif;
                $type = request('requestType');
                if ($type == "addToCart") {
                    $type = "AddToCart";
                }
                if ($type == "InitiateCheckout") {
                    $type = "InitiateCheckout";
                }
                if ($type == "ViewCart") {
                    $type = "ViewCart";
                }
                foreach ($pixels as $pixel) :
                    if ($pixel->access_token) :
                        $data = [];
                        if ($pixel->test_token) :
                            $data["test_event_code"] = $pixel->test_token;
                        endif;
                        $requestData = [
                            "event_name" => $type,
                            "event_time" =>  time(),
                            "event_id" => $aTCIC,
                            "event_source_url" => rawurlencode(request('source_url')),
                            "action_source" => "website",
                            'event_time' => time(),
                            'event_id' => $aTCIC,
                            'content_ids' => explode(',', request('content_ids')),
                            "content_type" => "product_group",
                            'value' => floatval(request('value')),
                            "currency" => request('currency'),
                            "contents" => json_decode(request('contents')),
                            "content_name" => request('productTitle'),
                            "num_items" => request('num_items'),
                        ];
                        $userData = [];
                        if (request('user_ip') && request('user_ip') != 'null') :
                            $userData["client_ip_address"] = request('user_ip');
                        endif;
                        if (request('user_agent')  && request('user_agent') != 'null') :
                            $userData["client_user_agent"] = request('user_agent');
                        endif;
                        if (request('fbp') && request('fbp') != 'null') :
                            $userData["fbp"] = request('fbp');
                        endif;
                        if (request('fbc') && request('fbc') != 'null') :
                            $userData["fbc"] = request('fbc');
                        endif;
                        if (request('c_user') && request('c_user') != 'null') :
                            $userData["fb_login_id"] = request('c_user');
                        endif;
                        $requestData['user_data'] = $userData;
                        $data['data'][] = $requestData;
                        $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                    endif;
                endforeach;
                if ($type == "InitiateCheckout") {
                    if (request('token')) {
                        if (Event::where('token', request('token'))->exists()) {
                            $event = Event::where('token', request('token'))->first();
                        } else {
                            $event = new Event;
                        }
                        $event->shop_id = $user->id;
                        $event->pixels = request('pixelIds');
                        $event->token = request('token');
                        $event->fbp = request('fbp');
                        $event->fbc = request('fbc');
                        $event->user_ip = request('user_ip');
                        $event->user_agent = request('user_agent');
                        $event->c_user = request('c_user');
                        $event->event_id = request('purchaseEventId');
                        $event->source_url = request('source_url');
                        $event->data = json_encode($userData);
                        $event->save();
                    }
                }
                return response()->json([
                    'status' => true,
                    'aTCIC' => $aTCIC
                ]);
            }
            if ($route == "viewCategory") {
                $user = User::where('name', $shop->name)->first();
                $pixels = $user->pixels;
                $pixels = $pixels->filter(function ($element) {
                    return in_array($element->pixel_id, explode(',', request('pixelIds')));
                });
                $aTCIC = request('viewCategoryEventId');
                foreach ($pixels as $pixel) :
                    if ($pixel->access_token) :
                        $data = [];
                        if ($pixel->test_token) :
                            $data["test_event_code"] = $pixel->test_token;
                        endif;
                        $requestData = [
                            "event_name" => "ViewCategory",
                            "event_time" =>  time(),
                            "event_id" => $aTCIC,
                            "event_source_url" => rawurlencode(request('source_url')),
                            "action_source" => "website",
                            'event_time' => time(),
                            'event_id' => $aTCIC,
                            'content_ids' => explode(',', request('categoryId')),
                            'content_category' => request('category')
                        ];
                        $userData = [];
                        if (request('user_ip') && request('user_ip') != 'null') :
                            $userData["client_ip_address"] = request('user_ip');
                        endif;
                        if (request('user_agent')  && request('user_agent') != 'null') :
                            $userData["client_user_agent"] = request('user_agent');
                        endif;
                        if (request('fbp') && request('fbp') != 'null') :
                            $userData["fbp"] = request('fbp');
                        endif;
                        if (request('fbc') && request('fbc') != 'null') :
                            $userData["fbc"] = request('fbc');
                        endif;
                        if (request('c_user') && request('c_user') != 'null') :
                            $userData["fb_login_id"] = request('c_user');
                        endif;
                        $requestData['user_data'] = $userData;
                        $data['data'][] = $requestData;
                        $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                    endif;
                endforeach;
                return response()->json([
                    'status' => true,
                    'aTCIC' => $aTCIC
                ]);
            }
            if ($route == "count") {
                $input = new History;
                $input->shop_id = $shop->id;
                $input->event = "Script";
                $input->save();
            }
        endif;
    }
}
