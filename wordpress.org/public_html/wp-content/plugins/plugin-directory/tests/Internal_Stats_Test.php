<?php
/**
 * REST API endpoint tests.
 *
 * @package WordPressdotorg_Plugin_Directory
 */

use PHPUnit\Framework\TestCase;

/**
 * Tests for the public Plugin Directory statistics REST API endpoint.
 *
 * @package WordPressdotorg_Plugin_Directory
 */
class Internal_Stats_Test extends TestCase {
	/**
	 * Posts created during a test.
	 *
	 * @var int[]
	 */
	private $post_ids = array();

	/**
	 * Deletes the posts created during a test.
	 */
	protected function tearDown(): void {
		foreach ( $this->post_ids as $post_id ) {
			wp_delete_post( $post_id, true );
		}

		parent::tearDown();
	}

	/**
	 * The endpoint returns only the number of plugins awaiting initial review.
	 */
	public function test_get_new_queue_count() {
		$this->create_plugin( 'new' );
		$this->create_plugin( 'new' );
		$this->create_plugin( 'pending' );
		$this->create_plugin( 'pending' );

		$request  = new WP_REST_Request( 'GET', '/plugins/v1/stats' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame( array( 'queue_count' => 2 ), $response->get_data() );
	}

	/**
	 * Creates a plugin post with the requested status.
	 *
	 * @param string $post_status The plugin post status.
	 */
	private function create_plugin( $post_status ) {
		$post_id = wp_insert_post( array(
			'post_title'  => 'Test Plugin',
			'post_type'   => 'plugin',
			'post_status' => $post_status,
		) );

		$this->assertIsInt( $post_id );
		$this->post_ids[] = $post_id;
	}
}
