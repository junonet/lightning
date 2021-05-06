<?php
/**
 * ブログカード
*/

if ( ! class_exists( 'VK_WP_Oembed_Blog_Card' ) ) {

  class VK_WP_Oembed_Blog_Card {
    /**
     * Constructor
     */
    public function __construct() {
      add_filter( 'embed_oembed_html', array( __CLASS__, 'vk_embed_oembed_html' ) );
      add_filter( 'embed_maybe_make_link', array( __CLASS__, 'vk_embed_maybe_make_link' ) , 9, 2 );
    }

    /**
    * WordPress独自のブログカード生成時のフィルターフックembed_oembed_html
    * File: wp-includes/class-wp-oembed.php
    * esc_url_raw は & などがはいってもエスケープさせないため
    * 管理画面側のHTMLも適応される
    */
    public static function vk_embed_oembed_html( $output ) {
      $pattern = '/<blockquote class="wp-embedded-content".*?><a href="(.+?)"/i';
      if ( ! preg_match( $pattern, $output, $match ) )  {
        return $output;
      }
      $url = esc_url_raw($match[1]);
      $content = static::vk_get_blog_card($url);
      return $content;
    }

    /**
     * WordPress独自のブログカードで生成出来ないもの
     * 「埋め込み URL このコンテンツを埋め込めませんでした。」 
     * と表示されるものに実行
     * 管理画面側のHTMLは適応されない
     */
    public static function vk_embed_maybe_make_link( $output, $url ) {
      $content = static::vk_get_blog_card($url);
      return $content;
    }

    /*
    ブログカードのHTML生成
    urlを渡すとHTMLが返ってくる
    wp_remote_getでリンク先のHTMLを取得
    */
    public static function vk_get_blog_card( $url ) {
      $response = wp_remote_get( $url );
      // URLのHTMLを$bodyに入れる
      $body = $response['body'];

      //正規表現でマッチするものを取得する
      if ( preg_match( '/<title>(.+?)<\/title>/is', $body, $matches ) ) {
        $title = $matches[1];
      }

      if ( preg_match( '/<meta.+?property=["\']og:image["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $body, $matches ) ) {
        $og_image = $matches[1];
      } 

      if ( preg_match( '/<meta.+?property=["\']og:description["\'][^\/>]*?content=["\']([^"\']+?)["\'].*?\/?>/is', $body, $matches ) ) {
        $og_description = $matches[1];
      }
      
      /*
      スタイルは一旦Horizontalカードを使用
      https://getbootstrap.jp/docs/4.2/components/card/#horizontal
      HTMLがカスタマイズ出来る形にする
      */
      $content = <<<EOF
      <div class="card mb-3">
        <a href="$url">
          <div class="row no-gutters">
            <div class="col-md-4">
              <img class="bd-placeholder-img" width="486" height="290" src="$og_image" alt="">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">$title</h5>
                <p class="card-text">$og_description</p>
              </div>
            </div>
          </div>
        </a>
      </div>
EOF;
      return $content;
    }
  }
  new VK_WP_Oembed_Blog_Card();
}
