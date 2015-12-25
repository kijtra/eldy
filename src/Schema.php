<?php
namespace Eldy;

class Schema
{
    private $data = array();
    private $container = null;

    public function __construct($data = array())
    {
        $this->data = $data;
    }

    public function __call($method, $args)
    {
        if ($args[0] instanceof \Closure) {
            $values = array(
                '@context' => 'http://schema.org',
                '@type' => $method
            );

            $this->container = new Container($values, $this->data);
            $args[0] = $args[0]->bindTo($this->container);
            $args[0]();
            return $this->container;
        } elseif('data' === strtolower($method)) {
            if (!empty($args[0])) {
                if (count($args) > 1) {
                    return $this->data[$args[0]] = $args[1];
                } else if (array_key_exists($args[0], $this->data)) {
                    return $this->data[$args[0]];
                }
            } else {
                return $this->data;
            }
        }
    }

    public function __invoke()
    {
        return $this->print();
    }

    private function toArray($object)
    {
        $data = array();
        foreach($object as $key => $val) {
            if (is_object($val)) {
                $data[$key] = $this->toArray($val);
            } else {
                $data[$key] = $val;
            }
        }

        return $data;
    }

    public function print()
    {
        if (empty($this->container)) {
            return;
        }

        $data = $this->toArray($this->container);
        if (!empty($data)) {
            $html = '<script type="application/ld+json">';
            $html .= json_encode($data, JSON_UNESCAPED_SLASHES);
            $html .= '</script>'.PHP_EOL;
            return $html;
        }
    }

    public function pretty()
    {
        if (empty($this->container)) {
            return;
        }

        $data = $this->toArray($this->container);
        if (!empty($data)) {
            $html = '<script type="application/ld+json">'.PHP_EOL;
            $html .= json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $html .= PHP_EOL.'</script>'.PHP_EOL;
            return $html;
        }
    }
}
