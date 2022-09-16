<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class CheckEmbbedController extends Controller
{
    public function index(){
        $shop = auth()->user();
        $result = $shop->api()->rest('GET', '/admin/api/2022-01/themes.json');
    
        foreach ($result['body']->container['themes'] as $theme) {

            if ($theme['role'] === 'main') {
              $activeid = $theme['id'];
            }
          }

          if($shop->api()->rest(
            'GET',
            '/admin/api/2022-01/themes/'.$activeid.'/assets.json',
            ['asset[key]' => 'templates/index.json']
          )['body'])
          {

            return response()->json([
             
              'status' => true
            ],200);
          }else{
            return response()->json([
                'status' => false
            ],200);
          }
      
    }
}
