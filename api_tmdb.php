<?php
$api_key = 'd5e9c2e7ae24e0b7bbeeb30461ff7199';

// Function to get movie image URL
function get_movie_image_url($movie_id) {
    global $api_key;

    // Get the base url for images from the configuration method
    $config_url = "https://api.themoviedb.org/3/configuration?api_key=" . $api_key;
    $response = file_get_contents($config_url);
    $config_data = json_decode($response, true);
    $base_url = $config_data['images']['secure_base_url'];

    // Get the movie data
    $url = "https://api.themoviedb.org/3/movie/{$movie_id}?api_key={$api_key}";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Get the poster and backdrop paths from the movie data
    $poster_path = $data['poster_path'];
    $backdrop_path = $data['backdrop_path'];

    // Construct the full urls for the poster and backdrop images
    $poster_url = "{$base_url}original{$poster_path}";
    return $poster_url;
}

function get_title($title) {
    $api_url = "http://localhost:5000/recommendations?title=" . urlencode($title);
    $response = file_get_contents($api_url);
    $result = json_decode($response, true);
    if (empty($result)) {
        return false;
    } else {
        return $result;
    }
    return $result;
}
?>
