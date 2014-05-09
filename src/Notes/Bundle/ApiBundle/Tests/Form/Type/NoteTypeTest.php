<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\Tests;

use Notes\Bundle\ApiBundle\Entity\Note;
use Notes\Bundle\ApiBundle\Form\Type\NoteType;
use Symfony\Component\Form\Test\TypeTestCase;

class NoteTypeTest extends TypeTestCase
{
    public function testSubmitExtraFields()
    {
        $type = new NoteType();
        $form = $this->factory->create($type);

        $formData = array(
            'id' => 12,
            'title' => 'foo',
            'content' => 'bar',
            'created_date' => 'foo',
            'modified_date' => 'foo'
        );

        $expected = new Note();
        $expected->setTitle('foo');
        $expected->setContent('bar');

        $form->submit($formData, false);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }
}
