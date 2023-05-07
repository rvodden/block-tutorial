<?php

namespace uk\org\brentso\orchestra_manager\post;

class PostLabels {
    protected string $singularName;
    protected string $pluralName;

    public function getPluralName() : string {
        if (isset($this->pluralName)) { return $this->pluralName; };
        return $this->getSingularName() . "s";
    }

    public function getName() : string {
        return $this->getPluralName();
    }

    public function setPluralName(string $pluralName) : PostLabels {
        $this->pluralName = $pluralName;
        return $this;
    }

    public function getSingularName() : string {
        return $this->singularName;
    }

    public function setSingularName(string $singularName) : PostLabels {
        $this->singularName = $singularName;
        return $this;
    }

    public function getLabelArray() : array {
        $singularName = $this->getSingularName();
        $pluralName = $this->getPluralName();
        return array(
            'name' => $pluralName,
            'singularName' => $singularName,
            'add_new_item' => "Add New " . $singularName,
            'update_item' => 'Update ' . $singularName,
            'edit_item' => 'Edit ' . $singularName,
            'new_item' => 'New ' . $singularName,
            'view_item' => 'View ' . $singularName,
            'view_items' => 'View ' . $pluralName,
            'search_items' => 'Search ' . $pluralName,
            'not_found' => "No " . $pluralName . " found.",
            'not_found_in_trash' => "No " . $pluralName . " found in trash.",
            'all_items' => 'All ' . $pluralName,
            'archives' => $singularName . " Archives.",
            'attributes' => $singularName . " Attributes",
            'insert_into_item' => "Insert into " . $singularName,
            'uploaded_to_this_item' => "Uploaded to this " . $singularName,
            'fiter_items_list' => "Filter " . $pluralName . " List",
            'items_list_navigation' => $pluralName . " List Navigation",
            'items_list' => $pluralName . "List",
            'item_published' => $singularName. " Published",
            'item_published_privately' => $singularName. " Published Privately",
            'item_reverted_to_draft' => $singularName . " reverted to draft",
            'item_scheduled' => $singularName . " scheduled",
            'item_updated' => $singularName . " updated",
            'item_link' => $singularName . " Link",
            'item_link_description' => "A link to a " . $singularName,
        );
    }
}
