<?php

require_once __DIR__ . '/../../../maintenance/Maintenance.php';

/**
 * Fix swauthor & srcwork data after swsite introduction
 *
 * @package    CreditsSource
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 * @version    $Id$
 */
class Swsite extends Maintenance {
	/**
	 * Batch size
	 *
	 * @var int
	 */
	private $limit = 50;

	/**
	 * The number of entries completed
	 *
	 * @var int[]
	 */
	private $completeCount = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Fix swauthor & srcwork data after swsite introduction';
		$this->requireExtension( 'CreditsSource' );
	}

	/**
	 * Execute the script
	 */
	public function execute() {
		foreach ( [ 'swauthor' => 'swa', 'srcwork' => 'srcwork' ] as $table => $prefix ) {
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
		$dbr = wfGetDB( DB_REPLICA );

		$rows = $dbr->select(
			[ 'swsite', $table ],
			[
				$prefix . '_id',
				$prefix . '_uri_part',
				'sws_id',
				'sws_work_uri',
			],
			[],
			__METHOD__,
			[
				'LIMIT' => $this->limit
			],
			[
				'swsite' => [
					'INNER JOIN', [
						$prefix . '_uri_part LIKE CONCAT(sws_work_uri, "%")'
					]
				]
			]
		);

		$return = false;

		foreach ( $rows as $row ) {
			$dbw->update(
				$table,
				[
					$prefix . '_uri_part' => str_replace( $row->sws_work_uri, '', $row->{$prefix . '_uri_part'} ),
					$prefix . '_site' => $row->sws_id
				],
				[ $prefix . '_id' => $row->{$prefix . '_id'} ]
			);

			$this->completeCount[$table]++;
			$return = true;
		}

		return $return;
	}
}

$maintClass = Swsite::class;
require_once RUN_MAINTENANCE_IF_MAIN;
