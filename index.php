<?php
/*
Plugin Name: Amministrazione Trasparente List ID Based Sorting
Description: Dynamically lists child pages of "Amministrazione Trasparente" into a specific DOM element, sorted by a predefined order of IDs.
Version: 1.0
Author: Your Name
*/

// Customizable Variables
$parent_page_id = 581; // ID of the "Amministrazione Trasparente" page
$target_dom_id = 'amministrazione_trasparente_list'; // ID of the DOM element where the list will be displayed
$folder_icon_url = 'http://apsproncegno.chebellagiornata.it/wp-content/uploads/2023/12/folder-icon.png'; // URL of the folder icon

// Predefined order of IDs
$predefined_order = [
  700, 874, 950, 976, 1042, 1048, 1080, 1103, 1125, 1139,
  1142, 1160, 1174, 1222, 1239, 1257, 1283, 1307, 1324,
  1329, 1333, 1339, 1344,
];

// Main function to display child pages
function display_child_pages_list_ordered() {
    global $parent_page_id, $target_dom_id, $folder_icon_url, $predefined_order;
    
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'post_parent' => $parent_page_id,
        'post_status' => 'publish',
    );

    $query = new WP_Query($args);
    $pages = $query->posts;
    $sorted_pages = [];

    // Sorting pages based on predefined order of IDs
    foreach ($predefined_order as $id) {
        foreach ($pages as $page) {
            if ($page->ID == $id) {
                $sorted_pages[] = $page;
                break; // Found the page, move to the next ID
            }
        }
    }

    // Append pages not in the predefined list
    foreach ($pages as $page) {
        if (!in_array($page->ID, $predefined_order)) {
            $sorted_pages[] = $page;
        }
    }

    // Generate and inject the HTML list
    $list_html = '<ul>';
    foreach ($sorted_pages as $page) {
        $list_html .= '<li style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 0.4rem;">';
        $list_html .= '<img class="alignnone size-full wp-image-698" style="margin-right: 0.4rem;" src="' . esc_url($folder_icon_url) . '" alt="Folder" width="18" />';
        $list_html .= '<a href="' . get_permalink($page->ID) . '">' . esc_html($page->post_title) . '</a></li>';
    }
    $list_html .= '</ul>';

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var targetElement = document.getElementById('$target_dom_id');
                if (targetElement) {
                    targetElement.innerHTML = '" . addslashes($list_html) . "';
                }
            });
          </script>";
}

add_action('wp_footer', 'display_child_pages_list_ordered');
?>
