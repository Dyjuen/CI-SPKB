<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination mb-0">
		<?php if ($pager->hasPrevious()) : ?>
			<li class="page-item">
				<a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="page-link">
					<span aria-hidden="true"><?= lang('Pager.first') ?></span>
				</a>
			</li>
			<li class="page-item">
				<a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="page-link">
					<span aria-hidden="true">&laquo;</span>
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

		<?php if ($pager->hasNext()) : ?>
			<li class="page-item">
				<a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="page-link">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
			<li class="page-item">
				<a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="page-link">
					<span aria-hidden="true"><?= lang('Pager.last') ?></span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>

<style>
.pagination .page-link {
    color: var(--navy);
    background-color: var(--white);
    border: 1px solid var(--border);
    padding: 8px 16px;
    font-size: .875rem;
    transition: all .2s;
}
.pagination .page-link:hover {
    background-color: var(--cream);
    color: var(--gold);
    border-color: var(--gold);
}
.pagination .page-item.active .page-link {
    background-color: var(--gold);
    border-color: var(--gold);
    color: var(--navy);
    font-weight: 600;
}
.pagination .page-item:first-child .page-link {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}
.pagination .page-item:last-child .page-link {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}
</style>
