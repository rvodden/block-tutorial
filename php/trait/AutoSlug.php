<?php

namespace uk\org\brentso\orchestra_manager\trait;

trait AutoSlug
{
	protected string $slug;
	abstract function getTitle() : string;

	public function getSlug() : string {
		if ( isset($this->slug)) { return $this->slug; };
		return $this->convertFromCamelCaseToKebabCase($this->title);
	}

	public function setSlug(string $slug) : Self {
		$this->slug = $slug;
		return $this;
	}

    private static function convertFromCamelCaseToKebabCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = strtoupper($match) ? strtolower($match) : lcfirst($match) == $match;
        }
        return implode('-', $ret);
    }
}
