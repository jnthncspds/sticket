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

        $query = $em->createQuery('SELECT u.username, t.id, t.titulo, t.estado, t.fecha, t.categoria, t.prioridad FROM AppBundle:User u INNER JOIN AppBundle:Ticket t WITH u.id = t.idUsuario');
        $users = $query->getResult();


        return $this->render('default/admin.html.twig', array('list' => $list, 'users' => $users));
      }
  }
