<?php
defined( 'ABSPATH' ) || exit;

get_header();

$terms = get_terms( "clothes-type" );

$args = [
	"post_type"      => "clothes",
	"posts_per_page" => 10,
	"orderby"        => "date",
	"order"          => "DESC",
];

$query = new WP_query( $args );

?>
    <div class="container">
        <div class="row" style="margin-top: 30px;">
            <div class="col-sm-12">
				<?php foreach ( $terms as $term ): ?>
                    <a href="<?php echo get_term_link( $term,
						'clothes-type' ) ?>"
                       style="margin-right: 10px;"><?php echo $term->name; ?></a>
				<?php endforeach; ?>
            </div>
        </div>
        <div class="row">
			<?php while ( $query->have_posts() ):
				$query->the_post(); ?>
                <div class="col-sm-3" style="margin-top: 30px;">
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
		<?php
		$user = wp_get_current_user();
		if ( count( array_intersect( [ "administrator", "editor" ],
			(array) $user->roles ) )
		): ?>
            <div class="row">
                <div class="col-sm-12">
                    <h2>Add Clothes</h2>
                    <form id="add-clothes-form" action="" method="post"
                          enctype="multipart/form-data">
                        <input id="user-id" type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
                        <div class="form-group">
                            <label for="clothes-title">
                                Title:
                            </label>
                            <input id="clothes-title" class="form-control"
                                   type="text"
                                   name="title">
                        </div>
                        <div class="form-group">
                            <label for="clothes-description">
                                Description:
                            </label>
                            <textarea id="clothes-description"
                                      class="form-control"
                                      name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="clothes-thumbnail">
                                Thumbnail:
                            </label>
                            <input id="clothes-thumbnail" type="file"
                                   class="form-control-file"
                                   name="thumbnail">
                        </div>
                        <div class="form-group">
                            <label for="clothes-size">
                                Size:
                            </label>
                            <input id="clothes-size" class="form-control"
                                   type="" name="size">
                        </div>
                        <div class="form-group">
                            <label for="clothes-color">
                                Color:
                            </label>
                            <input id="clothes-color" class="form-control"
                                   type="text"
                                   name="color">
                        </div>
                        <div class="form-group">
                            <label for="clothes-sex">
                                Sex:
                            </label>
                            <input id="clothes-sex" class="form-control" type=""
                                   name="sex">
                        </div>
                        <div class="form-group">
                            <label for="clothes-type">
                                Type:
                            </label>
                            <select id="clothes-type" name="clothes-type" class="form-control" multiple>
								<?php
								$terms = get_terms( "clothes-type", [ "hide_empty" => false ] );
								foreach ( $terms as $term ): ?>
                                    <option value="<?php echo $term->name; ?>"><?php echo $term->name; ?></option>
								<?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save
                        </button>
                    </form>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php
wp_reset_postdata();
get_footer();
