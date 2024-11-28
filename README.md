# Digital Health - Researchers Plugin

[![License](https://img.shields.io/badge/license-GPLv2-blue.svg)](LICENSE)

## About

The **Digital Health - Researchers Plugin** is designed to integrate publication data from PubMed and related sources into WordPress websites. This plugin allows users to display detailed publication lists for specific authors, including citations from Dimensions, Altmetric, and Scite.ai.

This project is developed by **Digital Health Research Center**, a subsidiary of **IPOPS (Instituto Peruano de Orientación Psicológica)**, as part of our commitment to providing innovative tools for research and academic dissemination.

## Current Version

- **Version:** 1.0.1

## Features

- Fetch and display publications from PubMed for specified authors.
- Highlight specific author aliases in bold in publication lists.
- Include citation badges from:
  - **Altmetric**
  - **Dimensions**
  - **Scite.ai**
- Fully customizable and responsive design.
- Allows integration via shortcode for easy use in WordPress pages and posts.

## Installation

1. **Download the Plugin**:
   - Visit [Digital Health's official website](https://digitalhealth.pe) to download the latest version of the plugin.
   - Alternatively, clone the repository:
     ```bash
     git clone https://github.com/DigitalHealthpe/DH_Researchers.git
     ```
2. **Upload to WordPress**:
   - Go to your WordPress admin panel.
   - Navigate to `Plugins` > `Add New`.
   - Upload the plugin zip file or place the unzipped folder in the `/wp-content/plugins/` directory.

3. **Activate the Plugin**:
   - In your WordPress admin panel, navigate to `Plugins`.
   - Find the **Digital Health - Researchers Plugin** and click `Activate`.

4. **Configure API Key**:
   - Go to `Digital Health` > `API Key`.
   - Enter your NCBI API Key. If you don’t have one, create it [here](https://account.ncbi.nlm.nih.gov/settings/).

## Usage

1. **Add Publications to a Page or Post**:
   - Use the shortcode `[digitalhealth_researcher author_aliases="Author Alias 1; Author Alias 2"]`.
   - Example:
     ```html
     [digitalhealth_researcher author_aliases="Villarreal-Zegarra D"]
     ```
   - This will display publications for the provided author aliases.

2. **Publication Display**:
   - The plugin automatically retrieves publication data, including titles, journals, authors, and citation metrics.
   - The citations section includes Altmetric, Dimensions, and Scite.ai badges.

## Example Output

Below is an example of how the plugin renders publications on a personal page:

[David Villarreal-Zegarra's Personal Page](https://ipops.pe/david-villarreal-zegarra/)

The plugin retrieves publications from PubMed based on the author's aliases, displays metadata such as title, journal, and PMID, and integrates Altmetric, Dimensions, and Scite badges for enhanced citation tracking. This example showcases how researchers can use the plugin to highlight their academic contributions seamlessly on a WordPress site.
