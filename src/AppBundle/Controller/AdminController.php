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
        $datos = $query->getResult();


        return $this->render('default/admin.html.twig', array('list' => $list, 'datos' => $datos));
      }

      /**
      *
      * @Route("/admin/detalles/{id}", name = "detalles")
      */

      public function detallesAction($id){
        $t = $this->getDoctrine()
        ->getRepository('AppBundle:Ticket')
        ->find($id);

        return $this->render('default/details.html.twig', array(
          't' => $t
        ));
      }

      /**
      * @Route("/admin/editar/{id}", name="editar")
      */

      public function editarAction($id, Request $request){
        $ticket = $this->getDoctrine()
        ->getRepository('AppBundle:Ticket')
        ->find($id);


        $form = $this->CreateFormBuilder($ticket)
        ->add('titulo', TextType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
        #->add('date', DateType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
        ->add('descripcion', TextareaType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
        ->add('categoria', ChoiceType::class, array(
          'choices'  => array(
          'Tecnologia' => 'tech',
          'Administrativo' => 'admin',
          'Servicio' => 'serv'),), array('attr'=>array('class'=>'selectpicker', 'style' => 'margin-bottom:15px')))
        ->add('prioridad', ChoiceType::class, array(
          'choices'  => array(
          'Alto' => 'Alto',
          'Normal' => 'Normal',
          'Bajo' => 'Bajo'),), array('attr'=>array('class'=>'selectpicker', 'style' => 'margin-bottom:15px')))
        ->add('estado', ChoiceType::class, array(
          'choices'  => array(
          'Procesando' => 'Procesando',
          'Declinado' => 'Declinado',
          'Resuelto' => 'Resuelto'),), array('attr'=>array('class'=>'selectpicker', 'style' => 'margin-bottom:15px')))
        ->add('editar', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary', 'style' => 'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $title = $form['titulo']->getData();
          $estado = $form['estado']->getData();
          $prioridad = $form['prioridad']->getData();
          $descripcion = $form['descripcion']->getData();
          $categoria = $form['categoria']->getData();

          $ticket->setTitulo($title);
          $ticket->setEstado($estado);

          $ticket->setDescripcion($descripcion);
          $ticket->setCategoria($categoria);
          $ticket->setPrioridad($prioridad);


          $em = $this->getDoctrine()->getManager();

          $em->persist($ticket);
          $em->flush();

          return $this->redirectToRoute('administrador');

      }

      return $this->render('default/editar.html.twig', array(
        'ticket' => $ticket,
        'form' => $form->createView(),
      ));
  }
}
