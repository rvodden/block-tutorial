<?php

namespace uk\org\brentso\orchestra_manager\post;

use uk\org\brentso\orchestra_manager\trait\{AddableToAdminMenu};
use uk\org\brentso\orchestra_manager\custom_field\{CustomFieldInterface};

/**
 * Register the concert custom type for the plugin.
 *
 * TODO: write a description here if we need to
 *
 * @package    orchestra_management
 * @subpackage orchestra_management/posts
 * @author     Richard Vodden <richard@vodden.com>
 */
class PostType implements PostTypeInterface
{
	use AddableToAdminMenu;

	/**
	 * The loader that's responsible for maintaining and registering all hooks
	 * that power
	 * the plugin.
	 *
	 * @since  0.0.1
	 * @access protected
	 * @var    Loader $loader Maintains and registers all hooks
	 *      for the plugin.
	 */

	protected PostLabels $postLabels;
	protected string $description;
	protected bool $isPublic = true;
	protected bool $isHierachical = false;
	protected array $supports = array("title", "editor", "revisions", "author", "excerpt", "thumbnail");
	protected $menuIcon;

	protected array $customFields;

	const SUPPORTS = array(
		'title',
		'editor',
		'comments',
		'revisions',
		'trackbacks',
		'author',
		'excerpt',
		'page-attributes',
		'thumbnail',
		'custom-fields',
		'post-formats'
	);

	public function __construct()
	{
		$this->customFields = array();
		$this->defineHooks();
		$this->defineAdminHooks();
	}

	private function defineHooks()
	{
		add_action('init', [$this, 'createPostType']);
	}

	private function defineAdminHooks()
	{
		add_action('save_post', [$this, 'saveCustomFields'], 10, 2);
	}

	public function createPostType()
	{
		$args = array();
		$args['labels'] = $this->postLabels->getLabelArray();
		if (isset($this->isPublic)) {
			$args['public'] = $this->isPublic;
		};
		if (isset($this->isHierachical)) {
			$args['hierarchical'] = $this->isHierachical;
		};
		$args['show_ui'] = true;
		$args = $this->setAdminMenuArgs($args);
		$args['show_in_rest'] = true;
		$args['exclude_from_search'] = false;
		if (isset($this->supports)) {
			$args['supports'] = $this->supports;
		};
		$args['rewrite'] = array('slug' => $this->getSlug());

		if (isset($this->menuIcon)) {
			$args["menu_icon"] = $this->menuIcon;
		};
		error_log(json_encode($args));
		register_post_type($this->getSlug(), $args);

		foreach (array_diff(self::SUPPORTS, $this->supports) as $support) {
			remove_post_type_support($this->getSlug(), $support);
		}

		$this->createCustomFields();
	}

	public function getSlug(): string
	{
		return strtolower($this->postLabels->getSingularName());
	}

	public function getTitle(): string
	{
		return "All " . $this->getLabels()->getPluralName();
	}

	public function addCustomField(
		CustomFieldInterface $customField
	) {
		$this->addSupport('custom-fields');
		$this->customFields[] = $customField;
		$this->addCustomColumn($customField);
		return $this;
	}

	protected function addCustomColumn(
		CustomFieldInterface $customField
	) {
		if ($customField->getDisplayInAdminTables()) {
			add_action('manage_' . $this->getSlug() . '_posts_columns', $customField, 'addColumnHeader');
			add_action(
				'manage_' . $this->getSlug() . '_posts_custom_column',
				$customField,
				'generateColumnContent',
				10,
				2
			);
		}
	}

	public function createCustomFields()
	{
		foreach ($this->customFields as $customField) {
			$customField->create($this);
		}
	}

	public function saveCustomFields($postId, $post)
	{
		foreach ($this->customFields as $customField) {
			$customField->save($postId, $post);
		}
	}

	public function displayCustomFields()
	{
		global $post;
		echo '<div class="form-wrap" >';
		foreach ($this->customFields as $customField) {
			$customField->display($post);
		}
		echo '</div>';
	}

	/**
	 * An array of labels for this post type. If not set, post labels are
	 * inherited for non-hierarchical types and page labels for hierarchical
	 * ones. See get_post_type_labels() for a full list of supported labels.
	 *
	 * @param PostLabels $postLabels;
	 *  */
	public function setLabels(PostLabels $postLabels)
	{
		$this->postLabels = $postLabels;
		return $this;
	}

	public function getLabels(): PostLabels
	{
		return $this->postLabels;
	}

	public function setDescription(string $description)
	{
		$this->description = $description;
		return $this;
	}

	public function setIsPublic(bool $isPublic)
	{
		$this->isPublic = $isPublic;
		return $this;
	}

	public function addSupport(string $support)
	{
		if (!in_array($support, self::SUPPORTS, true)) {
			throw new \Exception($support . "is not supported.");
		}

		if (!in_array($support, $this->supports, true)) {
			$this->supports[] = $support;
		}
		return $this;
	}

	public function supports(string $support): bool
	{
		$position = array_search($support, $this->supports);
		return $position !== false;
	}

	public function removeSupport(string $support)
	{
		$position = array_search($support, $this->supports);
		if ($position !== false) {
			unset($this->supports[$position]);
			$this->supports = array_values($this->supports);
		}
		return $this;
	}

	public function getAdminColumns(): array
	{
		$columns = array();

		if ($this->supports('title')) {
			$columns[] = 'title';
		}

		foreach ($this->customFields as $customField) {
			if ($customField->getDisplayInAdminTables()) {
				$columns[] = $customField;
			}
		}

		return $columns;
	}
}
