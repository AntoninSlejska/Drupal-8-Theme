<?php

/*!
 * Pattern Data Subtype Rule Class
 *
 * Copyright (c) 2014 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * If it's a directory at level 1 add the subtype info to PatternData::$store
 *
 */

namespace PatternLab\PatternData\Rules;

use \PatternLab\PatternData;
use \PatternLab\Timer;

class PatternSubtypeRule extends \PatternLab\PatternData\Rule {
	
	public function __construct($options) {
		
		parent::__construct($options);
		
		$this->depthProp  = 1;
		$this->extProp    = "";
		$this->isDirProp  = true;
		$this->isFileProp = false;
		$this->searchProp = "";
		$this->ignoreProp = "";
		
	}
	
	public function run($depth, $ext, $path, $pathName, $name) {
		
		// load default vars
		$patternType            = PatternData::getPatternType();
		$patternTypeDash        = PatternData::getPatternTypeDash();
		$patternTypeClean       = PatternData::getPatternTypeClean();
		$dirSep                 = PatternData::getDirSep();
		
		// set-up the names
		$patternSubtype         = $name;                                        // 10-molecules
		$patternSubtypeDash     = $this->getPatternName($name,false);           // blocks
		$patternSubtypeClean    = str_replace("-"," ",$patternSubtypeDash);     // blocks (dashes replaced with spaces)
		$patternSubtypePath     = $pathName;                                    // 00-atoms/10-molecules
		$patternSubtypePathDash = str_replace($dirSep,"-",$patternSubtypePath); // 00-atoms-10-molecules (file path)
		
		// create a key for the data store
		$patternStoreKey        = $patternTypeDash."-".$patternSubtypeDash."-plsubtype";
		
		// collect the data
		$patternStoreData       = array("category"   => "patternSubtype",
										"name"       => $patternSubtype,
										"nameDash"   => $patternSubtypeDash,
										"nameClean"  => $patternSubtypeClean,
										"type"       => $patternType,
										"typeDash"   => $patternTypeDash,
										"breadcrumb" => $patternTypeClean,
										"depth"      => $depth,
										"ext"        => $ext,
										"path"       => $path,
										"pathName"   => $patternSubtypePath,
										"pathDash"   => $patternSubtypePathDash,
										"isDir"      => $this->isDirProp,
										"isFile"     => $this->isFileProp);
		
		// if the pattern data store already exists make sure it is merged and overwrites this data
		$patternStoreData = (PatternData::checkOption($patternStoreKey)) ? array_replace_recursive(PatternData::getOption($patternStoreKey),$patternStoreData) : $patternStoreData;
		PatternData::setOption($patternStoreKey, $patternStoreData);
		
		// starting a new set of pattern types. it might not have any pattern subtypes
		PatternData::setPatternSubtype($patternSubtype);
		PatternData::setPatternSubtypeClean($patternSubtypeClean);
		PatternData::setPatternSubtypeDash($patternSubtypeDash);
		PatternData::setPatternSubtypeSet(true);
		
	}
	
}