<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;
use App\Models\User;

class ThemesPublishJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Shop's myshopify domain
   *
   * @var ShopDomain|string
   */
  public $shopDomain;

  /**
   * The webhook data
   *
   * @var object
   */
  public $data;

  /**
   * Create a new job instance.
   *
   * @param string   $shopDomain The shop's myshopify domain.
   * @param stdClass $data       The webhook data (JSON decoded).
   *
   * @return void
   */
  public function __construct($shopDomain, $data)
  {
    $this->shopDomain = $shopDomain;
    $this->data = $data;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    // Convert domain
    // $this->shopDomain = ShopDomain::fromNative($this->shopDomain);

    $shop = User::where('name', $this->shopDomain)->get()->first();
    $id = $shop->theme_id;
    $section = $shop->api()->rest('GET', '/admin/api/2022-01/themes/' . $id . '/assets.json');

    $sections = $shop->api()->rest(
      'GET',
      '/admin/api/2022-01/themes/' . $id . '/assets.json',
      ['asset[key]' => 'layout/theme.liquid']
    )['body']['asset'];

    $template = $sections['value'];
    $replace = str_replace("{% include 'multiple_pixels_fox' %}", "", $template);
    $sdata = array(
      'asset' =>
      array(
        'key' => 'layout/theme.liquid',
        'value' => $replace,
      ),
    );

    $snippet = $shop->api()->rest(
      'PUT',
      '/admin/api/2022-01/themes/' . $id . '/assets.json',
      $sdata
    );
    $result = $this->data;
    $theme_id = $result->id;

    $sections = $shop->api()->rest(
      'GET',
      '/admin/api/2022-01/themes/' . $theme_id . '/assets.json',
      ['asset[key]' => 'layout/theme.liquid']
    )['body']['asset'];

    $template = $sections['value'];

    $find = strpos($template, "</body>");
    $append = substr_replace($template, "{% include 'multiple_pixels_fox' %}\n", $find, 0);

    $newdata = array(
      'asset' =>
      array(
        'key' => 'layout/theme.liquid',
        'value' => $append,
      ),
    );
    $update_theme = $shop->api()->rest(
      'PUT',
      '/admin/api/2022-01/themes/' . $theme_id . '/assets.json',
      $newdata
    );

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
      '/admin/api/2022-01/themes/' . $theme_id . '/assets.json',
      $sdata
    );

    $shop->theme_id = $theme_id;
    $shop->save();


    //////////////feed page start////////////////


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

    //////////////////feed page end/////////////////////
    // Do what you wish with the data
    // Access domain name as $this->shopDomain->toNative()


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
