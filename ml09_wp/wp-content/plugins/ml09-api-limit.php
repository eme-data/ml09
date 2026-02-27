<?php
/**
 * Plugin Name: ML09 API Config
 * Description: Configuration API REST : limite, exposition CPT, réécriture URLs
 * Version: 3.0
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
        'carte_ml',
        'carte_paej',
        'point',
        'point_paej',
        'atelier',
        'evenement',
        'temoignage',
        'menu',
        'footer',
        'equipe',
        'image_accueil',
        'paej',
        'rapport_activite',
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

// Réécrire les anciennes URLs des médias vers le domaine actuel
function ml09_fix_old_urls($value) {
    if (is_string($value)) {
        $value = str_replace(
            'http://s1065353875.onlinehome.fr/ml09_wp',
            'https://ml09.org/ml09_wp',
            $value
        );
        $value = str_replace(
            'https://s1065353875.onlinehome.fr/ml09_wp',
            'https://ml09.org/ml09_wp',
            $value
        );
        $value = str_replace(
            'https://frhb10946ds.ikexpress.com/~deverchin/ml09_wp',
            'https://ml09.org/ml09_wp',
            $value
        );
        $value = str_replace(
            'http://frhb10946ds.ikexpress.com/~deverchin/ml09_wp',
            'https://ml09.org/ml09_wp',
            $value
        );
    } elseif (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] = ml09_fix_old_urls($item);
        }
    } elseif (is_object($value)) {
        foreach (get_object_vars($value) as $key => $item) {
            $value->$key = ml09_fix_old_urls($item);
        }
    }
    return $value;
}

// Appliquer la réécriture sur toutes les réponses REST API
add_filter('rest_pre_echo_response', 'ml09_fix_old_urls', 10, 1);

// Appliquer aussi sur les URLs des pièces jointes
add_filter('wp_get_attachment_url', function($url) {
    return ml09_fix_old_urls($url);
}, 10, 1);
