<?php

namespace package\Infrastructure\Presenter;

use package\Application\Model\ValueObject\PageNumber;
use package\Application\Model\ValueObject\RowsPerPage;

final class PaginatorHtmlBuilder
{
    public function build(RowsPerPage $limit, PageNumber $currentPage, PageNumber $maxPage): string
    {
        $html = '';
        $maxPage = $maxPage->value();
        for ($index = 0; $index < $maxPage; $index++) {
            $page = $index + 1;
            if ($page === $currentPage->value()) {
                $html .= "<span class=\"page\">$page</span>";
            } else {
                $html .= "<a href=\"/?action=list&limit={$limit->value()}&page={$page}\" class=\"page\">{$page}</a>";
            }
            $html .= '&nbsp;';
            $html .= "\n";
        }
        return $html;
    }
}
