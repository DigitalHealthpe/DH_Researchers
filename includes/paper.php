<?php
// Prevent direct access
if (!defined('ABSPATH')) exit;

// Register the shortcode
function digitalhealth_paper_shortcode($atts) {
    $attributes = shortcode_atts(array(
        'author_aliases' => '',
    ), $atts);

    if (empty($attributes['author_aliases'])) {
        return '<p><strong>Error:</strong> No author aliases provided. Please specify authors using the "author_aliases" attribute.</p>';
    }

    $author_aliases = array_map('trim', explode(';', $attributes['author_aliases']));
    $api_key = get_option('digital_health_user_code', '');

    if (empty($api_key) || !preg_match('/^[a-f0-9]{20,50}$/i', $api_key)) {
        return '<p><strong>Error:</strong> No valid NCBI API Key found. Please configure it in the plugin settings.</p>';
    }

    return digitalhealth_fetch_and_display_publications($api_key, $author_aliases);
}
add_shortcode('digitalhealth_researcher', 'digitalhealth_paper_shortcode');

// Function to fetch and display publications
function digitalhealth_fetch_and_display_publications($api_key, $author_aliases) {
    // Build the query from author aliases
    $author_query = implode(' OR ', array_map(function ($alias) {
        return '"' . $alias . '"[Full Author Name]';
    }, $author_aliases));

    // PubMed search endpoint and parameters
    $search_url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi';
    $search_params = array(
        'db' => 'pubmed',
        'term' => $author_query,
        'retmax' => '1000',
        'retmode' => 'json',
        'api_key' => $api_key,
    );

    // Fetch search results from PubMed
    $response = wp_remote_get(add_query_arg($search_params, $search_url));
    if (is_wp_error($response)) {
        return '<p><strong>Error:</strong> Unable to retrieve publications.</p>';
    }

    // Parse the response body and extract PMIDs
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['esearchresult']['idlist'])) {
        return '<p>No publications found for this author.</p>';
    }

    $pmids = $data['esearchresult']['idlist'];
    $pmids_string = implode(',', $pmids);

    // PubMed fetch details endpoint and parameters
    $fetch_url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi';
    $fetch_params = array(
        'db' => 'pubmed',
        'id' => $pmids_string,
        'retmode' => 'xml',
        'api_key' => $api_key,
    );

    // Fetch article details
    $response = wp_remote_get(add_query_arg($fetch_params, $fetch_url));
    if (is_wp_error($response)) {
        return '<p><strong>Error:</strong> Unable to retrieve publication details.</p>';
    }

    // Load the XML response
    $body = wp_remote_retrieve_body($response);
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($body);
    if ($xml === false) {
        return '<p><strong>Error:</strong> Failed to parse publication details.</p>';
    }

    // Start generating the HTML output
    $output = '<div id="articles">';
    $output .= '<b>This author has a total of ' . count($pmids) . ' publications. The publications listed below are automatically derived from MEDLINE/PubMed and other sources, which might result in incorrect or missing publications.</b>';
    $output .= '<style>
        .article { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .article-title { font-size: 18px; font-weight: bold; color: #0056b3; text-decoration: none; }
        .article-title:hover { text-decoration: underline; }
        .authors, .journal, .pmid, .citations { margin: 5px 0; }
        .bold-text { font-weight: bold; }
        .citations { display: flex; gap: 10px; margin-top: 5px; }
    </style>';

    // Loop through each article
    foreach ($xml->PubmedArticle as $article) {
        $title = (string)$article->MedlineCitation->Article->ArticleTitle;
        $journal = (string)$article->MedlineCitation->Article->Journal->Title;
        $pmid = (string)$article->MedlineCitation->PMID;
        
        // Extract DOI if available
        $doi = '';
        foreach ($article->PubmedData->ArticleIdList->ArticleId as $id) {
            if ((string)$id['IdType'] === 'doi') {
                $doi = (string)$id;
                break;
            }
        }

        $authors = $article->MedlineCitation->Article->AuthorList->Author;

        // Article container
        $output .= '<div class="article">';
        $output .= '<a class="article-title" href="https://pubmed.ncbi.nlm.nih.gov/' . esc_attr($pmid) . '" target="_blank">' . esc_html($title) . '</a>';
        
        // Authors
        $authors_text = 'Authors: ';
        $author_names = array();
        foreach ($authors as $author) {
            $lastName = isset($author->LastName) ? (string)$author->LastName : '';
            $foreName = isset($author->ForeName) ? (string)$author->ForeName : '';
            $fullName = trim($foreName . ' ' . $lastName);
            if (in_array($fullName, $author_aliases)) {
                $author_names[] = '<span class="bold-text"><b>' . esc_html($fullName) . '</b></span>';
            } else {
                $author_names[] = esc_html($fullName);
            }
        }
        $authors_text .= implode(', ', $author_names);
        $output .= '<div class="authors">' . $authors_text . '</div>';
        
        // Journal and PMID
        $output .= '<div class="journal">Journal: ' . esc_html($journal) . '</div>';
        $output .= '<div class="pmid">PMID: ' . esc_html($pmid) . '</div>';

        // Add citations for Dimensions, Altmetric, and scite.ai
        $output .= '<div class="citations">';
        $output .= '<div class="altmetric-embed" data-badge-popover="bottom" data-badge-type="2" data-pmid="' . esc_attr($pmid) . '" data-hide-no-mentions="true"></div>';
        $output .= '<span class="__dimensions_badge_embed__" data-pmid="' . esc_attr($pmid) . '" data-hide-zero-citations="true" data-legend="hover-bottom" data-style="large_rectangle"></span>';
        
        // Add scite.ai badge if DOI exists
        if (!empty($doi)) {
            $output .= '<div class="scite-badge" data-doi="' . esc_attr($doi) . '" data-layout="horizontal" data-tooltip-placement="bottom" data-show-zero="true" data-small="true" data-show-labels="false" data-tally-show="true"></div>';
        }
        $output .= '</div>'; // End citations

        $output .= '</div>'; // End article
    }

    $output .= '</div>';
    // Load scripts for Dimensions, Altmetric, and scite.ai
    $output .= '<script async src="https://badge.dimensions.ai/badge.js" charset="utf-8"></script>';
    $output .= '<script async src="https://d1bxh8uas1mnw7.cloudfront.net/assets/embed.js"></script>';
    $output .= '<script async type="application/javascript" src="https://cdn.scite.ai/badge/scite-badge-latest.min.js"></script>';

    return $output;
}
