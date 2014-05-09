<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\Tests\Fixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use OAuth2\OAuth2;

class TokenTestData extends AbstractFixture implements ContainerAwareInterface
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
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var \FOS\OAuthServerBundle\Model\ClientManagerInterface $clientManager */
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRandomId('2bzyjhi21ny88c4ksg44wwk40c00ck4g800w4o8c0okkgks8ws');
        $client->setSecret('2kdceqmaah4wcgw00kc4s88cckso04csk8cok4k00kwgc00k4g');
        $client->setAllowedGrantTypes(array(OAuth2::GRANT_TYPE_USER_CREDENTIALS, OAuth2::GRANT_TYPE_REFRESH_TOKEN));
        $clientManager->updateClient($client);

        // Create an access token
        $userRepository = $manager->getRepository('NotesApiBundle:User');
        $john = $userRepository->find(1);
        $florian = $userRepository->find(2);
        $david = $userRepository->find(3);

        /** @var \FOS\OAuthServerBundle\Storage\OAuthStorage $storage */
        $storage = $this->container->get('fos_oauth_server.storage');
        $storage->createAccessToken('john', $client, $john, time() + 3600);
        $storage->createAccessToken('florian', $client, $florian, time() + 3600);
        $storage->createAccessToken('david', $client, $david, time() + 3600);
    }
}
