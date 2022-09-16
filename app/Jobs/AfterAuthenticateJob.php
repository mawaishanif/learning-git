<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use Osiset\ShopifyApp\Storage\Queries\Shop as ShopQuery;
use Auth;
use App\Models\User;

class AfterAuthenticateJob
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $shopDomain;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    // $this->shopDomain = $shopDomain['name'];
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // $shop = User::where('name','globalnavtest.myshopify.com')->first();
    // $shop = User::where('name', $this->shopDomain)->firstOrFail();
    $shop = auth()->user();
    $domain = $shop->name;

    $shopApi = $shop->api()->rest('GET', '/admin/shop.json')['body']['shop'];
    $theme = $shop->api()->rest('GET', '/admin/api/2022-01/themes.json')['body']['themes'];

    foreach ($theme as $key => $value) {
      if ($value['role'] == 'main') {
        $id = $value['id'];
      }
    }

    $theme_id = $id;

    $shop->theme_id = $theme_id;
    $shop->admin_email = $shopApi['email'];
    $shop->admin_phone = $shopApi['phone'];
    $shop->country_name = $shopApi['country_name'];
    $shop->plan_display_name = $shopApi['plan_display_name'];

    $shop->save();

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

    $theme_id = $id;

    $temp = "";

    $data = array(
      'page' =>
      array(
        'title' => 'Pixels Fox Feed',
        'body_html' => $temp,
        'template_suffix' => 'pixels-fox-feed'
      ),
    );

    $view = view('liquid.pixels_fox_feed');
    $val = $view->render();

    $val = '<?xml version="1.0" encoding="UTF-8" ?>' . $val;
    // info("fox feed");
    $data2 = array(
      'asset' =>
      array(
        'key' => 'templates/page.pixels-fox-feed.liquid',
        'value' => $val,
      ),
    );

    $page2 = $shop->api()->rest("PUT", "/admin/api/2022-01/themes/{$theme_id}/assets.json", $data2)['body']['asset'];
    $page = $shop->api()->rest("GET", "/admin/api/2022-01/pages.json", ['handle' => 'pixels-fox-feed'])['body']['pages'];

    if (count($page) > 0) {
      $data = array(
        'page' =>
        array(
          'id' => $page[0]->id,
          'body_html' => $temp,
        ),
      );

      $shop->api()->rest("PUT", "/admin/api/2022-01/pages/{$page[0]->id}.json", $data);
    } else {
      $page = $shop->api()->rest('POST', '/admin/api/2022-01/pages.json', $data)['body']['page'];
      $user = User::find($shop->id);
      $user->feed_page = $page->id;
      $user->save();
    }


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
      $embed->current->blocks = json_decode('{
    "' . $id . '": {
      "type": "shopify:\/\/apps\/pixels-fox-conversion-api\/blocks\/app-embed\/467f8cf1-28cf-47c4-b02a-faba85c7400d",
      "disabled": false,
      "settings": {}
    }
  }');
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
