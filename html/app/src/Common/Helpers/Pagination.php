<?php

namespace app\src\Common\Helpers;

use app\src\Common\Constants\HttpConstants;

class Pagination
{
    private int $size = 10;

    private int $page = 1;

    private int $total_count = 0;

    public function __construct(?string $size = "10", ?string $page = "1")
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

    public function toPaginationResponse(mixed $data): array
    {
        return [
            HttpConstants::TOTAL_COUNT => $this->total_count,
            HttpConstants::TOTAL_PAGES => $this->getTotalPages($this->total_count),
            HttpConstants::PAGE => $this->getPage(),
            HttpConstants::SIZE => $this->getSize(),
            HttpConstants::HAS_MORE => $this->getHasMore($this->total_count),
            HttpConstants::DATA_LIST => $data
        ];
    }

    public function getTotal_count(): int
    {
        return $this->total_count;
    }

    public function setTotal_count(int $value)
    {
        $this->total_count = $value;
    }
}
