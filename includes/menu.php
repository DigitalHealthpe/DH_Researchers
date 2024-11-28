<?php
// Function to create the menu in the WordPress admin dashboard
function digitalhealth_menu() {
    add_menu_page(
        'Digital Health',            // Page title
        'Digital Health',            // Menu title
        'manage_options',            // Capability required to access
        'digital_health',            // Menu slug
        'digitalhealth_dashboard',   // Callback function to display the page
        'dashicons-admin-site-alt3', // Icon for the menu
        4                            // Position in the menu
    );
}
add_action('admin_menu', 'digitalhealth_menu');

// Callback function to display the plugin's admin page content
function digitalhealth_dashboard() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['digital_health_code'])) {
        // Validate and sanitize the API Key input
        $user_code = sanitize_text_field($_POST['digital_health_code']);
        if (preg_match('/^[a-f0-9]{20,50}$/i', $user_code)) { // Accept keys between 20 and 50 characters
            update_option('digital_health_user_code', $user_code);
            echo '<div class="notice notice-success is-dismissible"><p>API Key saved successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Invalid API Key format. Please ensure it is a hexadecimal string between 32 and 40 characters.</p></div>';
        }
    }

    // Retrieve the saved API Key
    $saved_code = get_option('digital_health_user_code', '');

    // Display the admin page form
    echo '<div class="wrap">';
    echo '<div style="display: flex; align-items: center;">';
    echo '<img src="https://research.digitalhealth.pe/wp-content/uploads/2024/11/Digital-Health-IPOPS.png" alt="Digital Health Logo" style="max-height: 120px; margin-right: 20px;">'; // Adjusted size and alignment
    echo '</div>';
    echo '<h1>Digital Health for Researchers</h1>';
    echo '<p>To use this plugin, you need to enter your <strong>NCBI API Key</strong>. This key enables integration with PubMed for publications.</p>';
    echo '<h3>How to Get Your NCBI API Key:</h3>';
    echo '<ol>';
    echo '<li><a href="https://account.ncbi.nlm.nih.gov/settings/" target="_blank">Go to the NCBI account settings page</a>.</li>';
    echo '<li>Log in with your NCBI account or create one if you don\'t have it.</li>';
    echo '<li>In the "API Key Management" section, click <strong>"Create new key"</strong>.</li>';
    echo '<li>Give your API Key a name (e.g., "Digital Health Plugin") and click <strong>"Create"</strong>.</li>';
    echo '<li>Copy the generated API Key and paste it below.</li>';
    echo '</ol>';
    echo '<form method="post">';
    echo '<label for="digital_health_code"><strong>Enter your API Key:</strong></label><br>';
    echo '<input type="text" id="digital_health_code" name="digital_health_code" value="' . esc_attr($saved_code) . '" placeholder="example: fedigitalhealth69624fa5108" pattern="[a-fA-F0-9]{20,50}" size="50" style="width: 50%;" required /><br>';
    echo '<button type="submit" class="button button-primary" style="margin-top: 10px;">Save API Key</button>';
    echo '</form>';

    // Agregar una sección para explicar cómo usar el shortcode
    echo '<div style="margin-top: 20px;">';
    echo '<h3>How to Use the Shortcode</h3>';
    echo '<p>You can use the shortcode <code>[digitalhealth_researcher]</code> in any page or post to display publications from specific authors.</p>';
    echo '<p><strong>Attributes:</strong></p>';
    echo '<ul>';
    echo '<li><code>author_aliases</code>: A semicolon-separated list of author aliases (e.g., "Villarreal D; Villarreal-Zegarra D"). The author\'s last name should be placed first, followed by the initials of the author\'s first name.</li>';
    echo '</ul>';
    echo '<p><strong>Example for author with a single alias:</strong></p>';
    echo '<pre style="background: #f7f7f7; padding: 10px; border: 1px solid #ddd; margin-top: 10px;">[digitalhealth_researcher author_aliases="Villarreal-Zegarra D"]</pre>';
    echo '<p><strong>Example for author with multiple aliases:</strong></p>';
    echo '<pre style="background: #f7f7f7; padding: 10px; border: 1px solid #ddd; margin-top: 10px;">[digitalhealth_researcher author_aliases="Vilela-Estrada A; Vilela-Estrada AL; Vilela A"]</pre>';
    echo '<p style="margin-top: 10px;">This will fetch and display all publications from the specified authors.</p>';
    echo '</div>';

}
