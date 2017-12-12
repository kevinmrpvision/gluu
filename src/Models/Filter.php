<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mrpvision\Gluu\Models;

/**
 * Description of Filter
 *
 * @author kevpat
 */
class Filter {
    //put your code here
    
    const EQUAL = 'eq';
    const NOT_EQUAL = 'neq';
    const CONTAINS = 'co';
    const START_WITH = 'sw';
    const END_WITH = 'ew';
    const PRESET = 'pr';
    const GREATER_THAN = 'gt';
    const GREATER_THAN_EQUAL_TO = 'ge';
    const LESS_THAN = 'lt';
    const LESS_THAN_EQUAL_TO = 'le';
    const LOGICAL_AND = 'AND';
    const LOGICAL_OR = 'OR';
    const LOGICAL_NOT = 'NOT';
    public function __construct($operator,$field,$value) {
        
    }
}
