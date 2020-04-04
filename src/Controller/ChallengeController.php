<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Challenge;
use App\Form\ReponseType;

class ChallengeController extends AbstractController
{
    /**
     * @Route("/challenge", name="challenge")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository('App:Challenge');
        $challList = $repo->findBy([], ['difficulty' => 'ASC', 'reward' => 'ASC']);
        return $this->render('challenge/index.html.twig', [
            'challList' => $challList,
        ]);
    }

    /**
     * @Route("/challenge/{id}", name="challenge_rep")
     * @Security("is_granted('ROLE_USER')")
     * @ParamConverter("challenge", class="App\Entity\Challenge")
     */
    public function repondre(Request $request, Challenge $challenge){

        $repo = $this->getDoctrine()->getRepository('App:Validation');
        $lastValidation = $repo->getLastValidation($challenge);

        $fakeChallenge = new Challenge();
        $form = $this->createForm(ReponseType::class, $fakeChallenge);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($challenge->getPassword()==$fakeChallenge->getPassword()){
                return $this->redirectToRoute('challenge_success', ['id'=>$challenge->getId()]);
            }
            return $this->redirectToRoute('challenge_failure', ['id'=>$challenge->getId()]);
        }

        return $this->render('challenge/rep.html.twig',[
            'challenge' => $challenge,
            'lastValidation' => $lastValidation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success/{id}", name="challenge_success")
     * @Security("is_granted('ROLE_USER')")
     * @ParamConverter("challenge", class="App\Entity\Challenge")
     */
    public function success(Request $request, Challenge $challenge){
        //mec comprends moi j'ai la flemme
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/failure/{id}", name="challenge_failure")
     * @Security("is_granted('ROLE_USER')")
     * @ParamConverter("challenge", class="App\Entity\Challenge")
     */
    public function failure(Request $request, Challenge $challenge){
        //mec comprends moi j'ai la flemme
        return $this->render('base.html.twig');
    }
}
