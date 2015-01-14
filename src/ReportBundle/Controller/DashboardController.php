<?php

namespace ReportBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of DashboardController
 * @Route("/")
 */

class DashboardController extends Controller {

	/**
	 * Main AdminDashboard.
	 *
	 * @Route("/", name="dashboard")
	 * @Method({"GET","POST"})
	 * @Template()
	 */
	public function indexAction(Request $request) {

		return array();
	}

}

