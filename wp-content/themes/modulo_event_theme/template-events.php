<?php /* Template Name: Modulo Events */ get_header(); ?>

	<main role="main">
		<!-- section -->
		<section>
		<?php
			$loop = new WP_Query( array(
					'post_type' => 'modulo-event',
					'posts_per_page' => 12,
					'meta_key'          => 'modulo_event_start_date',
					'orderby'           => 'meta_value',
					'order'             => 'ASC'
				)
			);

			// We iterate trough the loop in order to split the events by months.
			// We want to find events for 3 distinct months
			// and display them month by month
			$current_date = date_create("now");
			$current_month = date_format($current_date, 'm');
			$iterate_month = -1;
			$iterate_count = 0;
			$output = '';

			while ($loop->have_posts()) {
				$loop->the_post();
				$start_date = get_custom_field('modulo_event_start_date');
				$end_date = get_custom_field('modulo_event_end_date');
				$start_month = date_format($start_date, 'm');

				// Since query asks for events in chronological manner, we are sure the first
				// event we get happens during the first month
				if ($iterate_month === -1 || $start_month === $iterate_month) {
					if ($iterate_month === -1) {
						$iterate_month = $start_month;
						$iterate_count++;
						$output .= '<h1 class="monthTitle">' . convert_month_to_french($iterate_month) . ' ' .  date_format($start_date, 'Y') . '</h1>
									<div class="homeContainerContent">';
					}
					
					// We display the event
					$output .=  "<div class='homeContainerNextEvents'>
									<div class='homeContainerNextEventImage'>";
					$output .=  "<a href='" . get_permalink() . "'>";
					$output .= get_the_post_thumbnail();
					$output .= "</a>";
					$output .= "<div class='homeContainerNextEventType eventType'><span>";
					$output .= get_custom_field('modulo_event_type');
					$output .= "</span></div>";

					$output .= "</div>
									<div class='homeContainerNextEventText'>";
					$output .= "<a href='" . get_permalink() . "'>";
					$output .= "<h1> ";
					$output .= get_the_title();
					
					$output .= "</h1> </a> <p> ";
					$output .=  get_french_date_range($start_date, $end_date);
					$output .= "</p> </div> </div>";

				}

				// 
				else if ($start_month > $iterate_month) {
					// We stop searching after we find 3 distinct months of events
					if ($iterate_count > 2) {
						$output .= '</div>';
						wp_reset_query();
						break;
					}

					// New month !
					$iterate_month = $start_month;
					$output .= '</div>';
					$output .= '<h1 class="monthTitle">' . convert_month_to_french($iterate_month) . ' ' .  date_format($start_date, 'Y') . '</h1>';
					$output .= '<div class="homeContainerContent">';

					// We display the event
					$output .=  "<div class='homeContainerNextEvents'>
									<div class='homeContainerNextEventImage'>";
					$output .=  "<a href='" . get_permalink() . "'>";
					$output .= get_the_post_thumbnail();
					$output .= "</a>";

					$output .= "<div class='homeContainerNextEventType eventType'><span>";
					$output .= get_custom_field('modulo_event_type');
					$output .= "</span></div>";

					$output .= "</div>
									<div class='homeContainerNextEventText'>";

					$output .= "<a href='" . get_permalink() . "'>";
					$output .= "<h1> ";
					$output .= get_the_title();
					
					$output .= "</h1> </a> <p> ";
					$output .=  get_french_date_range($start_date, $end_date);
					$output .= "</p> </div> </div>";
				}
			}

			echo ($output);

		?>
		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
