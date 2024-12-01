document.addEventListener('DOMContentLoaded', function () {
    // Function to reload external scripts like Altmetric, Dimensions, and Scite.ai
    function reloadExternalScripts() {
        // Reload Altmetric
        if (typeof _altmetric_embed_init === 'function') {
            _altmetric_embed_init();
        }

        // Reload Dimensions
        if (typeof __dimensions_embed === 'object') {
            __dimensions_embed.addBadges();
        }

        // Reload Scite.ai
        if (typeof scite === 'object' && scite.reloadBadges) {
            scite.reloadBadges();
        }
    }

    // Show an alert if API Key or data is missing
    function showError(message) {
        alert(message);
    }

    // Function to copy DOI to clipboard
    document.querySelectorAll('.scite-badge').forEach(function (badge) {
        badge.addEventListener('click', function () {
            const doi = badge.getAttribute('data-doi');
            navigator.clipboard.writeText(doi).then(() => {
                alert('DOI copied to clipboard: ' + doi);
            });
        });
    });

    // Expand/Collapse Article Details
    document.querySelectorAll('.article-title').forEach(function (title) {
        title.addEventListener('click', function (event) {
            event.preventDefault();
            const parentArticle = title.closest('.article');
            const details = parentArticle.querySelectorAll('.authors, .journal, .pmid, .citations');

            details.forEach(function (detail) {
                detail.style.display = detail.style.display === 'none' ? 'block' : 'none';
            });
        });
    });

    // Adjust layout for responsive design
    function adjustForResponsiveDesign() {
        const articles = document.querySelectorAll('.article');
        if (window.innerWidth < 768) {
            articles.forEach(article => {
                article.style.padding = '8px 0';
            });
        } else {
            articles.forEach(article => {
                article.style.padding = '10px 0'; // Default padding
            });
        }
    }

    // Add event listener for resizing
    window.addEventListener('resize', adjustForResponsiveDesign);
    adjustForResponsiveDesign(); // Call on load

    // Call reloadExternalScripts after the content is dynamically loaded
    reloadExternalScripts();
});
