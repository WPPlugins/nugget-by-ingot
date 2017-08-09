<?php
/**
 * An abstract persistior that uses call_user_func() for read/write
 *
 * @package   ingot\MaBandit
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock
 */

namespace MaBandit\Persistence;


class AbstractPersistor implements Persistor {
	/**
	 * Holds levers (variants in Ingotish)
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $_levers = array();

	/**
	 * Name of function or callable array for class/method to to read levers.
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 *
	 * @var string|array
	 */
	private $_read_callback;

	/**
	 * Name of function or callable array for class/method to to write levers.
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 *
	 * @var string|array
	 */
	private $_write_callback;

	/**
	 * Experiment ID
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 *
	 * @var string|array
	 */
	private $ID;

	/**
	 *
	 * @param int $id Group ID
	 * @param string|array $ $read_callback Name of function or callable array for class/method to to read levers.
	 * @param string|array $write_callback Name of function or callable array for class/method to to write levers.
	 */
	public function __construct( $id, $read_callback, $write_callback ) {
		$this->ID = $id;
		$this->_read_callback = $read_callback;
		$this->_write_callback = $write_callback;
	}

	/**
	 * Save a variant as a lever
	 *
	 * @since 0.1.0
	 *
	 * @param \MaBandit\Lever $lever
	 *
	 * @return \MaBandit\Lever
	 */
	public function saveLever(\MaBandit\Lever $lever) {
		$this->get_levers();
		if ( ! is_array( $this->_levers ) || !array_key_exists($lever->experiment, $this->_levers) || !is_array($this->_levers[$lever->experiment])) {
			$this->_levers[ $lever->experiment ] = array();
		}

		$this->_levers[$lever->experiment][$lever->getValue()] = $lever;
		$this->save_levers();
		return $lever;
	}


	/**
	 * Load a variant as a lever
	 *
	 * @since 0.1.0
	 *
	 * @param \MaBandit\Persistence\PersistedLever $lever
	 *
	 * @return null
	 */
	public function loadLever( \MaBandit\Persistence\PersistedLever $lever) {
		$this->get_levers();
		$_lever = $lever->getExperiment();
		if ( ! isset( $this->_levers[ $_lever ] ) || !is_array($this->_levers[ $_lever ])) {
			return null;
		}

		return $this->_levers[$_lever][$lever->getValue()];
	}

	/**
	 * Batch save new levers
	 *
	 * @since 0.1.0
	 *
	 * @param array $levers
	 * @param int $experiment_id
	 */
	public function batchSave( array $levers, $experiment_id ){
		if( ! isset( $levers[ $experiment_id ] ) ){
			$levers = [
				$experiment_id => $levers
			];
		}
		$this->_levers = $levers;
		$this->save_levers();
	}

	/**
	 * Load a lever (variant) for an experiment (group)
	 *
	 * @since 0.1.0
	 *
	 * @param \MaBandit\Persistence\PersistedLever $lever
	 *
	 * @return null
	 */
	public function loadLeversForExperiment( \MaBandit\Persistence\PersistedLever $lever) {
		$this->get_levers();
		$the_lever = $lever->getExperiment();
		if( isset( $this->_levers[ $the_lever ] )  ) {
			return $this->_levers[ $the_lever ];
		}else{
			return array();
		}
	}

	/**
	 * Get saved levers and set in _levers property
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 */
	protected function get_levers() {
		$this->_levers = call_user_func( $this->_read_callback, $this->ID );
	}

	/**
	 * Save contents of _levers property
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 */
	protected function save_levers(){
		call_user_func( $this->_write_callback, $this->ID, $this->_levers );
	}
}
