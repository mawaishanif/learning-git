<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Pixel;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Feed;
use App\Models\Contact;

class PixelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plan_id = auth()->user()->plan_id;
        $shopify_freemium = auth()->user()->shopify_freemium;

        $shop = auth()->user();
        $pixels = Pixel::where('shop_id', $shop->id)->get();


        $collections = [];
        $custom_collection = $shop->api()->rest('GET', '/admin/custom_collections.json')['body']['custom_collections'];
        $smart_collection = $shop->api()->rest('GET', '/admin/smart_collections.json')['body']['smart_collections'];
        $catalog = Feed::where('shop_id', $shop->id)->get();

        if (count($custom_collection) > 0) {
            foreach ($custom_collection as $data) {
                array_push($collections, ['id' => $data['id'], 'title' => $data['title'], 'handle' => $data['handle'], 'type' => 'custom_collections']);
            }
        }
        if (count($smart_collection) > 0) {
            foreach ($smart_collection as $data) {
                array_push($collections, ['id' => $data['id'], 'title' => $data['title'], 'handle' => $data['handle'], 'type' => 'smart_collections']);
            }
        }
        $query = "{
                      shop {
                        name
                        productTypes(first: 250) {
                          edges {
                            node
                          }
                        }
                      }
                    }
                    ";

        $productTypes = $shop->api()->graph($query)['body']['data']['shop'];


        if (isset($productTypes['productTypes'])) {
            $productTypes = $productTypes['productTypes']['edges'];
        } else {
            $productTypes = false;
        }

        return view('welcome', compact('collections', 'pixels', 'plan_id', 'productTypes', 'catalog', 'shopify_freemium'));
        // return view('welcome')->with(compact('pixels','collectoins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function billingStatus()
    {
        $shop = Auth::user();
        $id = $shop->id;

        $user = User::where('id', $id)->first();
        $plan_status = $user->plan_id;
        return view('billing', compact('plan_status'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {

        $shop = Auth::user();

        $countpixels = Pixel::where('shop_id', $shop->id)->count();
        $checkplan = Pixel::where('id', $shop->id)->count();

        if ($req->post('mode') != "update") {
            if ($shop->shopify_freemium == 0) :
                $count = Pixel::where('shop_id', $shop->id)->count();

                if ($shop->plan_id == null &&  $count >= 1) :

                    return response()->json([
                        'error' => 'For FB pixel more than 1 installation, You must have paid plan.'
                    ], 200);
                endif;
                if ($shop->plan_id == 1 && $count >= 2) {
                    return response()->json([
                        'error' => 'Please Upgrade Your Plan.'
                    ], 200);
                }
                if ($shop->plan_id == 2 && $count >= 5) {
                    return response()->json([
                        'error' => 'Please Upgrade Your Plan.'
                    ], 200);
                }
            endif;
        }
        $data = $req->all();
        $type = $req->post('pixel_type');
        $collection = $req->post('collection_id');
        $tag = $req->post('tag_id');
        $pixel_prefix = $req->post('pixel_prefix');
        $id = $req->post('pixel_id');
        $access_token = $req->post('aceess_token');
        $test_token = $req->post('test_token');
        $mode = $req->post('mode');
        $pixel_prefix = $req->post('pixel_prefix');
        $updateId = $req->post('updateId');
        $access_token = $req->post('access_token');
        $test_token = $req->post('test_token');
        if ($mode == "update") {
            $input = Pixel::find($updateId);
        } else {
            $input = new Pixel;
        }
        if ($access_token) :
            $input->access_token = $access_token;
            $input->test_token = $test_token;
        endif;
        $input->type = $type;
        $input->pixel_id = $id;
        $input->shop_id = $shop->id;
        if ($type == "niche") {
            $input->tag = $pixel_prefix . $tag;
        }
        if ($type == "collection") {
            // $input->collection = $collection;
            $input->collection = json_encode($collection);
        }
        $input->save();
        return response()->json([
            'pixel' => $input
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $shop = Auth::user();
        $id = request('id');
        $pixel = Pixel::where('shop_id', $shop->id)->where('id', $id)->first();
        return response()->json($pixel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function  getPixel(Request $request)
    {

        $shop_id = auth()->user()->id;

        $pixelData = Pixel::where('shop_id', $shop_id)->get();
        return $pixelData;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {



        $shop = Auth::user();
        $id = request('id');
        $pixel = Pixel::where('shop_id', $shop->id)->where('id', $id)->first();
        $pixel->delete();
        return response()->json(200);
    }

    public function status(Request $req)
    {
        
        $pixel = Pixel::where('id', $req->get('status_id'))->first();
        $interval = $pixel->created_at->diffInDays(now());

        $shop = Auth::user();
        
        if($shop->plan_id == null && $interval > 7)
        {
            return response()->json([
                'status' => 'false',
                'message' => 'Your 1 Pixel ID 7 Day Trail Has been Expired, Please Upgrade Your Plan'
            ], 200);
        }
        else
        {

            $domain = $shop->name;
    
    
            $theme = $shop->api()->rest('GET', '/admin/api/2022-01/themes.json')['body']['themes'];
    
            foreach ($theme as $key => $value) {
                if ($value['role'] == 'main') {
                    $id = $value['id'];
                }
            }
    
            $sections = $shop->api()->rest(
                'GET',
                '/admin/api/2022-01/themes/' . $id . '/assets.json',
                ['asset[key]' => 'layout/theme.liquid']
            )['body']['asset'];
    
            $template = $sections['value'];
    
            if (strpos($template, "{% include 'multiple_pixels_fox' %}") == false) {
                $find = strpos($template, "</body>");
                $append = substr_replace($template, "{% include 'multiple_pixels_fox' %}\n", $find, 0);
                // dd($append);
    
                $data = array(
                    'asset' =>
                    array(
                        'key' => 'layout/theme.liquid',
                        'value' => $append,
                    ),
                );
    
                $snippet = $shop->api()->rest(
                    'PUT',
                    '/admin/api/2022-01/themes/' . $id . '/assets.json',
                    $data
                );
    
                //   Log::channel('shopify')->info('Shopify =', ['Update Theme Include' => "Done"]);
            }
            //Pixel Snippet
            $view = view('liquid.multiple_pixels_fox');
            $data = $view->render();
    
            $sdata = array(
                'asset' =>
                array(
                    'key' => 'snippets/multiple_pixels_fox.liquid',
                    'value' => $data,
                ),
            );
    
            $snippet = $shop->api()->rest(
                'PUT',
                '/admin/api/2022-01/themes/' . $id . '/assets.json',
                $sdata
            );
    
    
            $data = $req->all();
            $status_id = $req->get('status_id');
    
            $input = Pixel::find($status_id);
            $status = $input->status;
            if ($status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            $input->status = $status;
            $input->save();
    
            return response()->json([
                'status' => $input,
                'message' => 'Successfully updated record!'
            ], 200);
        }
    }

    public function contact(Request $req)
    {

        $shop = Auth::user();
        $data = $req->all();
        $input = new Contact;
        $email = $req->get('email');
        $subject = $req->get('subject');
        $message = $req->get('message');
        $id = $shop->id;

        $input->shop_id = $id;
        $input->email = $email;
        $input->subject = $subject;
        $input->message = $message;
        $input->save();

        return response()->json([
            'status' => $input
        ], 200);
    }
    public function purchase($name)
    {


        $user = User::where('name', $name)->first();
        $pixels = $user->pixels;
        $pixels = $pixels->filter(function ($element) {
            return in_array($element->pixel_id, explode(',', request('pixelIds')));
        });
        $result_array = array();
        $strings_array = explode(',', request('content_ids'));
        foreach ($strings_array as $each_number) {
            $result_array[] = (int) $each_number;
        }
        $purchaseId = request('purchaseEventId');
        foreach ($pixels as $pixel) :
            if ($pixel->access_token) :
                $data = [];
                if ($pixel->test_token) :
                    $data["test_event_code"] = $pixel->test_token;
                endif;
                $requestData = [
                    "event_name" => "Purchase",
                    "event_time" =>  time(),
                    "event_id" => $purchaseId,
                    "event_source_url" => rawurlencode(request('source_url')),
                    "action_source" => "website",
                    'content_ids' => $result_array,
                    "content_type" => "product_group",
                    'value' => request('value'),
                    "currency" => request('currency'),
                    "contents" => json_decode(request('contents')),
                    "num_items" => request('num_items'),
                    'order_id' => request('order_id'),
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
                if (request('fn') && request('fn') != 'null') :
                    $userData["fn"] = hash("sha256", request('fn'));
                endif;
                if (request('ln') && request('ln') != 'null') :
                    $userData["ln"] = hash("sha256", request('ln'));
                endif;
                if (request('ph') && request('ph') != 'null') :
                    $userData["ph"] = hash("sha256", request('ph'));
                endif;
                if (request('em') && request('em') != 'null') :
                    $userData["em"] = hash("sha256", request('em'));
                endif;
                if (request('ct') && request('ct') != 'null') :
                    $userData["ct"] = hash("sha256", request('ct'));
                endif;
                if (request('zp') && request('zp') != 'null') :
                    $userData["zp"] = hash("sha256", request('zp'));
                endif;
                if (request('st') && request('st') != 'null') :
                    $userData["st"] = hash("sha256", request('st'));
                endif;
                if (request('country') && request('country') != 'null') :
                    $userData["country"] = hash("sha256", request('country'));
                endif;
                if (request('order_id') && request('order_id') != 'null') :
                    $userData["subscription_id"] = request('order_id');
                endif;
                $requestData['user_data'] = $userData;
                $data['data'][] = $requestData;
                $result = Http::post('https://graph.facebook.com/v11.0/' . $pixel->pixel_id . '/events?access_token=' . $pixel->access_token, $data);
                if ($result->failed()) {
                    // info('--------------------------');
                    // info($result->json());
                    // info($requestData);
                    // info('--------------------------');
                }
            endif;

            $input = new History;
            $input->shop_id = $user->id;
            $input->event = "Purchase";
            $input->event_id = $purchaseId;
            $input->pixel = $pixel->pixel_id;
            if (isset($requestData)) :
                $input->data = json_encode($requestData);
            endif;
            $input->save();
        endforeach;
        return response()->json([
            'status' => true,
            'purchaseId' => $purchaseId
        ]);
    }
    
    /*
        This function checks the trial period of those user which do not have any paid plan
        and turn the pixel status off.
    */
    
    public function pixeltrialcheck7days()
    {
        $shop = User::where('plan_id',null)->with('pixels')->get();
        foreach($shop as $sp)
        {
            if(count($sp->pixels) != 0)
            {
                $interval = $sp->pixels[0]->created_at->diffInDays(now());
                if($interval > 7)
                {
                    $aa = Pixel::where('id',$sp->pixels[0]->id)->update(['status' => 0]);
                    // info(11);
                }
            }
        }
    }
    
}
