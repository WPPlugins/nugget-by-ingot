<?php
/**
 * Create a new experiment
 *
 * @package   ingot\MaBandit
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock
 */

namespace MaBandit;


class CreateExperiment {

	/**
	 * @var \MaBandit\Experiment
	 */
	private $experiment;

	/**
	 * Make a new experiment with given variants
	 *
	 * @since 0.1.0
	 *
	 * @param array $variants
	 * @param int|string $id Experiment (group) ID
	 * @param \MaBandit\MaBandit $bandit MaBandit object
	 */
	public function __construct( array $variants, $id, \MaBandit\MaBandit $bandit ) {
		$this->experiment = $bandit->createExperiment( (string) $id, $variants );
		if( method_exists( $bandit->getPersistor(), 'save_levers' ) ){
			$bandit->getPersistor()->batchSave( $this->experiment->getLevers(), $id );
		}

	}

	/**
	 * Get experiment
	 *
	 * @since 0.1.0
	 *
	 * @return \MaBandit\Experiment
	 */
	public function get_experiment(){
		return $this->experiment;
	}

}
