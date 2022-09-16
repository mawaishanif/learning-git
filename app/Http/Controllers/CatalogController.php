<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed;
use Auth;

class CatalogController extends Controller
{
    public function index()
    {
        $shop = Auth::user();
        
       
        $catalog = Feed::where('shop_id',$shop->id)->get();
        if(request()->ajax())
        {
            return response()->json($catalog);    
        }
        $collections = [];
        $custom_collection = $shop->api()->rest('GET','/admin/custom_collections.json')['body']['custom_collections'];
        $smart_collection = $shop->api()->rest('GET','/admin/smart_collections.json')['body']['smart_collections'];
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
      
        
        if(isset($productTypes['productTypes']))
        {
            $productTypes = $productTypes['productTypes']['edges'];
             
        }
        else
        {
            $productTypes = false;
        }
        
        // dd($productTypes);
        
        if(count($custom_collection) > 0)
        {
            foreach($custom_collection as $data)
            {
                array_push($collections , ['id' => $data['id'] , 'title' => $data['title'],'handle' => $data['handle'] , 'type' => 'custom_collections']);
            }
        }
        if(count($smart_collection) > 0)
        {
            foreach($smart_collection as $data)
            {
                array_push($collections , ['id' => $data['id'] , 'title' => $data['title'] ,'handle' => $data['handle'], 'type' => 'smart_collections']);
            }
        }
         
        return view('welcome',compact('collections','catalog' , 'productTypes'));
    }
    
    public function store(Request $req)
    {
        $shop = Auth::user();
        $data = $req->all();
       
        $feedName = $req->post('feedName');
        $collection = $req->post('collection');
        $category = $req->post('category');
        $variants = $req->post('variants');
        
        $productType = $req->post('ptype');
        
        if($productType == "0")
        {
            $productType = "all";
        }
        
        if($productType == "custom")
        {
            $productType = $req->post('custom_type');
        }
        
        $mode = $req->post('mode');
        $updateId = $req->post('updateId');
        
        if($mode == "update")
        {
            $input = Feed::find($updateId);
        }
        else
        {
            $input = new Feed;
        }
        
        $input->feedName = $feedName;
        $input->shop_id = $shop->id;
        $input->collection_handle = $collection;
        $input->category = rawurlencode($category);
        $input->variants = $variants;
        $input->product_type = $productType;
        $input->save();
        
        return response()->json(200);
    }
    
    public function destroy()
    {
        $shop = Auth::user();
        $id = request('id');
        $pixel = Feed::where('shop_id',$shop->id)->where('id',$id)->first();
        $pixel->delete();
        return response()->json(200);
    }
    
    public function generate($id)
    {
        $shop = Auth::user();
        $catalog = Feed::where('shop_id',$shop->id)->where('id',$id)->first();
        $url = url("/")."/{$shop->name}/pages/pixels-fox-feed?collection={$catalog->collection_handle}";
        return response()->json($url);
    }
}
