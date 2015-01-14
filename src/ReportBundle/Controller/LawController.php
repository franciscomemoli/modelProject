<?php

namespace ReportBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ModelBundle\Entity\Law;
use ReportBundle\Form\Type\LawType;
use ReportBundle\Form\Type\LawFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Law controller.
 *
 * @Route("/law")
 */
class LawController extends Controller
{
    /**
     * Lists all Law entities.
     *
     * @Route("/", name="law")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new LawFilterType());
        if (!is_null($response = $this->saveFilter($form, 'law', 'law'))) {
            return $response;
        }
        $qb = $em->getRepository('ModelBundle:Law')->createQueryBuilder('l');
        $paginator = $this->filter($form, $qb, 'law');
        
        return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Law entity.
     *
     * @Route("/{id}/show", name="law_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Law $law)
    {
        $deleteForm = $this->createDeleteForm($law->getId(), 'law_delete');

        return array(
            'law' => $law,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Law entity.
     *
     * @Route("/new", name="law_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $law = new Law();
        $form = $this->createForm(new LawType(), $law);

        return array(
            'law' => $law,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Law entity.
     *
     * @Route("/create", name="law_create")
     * @Method("POST")
     * @Template("ReportBundle:Law:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $law = new Law();
        $form = $this->createForm(new LawType(), $law);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($law);
            $em->flush();

            return $this->redirect($this->generateUrl('law_show', array('id' => $law->getId())));
        }

        return array(
            'law' => $law,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Law entity.
     *
     * @Route("/{id}/edit", name="law_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Law $law)
    {
        $editForm = $this->createForm(new LawType(), $law, array(
            'action' => $this->generateUrl('law_update', array('id' => $law->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($law->getId(), 'law_delete');

        return array(
            'law' => $law,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Law entity.
     *
     * @Route("/{id}/update", name="law_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("ReportBundle:Law:edit.html.twig")
     */
    public function updateAction(Law $law, Request $request)
    {
        $editForm = $this->createForm(new LawType(), $law, array(
            'action' => $this->generateUrl('law_update', array('id' => $law->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('law_edit', array('id' => $law->getId())));
        }
        $deleteForm = $this->createDeleteForm($law->getId(), 'law_delete');

        return array(
            'law' => $law,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="law_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('law', $field, $type);

        return $this->redirect($this->generateUrl('law'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Save filters
     *
     * @param  FormInterface $form
     * @param  string        $name   route/entity name
     * @param  string        $route  route name, if different from entity name
     * @param  array         $params possible route parameters
     * @return Response
     */
    protected function saveFilter(FormInterface $form, $name, $route = null, array $params = null)
    {
        $request = $this->getRequest();
        $url = $this->generateUrl($route ?: $name, is_null($params) ? array() : $params);
        if ($request->query->has('submit-filter') && $form->handleRequest($request)->isValid()) {
            $request->getSession()->set('filter.' . $name, $request->query->get($form->getName()));
            return $this->redirect($url);
        } elseif ($request->query->has('reset-filter')) {
            $request->getSession()->set('filter.' . $name, null);

            return $this->redirect($url);
        }
    }

    /**
     * Filter form
     *
     * @param  FormInterface                                       $form
     * @param  QueryBuilder                                        $qb
     * @param  string                                              $name
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    protected function filter(FormInterface $form, QueryBuilder $qb, $name)
    {
        if (!is_null($values = $this->getFilter($name))) {

            if ($form->submit($values)->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $qb);
            }
        }

        // possible sorting
        $this->addQueryBuilderSort($qb, $name);
        return $this->get('knp_paginator')->paginate($qb, $this->getRequest()->query->get('page', 1), 20);
    }

    /**
     * Get filters from session
     *
     * @param  string $name
     * @return array
     */
    protected function getFilter($name)
    {
        return $this->getRequest()->getSession()->get('filter.' . $name);
    }

    /**
     * Deletes a Law entity.
     *
     * @Route("/{id}/delete", name="law_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Law $law, Request $request)
    {
        $form = $this->createDeleteForm($law->getId(), 'law_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($law);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('law'));
    }

    /**
     * Create Delete form
     *
     * @param integer                       $id
     * @param string                        $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
