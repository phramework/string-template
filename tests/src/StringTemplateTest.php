<?php

namespace Phramework\StringTemplate;

/**
 * @coversDefaultClass Phramework\StringTemplate\StringTemplate
 */
class StringTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::render
     */
    public function testRender()
    {
        $user = (object) [
            'id' => '1',
            'type' => 'user',
            'attributes' => (object) [
                'email'      => 'nohponex@gmail.com',
                'first_name' => 'Xenofon',
                'last_name'  => 'Spafaridis',
                'user_type'  => 'DOCTOR'
            ]
        ];

        $template = (new StringTemplate())
            ->addResource(
                'user',
                $user,
                (object) [
                    'role' => 'Doctor',
                    'additional' => 'abc'
                ]
            );

        $rendered = $template->render(
            'User {user.first_name} {user.last_name} with ID {user.id} has role {user.role}'
        );

        $this->assertSame(
            'User Xenofon Spafaridis with ID 1 has role Doctor',
            $rendered
        );
    }
}
