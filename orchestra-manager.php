<?php

/**
 * Plugin Name:       Orchestra Manager
 * Plugin URI:        http://github.com/rvodden/orchestra-manager
 * Description:       This is a block created with the block tutorial.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.0.1
 * Author:            Richard Vodden
 * License:           MIT
 * License URI:       https://opensource.org/license/mit/
 * Text Domain:       orchestra-manager
 * Domain Path:       orchestra-manager
 *
 * @package           orchestra-manager
 */

require_once __DIR__ . '/vendor/autoload.php';

use uk\org\brentso\orchestra_manager\post\{PostType, PostLabels};
use uk\org\brentso\orchestra_manager\taxonomy\{Taxonomy, TaxonomyLabels};
use uk\org\brentso\orchestra_manager\admin_menu\{AdminMenu};
use uk\org\brentso\orchestra_manager\block\{BlockCategory};
use uk\org\brentso\orchestra_manager\custom_field\{CustomField};

/* Configure Post Types */

$orchestraManagerMenu = (new AdminMenu())
	->setPageTitle("Orchestra Manager")
	->setMenuTitle("Orchestra Manager")
	->setCapability("read");

$personPostType = (new PostType())
	->setLabels((new PostLabels)->setSingularName('Person')->setPluralName('People'))
	->setAdminMenu($orchestraManagerMenu)
	->addCustomField((new CustomField())
			->setName('firstname')
			->setTitle('First Name'))
	->addCustomField((new CustomField())
			->setName('secondname')
			->setTitle('Second Name'));

$roleTaxonomy = (new Taxonomy)
	->setLabels((new TaxonomyLabels())->setSingularName("Role"))
	->setAdminMenu($orchestraManagerMenu)
	->registerPostType($personPostType);

$piecePostType = (new PostType())
	->setLabels((new PostLabels)->setSingularName('Piece'))
	->setAdminMenu($orchestraManagerMenu)
	->addCustomField((new CustomField())
			->setName('composer')
			->setTitle('Composer')
			->setType('integer'));

$concertPostType = (new PostType())
	->setLabels((new PostLabels)->setSingularName('Concert'))
	->setAdminMenu($orchestraManagerMenu)
	->addCustomField((new CustomField())
			->setName('conductor')
			->setTitle('Conductor')
			->setType('integer'));

/* Configure Blocks */

$orchestraManagerBlockCategory = (new BlockCategory)->setTitle("Orchestra Manager");

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function orchestraManagerCreateBlock()
{
	register_block_type( __DIR__ . '/build/first-block' );
	register_block_type(__DIR__ . '/build/composer');
}
add_action('init', 'orchestraManagerCreateBlock');
