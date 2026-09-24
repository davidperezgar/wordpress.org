<?php
/**
 * Tests for the plugin rejection reasons and email content.
 *
 * @package WordPressdotorg\Plugin_Directory\Tests
 */

use PHPUnit\Framework\TestCase;
use WordPressdotorg\Plugin_Directory\Email\Plugin_Rejected;
use WordPressdotorg\Plugin_Directory\Template;

/**
 * Tests plugin rejection emails.
 *
 * @group email
 */
class Plugin_Rejected_Test extends TestCase {

	/**
	 * Tests that scraping is available as a rejection reason.
	 */
	public function test_scraping_is_a_rejection_reason() {
		$this->assertSame( 'Scraping', Template::get_rejection_reasons()['scraping'] );
	}

	/**
	 * Tests that the scraping rejection email explains the applicable guideline.
	 */
	public function test_scraping_rejection_email_explains_the_guideline() {
		$reflection = new ReflectionClass( Plugin_Rejected::class );
		$email      = $reflection->newInstanceWithoutConstructor();

		$args = $reflection->getProperty( 'args' );
		$args->setAccessible( true );
		$args->setValue( $email, [ 'reason' => 'scraping' ] );

		$content = $email->get_rejection_reason();

		$this->assertStringContainsString( 'scraping', $content );
		$this->assertStringContainsString( 'https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/#2-developers-are-responsible-for-the-contents-and-actions-of-their-plugins', $content );
		$this->assertStringContainsString( 'Please do not resubmit this plugin before corresponding with us.', $content );
	}
}
