<?php

class Radio extends Generic
{
    const DEFAULT_AFTER = true;
    const DEFAULT_SPACER = '&nbps;';
    
    protected bool $after = self::DEFAULT_AFTER;
    protected string $spacer = self::DEFAULT_SPACER;
    protected array $options = [];
    protected array $selectedKeys = [];

    public function setOptions(array $options, array $selectedKeys = [], string $spacer = self::DEFAULT_SPACER, bool $after = self::DEFAULT_AFTER)
    {
        $this->options = $options;
        $this->selectedKeys = $selectedKeys;
        $this->attributes['name'] .= '[]';
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
            if (in_array($value, $this->selectedKeys)) {
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