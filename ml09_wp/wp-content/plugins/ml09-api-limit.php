<?php
/**
 * Plugin Name: ML09 API Limit
 * Description: Augmente la limite de l'API REST pour afficher tous les points sur la carte
 * Version: 1.1
 * Author: ML09
 */

// Augmenter la limite globale de l'API REST
add_filter('rest_post_collection_params', 'ml09_augmenter_limite_api_globale', 10, 2);

function ml09_augmenter_limite_api_globale($params, $post_type) {
    // Augmenter la limite maximale à 100
    if (isset($params['per_page'])) {
        $params['per_page']['maximum'] = 100;
    }
    return $params;
}

// Cibler spécifiquement les custom post types "point" et "point_paej"
add_filter('rest_point_query', 'ml09_forcer_limite_point', 10, 2);
add_filter('rest_point_paej_query', 'ml09_forcer_limite_point', 10, 2);

function ml09_forcer_limite_point($args, $request) {
    // Forcer 100 résultats si demandé
    if ($request->get_param('per_page') == 100) {
        $args['posts_per_page'] = 100;
    }
    return $args;
}

// Solution alternative : hook sur pre_get_posts pour l'API REST
add_action('pre_get_posts', 'ml09_modifier_requete_api');

function ml09_modifier_requete_api($query) {
    // Vérifier si c'est une requête API REST
    if (defined('REST_REQUEST') && REST_REQUEST) {
        $post_type = $query->get('post_type');

        // Cibler les types de posts "point" et "point_paej"
        if ($post_type === 'point' || $post_type === 'point_paej') {
            // Forcer -1 pour récupérer TOUS les posts (pas de limite)
            $query->set('posts_per_page', -1);
        }
    }
}
