<?php

class CollectionTest extends TestCase
{
    /**
     * @test
     */
    public function get_a_collection_by_name() {

        $response = $this->client->get('/collections/travel');

        $this->assertEquals(
            self::HTTP_OK,
            $response->getStatusCode()
        );

        $this->markTestIncomplete('add expected return data.');

        $expected = [
            'id' => 'string'
        ];

        $this->assertValidArray($expected, $this->getResponseArray($response)['data'][0]);
    }
}