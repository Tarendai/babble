<?php

/**
 * Translations and languages API.
 *
 * @package Babble
 * @since Alpha 1
 */

/*  Copyright 2011 Simon Wheatley

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/**
 * Returns the current language code.
 *
 * @FIXME: Currently does not check for language validity, though perhaps we should check that elsewhere and redirect?
 *
 * @return string A language code
 * @access public
 **/
function bbl_get_current_lang_code() {
	// Outside the admin area, it's a WP Query Variable
	if ( ! is_admin() )
		return get_query_var( 'lang' ) ? get_query_var( 'lang' ) : bbl_get_default_lang_code();
	// In the admin area, it's a GET param
	$current_user = wp_get_current_user();
	return get_user_meta( $current_user->ID, 'bbl_admin_lang', true ) ? get_user_meta( $current_user->ID, 'bbl_admin_lang', true ) : bbl_get_default_lang_code();
}

/**
 * Set the current lang.
 * 
 * @uses Babble_Locale::switch_lang to do the actual work
 * @see switch_to_blog for similarities
 *
 * @param string $lang The language code to switch to 
 * @return void
 **/
function bbl_switch_to_lang( $lang ) {
	global $bbl_locale;
	$bbl_locale->switch_to_lang( $lang );
}

/**
 * Restore the previous lang.
 * 
 * @uses Babble_Locale::restore_lang to do the actual work
 * @see restore_current_blog for similarities
 *
 * @return void
 **/
function bbl_restore_lang() {
	global $bbl_locale;
	$bbl_locale->restore_lang();
}

/**
 * Get the terms which are the translations for the provided 
 * post ID. N.B. The returned array of term objects (and false 
 * values) will include the post for the post ID passed.
 * 
 * @FIXME: Should I filter out the term ID passed?
 *
 * @param int|object $term Either a WP Term object, or a term_id 
 * @return array Either an array keyed by the site languages, each key containing false (if no translation) or a WP Post object
 * @access public
 **/
function bbl_get_term_translations( $term, $taxonomy = null ) {
	global $bbl_taxonomies;
	return $bbl_taxonomies->get_term_translations( $term, $taxonomy );
}

/**
 * Return the admin URL to create a new translation for a term in a
 * particular language.
 *
 * @param int|object $default_term The term in the default language to create a new translation for, either WP Post object or post ID
 * @param string $lang The language code 
 * @param string $taxonomy The taxonomy
 * @return string The admin URL to create the new translation
 * @access public
 **/
function bbl_get_new_term_translation_url( $default_term, $lang, $taxonomy = null ) {
	global $bbl_taxonomies;
	return $bbl_taxonomies->get_new_term_translation_url( $default_term, $lang, $taxonomy );
}

/**
 * Returns the language code associated with a particular taxonomy.
 *
 * @param string $taxonomy The taxonomy to get the language for 
 * @return string The lang code
 **/
function bbl_get_taxonomy_lang_code( $taxonomy ) {
	global $bbl_taxonomies;
	return $bbl_taxonomies->get_taxonomy_lang_code( $taxonomy );
}

/**
 * Return the base taxonomy (in the default language) for a 
 * provided taxonomy.
 *
 * @param string $taxonomy The name of a taxonomy 
 * @return string The name of the base taxonomy
 **/
function bbl_get_base_taxonomy( $taxonomy ) {
	global $bbl_taxonomies;
	return $bbl_taxonomies->get_base_taxonomy( $taxonomy );
}

/**
 * Returns the equivalent taxonomy in the specified language.
 *
 * @param string $taxonomy A taxonomy to return in a given language
 * @param string $lang_code The language code for the required language (optional, defaults to current)
 * @return void
 **/
function bbl_get_taxonomy_in_lang( $taxonomy, $lang_code = null ) {
	global $bbl_taxonomies;
	return $bbl_taxonomies->get_taxonomy_in_lang( $taxonomy, $lang_code );
}

/**
 * Get the posts which are the translations for the provided 
 * post ID. N.B. The returned array of post objects (and false 
 * values) will include the post for the post ID passed.
 * 
 * @FIXME: Should I filter out the post ID passed?
 *
 * @param int|object $post Either a WP Post object, or a post ID 
 * @return array Either an array keyed by the site languages, each key containing false (if no translation) or a WP Post object
 * @access public
 **/
function bbl_get_post_translations( $post ) {
	global $bbl_post_public;
	return $bbl_post_public->get_post_translations( $post );
}

/**
 * Returns the post ID for the post in the default language from which 
 * this post was translated.
 *
 * @param int|object $post Either a WP Post object, or a post ID 
 * @return int The ID of the default language equivalent post
 * @access public
 **/
function bbl_get_default_lang_post( $post ) {
	global $bbl_post_public;
	return $bbl_post_public->get_default_lang_post( $post );
}

/**
 * Return the language code for the language a given post is written for/in.
 *
 * @param int|object $post Either a WP Post object, or a post ID 
 * @return string|object Either a language code, or a WP_Error object
 * @access public
 **/
function bbl_get_post_lang( $post ) {
	global $bbl_post_public;
	return $bbl_post_public->get_post_lang( $post );
}

/**
 * Return the admin URL to create a new translation for a post in a
 * particular language.
 *
 * @param int|object $default_post The post in the default language to create a new translation for, either WP Post object or post ID
 * @param string $lang The language code 
 * @return string The admin URL to create the new translation
 * @access public
 **/
function bbl_get_new_post_translation_url( $default_post, $lang ) {
	global $bbl_post_public;
	return $bbl_post_public->get_new_post_translation_url( $default_post, $lang );
}

/**
 * Return the post type name for the equivalent post type for the 
 * supplied original post type in the requested language.
 *
 * @param string $post_type The originating post type
 * @param string $lang_code The language code for the required language (optional, defaults to current)
 * @return string A post type name, e.g. "page" or "post"
 **/
function bbl_get_post_type_in_lang( $original_post_type, $lang_code = null ) {
	global $bbl_post_public;
	return $bbl_post_public->get_post_type_in_lang( $original_post_type, $lang_code );
}

/**
 * Return the active language objects for the current site. A
 * language object looks like:
 * 'ar' => 
 * 		object(stdClass)
 * 			public 'names' => string 'Arabic'
 * 			public 'code' => string 'ar'
 * 			public 'url_prefix' => string 'ar'
 * 			public 'text_direction' => string 'rtl'
 * 
 * @uses Babble_Languages::get_active_langs to do the actual work
 *
 * @return array An array of Babble language objects
 **/
function bbl_get_active_langs() {
	global $bbl_languages;
	return $bbl_languages->get_active_langs();
}

/**
 * Returns the current language object, respecting any
 * language switches; i.e. if your request was for
 * Arabic, but the language is currently switched to
 * French, this will return French.
 *
 * @return object A Babble language object
 **/
function bbl_get_current_lang() {
	global $bbl_languages;
	return $bbl_languages->get_current_lang();
}

/**
 * Returns the default language code for this site.
 *
 * @return string A language code, e.g. "he_IL"
 **/
function bbl_get_default_lang_code() {
	global $bbl_languages;
	return $bbl_languages->get_default_lang_code();
}

/**
 * Checks whether either the provided language code, 
 * if provided, or the current language code are
 * the default language.
 * 
 * i.e. is this language the default language
 *
 * n.b. the current language could have been switched
 * using bbl_switch_to_lang
 *
 * @param string $lang_code The language code to check (optional) 
 * @return bool True if the default language
 **/
function bbl_is_default_lang( $lang_code = null ) {
	if ( is_null( $lang_code ) )
		$lang_code = bbl_get_current_lang();
	return ( bbl_get_default_lang_code() == $lang_code->code );
}

/**
 * Returns the default language code for this site.
 *
 * @return string The language URL prefix set by the admin, e.g. "de"
 **/
function bbl_get_default_lang_url_prefix() {
	global $bbl_languages;
	$code = $bbl_languages->get_default_lang_code();
	return $bbl_languages->get_url_prefix_from_code( $code );
}

/**
 * Returns the language code for the provided URL prefix.
 *
 * @param string $url_prefix The URL prefix to find the language code for 
 * @return string The language code, or false
 **/
function bbl_get_lang_from_prefix( $url_prefix ) {
	global $bbl_languages;
	return $bbl_languages->get_code_from_url_prefix( $url_prefix );
}

/**
 * Start logging for Babble
 *
 * @return void
 **/
function bbl_start_logging() {
	global $bbl_log;
	$bbl_log->logging = true;
}

/**
 * Stop logging for Babble
 *
 * @return void
 **/
function bbl_stop_logging() {
	global $bbl_log;
	$bbl_log->logging = false;
}

/**
 * Start logging for Babble
 *
 * @return void
 **/
function bbl_is_logging() {
	global $bbl_log;
	return $bbl_log->logging;
}

/**
 * Log a message.
 *
 * @param string $msg Log this message 
 * @return void
 **/
function bbl_log( $msg ) {
	global $bbl_log;
	if ( $bbl_log )
		$bbl_log->log( $msg );
	else
		error_log( "Full Babble logging unavailable: $msg" );
}

?>