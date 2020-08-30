<?php

class Radio extends Generic
{
    const DEFAULT_AFTER = true;
    const DEFAULT_OPTION_KEY = 'Choose';
    const DEFAULT_OPTION_VALUE = '0';
    const DEFAULT_SPACER = '&nbps;';
    
    protected bool $after = self::DEFAULT_AFTER;
    protected string $spacer = self::DEFAULT_SPACER;
    protected array $options = [];
    protected $selectedKey = self::DEFAULT_OPTION_VALUE;

    public function setOptions(array $options, $selectedKey = self::DEFAULT_OPTION_VALUE, string $spacer = self::DEFAULT_SPACER, bool $after = self::DEFAULT_AFTER)
    {
        $this->options = $options;
        $this->selectedKey = $selectedKey;
        $this->after = $after;
        $this->spacer = $spacer;
    }

    public function getInputOnly()
    {
        $count = 1;
        $baseId = $this->attributes['id'];
        foreach ($this->options as $label => $value)
        {
            $this->attributes['id'] = $baseId . $count++;
            $this->attributes['value'] = $value;
            if ($this->selectedKey == $value) {
                $this->attributes['checked'] = '';
            } elseif (isset($this->attributes['checked'])) {
                unset($this->attributes['checked']);
            }
            if ($this->after) {
                $html = parent::getInputOnly() . $label;
            } else {
                $html = $label . parent::getInputOnly();
            }
            $output .= $html . $this->spacer;
        }
        return $output;
    }
}