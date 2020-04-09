<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        $userlist = $this->getDoctrine()->getRepository('App:User')->findAll();
        $nbusers = sizeof($userlist);
        $adminlist = $this->getDoctrine()->getRepository('App:User')->findAllRole('ROLE_ADMIN');
        $nbadmins = sizeof($adminlist);
        $challlist = $this->getDoctrine()->getRepository('App:Challenge')->findAll();
        $nbchalls = sizeof($challlist);
        $attemptlist = $this->getDoctrine()->getRepository('App:Attempt')->findAll();
        $nbattempts = sizeof($attemptlist);
        $validationlist = $this->getDoctrine()->getRepository('App:Validation')->findAll();
        $nbvalidations = sizeof($validationlist);

        return $this->render('admin/index.html.twig', [
            'nbusers' => $nbusers,
            'nbadmins' => $nbadmins,
            'nbchalls' => $nbchalls,
            'nbattempts' => $nbattempts,
            'nbvalidations' => $nbvalidations
        ]);
    }
    /**
     * @Route("/admin/users", name="admin_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function users()
    {
        $userlist = $this->getDoctrine()->getRepository('App:User')->findAll();
        $nbusers = sizeof($userlist);
        $adminlist = $this->getDoctrine()->getRepository('App:User')->findAllRole('ROLE_ADMIN');
        $nbadmins = sizeof($adminlist);
        $modolist = $this->getDoctrine()->getRepository('App:User')->findAllRole('ROLE_MODO');
        $nbmodos = sizeof($adminlist);

        return $this->render('admin/list.html.twig', [
            'nbusers' => $nbusers,
            'nbadmins' => $nbadmins,
            'nbmodos' => $nbmodos,
            'userlist' => $userlist,
            'adminlist' => $adminlist,
            'modolist' => $modolist,
        ]);
    }

    /**
     * @Route("/promote_admin/{id}", name="promote_admin")
     */
    public function promote_admin(User $user){
        return [];
    }

    /**
     * @Route("/promote_modo/{id}", name="promote_modo")
     */
    public function promote_modo(User $user){
        return [];
    }

    /**
     * @Route("/demote_admin/{id}", name="demote_admin")
     */
    public function demote_admin(User $user){
        return [];
    }

    /**
     * @Route("/demote_modo/{id}", name="demote_modo")
     */
    public function demote_modo(User $user){
        return [];
    }
}
