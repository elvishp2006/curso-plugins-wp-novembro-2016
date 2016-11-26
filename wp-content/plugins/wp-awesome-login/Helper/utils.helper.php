<?php
namespace WPA\Login;

// Avoid that files are directly loaded
if ( ! function_exists( 'add_action' ) ) :
	exit(0);
endif;

class Utils_Helper
{
   /**
	* Verify nonce by $_POST param
	*
	* @return bool
	*/
	public static function verify_nonce_post( $name, $action )
	{
		return wp_verify_nonce( self::post( $name, false ), $action );
	}

   /**
	* Print html dencode
	*
	* @return bool
	*/
	public static function html( $text )
	{
		$text = htmlspecialchars_decode( $text );
		$text = str_replace( '\\', '', $text );

		return strip_tags( $text, '<p><strong><span><br><a>' );
	}

   /**
	* Change pipe for markup
	*
	* @return bool
	*/
	public static function title_pipe( $title, $element = 'strong' )
	{
		if ( strpos( $title, '|' ) === false )
			return $title;

		$title = explode( '|', $title );

		return sprintf( "<{$element}>%s</{$element}>%s", trim( $title[0] ), $title[1] );
	}

	/**
	 * Gets the post ID
	 *
	 * Gets the post ID when the page screen is loaded
	 * and when the post is saved.
	 *
	 * @since 0.1
	 * @return int returns the post ID
	 */
	public static function get_post_id()
	{
		$post_id = null;

		if ( isset( $_GET['post'] ) ) :
			$post_id = intval( $_GET['post'] );
		endif;

		if ( isset( $_POST['post_ID'] ) ) :
			$post_id = intval( $_POST['post_ID'] );
		endif;

		return $post_id;
	}

   /**
	* Retrieves the database charset do create new tables.
	*
	* @global type $wpdb
	* @return type
	*/
	public static function get_charset()
	{
		global $wpdb;

		$charset_collate = '';

		if ( ! $wpdb->has_cap( 'collation' ) )
			return;

		if ( ! empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

		if ( ! empty( $wpdb->collate ) )
			$charset_collate .= "\nCOLLATE $wpdb->collate";

		return $charset_collate;
	}

	/**
	 * Get Ip Host Machine Acess
	 *
	 * Use this function for get ip
	 *
	 * @since 0.1
	 * @return string
	 */
	public static function get_ipaddress()
	{
		$ip_address = false;

		if ( isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
			$ip_address = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];

		if ( empty( $ip_address ) )
			$ip_address = $_SERVER[ 'REMOTE_ADDR' ];

		if ( strpos( $ip_address, ',' ) !== false ) :
			$ip_address = explode( ',', $ip_address );
			$ip_address = $ip_address[0];
		endif;

		return esc_attr( $ip_address );
	}

	public static function is_request_ajax()
	{
		if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) )
			$request_ajax = $_SERVER['HTTP_X_REQUESTED_WITH'];

		return ( ! empty( $request_ajax ) && strtolower( $request_ajax ) == 'xmlhttprequest' );
	}

	public static function get_user_agent()
	{
		if ( ! isset( $_SERVER ) )
			return 'none';

		return $_SERVER[ 'HTTP_USER_AGENT' ];
	}

	public static function get_thumbnail_url( $thumbnail_id, $size )
	{
		$attachment = wp_get_attachment_image_src( $thumbnail_id, $size );

		if ( ! $attachment )
			return false;

		return $attachment[0];
	}

	public static function get_query( $args = array(), $defaults = array() )
	{
		return new \WP_Query( wp_parse_args( $args, $defaults ) );
	}

	public static function get( $key, $default = '', $sanitize = 'esc_html' )
	{
		if ( ! isset( $_GET[ $key ] ) OR empty( $_GET[ $key ] ) )
			return $default;

		if ( is_array( $_GET[ $key ] ) )
			return $_GET[ $key ];

		return self::sanitize_type( $_GET[ $key ], $sanitize );
	}

	public static function request( $key, $default = '', $sanitize = 'esc_html' )
	{
		if ( ! isset( $_REQUEST[ $key ] ) OR empty( $_REQUEST[ $key ] ) )
			return $default;

		return self::sanitize_type( $_REQUEST[ $key ], $sanitize );
	}

	public static function post( $key, $default = '', $sanitize = 'esc_html' )
	{
		if ( ! isset( $_POST[ $key ] ) OR empty( $_POST[ $key ] ) )
			return $default;

		if ( is_array( $_POST[ $key ] ) )
			return $_POST[ $key ];

		return self::sanitize_type( $_POST[ $key ], $sanitize );
	}

	public static function sanitize_type( $value, $name_function )
	{
		if ( ! $name_function )
			return $value;

		if ( ! is_callable( $name_function ) )
			return esc_html( $value );

		return call_user_func( $name_function, $value );
	}

	public static function error_server_json( $code, $message = 'Generic Message Error', $echo = true )
	{
		$response = json_encode(
			array(
				'status' 	=> 'error',
				'code'   	=> $code,
				'message'	=> $message,
			)
		);

		if ( ! $echo )
			return $response;

		echo $response;
	}

	public static function success_server_json( $code, $message = 'Generic Message Success', $echo = true )
	{
		$response = json_encode(
			array(
				'status' 	=> 'success',
				'code'   	=> $code,
				'message'	=> $message,
			)
		);

		if ( ! $echo )
			return $response;

		echo $response;
	}

	public static function object_server_json( $args = array(), $echo = true )
	{
		$response = json_encode( $args );

		if ( ! $echo )
			return $response;

		echo $response;
	}

	public static function limit_text( $text, $limit, $more = '...' )
	{
		if ( strlen( $text ) > $limit )
			$text = substr( $text, 0, $limit ) . $more;

		return $text;
	}

	public static function json_decode_quoted( $data, $is_assoc = true )
	{
		return json_decode( str_replace( '&quot;', '"', $data ), $is_assoc );
	}

	public static function add_custom_capabilities( $roles, array $caps )
	{
		foreach ( (array)$roles as $role ) :
			$current_role = get_role( $role );

			array_map( array( &$current_role, 'add_cap' ), $caps );
		endforeach;
	}
}