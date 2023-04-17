<?php
/**
 * Template Name: Generate Pokemon
 *
 * Template for generate a random Pokemon by calling de PokeAPI and store in the DB.
 *
 * @package Understrap
 */

use Understrap_Child\Functions\Generate_Pokemon;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

Generate_Pokemon::init();