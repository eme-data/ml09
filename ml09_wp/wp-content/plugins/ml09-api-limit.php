<?php
/**
 * Plugin Name: ML09 API Config
 * Description: Configuration API REST : limite de résultats et exposition des custom post types
 * Version: 2.0
 * Author: ML09
 */

// Augmenter la limite par page de l'API REST
add_filter('rest_post_collection_params', 'ml09_augmenter_limite_api', 10, 1);

function ml09_augmenter_limite_api($params) {
    if (isset($params['per_page'])) {
        $params['per_page']['maximum'] = 100;
    }
    return $params;
}

// Forcer show_in_rest pour les custom post types du site
add_filter('register_post_type_args', 'ml09_forcer_rest_api', 20, 2);

function ml09_forcer_rest_api($args, $post_type) {
    $types_rest = [
        'point',
        'point_paej',
        'atelier',
        'evenement',
        'temoignage',
        'menu',
        'footer',
        'equipe',
        'image_accueil',
        'atelier_texte',
        'evenement_texte',
        'emploi_texte',
        'formation_texte',
        'sante_texte',
        'mobilite_texte',
        'logement_texte',
        'paej_texte',
        'premier_pas_texte',
        'suivi_perso_texte',
        'service_entreprise_texte',
    ];

    if (in_array($post_type, $types_rest)) {
        $args['show_in_rest'] = true;
    }

    return $args;
}
