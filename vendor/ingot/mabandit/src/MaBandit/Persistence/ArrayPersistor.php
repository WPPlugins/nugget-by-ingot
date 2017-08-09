<?php

namespace MaBandit\Persistence;

class ArrayPersistor implements Persistor
{

  private $_levers = array();
  
  public function saveLever(\MaBandit\Lever $lever)
  {
    if (!array_key_exists($lever->experiment, $this->_levers)
      or !is_array($this->_levers[$lever->experiment]))
        $this->_levers[$lever->experiment] = array();
    $this->_levers[$lever->experiment][$lever->getValue()] = $lever;
    return $lever;
  }

  public function loadLever(\MaBandit\Persistence\PersistedLever $lever)
  {
    if (!is_array($this->_levers[$lever->getExperiment()]))
      return null;
    return $this->_levers[$lever->getExperiment()][$lever->getValue()];
  }

  public function loadLeversForExperiment(\MaBandit\Persistence\PersistedLever $lever)
  {
	  $x = $this->_levers;
	  $the_lever = $lever->getExperiment();
	  if( isset( $this->_levers[ $the_lever ] )  ) {
		  return $this->_levers[ $the_lever ];
	  }else{
		  return array();
	  }
  }
}
