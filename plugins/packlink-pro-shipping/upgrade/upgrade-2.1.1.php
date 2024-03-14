<?php
/** @noinspection PhpUnhandledExceptionInspection */

use Logeecom\Infrastructure\Configuration\Configuration;
use Logeecom\Infrastructure\ORM\RepositoryRegistry;
use Logeecom\Infrastructure\ServiceRegister;
use Packlink\BusinessLogic\Scheduler\Models\DailySchedule;
use Packlink\BusinessLogic\Scheduler\Models\HourlySchedule;
use Packlink\BusinessLogic\Scheduler\Models\Schedule;
use Packlink\BusinessLogic\ShippingMethod\Utility\ShipmentStatus;
use Packlink\BusinessLogic\Tasks\UpdateShipmentDataTask;

// This section will be triggered when upgrading to 2.1.1 or later version of plugin.
$configuration = ServiceRegister::getService( Configuration::CLASS_NAME );
$repository    = RepositoryRegistry::getRepository( Schedule::getClassName() );

$schedules = $repository->select();

/** @var Schedule $schedule */
foreach ( $schedules as $schedule ) {
	$task = $schedule->getTask();

	if ( $task->getType() === UpdateShipmentDataTask::getClassName() ) {
		$repository->delete( $schedule );
	}
}

foreach ( array( 0, 30 ) as $minute ) {
	$hourly_statuses = array(
		ShipmentStatus::STATUS_PENDING,
	);

	$shipment_data_half_hour_schedule = new HourlySchedule(
		new UpdateShipmentDataTask( $hourly_statuses ),
		$configuration->getDefaultQueueName()
	);
	$shipment_data_half_hour_schedule->setMinute( $minute );
	$shipment_data_half_hour_schedule->setNextSchedule();
	$repository->save( $shipment_data_half_hour_schedule );
}

$daily_statuses = array(
	ShipmentStatus::STATUS_IN_TRANSIT,
	ShipmentStatus::STATUS_READY,
	ShipmentStatus::STATUS_ACCEPTED,
);

$daily_shipment_data_schedule = new DailySchedule(
	new UpdateShipmentDataTask( $daily_statuses ),
	$configuration->getDefaultQueueName()
);

$daily_shipment_data_schedule->setHour( 11 );
$daily_shipment_data_schedule->setNextSchedule();

$repository->save( $daily_shipment_data_schedule );

// we updated this to PACKLINK_VERSION, so we delete the old one.
delete_option( 'PACKLINK_DATABASE_VERSION' );
