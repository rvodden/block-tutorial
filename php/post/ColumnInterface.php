<?php

namespace uk\org\brentso\orchestra_manager\post;

interface ColumnInterface {
    public function manageColumns($column);
    public function manageCustomColumn($column, $postId);
}
