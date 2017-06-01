<?php
/**
 * The Leader Portfolio Custom Post type class.
 */
class Leader_Portfolio
{
    /**
     * Initilize the hooks neccessary for the leader portfolio custom post type.
     */
    public function __construct()
    {
    	// Registers the custom post type portfolio on initlization of the theme.
        add_action( 'init', array($this, 'the_leader_portfolio_cpt'));
        
        // Registers Portfolio Categories Custom Taxonamy on Wordpress init
		add_action( 'init', array( $this, 'the_leader_portfolio_taxonamy' ) );
    }

    /**
     * Function to create portfolio custom post type in theme.
     * @param none
     * @return registers the custom post type portfolio.
     */
    public function the_leader_portfolio_cpt()
    {
    	// arguments for custom post type portfolio.
    	$arguments = array(
			'labels' => array(
				'name' 				 => __( 'Portfolio', 'the_leader' ),
				'singular_name' 	 => __( 'Portfolio Item', 'the_leader' ),
				'all_items'			 => __( 'All Items', 'the_leader' ),
				'add_new_item'		 => __( 'Add New Item', 'the_leader' )
			),
			'public' 			=> true,
			'capability_type' 	=> 'post',
			'rewrite' 			=> array( 'slug' => 'portfolio/items' ),
			'supports' 			=> array( 'title', 'editor', 'post-formats', 'thumbnail', 'comments', 'excerpt' ),
			'menu_position' 	=> 2,
			'menu_icon' 		=> 'dashicons-portfolio'
		);
    	// registering the custom post type.
		register_post_type( 'the_leader_portfolio', $arguments );
    }

    /**
     * Adds the custom taxonamy for the_leader custom post type portfolio.
     * @param none.
     * @return registers the custom taxonomy.
     */
    public function the_leader_portfolio_taxonamy()
    {
    	// configuration of CT
		$arguments = array(
			'labels' => array(
				'name'				=> __( 'Portfolio Categories', 'the_leader' ),
				'singular_name'		=> __( 'Portfolio Category', 'the_leader' ),
				'search_items'		=> __( 'Search Categories', 'the_leader' ),
				'popular_items'		=> __( 'Popular Categories', 'the_leader' ),
				'all_items'			=> __( 'All Categories', 'the_leader' ),
				'parent_item'		=> __( 'Parent Category', 'the_leader' ),
				'parent_item_colon' => __( 'Parent Category:', 'the_leader' ),
				'edit_item'			=> __( 'Edit Category', 'the_leader' ),
				'update_item' 		=> __( 'Update Category', 'the_leader' ),
				'add_new_item'		=> __( 'Add New Category', 'the_leader' ),
				'new_item_name'		=> __( 'New Category Name', 'the_leader' ),
				'menu_name'			=> __( 'Portfolio Categories', 'the_leader' )
			),
			'public' 			=> true,
			'show_in_nav_menus' => true,
			'show_admin_column' => true,
			'show_tagcloud'		=> true,
			'hierarchical' 		=> true,
			'rewrite' 			=> array("slug" => "portfolio/category")
		);

		// register taxonomy - Portfolio Categories
		register_taxonomy( 'the_leader_portfolio_cats', array( 'the_leader_portfolio' ), $arguments );
    }
}

/**
 * The leader portfolio widget class extends wordpress WP_Widget class.		
 */
class The_leader_Widget extends WP_Widget
{
    /**
     * summary
     */
    public function __construct()
    {
        $widget_opts = array(
				'classname'		=> 'widget_recent_portfolio',
				'description' 	=> __( "Your site&#8217;s most recent Portfolio Posts.")
			);

			parent::__construct(
				'recent_portfolio',
				__( 'Recent Portfolio Posts', 'the_leader' ),
				$widget_opts
			);
    }

    public function form($instance)
    {
    	$defaults = array(
				'title' 		  => __( 'Recent Portfolio Posts', 'the_leader' ),
				'posts_number' 	  => 4,
				'display_image'   => true,
				'display_date' 	  => true,
				'order_by_random' => false
			);

			$instance = wp_parse_args( (array)$instance, $defaults );
			?>

				<!-- Title -->
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'the_leader' ); ?></label>
					<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>">
				</p>

				<!-- Posts Number -->
				<p>
					<label for="<?php echo $this->get_field_id('posts_number'); ?>"><?php _e( 'Number of posts to show:', 'the_leader' ); ?></label>
					<input type="text" size="3" name="<?php echo $this->get_field_name('posts_number'); ?>" id="<?php echo $this->get_field_id('posts_number'); ?>" value="<?php echo esc_attr($instance['posts_number']); ?>">
				</p>

				<p>
					<input type="checkbox" name="<?php echo $this->get_field_name('display_image'); ?>" id="<?php echo $this->get_field_id('display_image'); ?>"<?php checked( $instance['display_image'] ); ?>>
					<label for="<?php echo $this->get_field_id('display_image'); ?>"><?php _e( 'Display post Thumbnail?', 'the_leader' ); ?></label>
				</p>

				<p>
					<input type="checkbox" name="<?php echo $this->get_field_name('display_date'); ?>" id="<?php echo $this->get_field_id('display_date'); ?>"<?php checked( $instance['display_date'] ); ?>>
					<label for="<?php echo $this->get_field_id('display_date'); ?>"><?php _e( 'Display post Date?', 'the_leader' ); ?></label>
				</p>

				<p>
					<input type="checkbox" name="<?php echo $this->get_field_name('order_by_random'); ?>" id="<?php echo $this->get_field_id('order_by_random'); ?>"<?php checked( $instance['order_by_random'] ); ?>>
					<label for="<?php echo $this->get_field_id('order_by_random'); ?>"><?php _e( 'Order by random?', 'the_leader' ); ?></label>
				</p>

			<?php
    }

    public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			// Title
			$instance['title'] = wp_filter_nohtml_kses($new_instance['title']);

			// Posts Number
			$instance['posts_number'] = intval($new_instance['posts_number']);

			// Thumbnail
			$instance['display_image'] = isset( $new_instance['display_image'] ) ? (bool) $new_instance['display_image'] : false;

			// Date
			$instance['display_date'] = isset( $new_instance['display_date'] ) ? (bool) $new_instance['display_date'] : false;

			// Order by
			$instance['order_by_random'] = isset( $new_instance['order_by_random'] ) ? (bool) $new_instance['order_by_random'] : false;

			return $instance;
		}

	public function widget( $args, $instance ) {
			extract($args);

			$title 			 = apply_filters( 'widget-title', $instance['title'] );
			$posts_number 	 = $instance['posts_number'];
			$display_image 	 = isset( $instance['display_image'] ) ? (bool) $instance['display_image'] : false;
			$display_date 	 = isset( $instance['display_date'] ) ? (bool) $instance['display_date'] : false;
			$order_by_random = isset( $instance['order_by_random'] ) ? (bool) $instance['order_by_random'] : false;

			// widget html
			echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			if ( $order_by_random ) {
				$orderby = 'rand';
			} else {
				$orderby = 'date';
			}

			// loop through portfolio posts
			$portfolio = new WP_Query(array(
				'post_type' 	 => 'the_leader_portfolio',
				'posts_per_page' => $posts_number,
				'orderby'		 => $orderby
			));

			?>


			<div>
				<ul>
				<?php while( $portfolio->have_posts() ) : $portfolio->the_post(); ?>

					<li>

						<!-- Thumbnail -->
						<?php if ( $display_image === true) : ?>
							<div class="recent-folio-thumb">
								<i class="fa fa-image"></i>
								<?php the_post_thumbnail('thumbnail'); ?>
							</div>
						<?php endif; ?>

						<!-- Title -->
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

						<!-- Date -->
						<?php if ( $display_date ) : ?>
							<span ><?php the_time( get_option('date_format') ); ?></span>
						<?php endif; ?>

					</li>

				<?php endwhile; ?>

				<!-- Restore Original Loop -->
				<?php wp_reset_postdata(); ?>
				</ul>
			</div>


			<?php

			echo $after_widget;

		}

	} 


	// register
	function register_The_Leader_Widget() {
	   register_widget( 'The_leader_Widget' );
	}

	add_action( 'widgets_init', 'register_The_Leader_Widget' );	