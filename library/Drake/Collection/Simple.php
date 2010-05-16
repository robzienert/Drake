<?php
class Drake_Collection_Simple
implements Drake_Collection_Interface,
           Iterator,
           Countable
{
    protected $_elements = array();

    protected $_indexPosition = 0;

    protected $_indexCount = 0;

    public function __construct($elements = array())
    {
        foreach ($elements as $element) {
            $this->append($element);
        }
    }

    public function append($element)
    {
        $this->_elements[] = $element;
    }

    public function appendAll(Drake_Collection_Interface $collection)
    {
        while ($collection->hasNext()) {
            $this->add($collection->next());
        }
    }

    public function clear()
    {
        foreach ($this->_elements as $key => $value) {
            unset($this->_elements[$key]);
        }
        $this->reindex();
    }

    public function contains($object)
    {
        return in_array($object, $this->_elements);
    }

    public function containsAll(Drake_Collection_Interface $collection)
    {
        while($collection->hasNext()) {
            $element = $collection->next();
            if (!$this->contains($element)) {
                return false;
            }
        }
        return true;
    }

    private function find($object)
    {
        return array_search($object, $this->_elements, true);
    }

    public function isEmpty()
    {
        return count($this->_elements) > 0;
    }

    private function reindex()
    {
        $this->_elements = $this->toArray();
    }

    public function remove($object)
    {
        if ($index = $this->find($object)) {
            unset($this->_elements[$index]);
            $this->reindex();
        }
    }

    public function removeAll(Drake_Collection_Interface $collection)
    {
        while($collection->hasNext()) {
            $element = $collection->next();
            if ($this->contains($element)) {
                $this->remove($element);
            }
        }
    }

    public function retainAll(Drake_Collection_Interface $collection)
    {
        $newElements = array();
        while($collection->hasNext()) {
            $element = $collection->next();
            if ($this->contains($element)) {
                $newElements[] = $element;
            }
        }
        $this->_elements = $newElements;
    }

    public function rewind()
    {
        $this->_indexPosition = 0;
    }

    public function current()
    {
        if ($this->valid()) {
            return $this->_elements[$this->_indexPosition];
        }
        throw new Drake_Collection_OutOfBoundsException(
            "No element exists at index '{$this->_indexPosition}'");
    }

    public function key()
    {
        return $this->_indexPosition;
    }

    public function next()
    {
        ++$this->_indexPosition;
    }

    public function valid()
    {
        return isset($this->_elements[$this->_indexPosition]);
    }

    public function count()
    {
        return count($this->_elements);
    }

    public function toArray()
    {
        return array_values($this->_elements);
    }

}