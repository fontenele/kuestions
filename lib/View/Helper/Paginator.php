<?php

namespace Kuestions\Lib\View\Helper;

class Paginator {

    public $rowPerPage = 15;
    public $active = 1;
    public $totalItems = 0;
    public $totalPages = 0;
    public $numItems = 5;
    public $rows = array();
    public $items = array();
    public $formSelector = 'form';
    public $criteria = array();
    public $left = '<a>&laquo;</a>';
    public $right = '<a>&raquo;</a>';

    public function __construct($formSelector = 'form', $criteria = array()) {
        $this->rowsPerPage = \Kuestions\System::$config['system']['view']['datagrid']['rowsPerPage'];
        $this->formSelector = $formSelector;

        $this->criteria = $criteria;

        if (isset($criteria['p'])) {
            $this->active = $criteria['p'];
            unset($this->criteria['p']);
        }
    }

    public function getInputHidden() {
        $html = <<<HTML
                <input type="hidden" name="p" value="{$this->active}" />
HTML;
        return $html;
    }

    public function setRows($rows = array()) {
        $this->rows = $rows;
        if (count($rows) && isset($rows[0]) && isset($rows[0]['total'])) {
            $this->totalItems = $rows[0]['total'];

            $totalPages = round($this->totalItems / $this->rowPerPage);
            if ($totalPages * $this->rowPerPage < $this->totalItems) {
                $totalPages++;
            }
            $this->totalPages = $totalPages;

            $activeMiddle = $this->numItems % 2;

            $itemsPerSide = floor($this->numItems / 2);
            if ($activeMiddle && $this->active - $itemsPerSide > 0 && $this->active + $itemsPerSide <= $this->totalPages) {
                for ($i = $itemsPerSide; $i > 0; $i--) {
                    $this->items[] = $this->active - $i;
                }
                $this->items[] = $this->active;
                for ($i = 1; $i <= $itemsPerSide; $i++) {
                    $this->items[] = $this->active + $i;
                }
            } else {
                $itemsAdded = 0;
                if ($this->active - $itemsPerSide <= 0) {
                    $i = $this->active - $itemsPerSide;
                    while ($i <= $this->active) {
                        if ($i > 0 && $itemsAdded < $this->numItems) {
                            $this->items[] = $i;
                            $itemsAdded++;
                        }
                        $i++;
                    }
                    while ($itemsAdded < $this->numItems) {
                        $this->items[] = $i;
                        $itemsAdded++;
                        $i++;
                    }
                } else {
                    $i = $this->active + $itemsPerSide;

                    while ($i >= $this->active) {
                        if ($i >= $this->active && $i <= $this->totalPages && $itemsAdded < $this->numItems) {
                            $this->items[] = $i;
                            $itemsAdded++;
                        }
                        $i--;
                    }
                    while ($itemsAdded < $this->numItems) {
                        $this->items[] = $i;
                        $itemsAdded++;
                        $i--;
                    }

                    $this->items = \array_reverse($this->items);
                }
            }
        }
    }

    public function __toString() {
        $html = <<<HTML
                <ul class="pagination">
                    %s
                </ul>
HTML;
        $items = '';

        $leftDisabled = $this->active <= 1 ? "class='disabled'" : '';
        $items.= "<li {$leftDisabled}>{$this->left}</li>";

        foreach ($this->items as $page) {
            $active = $page == $this->active ? "class='active'" : '';
            $items.= "<li {$active}><a data-form='{$this->formSelector}'>{$page}</a></li>";
        }

        $rightDisabled = $this->active == $this->totalPages ? "class='disabled'" : '';
        $items.= "<li {$rightDisabled}>{$this->right}</li>";

        return sprintf($html, $items);
    }

}

/*
<li class="disabled">{$this->left}</li>
<li class="active"><a>1</a></li>
<li><a>2</a></li>
<li><a>3</a></li>
<li><a>4</a></li>
<li><a>5</a></li>
<li>{$this->left}</li>
 */