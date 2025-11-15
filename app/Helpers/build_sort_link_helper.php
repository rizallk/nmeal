<?php

if (!function_exists('buildSortLink')) {
  function buildSortLink($baseUrl,  $column,  $currentSortColumn,  $currentSortOrder, $otherFilters = [])
  {
    $newSortOrder = 'asc';
    if ($column == $currentSortColumn && $currentSortOrder == 'asc') {
      $newSortOrder = 'desc';
    }

    unset($otherFilters['sort-by'], $otherFilters['sort-order']);

    $sortFilters = [
      'sort-by' => $column,
      'sort-order' => $newSortOrder
    ];

    $allFilters = array_merge($otherFilters, $sortFilters);

    return site_url($baseUrl) . '?' . http_build_query($allFilters);
  }
}

// if (!function_exists('buildSortLink')) {
//   function buildSortLink($siteUrl, $column, $currentColumn, $currentOrder, $currentSearch)
//   {
//     $newOrder = ($currentColumn == $column && $currentOrder == 'asc') ? 'desc' : 'asc';
//     $Filters = [
//       'search'     => $currentSearch,
//       'sort-by'    => $column,
//       'sort-order' => $newOrder
//     ];
//     $Filters = array_filter($Filters);
//     return site_url($siteUrl) . '?' . http_build_query($Filters);
//   }
// }
