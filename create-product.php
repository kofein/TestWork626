<?php
/*
    Template Name: Create Product
*/
get_header(); ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php if (!current_user_can('administrator')) : ?>

                        <div class="alert alert-warning" role="alert">
                            This page available for administrators only. <a href="<?php echo wp_login_url( get_permalink() ); ?>">Login</a>
                        </div>

                    <?php else : ?>

                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-3">
                                    <div class='custom-product-image'>
                                        <div class='img-wrapper'></div>
                                        <input type="hidden" name="_image_field">
                                        <a class="remove-custom-img" href="#">
                                            <span class="dashicons dashicons-dismiss"></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <input type="hidden" name="action" value="create_product">
                                    <div class="mb-3">
                                        <label for="product-title" class="form-label">Product title</label>
                                        <input type="text" name="product_title" class="form-control" id="product-title" required="required">
                                    </div>
                                    <div class="mb-3">
                                        <label for="product-price" class="form-label">Product price</label>
                                        <input type="text" name="product_price" class="form-control" id="product-price"
                                               placeholder="0.00" required="required">
                                    </div>
                                    <div class="mb-3">
                                        <label for="_date_field" class="form-label">Product date</label>
                                        <input type="date" name="_date_field" class="form-control" id="_date_field"
                                               value="<?php echo date('Y-m-d') ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="_select" class="form-label">Product select</label>
                                        <select name="_select" class="form-select" id="_select"
                                                aria-label="Default select example" required="required">
                                            <option selected disabled value="">Select option</option>
                                            <option><?php echo __('rare', 'woocommerce') ?></option>
                                            <option><?php echo __('frequent', 'woocommerce') ?></option>
                                            <option><?php echo __('unusual', 'woocommerce') ?></option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </article><!-- #post-## -->
            <?php endwhile; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
do_action('storefront_sidebar');
get_footer();
