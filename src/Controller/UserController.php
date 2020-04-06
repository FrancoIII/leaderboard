<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use \App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        return $this->redirectToRoute('user_id',['id' => $this->getUser()->getId()]);
    }

    /**
     * @Route("/user/{id}", name="user_id")
     * @Security("is_granted('ROLE_USER')")
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function profil(User $user)
    {
        $validrepo = $this->getDoctrine()->getRepository('App:Validation');
        $challrepo = $this->getDoctrine()->getRepository('App:challenge');
        $challList = $validrepo->getChallengeValidated($user);
        $difficulty = $validrepo->getHarderValidated($user);
        $nbChall = sizeof($challList);
        $createdChalls = $challrepo->getCreatedChallenges($user);
        $nbChallC = sizeof($createdChalls);

        dump($difficulty);

        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'challList' => $challList,
            'difficulty' => $difficulty,
            'nbChall' => $nbChall,
            'nbChallC' => $nbChallC,
            'createdChalls' => $createdChalls,
        ]);
    }

    /**
     * @Route("/classement", name="classement")
     * @Security("is_granted('ROLE_USER')")
     */
    public function classement()
    {
        $listUser = $this->getDoctrine()->getRepository('App:User')->findBy([], ['score' => 'DESC']);

        return $this->render('user/index.html.twig', [
            'listUser' => $listUser,
        ]);
    }
}
