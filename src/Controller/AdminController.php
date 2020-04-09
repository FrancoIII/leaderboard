<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class AdminController
 * @package App\Controller
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
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
     */
    public function users()
    {
        $userlist = $this->getDoctrine()->getRepository('App:User')->findAllRole('ROLE_USER');
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
     * @Route("/set_admin/{id}", name="set_admin")
     */
    public function set_admin(User $user){
        $user->setRoles(array(["ROLE_ADMIN"]));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('admin_list');
    }

    /**
     * @Route("/set_modo/{id}", name="set_modo")
     */
    public function set_modo(User $user){
        $user->setRoles(array(["ROLE_MODO"]));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('admin_list');
    }

    /**
     * @Route("/set_user/{id}", name="set_user")
     */
    public function demote_admin(User $user){
        $user->setRoles(array(["ROLE_USER"]));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('admin_list');
    }
}
