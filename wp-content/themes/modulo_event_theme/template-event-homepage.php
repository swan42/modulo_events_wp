<?php /* Template Name: Modulo Event Homepage */ get_header(); ?>

	<main role="main">
		<!-- section -->
		<section>
		
		<?php
			$is_first_post = true;

			$loop = new WP_Query( array(
					'post_type' => 'modulo-event',
					'posts_per_page' => 7,
					'meta_key'          => 'modulo_event_start_date',
					'orderby'           => 'meta_value',
					'order'             => 'ASC'
				)
			);
		?>

		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

		<?php $start_date = get_custom_field('modulo_event_start_date');
			$end_date = get_custom_field('modulo_event_end_date');
			$current_date = date_create("now");

			// First, highlighted event
			if ($start_date > $current_date && $is_first_post === true):  
				$is_first_post = false; ?>

				<a href="<?php the_permalink(); ?>">
					<div class="homeFirstPostContainer">

						<div class="homeFirstPostImg">
							<?php the_post_thumbnail(); ?>
						</div>

						<div class="homeFirstPostContainerText">
							<h1> <?php the_title(); ?> </h1>
							<p><?php the_content(); ?></p>
						</div>
						
					</div>
				</a>

				<div class="homeContainer">
					<div class="homeContainerText">
						<h1>Événements à venir</h1>
					<a href="evenements/">Voir tous les événements</a>
					</div>

					<div class="homeContainerContent">

			<?php // Others homepage events, displayed as thumbnails 
				elseif ($start_date > $current_date): ?>
				<a href="<?php the_permalink(); ?>">
					<div class="homeContainerNextEvents">
						<div class="homeContainerNextEventsVisuals">
							<div class="homeContainerNextEventImage">
								<?php the_post_thumbnail(); ?>
							</div>
							<div class='homeContainerNextEventType eventType'>
								<span>
									<?php echo (get_custom_field('modulo_event_type')); ?>
								</span>
							</div>
						</div>
						<div class="homeContainerNextEventText">
							<h1> <?php the_title(); ?> </h1>
							<p><?php echo (get_french_date_range($start_date, $end_date)); ?></p>
						</div>
					</div>
				</a>
			
			<?php endif; ?>

		<?php endwhile; wp_reset_query(); ?>

					</div>
				</div>

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
