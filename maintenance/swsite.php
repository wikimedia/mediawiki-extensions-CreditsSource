<?php

require_once( dirname( __FILE__ ) . '/../../../maintenance/Maintenance.php' );

/**
 * Fix swauthor & srcwork data after swsite introduction
 *
 * @package    CreditsSource
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
class CreditsSource_swsite extends Maintenance {
	/**
	 * Batch size
	 *
	 * @var int
	 */
	private $limit = 50;

	/**
	 * The number of entries completed
	 *
	 * @var int
	 */
	private $completeCount = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Fix swauthor & srcwork data after swsite introduction';
	}

	/**
	 * Execute the script
	 */
	public function execute() {
		foreach ( array( 'swauthor' => 'swa', 'srcwork' => 'srcwork' ) as $table => $prefix ) {
			$this->output( "Updating $table entries.\n" );
			$this->completeCount[$table] = 0;

			while ( $this->refreshTable( $table, $prefix ) ) {
				wfWaitForSlaves();
			}

			$count = $this->completeCount[$table];
			$this->output( "Done. Refreshed $count entries in $table.\n" );
		}
	}

	/**
	 * Refreshes a batch of entries
	 *
	 * @param string $table table name to update
	 * @param string $prefix table's columns prefix
	 * @return bool
	 */
	public function refreshTable( $table, $prefix ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_SLAVE );

		$rows = $dbr->select(
			array( 'swsite', $table ),
			array(
				$prefix.'_id',
				$prefix.'_uri_part',
				'sws_id',
				'sws_work_uri',
			),
			array(),
			__METHOD__,
			array(
				'LIMIT' => $this->limit
			),
			array(
				'swsite' => array(
					'INNER JOIN', array(
						$prefix.'_uri_part LIKE CONCAT(sws_work_uri, "%")'
					)
				)
			)
		);

		$return = false;

		foreach ( $rows as $row ) {
			$dbw->update(
				$table,
				array(
					$prefix.'_uri_part' => str_replace( $row->sws_work_uri, '', $row->{$prefix.'_uri_part'} ),
					$prefix.'_site' => $row->sws_id
				),
				array( $prefix.'_id' => $row->{$prefix.'_id'} )
			);

			$this->completeCount[$table]++;
			$return = true;
		}

		return $return;
	}
}

$maintClass = "CreditsSource_swsite";
require_once( RUN_MAINTENANCE_IF_MAIN );
