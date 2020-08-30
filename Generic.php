<?php

class Generic
{
    const ROW = 'row';
    const FORM = 'form';
    const INPUT = 'input';
    const LABEL = 'label';
    const ERRORS = 'errors';
    const TYPE_FORM = 'form';
    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';
    const TYPE_SUBMIT = 'submit';
    const TYPE_SELECT = 'select';
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const DEFAULT_TYPE = self::TYPE_TEXT;
    const DEFAULT_WRAPPER = 'div';

    protected string $name;
    protected $type;
    protected string $label;
    protected array $attributes;
    protected array $wrappers;
    protected array $errors;
    protected string $pattern = '<input name="%s" type="%s" %s>';

    public function __construct(string $name, $type = null, string $label = '', array $attributes = [], array $wrappers = [], array $errors = [])
    {
        $this->name = $name;
        if ($type instanceof Generic) {
            $this->type = $type->getType();
            $this->errors = $type->getErrorsArray();
            $this->label = $type->getLabelValue();
            $this->attributes = $type->getAttributes();
            $this->wrappers = $type->getWrappers();
        } else {
            $this->type = $type ?? self::DEFAULT_TYPE;
            $this->label = $label;
            $this->attributes = $attributes;
            $this->errors = $errors;
            if ($wrappers) {
                $this->wrappers = $wrappers;
            } else {
                $this->wrappers[self::INPUT]['type'] = self::DEFAULT_WRAPPER;
                $this->wrappers[self::LABEL]['type'] = self::DEFAULT_WRAPPER;
                $this->wrappers[self::ERRORS]['type'] = self::DEFAULT_WRAPPER;
            }
        }
        $this->attributes['id'] = $this->name;
    }

    public function render()
    {
        return ($this->getLabel() . $this->getInputWithWrapper() . $this->getErrors());
    }

    protected function getLabel()
    {
        return sprintf($this->getWrapperPattern(self::LABEL), $this->label);
    }

    protected function getInputOnly()
    {
        return sprintf($this->pattern, $this->name, $this->type, $this->getAttribs());
    }

    protected function getInputWithWrapper()
    {
        return sprintf($this->getWrapperPattern(self::INPUT), $this->getInputOnly());
    }

    protected function getErrors()
    {
        $html = '';
        if (!isset($this->errors[$this->name]) || empty($this->errors[$this->name])) {
            return $html;
        }
        foreach ($this->errors[$this->name] as $error)
        {
            $html .= sprintf($this->getWrapperPattern(self::ERRORS), $error);
        }
        return $html;
    }

    protected function camelCase(string $string)
    {
        $ucw = ucwords($string);
        $ucw = str_replace(' ', '', $string);
        $camelcase = strtolower($ucw[0]) . substr($string, 1);
        return $camelcase;
    }

    protected function getWrapperPattern($element)
    {
        $type = $this->wrappers[$element]['type'];
        $wrapper = "<{$type}";
        foreach ($this->wrappers[$element] as $key => $value)
        {
            if ($key !== 'type') {
                $wrapper .= " {$key}=\"{$value}\"";
            }
        }
        $wrapper .= ">%s</{$type}>";
        return $wrapper;
    }

    protected function getAttribs()
    {
        $attribs = '';
        foreach ($this->attributes as $key => $value)
        {
            $key = strtolower($key);
            if ($value) {
                if ($key === 'value') {
                    if (is_array($value)) {
                        foreach ($value as $i => $v) {
                            $value[$i] = htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        }
                    } else {
                        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                }
                if ($key === 'href')
                {
                    $value = urlencode($value);
                }
                $attribs .= "{$key}=\"{$value}\" ";
            } else {
                $attribs .= "{$key} ";
            }
        }
        return trim($attribs);
    }

    public function setSingleAttribute(string $key, string $value)
    {
        if (isset($this->attributes[$key])) {
            if ($key === 'class') {
                $this->attributes[$key] .= " {$value}";
            }
        }
        $this->attributes[$key] = $value;
    }

    public function setSingleError(string $error)
    {
        $this->errors[$this->name] = $error;
    }

    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getLabelValue()
    {
        return $this->lable;
    }

    public function setLabelValue(string $lable)
    {
        $this->lable = $label;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getErrorsArray()
    {
        return $this->errors;
    }

    public function setErrorsArray(array $errors)
    {
        $this->errors = $errors;
    }

    public function getWrappers()
    {
        return $this->wrappers;
    }

    public function setWrappers(array $wrappers)
    {
        $this->wrappers = $wrappers;
    }
}

// $wrapper = [
//     Generic::LABEL => ['type' => 'label'],
//     Generic::INPUT => ['type' => 'div'],
//     Generic::ERRORS => ['type' => 'div', 'class' => 'invalid-feedback']
//     ];
    
// $errors = [
//     'firstname' => ['this field is required.'],
//     'email' => ['email is invalid.']
//     ];
    
// $field = new Generic('firstname', Generic::TYPE_TEXT, 'Firstname', ['class' => 'form-control'], $wrapper, $errors);

// echo $field->render();