<?php
/**
 * Plugin Name: WooCommerce Subscriptions - Disable Report Cache Updates
 * Plugin URI: https://github.com/Prospress/woocommerce-subscriptions-disable-report-cache-updates
 * Description: Disable the automatic updates to report cache run by WooCommerce Subscriptions.
 * Author: Prospress Inc.
 * Author URI: http://prospress.com/
 * Version: 1.2.0
 * License: GPLv3
 *
 * GitHub Plugin URI: Prospress/woocommerce-subscriptions-disable-report-cache-updates
 * GitHub Branch: master
 *
 * Copyright 2018 Prospress, Inc.  (email : freedoms@prospress.com)
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

require_once( 'includes/class-pp-dependencies.php' );

/**
 * Attach callbacks.
 */
function wcs_drcu_init() {

	if ( ! PP_Dependencies::is_subscriptions_active() ) {
		return;
	}

	// Pre WooCommerce Subscriptions 2.3, disable the reports by un-scheduling the cron report cache update events.
	if ( version_compare( WC_Subscriptions::$version, '2.3.0', '<' )  ) {
		add_action( 'shutdown', 'wcs_disable_report_cache_update', 11 );
	} else {
		add_filter( 'pre_option_woocommerce_subscriptions_cache_updates_enabled', 'wcs_disable_background_report_cache_updates', 11 );
	}
}
add_action( 'plugins_loaded', 'wcs_drcu_init', 11 );

/**
 * Disable the report cache updates by returning 'no' from the woocommerce_subscriptions_cache_updates_enabled option.
 *
 * @return string 'no' to disable cache updates.
 */
function wcs_disable_background_report_cache_updates() {
	return 'no';
}

/**
 * Clear subscription report cache updates from WP Cron.
 *
 * Intended to run on shutdown and on WC Subscriptions versions prior to 2.3.
 */
function wcs_disable_report_cache_update() {

	if ( ! is_admin() || ! PP_Dependencies::is_subscriptions_active() ) {
		return;
	}

	$cached_report_classes = array(
		'WC_Report_Subscription_Events_By_Date',
		'WC_Report_Upcoming_Recurring_Revenue',
		'WC_Report_Subscription_By_Product',
		'WC_Report_Subscription_By_Customer',
		'WCS_Report_Subscription_Events_By_Date',
		'WCS_Report_Upcoming_Recurring_Revenue',
		'WCS_Report_Subscription_By_Product',
		'WCS_Report_Subscription_By_Customer',
	);

	foreach ( $cached_report_classes as $report_class ) {
		wp_clear_scheduled_hook( 'wcs_report_update_cache', array( 'report_class' => $report_class ) );
	}
}
