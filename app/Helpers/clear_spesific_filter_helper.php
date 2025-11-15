<?php

if (!function_exists('clearSpesificFilter')) {
  function clear_spesific_filter_helper($baseUrl,  $currentFilters, $spesificFilter)
  {
    $clearFilters = $currentFilters;
    unset($clearFilters[$spesificFilter]);
    return site_url($baseUrl) . '?' . http_build_query($clearFilters);
  }
}
