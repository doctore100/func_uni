<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults($data)
{
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() == 'post' or get_post_type() == 'page') {
            $results['generalInfo'][] = array(
                'authorName' => get_the_author(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'type' => get_post_type()
            );
        }

        if (get_post_type() == 'professor') {

            $results['professors'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'thumbnails'),
            );
        }

        if (get_post_type() == 'program') {
            $relatedCampuses = get_field('related_campus');
            if($relatedCampuses){
                foreach ($relatedCampuses as $campus) {
                    $results['campuses'][] = array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    );

                }

            }

            $results['programs'][] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            );
        }

        if (get_post_type() == 'campus') {
            $results['campuses'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            );
        }

        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $excerpt = null;
            if (has_excerpt()) {
                $excerpt = get_the_excerpt();
            } else {
                $excerpt = wp_trim_words(get_the_content(), 20);
            }
            $results['events'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'excerpt' => $excerpt,
            );
        }

    }

    $results = ($results['programs']) ? professorProgramRelationship($results) : $results;

    return $results;
}

/**
 * @param array $results
 * @return array
 */
function professorProgramRelationship(array $results): array
{

    wp_reset_postdata();
    $programsMetaQuery = array(
        'relation' => 'OR'
    );
    foreach ($results['programs'] as $program) {
        $programsMetaQuery[] = array(
            'key' => 'related_program',
            'compare' => 'LIKE',
            'value' => '"' . $program['id'] . '"'
        );
    }

    $programRelationsShip = new WP_Query(array(
        'post_type' => array('professor', 'event'),
        'meta_query' => $programsMetaQuery
    ));
    while ($programRelationsShip->have_posts()) {
        $programRelationsShip->the_post();

        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $excerpt = null;
            if (has_excerpt()) {
                $excerpt = get_the_excerpt();
            } else {
                $excerpt = wp_trim_words(get_the_content(), 20);
            }
            $results['events'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'excerpt' => $excerpt,
            );
        } else {
            $results['professors'][] = array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
            );
        }
    }
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));

    return $results;
}