<?php

namespace uk\org\brentso\orchestra_manager\trait;

use uk\org\brentso\orchestra_manager\admin_menu\{AdminMenu};

trait AddableToAdminMenu
{
	protected ?AdminMenu $adminMenu = NULL;
	protected bool $showInMenu = false;

	abstract function getTitle() : string;
	abstract function getSlug() : string;

	public function setAdminMenu(AdminMenu $adminMenu) : Self
	{
        $this->adminMenu = $adminMenu;
        $this->showInMenu = true;
        return $this;
    }

    public function setShowInMenu(bool $showInMenu) : Self
	{
        $this->showInMenu = $showInMenu;
        return $this;
    }

	public function registerSubMenu() : void
	{
		add_action('admin_menu', function(): void {
			if ($this->showInMenu && isset($this->adminMenu)) {
				add_submenu_page(
					$this->adminMenu->getSlug(),
					$this->getTitle(),
					$this->getTitle(),
					'read', // TODO: fix this
					'edit-tags.php?taxonomy=' . $this->getSlug()
				);
			}
		});

		add_action( 'parent_file', function ($parentFile) : string {
			global $current_screen;

			$taxonomy = $current_screen->taxonomy;
			if ($taxonomy == $this->getSlug()) {
				$parentFile = $this->adminMenu->getSlug();
			}

			return $parentFile;
		});
	}

	protected function setAdminMenuArgs(array $args) : array
	{
        if (isset($this->showInMenu)) {
            if ($this->showInMenu && isset($this->adminMenu)) {
                $args['show_in_menu'] = $this->adminMenu->getSlug();
			} else {
                $args['show_in_menu'] = $this->showInMenu;
			}
        }
		return $args;
	}

}
