<?php

namespace uk\org\brentso\orchestra_manager\taxonomy;

class TaxonomyLabels
{
    protected $singularName;
    protected $pluralName;

	public function setSingularName(string $singularName) : Self {
		$this->singularName = $singularName;
		return $this;
	}

    public function getSingularName() : string {
        return $this->singularName;
    }

	public function setPluralName(string $pluralName) : Self {
		$this->pluralName = $pluralName;
		return $this;
	}

	public function getPluralName() : string {
        return isset($pluralName) ? $this->pluralName : $this->singularName . "s";
    }

    public function getLabelArray() : array {
		$pluralName = $this->getPluralName();
		$singularName = $this->getSingularName();
        return array(
            'name' => $pluralName,
            'singular_name' => $singularName,
            'add_new_item' => "Add New " . $singularName,
            'edit_item' => 'Edit ' . $singularName,
            'update_item' => 'Update ' . $singularName,
            'search_items' => 'Search ' . $pluralName
        );
    }
}
