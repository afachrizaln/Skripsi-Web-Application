<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RTree
{
	$nodeId = 0;
	$mbrMinX;
	$mbrMinY;
	$mbrMaxX;
	$mbrMaxY;
	$Xmin;
	$Xmax;
	$Ymin;
	$Ymax;

	$entriesMinX = array();
	$entriesMinY = array();
	$entriesMaxX = array();
	$entriesMaxY = array();
	$ids = array();
	$level;
	$entryCount = 0;
	$parent;
	$name;
	//NODE TYPE - INTERNAL OR LEAF:
	$type; 

	public function nodeEntry($nodeId, $Xmin, $Xmax, $Ymin, $Ymax, $parent, $type) {
		$this->nodeId = $nodeId;
		$this->Xmax = $Xmax;
		$this->Xmin = $Xmin;
		$this->Ymax = $Ymax;
		$this->Ymin = $Ymin;
		$this->parent = $parent;
		$this->entriesMinX = array();
		$this->entriesMinY = array();
		$this->entriesMaxX = array();
		$this->entriesMaxY = array();
		$this->name = "Node "+ $nodeId;
		$this->type = $type;
	}

	public function addEntry($Xmin, $Xmax, $Ymin, $Ymax, $Nodename) {
		$ids[$entryCount] = $Nodename;
		$entriesMinX[$entryCount] = $Xmin;
		$entriesMinY[$entryCount] = $Ymin;
		$entriesMaxX[$entryCount] = $Xmax;
		$entriesMaxY[$entryCount] = $Ymax;
		$entryCount++;
	}
}