<?php

if (!function_exists('getSortIcon')) {
  function getSortIcon($column, $currentColumn, $currentOrder)
  {
    if ($currentColumn == $column) {
      return $currentOrder == 'asc' ? ' <i class="bi bi-sort-up"></i>' : ' <i class="bi bi-sort-down"></i>';
    }
    return '';
  }
}
