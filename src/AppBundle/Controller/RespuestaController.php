<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Respuesta;



use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RespuestaController extends Controller
{
  /**
  * @Route("/admin/respuestas", name="respuestas")
  */
  public function respuestasAction(){

    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery('SELECT
      u.username, t.id, t.titulo, t.estado, t.fecha, t.categoria, t.prioridad
      FROM AppBundle:User u
      INNER JOIN AppBundle:Ticket t
      WITH u.id = t.idUsuario
      WHERE t.estado = :estado')->setParameter('estado', 'Procesando');

    $ticket = $query->getResult();

    return $this->render('respuesta/respuesta.html.twig', array('ticket' => $ticket));
  }

  /**
  * @Route("/admin/respuestas/{id}", name="respondiendo")
  */

  public function respAction($id, Request $request){

    $respuesta = new Respuesta();

    $ticket = $this->getDoctrine()
    ->getRepository('AppBundle:Ticket')
    ->find($id);

    $form = $this->CreateFormBuilder()
    ->add('titulo', TextType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
    ->add('respuesta', TextareaType::class, array('attr'=>array('class'=>'form-control', 'style' => 'margin-bottom:15px')))
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
    ->add('Enviar Respuesta', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary', 'style' => 'margin:15px')))
    ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $title = $form['titulo']->getData();
      $descripcion = $form['respuesta']->getData();
      $testado = $form['estado']->getData();
      $tprioridad = $form['prioridad']->getData();
      $tcategoria = $form['categoria']->getData();

      $respuesta->setTitulo($title);
      $respuesta->setRespuesta($descripcion);
      $respuesta->setTicketId($id);

      $ticket->setEstado($testado);
      $ticket->setCategoria($tcategoria);
      $ticket->setPrioridad($tprioridad);

      $em = $this->getDoctrine()->getManager();

      $em->persist($respuesta);
      $em->flush();

      $this->addFlash(
        'notice', 'respuesta enviada'
      );

      return $this->redirectToRoute('respuestas');

  }

  return $this->render('respuesta/respondiendo.html.twig', array(
    'respuesta' => $respuesta,
    'ticket' => $ticket,
    'form' => $form->createView(),
  ));
  }
}
