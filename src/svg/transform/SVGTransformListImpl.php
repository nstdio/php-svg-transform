<?php

namespace nstdio\svg\transform;

/**
 * Class SVGTransformListImpl
 *
 * @package nstdio\svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SVGTransformListImpl implements SVGTransformList
{
    /**
     * @var SVGTransform[]
     */
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Returns the specified item from the list. The returned item is the item itself and not a copy. Any changes made
     * to the item are immediately reflected in the list.
     *
     * @param int $index The index of the item from the list which is to be returned. The first item is number 0.
     *
     * @return SVGTransform
     */
    public function getItem($index)
    {
        return $this->items[$index];
    }

    /**
     * Inserts a new item into the list at the specified position. The first item is number 0. If newItem is already in
     * a list, it is removed from its previous list before it is inserted into this list. The inserted item is the item
     * itself and not a copy. If the item is already in this list, note that the index of the item to insert before is
     * before the removal of the item.
     *
     * @param SVGTransform $newItem The item which is to be inserted into the list.
     * @param int          $index   The index of the item before which the new item is to be inserted. The first item
     *                              is number 0. If the index is equal to 0, then the new item is inserted at the front
     *                              of the list. If the index is greater than or equal to numberOfItems, then the new
     *                              item is appended to the end of the list.
     *
     * @return SVGTransform The inserted item.
     */
    public function insertItemBefore(SVGTransform $newItem, $index)
    {
        $indexBefore = $this->indexBefore($index);

        $oldIndex = $this->indexOf($newItem);
        $newItem->attach($this);

        if ($indexBefore === 0) {
            array_unshift($this->items, $newItem);
        } else if ($indexBefore === $this->numberOfItems) {
            $this->items[] = $newItem;
        } else {
            array_splice($this->items, $indexBefore, 0, [$newItem]);
        }

        if ($oldIndex !== false) {
            $this->unsetKeyAndReset($oldIndex);
        }

        return $newItem;
    }

    private function indexBefore($index)
    {
        $index = intval($index);

        if ($index === 0) {
            return 0;
        }

        if ($index < 0) {
            throw new \InvalidArgumentException('$index < 0');
        }

        $numberOfItems = $this->numberOfItems;

        if ($index >= $numberOfItems) {
            $index = $numberOfItems;
        } else {
            --$index;
        }

        return $index;
    }

    private function indexOf(SVGTransform $item)
    {
        return array_search($item, $this->items, true);
    }

    private function unsetKeyAndReset($key)
    {
        unset($this->items[$key]);
        $this->items = array_values($this->items);
    }

    public function remove(SVGTransform $item)
    {
        $this->removeIfPresent($item);
    }

    private function removeIfPresent(SVGTransform $item)
    {
        if (($index = $this->indexOf($item)) !== false) {
            $this->unsetKeyAndReset($index);
        }
    }

    /**
     * Replaces an existing item in the list with a new item. If newItem is already in a list, it is removed from its
     * previous list before it is inserted into this list. The inserted item is the item itself and not a copy. If the
     * item is already in this list, note that the index of the item to replace is before the removal of the item.
     *
     * @param SVGTransform $newItem The item which is to be inserted into the list.
     * @param int          $index   The index of the item which is to be replaced. The first item is number 0.
     *
     * @return SVGTransform The inserted item.
     */
    public function replaceItem(SVGTransform $newItem, $index)
    {
        $this->checkIndex($index);

        $oldIndex = $this->indexOf($newItem);
        $newItem->attach($this);

        $this->items[$index] = $newItem;

        if ($oldIndex !== false) {
            $this->unsetKeyAndReset($oldIndex);
        }

        return $this->items[$index];
    }

    /**
     * @param $index
     */
    private function checkIndex($index)
    {
        $index = intval($index);

        if ($index >= $this->numberOfItems || $index < 0) {
            throw new \InvalidArgumentException('Invalid index.');
        }
    }

    /**
     * Removes an existing item from the list.
     *
     * @param int $index The index of the item which is to be removed. The first item is number 0.
     *
     * @return SVGTransform The removed item.
     */
    public function removeItem($index)
    {
        $this->checkIndex($index);

        $transform = $this->items[$index];

        $this->unsetKeyAndReset($index);

        return $transform;
    }

    /**
     * Inserts a new item at the end of the list. If newItem is already in a list, it is removed from its previous list
     * before it is inserted into this list. The inserted item is the item itself and not a copy.
     *
     * @param SVGTransform $newItem The item which is to be inserted. The first item is number 0.
     *
     * @return SVGTransform The inserted item.
     */
    public function appendItem(SVGTransform $newItem)
    {
        $newItem->attach($this);

        $this->items[] = $newItem;

        return $newItem;
    }

    /**
     * Creates an SVGTransform object which is initialized to SVGTransform of type SVG_TRANSFORM_MATRIX and whose
     * values are the given matrix. The values from the parameter matrix are copied, the matrix parameter is not
     * adopted as SVGTransform::matrix.
     *
     * @param SVGMatrix $matrix The matrix which defines the SVGTransformation.
     *
     * @return SVGTransform The returned SVGTransform object.
     */
    public function createSVGTransformFromMatrix(SVGMatrix $matrix)
    {
        $result = new SVGTransformImpl();
        $result->setMatrix($matrix);

        return $result;
    }

    /**
     * Consolidates the list of separate SVGTransform objects by multiplying the equivalent SVGTransformation matrices
     * together to result in a list consisting of a single SVGTransform object of type SVG_TRANSFORM_MATRIX. The
     * consolidation operation creates new SVGTransform object as the first and only item in the list. The returned
     * item is the item itself and not a copy. Any changes made to the item are immediately reflected in the list.
     *
     * @return SVGTransform The resulting SVGTransform object which becomes single item in the list. If the list was
     *                   empty, then a value of null is returned.
     */
    public function consolidate()
    {
        if ($this->numberOfItems === 0) {
            return null;
        }

        $firstItem = array_shift($this->items);
        $product = array_reduce($this->items, [$this, "reduceCallback"], $firstItem);

        return $this->initialize($product);
    }

    /**
     * Clears all existing current items from the list and re-initializes the list to hold the single item specified by
     * the parameter. If the inserted item is already in a list, it is removed from its previous list before it is
     * inserted into this list. The inserted item is the item itself and not a copy.
     *
     * @param SVGTransform $newItem The item which should become the only member of the list.
     *
     * @return SVGTransform The item being inserted into the list.
     */
    public function initialize(SVGTransform $newItem)
    {
        $this->clear();

        $newItem->attach($this);

        $this->items[] = $newItem;

        return $this->items[0];
    }

    /**
     * Clears all existing current items from the list, with the result being an empty list.
     *
     * @return void
     */
    public function clear()
    {
        unset($this->items);

        $this->items = [];
    }

    public function __get($name)
    {
        if ($name === 'numberOfItems') {
            return count($this->items);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->numberOfItems;
    }

    /**
     * @param $carry SVGTransform
     * @param $item  SVGTransform
     *
     * @return SVGTransform
     */
    private function reduceCallback($carry, $item)
    {
        $result = new SVGTransformImpl();
        $result->setMatrix($carry->matrix()->multiply($item->matrix()));

        return $result;
    }

}