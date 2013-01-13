<?php

class HomeTest extends TestCase
{

	public function tearDown()
	{
		Mockery::close();
	}

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBoxOffice()
	{
		$movieData = Mockery::mock('MovieData\MovieData');

		$movieData->shouldReceive('boxOffice')->once()->andReturn([['id' => 1, 'url' => 'ffds', 'title' => 'fsdffsf']]);

		$this->app->instance('MovieData\MovieData', $movieData);

		$response = $this->call('GET', '/');

		$view = $response->getOriginalContent();

		$this->assertTrue($response->isOk());

		$this->assertEquals([['id' => 1, 'url' => 'url', 'title' => 'fsdffsf']], $view['boxOffice']);
	}

}