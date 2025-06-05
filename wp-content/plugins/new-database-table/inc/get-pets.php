<?php

class Get_Pets {
	public function __construct() {
		global $wpdb;
		$table_name = $wpdb->prefix . "pets";
		$this->args = $this->get_args();
		$this->placeholders = $this->create_placeholders();
		$query = "SELECT * FROM $table_name";
		$count_query = "SELECT COUNT(*) FROM $table_name";
		$query .= $this->create_where_text();
		$count_query .= $this->create_where_text();
		$query .= " LIMIT 100";

		$this->count = $wpdb->get_var($wpdb->prepare(   $count_query, $this->placeholders ));
		$this->pets = $wpdb->get_results($wpdb->prepare( $query, $this->placeholders ));
	}

	/**
	 * Gets sanitized request parameters from $_GET for filtering pets.
	 *
	 * @return array Sanitized parameters.
	 */
	/**
	 * Gets sanitized request parameters from $_GET for filtering pets.
	 *
	 * @return array Sanitized parameters.
	 */
	function get_args(): array {
		$filter_params = [
			'favcolor' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
			'species' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
			'minyear' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
			'maxyear' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
			'minweight' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
			'maxweight' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
			'favhobby' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
			'favfood' => [ 'filter' => FILTER_CALLBACK, 'options' => 'sanitize_text_field' ],
		];

		$result = filter_input_array( INPUT_GET, $filter_params, false );

		// Check if $result is null and return empty array if it is
		if ($result === null) {
			return [];
		}

		// Remove null values (parameters that weren't in the request)
		return array_filter( $result, function ( $value ) {
			return $value !== null && $value !== false;
		} );
	}

	function create_placeholders(): array {
		return array_map( function ( $value ) {
			return $value;
		}, $this->args );
	}

	function create_where_text(): string {
		$where_text = "";
		if ( count( $this->args ) ) {
			$where_text = " WHERE ";
		}
		$current_position = 0;
		foreach ( $this->args as $key => $value ) {
			$where_text .= $this->spacific_query( $key);
			if ( $current_position != count( $this->args ) - 1 ) {
				$where_text .= " AND ";
			}
			$current_position ++;

		}

		return $where_text;
	}
	function spacific_query( $key ): string {
		switch ( $key ) {
			case "ninweight":
				return "petweight >= %d";
			case "maxweight":
				return "petweight <= %d";
			case "minyear":
				return "brithyear >= %d";
			case "maxyear":
				return "brithyear <= %d";
			default:
				return $key . " = %s";
		}
	}

}