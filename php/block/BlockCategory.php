<?php

namespace uk\org\brentso\orchestra_manager\block;

use uk\org\brentso\orchestra_manager\trait\{AutoSlug};

class BlockCategory {

	use AutoSlug;

	protected string $title;

	public function __construct() {
		add_filter('block_categories_all', [$this, 'filterCategories']);
	}

	public function setTitle(string $title) : Self {
		$this->title = $title;
		return $this;
	}

	public function getTitle() : string {
		return $this->title;
	}

	public function filterCategories( $categories ) {
		$categories[] = array (
			'slug' => $this->getSlug(),
			'title' => $this->getTitle()
		);
		return $categories;
	}
}
