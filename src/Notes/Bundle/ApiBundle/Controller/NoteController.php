<?php

/*
 * This file is part of the Notes application.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notes\Bundle\ApiBundle\Controller;

use Notes\Bundle\ApiBundle\Entity\Note;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\DiExtraBundle\Annotation\Inject;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;

/**
 * REST controller for a {@link Note}.
 *
 * @author Florian Voutzinos <florian@voutzinos.com>
 */
class NoteController
{
    /**
     * @Inject("security.context")
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @Inject("doctrine")
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    /**
     * @Inject("form.factory")
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @Inject("router")
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @ApiDoc(
     *     description="Gets all notes of the authenticated user",
     *     filters={
     *         {"name"="search", "dataType"="string"},
     *         {"name"="limit", "dataType"="integer"}
     *     },
     *     statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the api",
     *         200="When successful"
     *     }
     * )
     * @Get("/notes", name="notes_rest_note_getall", defaults={"_format" = "json"})
     * @View
     */
    public function getAllAction(Request $request)
    {
        /** @var \Notes\Bundle\ApiBundle\Entity\User $user */
        $user = $this->securityContext->getToken()->getUser();
        /** @var \Notes\Bundle\ApiBundle\Repository\NoteRepository $repository */
        $repository = $this->doctrine->getRepository('NotesApiBundle:Note');

        $limit = $request->query->get('limit');

        return $repository->findByUser(
            $user,
            $request->query->get('search'),
            null === $limit ? $limit : (int) $limit
        );
    }

    /**
     * @ApiDoc(
     *     description="Gets a note of the authenticated user",
     *     statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the note",
     *         404="When the note does not exist",
     *         200="When successful"
     *     }
     * )
     * @Get("/note/{id}", name="notes_rest_note_get",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View
     */
    public function getAction(Note $note)
    {
        /** @var \Notes\Bundle\ApiBundle\Entity\User $user */
        $user = $this->securityContext->getToken()->getUser();

        if ($note->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }

        return $note;
    }

    /**
     * @ApiDoc(
     *     description="Deletes a note of the authenticated user",
     *     statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the note",
     *         404="When the note does not exist",
     *         204="When successful"
     *     }
     * )
     * @Delete("/note/{id}", name="notes_rest_note_delete",
     *     requirements={"id" = "\d+"}, defaults={"_format" = "json"}
     * )
     * @View(statusCode=204)
     */
    public function deleteAction(Note $note)
    {
        /** @var \Notes\Bundle\ApiBundle\Entity\User $user */
        $user = $this->securityContext->getToken()->getUser();

        if ($note->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException();
        }

        $em = $this->doctrine->getManager();
        $em->remove($note);
        $em->flush();
    }

    /**
     * @ApiDoc(
     *     description="Creates a note for the authenticated user",
     *     parameters={
     *         {"name"="title", "dataType"="string", "required"=true, "description"="The note title"},
     *         {"name"="content", "dataType"="string", "required"=true, "description"="The note content"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the note",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Post("/note", name="notes_rest_note_create", defaults={"_format" = "json"})
     */
    public function createAction(Request $request)
    {
        /** @var \Notes\Bundle\ApiBundle\Entity\User $user */
        $user = $this->securityContext->getToken()->getUser();

        $note = new Note();
        $note->setUser($user);

        return $this->processForm($note, $request);
    }

    /**
     * @ApiDoc(
     *     description="Updates a note of the authenticated user",
     *     parameters={
     *         {"name"="title", "dataType"="string", "required"=true, "description"="The note title"},
     *         {"name"="content", "dataType"="string", "required"=true, "description"="The note content"}
     *      },
     *      statusCodes={
     *         401="When the user is not registered",
     *         403="When the user is not allowed to access the note",
     *         400="When the submitted data is invalid",
     *         200="When successful"
     *     }
     * )
     * @Put("/note/{id}", name="notes_rest_note_update", defaults={"_format" = "json"})
     */
    public function updateAction(Note $note, Request $request)
    {
        /** @var \Notes\Bundle\ApiBundle\Entity\User $user */
        $user = $this->securityContext->getToken()->getUser();

        if ($note->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException();
        }

        return $this->processForm($note, $request);
    }

    private function processForm(Note $note, Request $request)
    {
        $isNew = null === $note->getId();
        $statusCode = $isNew ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;
        $form = $this->formFactory->createNamed('', 'notes_api_note', $note);
        $form->submit($request, false);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($note);
            $em->flush();

            $headers = array();
            if ($isNew) {
                $headers['Location'] = $this->router->generate(
                    'notes_rest_note_get',
                    array('id' => $note->getId()),
                    true
                );
            }

            return \FOS\RestBundle\View\View::create($note, $statusCode, $headers);
        }

        return \FOS\RestBundle\View\View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
