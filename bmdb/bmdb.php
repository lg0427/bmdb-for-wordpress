<?php
/*
Plugin Name: 豆瓣读书观影记录
Plugin URI: https://noooooe.cn
Description: 用的是牧风的SDK，原项目地址https://mufeng.me/post/have-seen-the-film。我拿来适配了wordpress，三无产品，不接受技术支持。
Version: 1.0
Author: Bearye
Author URI: https://noooooe.cn
*/

register_activation_hook( __FILE__, 'bmdb');

function my_bmdb_css_js(){
    wp_enqueue_script("jquery");
    wp_enqueue_style( 'bbmdbb', plugins_url( 'includes/Bmdb.min.css',__FILE__));
    wp_enqueue_script( 'bbmdbb', plugins_url( 'includes/Bmdb.min.js',__FILE__));
}
add_action('wp_enqueue_scripts', 'my_bmdb_css_js');

function add_bmdb($atts, $content=null, $code=""){

    echo '<div class="BMDB"></div>';
    if ($content == 'movies'){
        ?>

        <script>
            jQuery(document).ready(function ($) {
                new Bmdb({
                    type: 'movies',
                    selector: '.BMDB',
                    secret: '<?php get_option('bmdb_secret') ?>',
                    noMoreText: '没有更多数据了',
                    limit: 30
                })
            })
        </script>

        <?php
    }elseif ($content == 'books'){
        ?>
        <script>
            jQuery(document).ready(function ($) {
                new Bmdb({
                    type: 'books',
                    selector: '.BMDB',
                    secret: '<?php get_option('bmdb_secret') ?>',
                    noMoreText: '没有更多数据了',
                    limit: 30
                })
            })
        </script>
        <?php
    }
}
add_shortcode('bmdb', 'add_bmdb');

function bmdb_admin(){
    if( !empty($_POST) && check_admin_referer('bmdb_update') ) {
        update_option('bmdb_secret', $_POST['bmdb_secret']);
        ?>
        <div id="message" class="updated">
            <p><strong>更改成功</strong></p>
        </div>
        <?php
    }
    ?>
    <div class="wrap">
        <h1>豆瓣读书观影设置</h1>
        <form method="post" action="" novalidate="novalidate">
            <input type="hidden" name="option_page" value="general"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="adde27b40a"><input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php">
            <table class="form-table" role="presentation">

                <tbody><tr>
                    <th scope="row"><label for="bmdb_secret">Secret</label></th>
                    <td>
                        <input name="bmdb_secret" type="text" id="bmdb_secret" value="<?php echo esc_attr(get_option('bmdb_secret')) ?>" class="regular-text">
                        <p class="description" id="new-admin-email-description">在页面填入 <code>[bmdb]movies[/bmdb]</code> 显示观影记录，填入 <code>[bmdb]books[/bmdb]</code> 显示读书记录。</p>
                        <p class="description" id="new-admin-email-description">secret请到https://bm.weajs.com/申请</p>
                    </td>
                </tr>
                </tbody></table>


            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="保存更改"></p>
            <?php wp_nonce_field('bmdb_update'); ?>
        </form>
    </div>
<?php
}



function bmdb_menu() {
    add_options_page('豆瓣读书观影', '豆瓣读书观影', 'manage_options', 'bmdb','bmdb_admin' );
}
add_action( 'admin_menu', 'bmdb_menu' );