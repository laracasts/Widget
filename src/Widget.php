<?php

namespace Laracasts\Widget;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Illuminate\Support\Str;

abstract class Widget
{
    /**
     * @return string
     */
    public function viewName()
    {
        return Str::kebab(class_basename($this));
    }

    /**
     * Load the view for the widget.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('widgets.' . $this->viewName());
    }

    /**
     * Build the view data.
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function viewData()
    {
        $viewData = [];

        foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $viewData[$property->getName()] = $property->getValue($this);
        }

        foreach ((new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (! in_array($name = $method->getName(), ['view', 'render', '__toString'])) {
                $viewData[$name] = $method->getClosure($this);
            }
        }

        return $viewData;
    }

    public static function render()
    {
        return new static;
    }

    public function __toString()
    {
        return $this->view()->with($this->viewData())->__toString();
    }
}
