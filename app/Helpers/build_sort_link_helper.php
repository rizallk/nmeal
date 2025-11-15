<?php

if (!function_exists('buildSortLink')) {
  function buildSortLink($baseUrl,  $column,  $currentSortColumn,  $currentSortOrder, $otherParams = [])
  {
    $newSortOrder = 'asc';
    if ($column == $currentSortColumn && $currentSortOrder == 'asc') {
      $newSortOrder = 'desc';
    }

    unset($otherParams['sort-by'], $otherParams['sort-order']);

    $sortParams = [
      'sort-by' => $column,
      'sort-order' => $newSortOrder
    ];

    $allParams = array_merge($otherParams, $sortParams);

    return site_url($baseUrl) . '?' . http_build_query($allParams);
  }
}

// if (!function_exists('buildSortLink')) {
//   function buildSortLink($siteUrl, $column, $currentColumn, $currentOrder, $currentSearch)
//   {
//     $newOrder = ($currentColumn == $column && $currentOrder == 'asc') ? 'desc' : 'asc';
//     $params = [
//       'search'     => $currentSearch,
//       'sort-by'    => $column,
//       'sort-order' => $newOrder
//     ];
//     $params = array_filter($params);
//     return site_url($siteUrl) . '?' . http_build_query($params);
//   }
// }
