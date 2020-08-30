<?php

class Select extends Generic
{

    protected array $options = [];
    protected array $selectedKey = [];

    public function setOptions(array $options, array $selectedKeys = [])
    {
        $this->options = $options;
        $this->name .= '[]';
        $this->selectedKeys = $selectedKeys;
    }

    public function getSelect()
    {
        $this->pattern = '<select name="%s" %s>' . PHP_EOL;
        return sprintf($this->pattern, $this->name, $this->getAttribs());
    }

    public function getOptions()
    {
        $output = '';
        foreach ($this->options as $label => $value)
        {
            $output .= '<option value="' . $value . '"';
            if (in_array($value, $this->selectedKeys))
            {
                $output .= ' selected';
            }
            $output .= '>' . $label . "</option>";
        }
        return $output;
    }

    public function getInputOnly()
    {
        return ($this->getSelect . $this->getOptions . '</select>');
    }
}