<?php

namespace uk\org\brentso\orchestra_manager\taxonomy;

use uk\org\brentso\orchestra_manager\trait\{AddableToAdminMenu};

class Taxonomy {

	use AddableToAdminMenu;

    protected $taxonomyLabels;
    protected $postTypes;

    public function __construct()
    {
        add_action('init', [$this, 'createTaxonomy']);
    }

	public function setLabels(TaxonomyLabels $taxonomyLabels) : Self
	{
		$this->taxonomyLabels = $taxonomyLabels;
		return $this;
	}

	public function getLabels() : TaxonomyLabels
	{
		return $this->taxonomyLabels;
	}

    public function getSlug() : string
	{
        return strtolower($this->getLabels()->getSingularName());
    }

	public function getTitle(): string
	{
		return $this->getLabels()->getPluralName();
	}

    public function registerPostType($postType)
	{
        $this->postTypes[] = $postType;
    }

    public function addTerm($term, $description = NULL)
	{
        $args = array();
        if (isset($description)) {
            $args['description'] = $description;
        }
        wp_insert_term($term, $this->getSlug(), $args);
    }

	public function createTaxonomy()
	{
		$postTypeSlugs = [];
		foreach ($this->postTypes as $postType) {
			$postTypeSlugs[] = $postType->getSlug();
		}
        register_taxonomy(
            $this->getSlug(),
            $postTypeSlugs,
            array(
                'hierarchical' => false,
                'labels' => $this->getLabels()->getLabelArray(),
                'show_in_rest' => true,
                'show_admin_column' => true,
                'rewrite' => array(
                    'slug' => $this->getSlug(),
                    'with_front' => false,
                    'hierarchical' => false,
                ),
                'capabilities' => array(
                    'manage_terms' => 'edit_posts',
                    'edit_terms' => 'edit_posts',
                    'delete_terms' => 'edit_posts',
                    'assign_terms' => 'edit_posts'
                )
            )
        );
		$this->registerSubMenu();
    }

}
