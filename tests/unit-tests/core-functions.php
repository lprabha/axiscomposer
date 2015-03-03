<?php
/**
 * Test AB core functions
 *
 * @since 1.0
 */
class AB_Tests_Core_Functions extends AB_Unit_Test_Case {

	/**
	 * Test test_axisbuilder_get_core_supported_themes()
	 *
	 * @since 1.0
	 */
	public function test_axisbuilder_get_core_supported_themes() {

		$expected_themes = array( 'twentyfifteen', 'twentyfourteen', 'twentythirteen', 'twentytwelve','twentyeleven', 'twentyten' );

		$this->assertEquals( $expected_themes, axisbuilder_get_core_supported_themes() );
	}

	/**
	 * Test test_axisbuilder_get_layout_supported_screens()
	 *
	 * @since 1.0
	 */
	public function test_axisbuilder_get_layout_supported_screens() {

		$expected_screens = array( 'post', 'page', 'portfolio', 'jetpack-portfolio' );

		$this->assertEquals( $expected_screens, axisbuilder_get_layout_supported_screens() );
	}
}
