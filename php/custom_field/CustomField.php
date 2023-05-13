<?php

namespace uk\org\brentso\orchestra_manager\custom_field;

use uk\org\brentso\orchestra_manager\trait\{AutoSlug};
use uk\org\brentso\orchestra_manager\post\{PostType};

class CustomField implements CustomFieldInterface
{

	use AutoSlug;

	protected string $name;
	protected string $title;
	protected string $description;
	protected string $type = 'string';
	protected array $scope;
	protected bool $displayInAdminTables = false;

	const SUPPORTED_TYPES = ['string', 'boolean', 'integer', 'number', 'array', 'object'];

	public function getValue($post)
	{
		// TODO: this should probably consistently require the post_id
		if ($post instanceof \WP_Post) {
			$postId = $post->ID;
		} else {
			$postId = $post;
		}
		return get_post_meta($postId, $this->name, true);
	}

	public function getSlug()
	{
		return $this->getName();
	}

	public function create(PostType $postType)
	{
		error_log("Creating " . $this->getSlug() . "(" . $this->type . ") on " . $postType->getSlug());
		$args = array(
			'show_in_rest' => true,
			'single' => true,
			'type' => $this->type,
			'sanitize_callback' => 'wp_kses_post'
		);
		if (isset($this->description)) {
			$args['description'] = $this->description;
		};
		error_log(json_encode($args));

		$response = register_post_meta(
			$postType->getSlug(),
			$this->getSlug(),
			$args
		);
		if ($response) {
			error_log("Success!");
		} else {
			error_log("Failed to create post meta");
		};
	}

	public function setName(string $name)
	{
		$this->name = $name;
		return $this;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setTitle(string $title)
	{
		$this->title = $title;
		return $this;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setDescription(string $description): Self
	{
		$this->description = $description;
		return $this;
	}

	public function setType(string $type): Self
	{
		if (!in_array($type, Self::SUPPORTED_TYPES, true)) {
			throw new \Exception($type . " is not supported.");
		}
		$this->type = $type;
		return $this;
	}

	public function setDisplayInAdminTables(bool $displayInAdminTables): Self
	{
		$this->displayInAdminTables = $displayInAdminTables;
		return $this;
	}

	public function getDisplayInAdminTables(): bool
	{
		return $this->displayInAdminTables;
	}

	public function addColumnHeader($columns)
	{
		$columns[$this->name] = $this->title;
		return $columns;
	}

	public function generateColumnContent($column_name, $post_id)
	{
		if ($column_name == $this->name) {
			echo $this->getValue($post_id);
		}
	}

	public function save($postId, $post)
	{
		if (isset($_POST[$this->name])) {
			$value = $_POST[$this->name];
			update_post_meta($postId, $this->name, $value);
		} else {
			delete_post_meta($postId, $this->name);
		}
	}
}
