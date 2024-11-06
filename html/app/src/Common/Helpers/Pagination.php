<?php

namespace app\src\Common\Helpers;

use app\src\Common\Constants\PaginationConstants;

class Pagination
{
    private int $size = 10;

    private int $page = 1;

    public function __construct(string $size = "10", string $page = "1")
    {
        if (is_numeric($size) && (int)$size !== 0) {
            $this->size = (int)$size;
        }

        if (is_numeric($page) && (int)$page !== 0) {
            $this->page = (int)$page;
        }
    }

    public function getOffset(): int
    {
        if ($this->page == 0) {
            return 0;
        }

        return ($this->page - 1) * $this->size;
    }

    public function setPage(string $pageQuery)
    {
        if ($pageQuery == "") {
            $this->page = 0;
        }

        if (is_numeric($pageQuery) && (int)$pageQuery !== 0) {
            $this->page = (int)$pageQuery;
        }
    }

    public function setSize(string $sizeQuery)
    {
        if ($sizeQuery == "") {
            $this->size = 0;
        }

        if (is_numeric($sizeQuery) && (int)$sizeQuery !== 0) {
            $this->size = (int)$sizeQuery;
        }
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getLimit(): int
    {
        return $this->size;
    }

    public function getTotalPages(int $total_count): int
    {
        $d = (float)$total_count / (float)$this->getSize();
        return (int)ceil($d);
    }

    public function getHasMore(int $total_count): bool
    {
        return (float)$this->getPage() < (float)((float)$total_count / (float)$this->getSize());
    }

    public function toPaginationResponse(int $total_count, mixed $data): array
    {
        return [
            PaginationConstants::TOTAL_COUNT => $total_count,
            PaginationConstants::TOTAL_PAGES => $this->getTotalPages($total_count),
            PaginationConstants::PAGE => $this->getPage(),
            PaginationConstants::SIZE => $this->getSize(),
            PaginationConstants::HAS_MORE => $this->getHasMore($total_count),
            PaginationConstants::DATA_LIST => $data
        ];
    }
}
