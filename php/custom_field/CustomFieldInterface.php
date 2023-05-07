<?php

namespace uk\org\brentso\orchestra_manager\custom_field;

use uk\org\brentso\orchestra_manager\post\{PostType};

interface CustomFieldInterface {

	public function create(PostType $postType);
    // public function display(\WP_Post $post);

    public function save($postId, \WP_Post $post);

    public function getName();
    public function getTitle();
    public function getDisplayInAdminTables() : bool;

    public function addColumnHeader(array $columns);
    public function generateColumnContent(string $columnName, int $postId);

}
