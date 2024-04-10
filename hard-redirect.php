<?php
/**
 * URI Handler.
 *
 * Redirects to specific locations based on the 'hr_id' query parameter, maintaining all other query parameters except 'hr_id'.
 */
function hard_redirect() {
    // Use filter_input() to safely fetch 'hr_id' parameter from the URL.
    $id = filter_input(INPUT_GET, 'hr_id', FILTER_SANITIZE_NUMBER_INT);

    // Define a hard-coded mapping of 'hr_id' values to URL slugs.
    $id_paths = [
        1 => '/sample-page/',
		2 => 'https://google.com',
		3 => 'https://www.dropbox.com/s/2q9dtbklxgmvwh0/the-xprepper-system.pdf?dl=0',
    ];

    // Initialize an empty array to hold query parameters other than 'id'.
    $queryParams = [];

    // Loop through all GET parameters, exclude 'hr_id', and prepare the rest for appending.
    foreach ($_GET as $key => $value) {
        if ($key !== 'hr_id') { // Skip 'hr_id' parameter.
            $queryParams[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
        }
    }

    // Build query string from remaining parameters, if any.
    $queryString = http_build_query($queryParams);

    // Check if the current ID has a defined redirect path.
    if (array_key_exists($id, $id_paths)) {
        // Check if URL is absolute. If not, prepend home_url.
        if (filter_var($id_paths[$id], FILTER_VALIDATE_URL)) {
            $redirectUrl = $id_paths[$id];
        } else {
            $redirectUrl = home_url($id_paths[$id]);
        }
        $redirectUrl .= !empty($queryString) ? "?{$queryString}" : "";
        wp_redirect($redirectUrl);
        exit();
    }
}

add_action('template_redirect', 'hard_redirect');
