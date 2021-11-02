<?php
global $wp_query;

defined( 'ABSPATH' ) || exit;

get_header();

$term = get_term_by(
	"slug", get_query_var( 'term' ), get_query_var( 'taxonomy' )
);

$args = [
	"posts_per_page" => 4,
	"clothes-type"   => get_query_var( "term" ),
	"paged"          => get_query_var( "paged" ) ?: 1
];

$query_tmp = $wp_query;
$wp_query  = new WP_query( $args );

?>
    <div class="container">
        <div class="row" style="margin-top: 30px;">
            <div class="col-sm-4">
                <div>
                    Category: <?php echo $term->name; ?>
                </div>
                <div>
                    Description: <?php echo get_field(
						'description-acf', "clothes-type_" . $term->term_id
					); ?>
                </div>
            </div>
            <div class="col-sm-12" style="margin-top: 20px;">
                <div class="img-fluid">
                    <img src="<?php echo get_field(
						'image', "clothes-type_" . $term->term_id
					); ?>" alt=""/>
                </div>
            </div>
        </div>
        <div class="row">
			<?php while ( $wp_query->have_posts() ):
				$wp_query->the_post(); ?>
                <div class="col-sm-3">
                    <div class="img-fluid">
						<?php the_post_thumbnail(); ?>
                    </div>
                    <div style="margin: 15px 0 20px 0; font-weight: 600;">
                        <a href="<?php echo esc_url( get_permalink() ) ?>"
                           style="color: black; text-decoration: underline;"><?php the_title() ?></a>
                    </div>
                    <div>
                        Sex: <?php echo get_field( "sex", $post->ID ); ?>
                    </div>
                    <div>
                        Color: <?php echo get_field( "color", $post->ID ); ?>
                    </div>
                    <div>
                        Size: <?php echo get_field( "size", $post->ID ); ?>
                    </div>
                </div>
			<?php endwhile; ?>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="navigation" style="margin-top: 30px;">
                    <p><?php posts_nav_link(); ?></p></div>
            </div>
        </div>
    </div>
<?php
$wp_query = $query_tmp;
wp_reset_postdata();
get_footer();
