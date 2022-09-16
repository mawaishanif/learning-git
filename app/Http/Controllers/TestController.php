<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
  public function index()
  {

    $shop = auth()->user();
    $result = $shop->api()->rest('GET', '/admin/api/2022-01/themes.json');
    $theme_id = "";
    foreach ($result['body']->container['themes'] as $theme) {

      if ($theme['role'] === 'main') {
        $theme_id = $theme['id'];
      }
    }


    //   $res=$shop->api()->rest(
    //     'GET',
    //     '/admin/api/2022-01/themes/'.$activeid.'/assets.json',
    //     ['asset[key]' => 'config/settings_data.json']
    //   )['body'];

    // $val= json_decode($res['asset']['value'],true)['current']['blocks']['10185967436568479672']['disabled'];
    // dd($val);


    // app embbed code start

    //==asset api get== //

    $assets = $shop->api()->rest('GET', '/admin/api/2022-01/themes/' . $theme_id . '/assets.json');

    $embed = $shop->api()->rest(
      'GET',
      '/admin/api/2022-01/themes/' . $theme_id . '/assets.json',
      ['asset[key]' => 'config/settings_data.json']
    )['body'];

    $embed = json_decode($embed['asset']['value']);
    $id = "10185967436568479672";

    //==setting_data check blocks check  == //
    // app embbed code start

    //==asset api get== //

    $assets = $shop->api()->rest('GET', '/admin/api/2022-01/themes/' . $theme_id . '/assets.json');

    $embed = $shop->api()->rest(
      'GET',
      '/admin/api/2022-01/themes/' . $theme_id . '/assets.json',
      ['asset[key]' => 'config/settings_data.json']
    )['body'];

    $embed = json_decode($embed['asset']['value']);
    $id = "10185967436568479672";

    //==setting_data check blocks check  == //
    if (!isset($embed->current->blocks)) {
      $embed->current->blocks = json_decode('
    {
      "' . $id . '": {
        "type": "shopify:\/\/apps\/pixels-fox-conversion-api\/blocks\/app-embed\/467f8cf1-28cf-47c4-b02a-faba85c7400d",
        "disabled": false,
        "settings": {
        }
      }}
      ');
      $value =
        [
          "asset" => [
            "key" => "config/settings_data.json",
            "value" => json_encode($embed),
          ]
        ];
      //  return $value;
      $embeded = $shop->api()->rest('PUT', '/admin/api/2022-01/themes/' . $theme_id . '/assets.json', $value);
      // info('blocks');

    }
    //==setting_data check block id check  == //
    if (!isset($embed->current->blocks->$id)) {
      $embed->current->blocks->$id = json_decode('
      {
        "type": "shopify:\/\/apps\/pixels-fox-conversion-api\/blocks\/app-embed\/467f8cf1-28cf-47c4-b02a-faba85c7400d",
        "disabled": false,
        "settings": {
        }
  }
    ');
      $value =
        [
          "asset" => [
            "key" => "config/settings_data.json",
            "value" => json_encode($embed),
          ]
        ];
      $embeded = $shop->api()->rest('PUT', '/admin/api/2022-01/themes/' . $theme_id . '/assets.json', $value);
      // info('id');
    }
    //==setting_data check block id disabled button value  == //
    if (isset($embed->current->blocks->$id)) {
      $embed->current->blocks->$id->disabled = false;
      $value =
        [
          "asset" => [
            "key" => "config/settings_data.json",
            "value" => json_encode($embed),
          ]
        ];
      $embeded = $shop->api()->rest('PUT', '/admin/api/2022-01/themes/' . $theme_id . '/assets.json', $value);
      // info('false');
    }

    // app embeded code end


  }
}
