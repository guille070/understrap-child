<?php
/**
 * Template Name: Random Pokemon
 *
 * Template for displaying a random Pokemon storedin the DB.
 *
 * @package Understrap
 */

use Understrap_Child\Functions\Random_Pokemon;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

Random_Pokemon::query();