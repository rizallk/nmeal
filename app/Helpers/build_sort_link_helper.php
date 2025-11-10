<?php

if (!function_exists('buildSortLink')) {
  function buildSortLink($siteUrl, $column, $currentColumn, $currentOrder, $currentSearch)
  {
    $newOrder = ($currentColumn == $column && $currentOrder == 'asc') ? 'desc' : 'asc';
    $params = [
      'search'     => $currentSearch,
      'sort-by'       => $column,
      'sort-order' => $newOrder
    ];
    $params = array_filter($params);
    return site_url($siteUrl) . '?' . http_build_query($params);
  }
}
