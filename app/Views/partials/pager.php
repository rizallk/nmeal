<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
  <ul class="pagination">
    <?php if ($pager->hasPreviousPage()) : ?>
      <li class="page-item">
        <a href="<?= $pager->getPreviousPage() ?>" class="page-link" aria-label="<?= lang('Pager.previous') ?>">
          <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
        </a>
      </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
      <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
        <a href="<?= $link['uri'] ?>" class="page-link">
          <?= $link['title'] ?>
        </a>
      </li>
    <?php endforeach ?>

    <?php if ($pager->hasNextPage()) : ?>
      <li class="page-item">
        <a href="<?= $pager->getNextPage() ?>" class="page-link" aria-label="<?= lang('Pager.next') ?>">
          <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
        </a>
      </li>
    <?php endif ?>
  </ul>
</nav>