<?php
/**
 * Plugin Name: ML09 API Limit
 * Description: Augmente la limite de l'API REST pour afficher plus de points sur la carte
 * Version: 1.0
 * Author: ML09
 */

add_filter('rest_post_collection_params', 'ml09_augmenter_limite_api', 10, 1);

function ml09_augmenter_limite_api($params) {
    if (isset($params['per_page'])) {
        $params['per_page']['maximum'] = 100;
    }
    return $params;
}