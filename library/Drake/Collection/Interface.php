<?php
interface Drake_Collection_Interface {

    public function add($o);
    public function addAll($collection);
    public function clear();
    public function contains($o);
    public function containsAll($collection);
    public function isEmpty();
    public function remove($o);
    public function removeAll($collection);
    public function retainAll($collection);
    public function size();
    public function toArray();

}