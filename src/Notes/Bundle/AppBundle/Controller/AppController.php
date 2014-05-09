<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AppController extends Controller
{
    /**
     * @Route("/", name="notes_app_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('NotesAppBundle:App:index.html.twig', array(
            'clientId' => '1_' . $this->container->getParameter('oauth.random_id'),
            'clientSecret' => $this->container->getParameter('oauth.secret'),
            'tokenUrl' => $this->get('router')->generate('notes_oauth_token'),
        ));
    }
}
