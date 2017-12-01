<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R_Tree
{
	private $maxEntries;
	private $minEntries;
	private $numDims;
	private $pointDims;
	private $seedPicker;
	private $root;
	private $size;

		/**
		 * Creates a new RTree.
		 *
		 * @param maxEntries maximum number of entries per node
		 * @param minEntries minimum number of entries per node (except for the root
		 * node)
		 * @param numDims the number of dimensions of the RTree.
		 */
		public function RTree($maxEntries, $minEntries, $numDims, $seedPicker) {
			assert ($minEntries <= ($maxEntries / 2));
			$this->numDims = $numDims;
			$this->maxEntries = $maxEntries;
			$this->minEntries = $minEntries;
			$this->seedPicker = $seedPicker;
			for($i = 0; $i < $numDims; $i++){
				$this->pointDims[$i] = 0;
			}
			$this->root = $this->buildRoot(true);
			echo "<pre>";
			print_r($this->pointDims);
			echo "</pre>";
			
		}

		public function RTree2($maxEntries, $minEntries, $numDims) {
			$this->RTree($maxEntries, $minEntries, $numDims, 'LINEAR');
		}

		
		private function buildRoot($asLeaf) {
			$initCoords = array();
			$initDimensions = array();
			for ($i = 0; $i < $this->numDims; $i++) {
				$initCoords[$i] = sqrt(INF);
				$initDimensions[$i] = -2 * sqrt(INF);

			}

			return new Node($initCoords, $initDimensions, $asLeaf);

		}

		/**
		 * Builds a new RTree using default parameters: maximum 50 entries per node
		 * minimum 2 entries per node 2 dimensions
		 */
		public function getR() {
			return $this->root;
		}

		/**
		 * @return the maximum number of entries per node
		 */
		public function getMaxEntries() {
			return $this->maxEntries;
		}

		/**
		 * @return the minimum number of entries per node for all nodes except the
		 * root.
		 */
		public function getMinEntries() {
			return $minEntries;
		}

		/**
		 * @return the number of dimensions of the tree
		 */
		public function getNumDims() {
			return $numDims;
		}

		/**
		 * @return the number of items in $this tree.
		 */
		public function size() {
			return $size;
		}

		public function insert($coords, $dimensions, $entry)
		{
			assert(count($coords) == $this->numDims);
			assert(count($dimensions) == $this->numDims);

			$e = new Entry($coords, $dimensions, $entry);

			$l = $this->chooseLeaf($this->root, $e);

			$l->addChildren($e);
			$this->size++;
			$e->parent = $l;
			if (count($l->getChildren()) > $this->maxEntries)
			{
				$splits = $this->splitNode($l);
				$this->adjustTree($splits[0], $splits[1]);
			}
			else
			{
				$this->adjustTree($l, null);
			}

		}

		private function chooseLeaf($n, $e)
		{
			if ($n->getLeaf())
			{
				return $n;
			}
			$minInc = INF;
			$next = null;
			foreach ($n->children AS $c)
			{
				$inc = $this->getRequiredExpansion($c->coords, $c->dimensions, $e);
				if ($inc < $minInc)
				{
					$minInc = $inc;
					$next = $c;
				}
				else if ($inc == $minInc)
				{
					$curArea = 1.0;
					$thisArea = 1.0;
					for($i = 0; $i < count($c->dimensions); $i++)
					{
						$curArea *= $next->dimensions[$i];
						$thisArea *= $c->dimensions[$i];
					}
					if ($thisArea < $curArea)
					{
						$next = $c;
					}
				}
			}
			return chooseLeaf(next, e);
		}

		/**
		* Returns the increase in area necessary for the given rectangle to cover the
		* given entry.
		*/
		private function getRequiredExpansion($coords, $dimensions, $e)
		{
			$area = $this->getArea($dimensions);
			$deltas[count($dimensions)];
			for ($i = 0; $i < count($deltas); $i++)
			{
				if ($coords[$i] + $dimensions[$i] < $e->coords[$i] + $e->dimensions[$i])
				{
					$deltas[$i] = $e->coords[$i] + $e->dimensions[$i] - $coords[$i] - $dimensions[$i];
				}
				else if ($coords[$i] + $dimensions[$i] > $e->coords[$i] + $e->dimensions[$i])
				{
					$deltas[$i] = $coords[$i] - $e->coords[$i];
				}
			}
			$expanded = 1.0;
			for ($i = 0; $i < count($dimensions); $i++)
			{
				$expanded *= $dimensions[$i] + $deltas[$i];
			}
			return ($expanded - $area);
		}

		private function getArea($dimensions)
		{
			$area = 1.0;
			for($i = 0; $i < count($dimensions); $i++)
			{
				$area *= $dimensions[$i];
			}

			return $area;
		}

		private function adjustTree($n, $nn) //aman
		{
			if ($n == $this->root)
			{
				if ($nn != null)
				{
					$root = buildRoot(false);
					array_push($root->children, $n);
					$n->parent = $root;
					array_push($root->children, $nn);
					$nn->parent = $root;
				}
				$this->tighten($this->root);
				return;
			}

			$this->tighten($n);
			if ($nn != null)
			{
				$this->tighten($nn);
				if (count($n->parent->children) > $maxEntries)
				{
					$splits = $this->splitNode($n->parent);
					$this->adjustTree($splits[0], $splits[1]);
				}
			}
			if ($n->parent != null)
			{
				$this->adjustTree($n->parent, null);
			}
		}

		private function tighten($nodes)
		{

			assert(count($nodes->getChildren()) > 0);
			for ($i = 0; $i < $this->numDims; $i++)
			{
				$minCoords[$i] = INF;
				$maxCoords[$i] = -1.0 * INF;

				foreach ($nodes->getChildren() AS $c)
				{
		// we may have bulk-added a bunch of children to a node (eg. in
		// splitNode)
		// so here we just enforce the child->parent relationship.
					$c->parent = $nodes;
					if ($c->getCoordsWithKey($i) < $minCoords[$i])
					{
						$minCoords[$i] = $c->getCoordsWithKey($i);
					}
					if (($c->getCoordsWithKey($i) + $c->getDimensionsWithKey($i)) > $maxCoords[$i])
					{
						$maxCoords[$i] = ($c->getCoordsWithKey($i) + $c->getDimensionsWithKey($i));
					}
				}
			}
			for ($i = 0; $i < $this->numDims; $i++)
			{
				// Convert max coords to dimensions
				$maxCoords[$i] -= $minCoords[$i];
			}
			$nodes->setCoords($minCoords);
			$nodes->setDimensions($maxCoords);
		}

		/**
		 * Searches the RTree for objects overlapping with the given rectangle.
		 *
		 * @param coords the corner of the rectangle that is the lower bound of
		 * every dimension (eg. the top-left corner)
		 * @param dimensions the dimensions of the rectangle.
		 * @return a list of objects whose rectangles overlap with the given
		 * rectangle.
		 */
		public function search($coords, $dimensions) {
			assert(count($coords) == $this->numDims);
			assert(count($dimensions) == $this->numDims);
			$results = array();
			$results = $this->getSearch($coords, $dimensions, $this->root, $results);
			return $results;
		}

		private function getSearch($coords, $dimensions, $n, $results) {
			if ($n->getLeaf()) {
				foreach($n->getChildren() AS $e) {
					if($this->isOverlap($coords, $dimensions, $e->getCoords(), $e->getDimensions())) {
						array_push($results, $e->entry);
					}
				}
			} else {
				foreach($n->children AS $c) {
					if (isOverlap($coords, $dimensions, $c->coords, $c->dimensions)) {
						$this->getSearch($coords, $dimensions, $c, $results);
					}
				}
			}
			return $results;
		}


		private function splitNode($n)
		{    
			$nn = array();
			array_push($nn, $n);
			$obj = new Node($n->coords, $n->dimensions, $n->leaf);
			array_push($nn, $obj);

			$nn[1]->parent = $n->parent;
			if ($nn[1]->parent != null)
			{
				array_push($nn[1]->parent->children, $nn[1]);
			}
			
			$cc = $n->children;
			unset($n->children);

			$ss = $seedPicker;
			$this->lPickSeeds($cc);

			array_push($nn[0]->children, $ss[0]);
			array_push($nn[1]->children, $ss[1]);

			$this->tighten($nn);
			while (!empty($cc))
			{
				if ((count($nn[0]->children) >= $minEntries) && (count($nn[1]->children) + count($cc) == $minEntries))
				{
					array_push($nn[1]->children, $cc);
					unset($cc);
					$this->tighten($nn); // Not sure this is required.
					return $nn;
				}
			// 	else if ((count($nn[1]->children) >= $minEntries) && (count($nn[0]->children) + count($cc) == $minEntries))
			// 	{
			// 		nn[0].children.addAll(cc);
			// 		cc.clear();
			// 		tighten(nn); // Not sure this is required.
			// 		return nn;
			// 	}
			// 	Node c        = seedPicker == SeedPicker.LINEAR ? lPickNext(cc) : qPickNext(cc, nn);
			// 	Node preferred;
				
			// 	float e0 = getRequiredExpansion(nn[0].coords, nn[0].dimensions, c);
			// 	float e1 = getRequiredExpansion(nn[1].coords, nn[1].dimensions, c);
			// 	if (e0 < e1)
			// 	{
			// 		preferred = nn[0];
			// 	}
			// 	else if (e0 > e1)
			// 	{
			// 		preferred = nn[1];
			// 	}
			// 	else
			// 	{
			// 		float a0 = getArea(nn[0].dimensions);
			// 		float a1 = getArea(nn[1].dimensions);
			// 		if (a0 < a1)
			// 		{
			// 			preferred = nn[0];
			// 		}
			// 		else if (e0 > a1)
			// 		{
			// 			preferred = nn[1];
			// 		}
			// 		else
			// 		{
			// 			if (nn[0].children.size() < nn[1].children.size())
			// 			{
			// 				preferred = nn[0];
			// 			}
			// 			else if (nn[0].children.size() > nn[1].children.size())
			// 			{
			// 				preferred = nn[1];
			// 			}
			// 			else
			// 			{
			// 				preferred = nn[(int) Math.round(Math.random())];
			// 			}
			// 		}
			// 	}
			// 	preferred.children.add(c);
			// 	tighten(preferred);
			}
			return $nn;
		}





		private function isOverlap($scoords, $sdimensions, $coords, $dimensions) {

			$FUDGE_FACTOR = 1.001;
			for ($i = 0; $i < count($scoords); $i++)
			{
				$overlapInThisDimension = false;
				if ($scoords[$i] == $coords[$i])
				{

					$overlapInThisDimension = true;
				}
				else if ($scoords[$i] < $coords[$i])
				{
					if ($scoords[$i] + $FUDGE_FACTOR*$sdimensions[$i] >= $coords[$i])
					{
						$overlapInThisDimension = true;
					}
				}
				else if ($scoords[$i] > $coords[$i])
				{
					if ($coords[$i] + $FUDGE_FACTOR*$dimensions[$i] >= $scoords[$i])
					{
						$overlapInThisDimension = true;
					}
				}
				if (!$overlapInThisDimension)
				{


					return $overlapInThisDimension;
				}
			}

			return $overlapInThisDimension;
		}
	}

	class Node {
		private $coords;
		private $dimensions;
		private $children;
		private $leaf;
		private $parent;

		public function Node($coords, $dimensions, $leaf) {
			$this->coords = $coords;
			$this->dimensions = $dimensions;
			$this->leaf = $leaf;
			$this->children = array();

		}

		public function setCoords($coords){
			$this->coords = $coords;
		}

		public function setDimensions($dimensions){
			$this->dimensions = $dimensions;
		}

		public function addChildren($e){
			$this->children[] = $e;
		}

		public function getCoords(){
			return $this->coords;
		}

		public function getCoordsWithKey($i){
			return $this->coords[$i];
		}

		public function getDimensions(){
			return $this->dimensions;
		}

		public function getDimensionsWithKey($i){
			return $this->dimensions[$i];
		}

		public function getLeaf(){
			return $this->leaf;
		}

		public function getChildren(){
			return $this->children;
		}

		public function toString() {
			return 
			"Node{
				coords=" . implode("|",$this->coords) . ",
				dimensions=" . implode("|",$this->dimensions) . ",
				children=" . $this->children . ",
				leaf=" . $this->leaf . ",
				parent=" . $this->parent . "
			}";
		}
	}

	class Entry extends Node {

		public $entry;

		public function Entry($coords, $dimensions, $entry) {
		// an entry isn't actually a leaf (its parent is a leaf)
		// but all the algorithms should stop at the first leaf they encounter,
		// so this little hack shouldn't be a problem.
			parent::Node($coords, $dimensions, true);
			$this->entry = $entry;
		}

		public function toString() {
			return "Entry: " + $entry;
		}
	}



