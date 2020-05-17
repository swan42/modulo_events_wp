<?php get_header(); ?>

	<main role="main">
	<!-- section -->
	<section>
	<?php if (have_posts()): while (have_posts()) : the_post(); $currentID = get_the_ID(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="singleEventResponsiveLayout">
				<?php if ( has_post_thumbnail()) : ?>
					<?php the_post_thumbnail(); ?>
				<?php endif; ?>
			</div>
			
			<div class="singleEventContainer">
				<div class="singleEventTextContainer">
					<div class="eventHeader">
						<div class="eventType singlePageEventType">
							<span>
								<?php echo (get_custom_field('modulo_event_type')); ?>
							</span>
						</div>
						<p>
							<?php 
							$start_date = get_custom_field('modulo_event_start_date');
							$end_date = get_custom_field('modulo_event_end_date');
							echo (get_french_date_range($start_date, $end_date)); ?>
						</p>
					</div>
						
					<h1><?php the_title(); ?></h1>
					<p class="eventOrganizerText"> Événement organisé par <span class="eventOrganizerTitle"><?php echo get_custom_field('modulo_event_organizer'); ?></span> .</p>
					<p><?php the_content(); // Dynamic Content ?></p>
				</div>
				<div class="singleEventImgContainer">
					<!-- post thumbnail -->
					<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
						<?php the_post_thumbnail(); // Fullsize image for the single post ?>
					<?php endif; ?>
					<!-- /post thumbnail -->
				</div>

			</div>

		</article>
		<!-- /article -->

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>

		</article>
		<!-- /article -->

	<?php endif; ?>

	<h1 class="otherEventsText">Autres événements organisés par <?php echo get_custom_field('modulo_event_organizer'); ?></h1>
	<div class="otherEventsContainer">
		<?php
			$loop = new WP_Query( array(
					'post_type' => 'modulo-event',
					'posts_per_page' => 10,
					'meta_key'          => 'modulo_event_organizer',
					'meta_value' => get_custom_field('modulo_event_organizer'),
					'meta_compare' => '=='
				)
			);
		?>
		
		<?php while ( $loop->have_posts() ) : $loop->the_post(); $count++; ?>

		<!-- We check the other events ID to not display current event twice -->
		<?php if (get_the_ID() != $currentID) : 
			$start_date = get_custom_field('modulo_event_start_date');
			$end_date = get_custom_field('modulo_event_end_date'); ?>
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

	</section>
	<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
