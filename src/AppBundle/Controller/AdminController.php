<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ticket;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminController extends Controller
{
      /**
      * @Route("/admin", name="administrador")
      */

      public function adminAction(){

        $em = $this->getDoctrine()->getManager();
        $list = $this->getDoctrine()->getRepository('AppBundle:Ticket')->findAll();

        $query = $em->createQuery('SELECT
          u.username, t.id, t.titulo, t.estado, t.fecha, t.categoria, t.prioridad
          FROM AppBundle:User u
          INNER JOIN AppBundle:Ticket t
          WITH u.id = t.idUsuario');
        $datos = $query->getResult();


        return $this->render('dashboard/admin.html.twig', array('list' => $list, 'datos' => $datos));
      }

      /**
      * @Route("/admin/user", name="usuarios")
      */

      public function userAction(){
      $user = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('SELECT
        u.username, u.email, t.titulo, t.id, t.idUsuario
        FROM AppBundle:User u
        INNER JOIN AppBundle:Ticket t
        WITH u.id = t.idUsuario');


        $usuario = $query->getResult();
      return $this->render('crud/user.html.twig', array('usuario' => $usuario));
      }
}
