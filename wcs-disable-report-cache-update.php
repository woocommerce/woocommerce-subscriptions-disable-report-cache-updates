<?php
/**
 * Plugin Name: WooCommerce Subscriptions - Disable Report Cache Updates
 * Plugin URI: https://github.com/Prospress/woocommerce-subscriptions-disable-report-cache-updates
 * Description: Disable the automatic updates to report cache run by WooCommerce Subscriptions.
 * Author: Prospress Inc.
 * Author URI: http://prospress.com/
 * Version: 1.0
 *
 * Copyright 2016 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package		WooCommerce Subscriptions
 * @author		Prospress Inc.
 * @since		1.0
 */

function wcs_disable_report_cache_update() {

	$cached_report_classes = array(
		'WC_Report_Subscription_Events_By_Date',
		'WC_Report_Upcoming_Recurring_Revenue',
		'WC_Report_Subscription_By_Product',
		'WC_Report_Subscription_By_Customer',
	);

	foreach ( $cached_report_classes as $report_class ) {
		wp_clear_scheduled_hook( 'wcs_report_update_cache', array( 'report_class' => $report_class ) );
	}
}
add_action( 'shutdown', 'wcs_disable_report_cache_update', 11 );