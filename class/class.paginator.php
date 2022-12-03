<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH                                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2016 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

class JAK_Paginator{

	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $high;
	var $limit;
	var $return;
	var $items_per_page;
	var $jak_get_page;
	var $jak_where;
	var $jak_prevstyle = 'prev-button';
	var $jak_nextstyle = 'next-button';
	var $jak_prevtext = '&laquo;';
	var $jak_nexttext = '&raquo;';

	public function __construct()
	{
		$this->current_page = 1;
		$this->mid_range = 3;
	}

	public function paginate()
	{
		$this->num_pages = ceil($this->items_total/$this->items_per_page);
		$this->current_page = (int) $this->jak_get_page; // must be numeric > 0
		if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;

		if($this->num_pages > 1) {
			
			$this->return = ($this->current_page != 1 And $this->items_total >= 2) ? ' <nav aria-label="Page navigation"><ul class="pagination"><li class="page-item"><a class="page-link '.$this->jak_prevstyle.'" href="'.$this->jak_where.JAK_rewrite::jakParseurlpaginate($prev_page).'">'.$this->jak_prevtext.'</a></li>' : '<nav aria-label="Page navigation"><ul class="pagination">';

			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);
			
			for($i=1;$i<=$this->num_pages;$i++)
			{
				// loop through all pages. if first, last, or in range, display
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
					$this->return .= ($i == $this->current_page) ? '<li class="page-item active"><a class="page-link" title="Go to page '.$i.' of '.$this->num_pages.'" href="'.$this->jak_where.JAK_rewrite::jakParseurlpaginate($i).'">'.$i.'</a></li>' : '<li class="page-item"><a class="page-link" title="Go to page '.$i.' of '.$this->num_pages.'" href="'.$this->jak_where.JAK_rewrite::jakParseurlpaginate($i).'">'.$i.'</a></li>';
				}
			}
			$this->return .= ($this->current_page != $this->num_pages And $this->items_total >= 2) ? '<li class="page-item"><a href="'.$this->jak_where.JAK_rewrite::jakParseurlpaginate($next_page).'" class="page-link">'.$this->jak_nexttext.'</a></li></ul></nav>' : '</ul></nav>';
		}
		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = ($this->current_page * $this->items_per_page)-1;
		$this->limit = [$this->low,$this->items_per_page];
	}

	public function display_pages()
	{
		return $this->return;
	}
}
?>