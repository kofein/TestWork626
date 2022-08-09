<?php

add_action("wp_enqueue_scripts", "vendor_scripts_and_styles");
function vendor_scripts_and_styles()
{
    wp_enqueue_style("bootstrap", "https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css");
    wp_enqueue_script('bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js", ['jquery']);
}

add_action("wp_enqueue_scripts", "theme_scripts_and_styles");
function theme_scripts_and_styles()
{
    if (is_page('create-product')){
        wp_enqueue_media();
    }
    wp_enqueue_script( "front-script", get_stylesheet_directory_uri() . '/assets/front-script.js', ['jquery']);
    wp_enqueue_style( "front-style", get_stylesheet_directory_uri() . '/assets/front-style.css', ['jquery']);
}

add_action('admin_enqueue_scripts', 'admin_scripts_and_styles');
function admin_scripts_and_styles()
{
    wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/assets/admin-custom-style.css');
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/assets/admin-custom-script.js', ['jquery']);
}

add_action('woocommerce_product_options_general_product_data', 'woo_general_product_data_custom_field');
function woo_general_product_data_custom_field()
{
    global $post;

    // Image Field
    woocommerce_wp_hidden_input(
        [
            'id' => '_image_field',
            'value' => get_post_meta($post->ID, '_image_field', true)
        ]
    );

    // Date Field
    woocommerce_wp_text_input(
        [
            'id' => '_date_field',
            'label' => __('Date created', 'woocommerce'),
            'description' => __('View product create date.', 'woocommerce'),
            'type' => 'date',
            'value' => get_the_date('Y-m-d', $post->ID),
            'custom_attributes' => ['readonly' => 'readonly']
        ]
    );

    // Select
    woocommerce_wp_select(
        [
            'id' => '_select',
            'label' => __('Custom Select Field', 'woocommerce'),
            'options' => [
                'rare' => __('rare', 'woocommerce'),
                'frequent' => __('frequent', 'woocommerce'),
                'unusual' => __('unusual', 'woocommerce')
            ]
        ]
    );

}

add_action('woocommerce_process_product_meta', 'woo_save_general_custom_field');
function woo_save_general_custom_field($post_id)
{
    // Save Image Field
    $image_field = $_POST['_image_field'];
    update_post_meta($post_id, '_image_field', esc_attr($image_field));
    if (empty($_POST['_image_field'])) {
        delete_post_thumbnail( $post_id );
    }

    // Save Number Field
    $date_field = $_POST['_date_field'];
    update_post_meta($post_id, '_date_field', esc_attr($date_field));

    // Save Select
    $select = $_POST['_select'];
    update_post_meta($post_id, '_select', esc_attr($select));

}

add_action("woocommerce_product_options_pricing", "show_image_field");
function show_image_field()
{
    global $post;
    $img = wp_get_attachment_image(get_post_meta($post->ID, '_image_field', true), 'thumbnail');
    echo "
    <p>Custom image</p>
    <div class='custom-product-image'>
        <div class='img-wrapper'>$img</div>
        <button type='button' class='remove-custom-image notice-dismiss'></button>
    </div>";
}

add_action('admin_post_create_product', 'create_front_product');
function create_front_product()
{

    $post = array(
        'post_status' => "publish",
        'post_title' => esc_html($_POST['product_title']),
        'post_type' => "product",
    );

    $product_id = wp_insert_post($post, "Can't create product");

    wp_set_object_terms($product_id, 'simple', 'product_type');

    update_post_meta($product_id, '_regular_price', $_POST['product_price']);
    update_post_meta($product_id, '_price', $_POST['product_price']);
    update_post_meta( $product_id, '_image_field', $_POST['_image_field'] );
    update_post_meta($product_id, '_date_field', $_POST['_date_field']);
    update_post_meta($product_id, '_select', $_POST['_select']);

    set_post_thumbnail( $product_id, $_POST['_image_field'] );

    wp_redirect( get_permalink( $product_id) );

}