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
use OAuth2\OAuth2;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadOauthData implements FixtureInterface, ContainerAwareInterface
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
        /** @var \FOS\OAuthServerBundle\Model\ClientManagerInterface $clientManager */
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');

        // Create the client only if not existing
        if (null === $clientManager->findClientBy(array('id' => 1))) {
            $client = $clientManager->createClient();
            $client->setRandomId($this->container->getParameter('oauth.random_id'));
            $client->setSecret($this->container->getParameter('oauth.secret'));
            $client->setAllowedGrantTypes(array(OAuth2::GRANT_TYPE_USER_CREDENTIALS, OAuth2::GRANT_TYPE_REFRESH_TOKEN));
            $clientManager->updateClient($client);
        }
    }
}
