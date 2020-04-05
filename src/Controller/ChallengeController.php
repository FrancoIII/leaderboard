<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Challenge;
use App\Form\ReponseType;
use App\Entity\Attempt;
use App\Form\ChallengeType;
use App\Form\ValidationType;
use App\Entity\Validation;
use App\Entity\User;

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

            $datetime = new \DateTime();
            $attempt = new Attempt();
            $attempt->setChallenge($challenge)
                ->setAttemptedBy($this->getUser())
                ->setAttempt($fakeChallenge->getPassword())
                ->setAttemptedOn($datetime);

            $em = $this->getDoctrine()->getManager();
            $em->persist($attempt);
            $em->flush();

            $this->addFlash('failure', "Ce n'est pas la solution ! Try again ;)");
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

        $validation = new Validation();

        $form = $this->createForm(ValidationType::class, $validation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $date = new \DateTime();
            /** @var User $user */
            $user = $this->getUser();
            $validation->setCreatedBy($user)
                ->setChallenge($challenge)
                ->setValidatedOn($date);

            $user->setScore($user->getScore() + $challenge->getReward());

            $em->persist($user);
            $em->persist($validation);
            $em->flush();

            return $this->redirectToRoute('challenge');
        }

        return $this->render('challenge/valid.html.twig', [
            'form' => $form->createView(),
            'challenge' => $challenge,
        ]);
    }

    /**
     * @Route("/creer", name="challenge_creer")
     * @Security("is_granted('ROLE_USER')")
     * @ParamConverter("challenge", class="App\Entity\Challenge")
     */
    public function creer(Request $request, ?Challenge $challenge){

        $challenge = new  Challenge();

        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $date = new \DateTime();
            $challenge->setCreatedOn($date)
                ->setCreatedBy($this->getUser());

            $em->persist($challenge);
            $em->flush();

            return $this->redirectToRoute('challenge');
        }

        return $this->render('challenge/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
