<?php

trait skydropFormValidation
{
	/**
	 * Validate Text Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param  string $key Field key
	 * @param  string|null $value Posted Value
	 * @return string
	 */
    public function validate_text_field( $key, $value ) {
        $field = $this->get_form_fields()[$key];
        if ( isset($field['required']) && $field['required'] && empty( $value ) ) {
            throw new Exception("{$field['title']} value is empty!");
        }
        else {
            $value = is_null( $value ) ? '' : $value;
            return wp_kses_post( trim( stripslashes( $value ) ) );
        }
    }
	/**
	 * Validate Select Field.
	 *
	 * @param  string $key
	 * @param  string $value Posted Value
	 * @return string
	 */
	public function validate_select_field( $key, $value ) {
        $field = $this->get_form_fields()[$key];
        if ( isset($field['required']) && $field['required'] && empty( $value ) ) {
            throw new Exception("{$field['title']} is required!");
        }
        else {
            $value = is_null( $value ) ? '' : $value;
            return wc_clean( stripslashes( $value ) );
        }
    }
    /**
     * Validate Multiselect Field.
     *
     * @param  string $key
     * @param  string $value Posted Value
     * @return string
     */
    public function validate_multiselect_field( $key, $value ) {
        $field = $this->get_form_fields()[$key];
        if ( isset($field['required']) && $field['required'] && empty( $value ) ) {
            throw new Exception("You have to choose at least one {$field['title']}!");
        }
        else {
            return is_array( $value ) ? array_map( 'wc_clean', array_map( 'stripslashes', $value ) ) : '';
        }
    }
}
