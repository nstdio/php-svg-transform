<?php

namespace nstdio\svg\transform;

/**
 * Interface SVGTransformList
 * This interface defines a list of SVGSVGTransform objects.
 *
 * The SVGTransformList and SVGTransform interfaces correspond to the various attributes which specify a set of
 * SVGTransformations, such as the ‘SVGTransform’ attribute which is available for many of SVG's elements.
 *
 * @property-read int $numberOfItems The number of items in the list.
 * @package nstdio\svg\SVGTransform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface SVGTransformList extends Observer, \Countable
{
    /**
     * Clears all existing current items from the list, with the result being an empty list.
     *
     * @return void
     */
    public function clear();

    /**
     * Clears all existing current items from the list and re-initializes the list to hold the single item specified by
     * the parameter. If the inserted item is already in a list, it is removed from its previous list before it is
     * inserted into this list. The inserted item is the item itself and not a copy.
     *
     * @param SVGTransform $newItem The item which should become the only member of the list.
     *
     * @return SVGTransform The item being inserted into the list.
     */
    public function initialize(SVGTransform $newItem);

    /**
     * Returns the specified item from the list. The returned item is the item itself and not a copy. Any changes made
     * to the item are immediately reflected in the list.
     *
     * @param int $index The index of the item from the list which is to be returned. The first item is number 0.
     *
     * @return SVGTransform
     */
    public function getItem($index);

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
    public function insertItemBefore(SVGTransform $newItem, $index);

    /**
     * Replaces an existing item in the list with a new item. If newItem is already in a list, it is removed from its
     * previous list before it is inserted into this list. The inserted item is the item itself and not a copy. If the
     * item is already in this list, note that the index of the item to replace is before the removal of the item.
     *
     * @param SVGTransform $newItem The item which is to be inserted into the list.
     * @param int          $index   The index of the item which is to be replaced. The first item is number 0.
     *
     * @return SVGTransform The inserted item.
     * @throws \InvalidArgumentException If $index is negative.
     */
    public function replaceItem(SVGTransform $newItem, $index);

    /**
     * Removes an existing item from the list.
     *
     * @param int $index The index of the item which is to be removed. The first item is number 0.
     *
     * @return SVGTransform The removed item.
     */
    public function removeItem($index);

    /**
     * Inserts a new item at the end of the list. If newItem is already in a list, it is removed from its previous list
     * before it is inserted into this list. The inserted item is the item itself and not a copy.
     *
     * @param SVGTransform $newItem The item which is to be inserted. The first item is number 0.
     *
     * @return SVGTransform The inserted item.
     */
    public function appendItem(SVGTransform $newItem);

    /**
     * Creates an SVGTransform object which is initialized to SVGTransform of type SVG_SVGTransform_MATRIX and whose
     * values are the given matrix. The values from the parameter matrix are copied, the matrix parameter is not
     * adopted as SVGSVGTransform::matrix.
     *
     * @param SVGMatrix $matrix The matrix which defines the SVGTransformation.
     *
     * @return SVGTransform The returned SVGTransform object.
     */
    public function createSVGTransformFromMatrix(SVGMatrix $matrix);

    /**
     * Consolidates the list of separate SVGTransform objects by multiplying the equivalent SVGTransformation matrices
     * together to result in a list consisting of a single SVGTransform object of type SVG_SVGTransform_MATRIX. The
     * consolidation operation creates new SVGTransform object as the first and only item in the list. The returned
     * item is the item itself and not a copy. Any changes made to the item are immediately reflected in the list.
     *
     * @return SVGTransform The resulting SVGTransform object which becomes single item in the list. If the list was
     *                   empty, then a value of null is returned.
     */
    public function consolidate();
}