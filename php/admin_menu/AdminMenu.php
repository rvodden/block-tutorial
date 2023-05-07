<?php

namespace uk\org\brentso\orchestra_manager\admin_menu;

use uk\org\brentso\orchestra_manager\admin_menu;

class AdminMenu
{
    protected $loader;

    protected string $pageTitle;
    protected string $menuTitle;
    protected string $capability;
    protected string $menuSlug;
    protected string $callback = '';
    protected string $iconUrl = '';
    protected ?int $position = null;

    public function __construct() {
        add_action('admin_menu', [$this, 'addMenu']);
    }

	public function setPageTitle(string $pageTitle) : Self {
		$this->pageTitle = $pageTitle;
		return $this;
	}

	public function setMenuTitle(string $menuTitle) : Self {
		$this->menuTitle = $menuTitle;
		return $this;
	}

	public function setCapability(string $capability) : Self {
		$this->capability = $capability;
		return $this;
	}

	public function getMenuTitle() : string {
		return $this->menuTitle;
	}

	public function setIconUrl(string $iconUrl ) : Self {
		$this->iconUrl = $iconUrl;
		return $this;
	}

	public function setSlug(string $slug) : Self {
		$this->menuSlug = $slug;
		return $this;
	}

    public function getSlug() : string {
		if ( isset($this->menu_slug) ) { return $this->menu_slug; };
		return strtolower($this->getMenuTitle());
    }

	public function setPosition(int $position) : Self {
		$this->position = $postion;
		return $this;
	}

    public function addMenu() : void {
        add_menu_page(
            $this->pageTitle,
            $this->menuTitle,
            $this->capability,
            $this->getSlug(),
            $this->callback,
            $this->iconUrl,
            $this->position,
        );
    }

}
