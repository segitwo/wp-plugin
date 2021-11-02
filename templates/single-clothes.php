<?php
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );

while ( have_posts() ):
	the_post();
	?>
    <div class="container">
        <div class="row" style="margin-top: 30px;">
            <div class="col-sm-4">
                <div>
                    Type: <?php echo wp_get_post_terms(
						$post->ID, "clothes-type"
					)[0]->name; ?>
                </div>
                <div style="margin: 10px 0 20px 0; font-weight: 600;">
                    Name: <?php the_title(); ?>
                </div>
                <div class="img-fluid">
					<?php the_post_thumbnail(); ?>
                </div>
                <div style="margin: 20px 0 0 0">
                    Sex: <?php echo get_field( "sex", $post->ID ); ?>
                </div>
                <div>
                    Color: <?php echo get_field( "color", $post->ID ); ?>
                </div>
                <div>
                    Size: <?php echo get_field( "size", $post->ID ); ?>
                </div>
            </div>
        </div>
    </div>
<?php
endwhile;
get_footer();
