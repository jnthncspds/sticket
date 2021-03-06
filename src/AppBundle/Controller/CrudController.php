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

class CrudController extends Controller
{
  /**
  *
  * @Route("/admin/detalles/{id}", name = "detalles")
  */

  public function detallesAction($id){
    $t = $this->getDoctrine()
    ->getRepository('AppBundle:Ticket')
    ->find($id);

    return $this->render('crud/details.html.twig', array(
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
    ->add('categoria', ChoiceType::class, array('attr'=>array('class'=>'nav-link dropdown-toggle', 'style' => 'margin:10px'),
      'choices'  => array(
      'Tecnologia' => 'tech',
      'Administrativo' => 'admin',
      'Servicio' => 'serv'),))
    ->add('prioridad', ChoiceType::class, array('attr'=>array('class'=>'nav-link dropdown-toggle', 'style' => 'margin:10px'),
      'choices'  => array(
      'Alto' => 'Alto',
      'Normal' => 'Normal',
      'Bajo' => 'Bajo'),))
    ->add('estado', ChoiceType::class, array('attr'=>array('class'=>'nav-link dropdown-toggle', 'style' => 'margin:10px'),
      'choices'  => array(
      'Procesando' => 'Procesando',
      'Declinado' => 'Declinado',
      'Resuelto' => 'Resuelto'),))
    ->add('editar', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary', 'style' => 'margin:15px')))
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

      $this->addFlash(
        'notice', 'Ticket Editado'
      );

      return $this->redirectToRoute('administrador');

  }

  return $this->render('crud/editar.html.twig', array(
    'ticket' => $ticket,
    'form' => $form->createView(),
  ));
}


/**
* @Route("/admin/delete/{id}", name="borrar")
*/

    public function deleteAction($id, Request $request){
      $t = $this->getDoctrine()
      ->getRepository('AppBundle:Ticket')
      ->find($id);

      return $this->render('crud/borrarwarning.html.twig', array(
        't' => $t
      ));
    }

/**
*@Route("/admin/delete/confirmed/{id}", name="delete")
*/

    public function borrarAction($id){
      $t = $this->getDoctrine()
      ->getRepository('AppBundle:Ticket')
      ->find($id);
      $em = $this->getDoctrine()->getManager();

      $em->remove($t);
      $em->flush();

      return $this->redirectToRoute('administrador');

    }
}
