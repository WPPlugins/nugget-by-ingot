<?php

namespace MaBandit\Strategy;

class EpsilonGreedy extends Strategy
{
  
  private $_exploreEvery;

  private function __construct($exploreEvery)
  {
    $this->_exploreEvery = $exploreEvery;
  }

  public static function withExplorationEvery($exploreEvery)
  {
    if (!is_int($exploreEvery) or $exploreEvery < 1)
        throw new \MaBandit\Exception\InvalidExploitationLengthException();
    $i = new EpsilonGreedy($exploreEvery);
    return $i;
  }

  public function shouldExplore($levers)
  {
	  if ( 1 == $this->_exploreEvery ) {
			$should = true;
	  }else{
		  $should = ( ( $this->getTotalIterations( $levers ) + 1 ) % $this->_exploreEvery )
		            == 0;
	  }

	  return $should;

  }
}
