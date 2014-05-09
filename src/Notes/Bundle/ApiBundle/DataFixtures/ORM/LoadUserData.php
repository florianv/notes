<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Notes\Bundle\ApiBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $respository = $manager->getRepository('NotesApiBundle:User');

        foreach (array('one', 'two') as $number) {
            $username = $this->container->getParameter(sprintf('user_%s.username', $number));

            if (0 === count($respository->findBy(array('username' => $username)))) {
                $user = new User();
                $user->setUsername($username);
                $user->setEmail(sprintf('%s@test.org', $username));
                $user->setPlainPassword($this->container->getParameter(sprintf('user_%s.password', $number)));
                $user->setEnabled(true);

                $manager->persist($user);
                $manager->flush();
            }
        }
    }
}
