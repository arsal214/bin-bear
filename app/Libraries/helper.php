<?php

use App\Models\Setting;

/**
 * Get listing of a resource.
 *
 * @return string
 */
function adminSettings($name)
{
    return Setting::get($name);
}

function displayCategories($categories, $selectedCategory = null, $level = 0)
{
    foreach ($categories as $category) {
        if ($category->parent_id == null || $level > 0) {
            // Indent child categories based on the level
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level); // Adding indentation using non-breaking spaces

            echo '<option value="' . $category->id . '" ' .
                (isset($selectedCategory) && $category->id == $selectedCategory ? 'selected' : '') . '>' .
                $indent . $category->name . '</option>';

            // Recursively call the function for child categories
            if ($category->children && $category->children->count()) {
                displayCategories($category->children, $selectedCategory, $level + 1);
            }
        }
    }
}
